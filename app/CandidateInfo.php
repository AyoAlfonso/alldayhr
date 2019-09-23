<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CandidateInfo extends Authenticatable
{
    protected $fillable = [
        'user_id', 'candidate_id','othername', 'date_of_birth', 'gender', 'phone_number', 'state', 'lga', 'street', 'landmark', 'experience_level', 'certifications',
        'cover_letter', 'cv_url', 'cv_name', 'profile_image_url','residence_state','residence_lga', 'skills', 'nysc_status', 'nysc_completion_year', 'nysc_other_info', 'nationality', 'marital_status', 'languages'
    ];
    protected $rememberTokenName = false;
    // protected $casts = [
    //     'certifications' => 'array',
    //     'skills' => 'array'
    // ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function education()
    {
        return $this->hasMany(CandidateEducation::class, 'candidate_id', 'candidate_id');
    }

    public function olevel()
    {
        return $this->hasMany(CandidateOlevel::class, 'candidate_id', 'candidate_id');
    }

    public function work()
    {
        return $this->hasMany(CandidateWorkHistory::class, 'candidate_id', 'candidate_id');
    }

    public function documents()
    {
        return $this->hasMany(CandidateDocument::class, 'candidate_id', 'candidate_id');
    }

    public function candidatescores()
    {
        return $this->hasMany(CandidateScores::class, 'candidate_id', 'candidate_id');
    }

    public function job_applications()
    {
        return $this->hasMany(JobApplication::class, 'candidate_id', 'candidate_id');
    }

    public static function getByUserID($id)
    {
        return self::where('user_id', $id)->first();
    }

    public function updateDetails($data)
    {
        if (isset($data['firstname'])) {
            $this->user->firstname = $data['firstname'];
        }
        if (isset($data['lastname'])) {
            $this->user->lastname = $data['lastname'];
        }
        $this->user->update();
        return $this->update($data);
    }

    public function updateImage($request)
    {
        $profile_image_url = $this->profileImageUpload($this, $request);
        $this->profile_image_url = $profile_image_url;
        return $this->update();
    }

    private function profileImageUpload($candidate, $request)
    {
        if ($request->hasFile('profile_image')) {

            /**
             * check if user has a previous image
             */
            $profile_image_url = isset($candidate->profile) ? $candidate->profile->profile_image_url : null;
            if (!empty($profile_image_url)) {
                $prv_url = str_replace("https://" . config('filesystems.disks.s3.bucket') . ".s3." . config('filesystems.disks.s3.region') . ".amazonaws.com", "", $candidate->profile->profile_image_url);
                Storage::disk('s3')->delete($prv_url);
            }

            /**
             * save new profile image
             */
            $filenamewithextension = $request->file('profile_image')->getClientOriginalName();
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
            $extension = $request->file('profile_image')->getClientOriginalExtension();
            $filenametostore = 'candidate/profile/' . trim($filename) . '_' . time() . '.' . $extension;
            $result = Storage::disk('s3')->put($filenametostore, fopen($request->file('profile_image'), 'r+'), 'public');

            if ($result) {
                $profile_image_url = Storage::disk('s3')->url($filenametostore);
                return $profile_image_url;
            }
        }
        return null;
    }

    public function updateCV($request)
    {
        list($cv_url, $cv_name) = $this->uploadResume($request);
        return $this->update([
            'cv_url' => $cv_url,
            'cv_name' => $cv_name
        ]);
    }

    /**
     * @param $data
     * @return array
     */
    public static function uploadResume($data)
    {
        if ($data->hasFile('cv_file')) {
            if (!empty(Auth::guard('candidate')->user()->cv_url)) {
                $prv_url = str_replace("https://" . config('filesystems.disks.s3.bucket') . ".s3." . config('filesystems.disks.s3.region') . ".amazonaws.com", "", Auth::guard('candidate')->user()->cv_url);
                Storage::disk('s3')->delete($prv_url);
            }
            /**
             * save new profile image
             */
            $filenamewithextension = $data->file('cv_file')->getClientOriginalName();
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
            $extension = $data->file('cv_file')->getClientOriginalExtension();
            $filenametostore = 'candidate/document/' . trim($filename) . '_' . time() . '.' . $extension;
            $result = Storage::disk('s3')->put($filenametostore, fopen($data->file('cv_file'), 'r+'), 'public');

            if ($result) {
                $cv_url = Storage::disk('s3')->url($filenametostore);
                $cv_name = $filenamewithextension;
            }
            return [$cv_url, $cv_name];
        }
        return [null, null];
    }

    public function getCompletionRateAttribute()
    {
        $rate = 10;
        if (!empty($this->profile_image_url)) {
            $rate += 10;
        }
        if (!empty($this->cv_url)) {
            $rate += 20;
        }

        if (sizeof($this->work) > 0) {
            $rate += 20;
        }

        if (sizeof($this->education) > 0) {
            $rate += 20;
        }

        if (sizeof($this->documents) > 0) {
            $rate += 10;
        }

        if (!empty($this->nysc_status)) {
            $rate += 10;
        }

        return $rate;
    }

    public function getOlevelsAttribute()
    {
        $olevels = [];
        foreach ($this->olevel as $olevel) {
            $data['uuid'] = $olevel->uuid;
            $data['olevel_type'] = $olevel->type;
            for ($i = 1; $i <= 7; $i++) {
                //foreach ($olevel->results as $result) {
                $data['subject_' . ($i)] = isset($olevel->results[$i - 1]) ? (object)['label' => $olevel->results[$i - 1]->subject, 'value' => $olevel->results[$i - 1]->grade] : null;
            }
            $olevels[] = $data;
        }
        return $olevels;
    }

    public function HasAppliedJob($job_id)
    {
        if ($this->job_applications()->where('job_id', $job_id)->first()) {
            return true;
        }
        return false;
    }

    public function getAllJobApplicationsAttribute()
    {
        $data['saved'] = $this->job_applications()->whereNull('status_id')->with('job')->get();
        $data['applied'] = $this->job_applications()->whereNotNull('status_id')->with('job')->get();
        $data['bookmarked'] = [];
        return $data;

    }

    public function getAllProfile()
    {
        return $this->load(['user', 'education', 'work', 'documents', 'documents.type'])->append(['completion_rate', 'olevels', 'all_job_applications']);
    }

    public function jobApplicationValidation($job)
    {
        $messages = [];
        $show = false;

        $profile['label'] = 'Personal Info';
        $profile['type'] = 'Personal Information';
        $profile['value'] = true;
        if (empty($this->date_of_birth) ||
            empty($this->gender) ||
            empty($this->phone_number) ||
            empty($this->state) ||
            empty($this->lga) ||
            empty($this->street) ||
            empty($this->nationality) ||
            empty($this->marital_status) ||
            empty($this->languages) ||
            empty($this->experience_level)) {
            $profile['value'] = false;
            $show = true;
        }
        $messages[] = (object)$profile;

        $cv['label'] = 'Resume';
        $cv['type'] = 'Resume';
        $cv['value'] = true;
        if (empty($this->cv_url)) {
            $cv['value'] = false;
            $show = true;
        }
        $messages[] = (object)$cv;


        $edu['label'] = 'Education';
        $edu['type'] = 'Education';
        $edu['value'] = true;
        if ($this->education()->count() == 0) {
            $edu['value'] = false;
            $show = true;
        }
        $messages[] = (object)$edu;

        if($job){
            //profile info enforcement.
            if(in_array('work',$job->required_info)){
                $edu['label'] = 'Employment History';
                $edu['type'] = 'Employment History';
                $edu['value'] = true;
                if ($this->work()->count() == 0) {
                    $edu['value'] = false;
                    $show = true;
                }
                $messages[] = (object)$edu;
            }
            //profile info enforcement.
            if(in_array('olevel',$job->required_info)){
                $edu['label'] = "O'Levels";
                $edu['type'] = 'O\'Levels';
                $edu['value'] = true;
                if ($this->olevel()->count() == 0) {
                    $edu['value'] = false;
                    $show = true;
                }
                $messages[] = (object)$edu;
            }
            if(in_array('nysc',$job->required_info)){
                $edu['label'] = "NYSC Info";
                $edu['type'] = 'Skills & Other';
                $edu['value'] = true;
                if (empty($this->nysc_status)) {
                    $edu['value'] = false;
                    $show = true;
                }
                $messages[] = (object)$edu;
            }
            if(in_array('skillsother',$job->required_info)){
                $edu['label'] = "Skills or Certifications";
                $edu['type'] = 'Skills & Other';
                $edu['value'] = true;
                if (empty($this->skills) && empty($this->certifications) ) {
                    $edu['value'] = false;
                    $show = true;
                }
                $messages[] = (object)$edu;
            }



            //profile document enforcement.
            if($job->required_docs && sizeof($job->required_docs) > 0){
                foreach ($job->required_docs as $doc){
                    $doc_data = [];
                    $doc_type = DocumentType::where('uuid',$doc)->first();
                    if($doc_type){
                        $doc_data['label'] = $doc_type->name;
                        $doc_data['type'] = 'Other Documents';
                        $doc_data['value'] = true;
                        if (!$this->documents()->where('document_id',$doc)->first()) {
                            $doc_data['value'] = false;
                            $show = true;
                        }
                        $messages[] = (object)$doc_data;
                    }
                }
            }
        }

        return $show ? $messages : false;

    }

}
