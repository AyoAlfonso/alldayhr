<?php

namespace App\Http\Controllers\Front;

use App\CandidateEducation;
use App\Company;
use App\Helper\Reply;
use App\Http\Requests\FrontJobApplication;
use App\Job;
use App\JobApplication;
use App\JobApplicationAnswer;
use App\JobCategory;
use App\JobLocation;
use App\JobQuestion;
use App\Mail\sendgridEmail;
use App\Notifications\NewJobApplication;
use App\User;
use App\CandidateWorkHistory;
use App\Skill;
use App\JobSkill;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;

class FrontJobsController extends FrontBaseController
{
    public function __construct()
    {
        parent::__construct();
        $employee_job_titles = file_get_contents(base_path('resources/lang/en/employee_job_titles.json'));
        $employee_job_titles = json_decode($employee_job_titles, true);

        $rawCandidateInfoQuery = new CandidateWorkHistory();
        $rawSkills = Skill::all();
        $rawCategoories = JobCategory::all();
   
        $jobData = collect([]);/*$rawCandidateInfoQuery
        ->select('title', 'industry', 'company', 'job_function')
        ->groupBy('title', 'industry', 'company', 'job_function')->get();
        */
   
        $jobTitlesQuery = $jobData->map(function ($info) {
            return ['title' => $info->title];
        });

        $job_skills = $rawSkills->map(function ($info) {
            return ['name' => $info->name];
        });

 
        $job_categories =  $rawCategoories->map(function ($info) {
            return ['name' => $info->name];
        });

        $employeeJobFunctionsQuery = $jobData->map(function ($info) {
            return ['job_function' => $info->job_function];
        });

        $jobIndustryQuery = $jobData->map(function ($info) {
            return ['industry' => $info->industry];
        });
        $employee_job_titles=[];
        foreach ($jobTitlesQuery as $key => $value) {
            $employee_job_titles[] = array(
                'Occupation' => $jobTitlesQuery[$key]['title'],
            );
        }

        $employee_industry = [];
        foreach ($jobIndustryQuery as $key => $value) {
            $tempIndustry = $jobIndustryQuery[$key]['industry'];
            $tempIndustry = preg_split('/\s*,\s*/', str_replace('"', "", trim($tempIndustry, "[]")));
            foreach ($tempIndustry as $key => $value) {
                if($value){
                    $employee_industry[] = array(
                        'industry' => $value,
                    );
                }
            }
        }

        $employee_industry = array_unique($employee_industry, SORT_REGULAR);
        asort($employee_industry);
        asort($employee_job_titles);

        $this->employee_job_titles = $employee_job_titles;
        $this->employee_industry = $employee_industry;
        $this->job_skills = $job_skills;
        $this->job_categories = $job_categories;

        $this->pageTitle = __('modules.front.jobOpenings');
    }

    public function jobOpenings(Request $request )
    {

        $limit = $request->query('limit') ? $request->query('limit') : 8;
        $jobs = Job::activeJobsLimited($limit)->with(['company'])->latest('created_at');
        $this->jobs = $jobs->get();
        $this->jobsPagination = $jobs->simplePaginate(10);
        $this->locations = JobLocation::all();
        $this->categories = JobCategory::all();
        $candidate_user_info = Auth::guard('candidate')->user();
        $user = null;
        if($candidate_user_info) {
            $id = $candidate_user_info->user_id;
            $user = User::find($id);
        }
           return view('front.job-openings-adhoc-landing', $this->data)->with([ 'user' => $user ]);
    }

    public function jobDetail($slug)
    {
        $this->job = Job::where('slug', $slug)->with(['company'])->where('status', 'active')->firstOrFail();
        $this->relatedJobs = Job::where('category_id',$this->job->category_id)
                                ->with(['company'])
                                ->latest('created_at')->limit(2)->latest('created_at')->get();

        $candidate_user_info = Auth::guard('candidate')->user();
        $user = null;
        if($candidate_user_info) {
            $id = $candidate_user_info->user_id;
            $user = User::find($id);
        }

        return view('front.job-detail-adhoc', $this->data)->with([ 'user' => $user]);;
    }

    public function jobApply($slug)
    {
        $candidate_user = Auth::guard('candidate')->user()->user;

        $this->job = Job::where('slug', $slug)->first();
        $this->jobQuestion = JobQuestion::with(['question'])->where('job_id', $this->job->id)->get();
        $this->company = Company::find($this->job->company_id);
        $candidate_user->fullname = $candidate_user->firstname.' '. $candidate_user->lastname;

        if(Auth::guard('candidate')->user()) {
            $candidate_user_education = CandidateEducation::where('candidate_id', Auth::guard('candidate')->user()->candidate_id)->first();
            $candidate_user_info = Auth::guard('candidate')->user();
            $this->candidate_user_info = $candidate_user_info;
            $this->candidate_user = $candidate_user;
            $this->candidate_user_education = $candidate_user_education;
            $this->job_application_validation = $this->candidate_user_info->jobApplicationValidation($this->job);

            return view('front.job-apply', $this->data);
        }
        return  redirect('/');

    }

    public function getJobCategories(Request $request)
    {
     
        
        $limit = $request->query('limit') ? $request->query('limit') : null;
        $keyword = $request->query('keyword') ? $request->query('keyword') : null;
        $industry = $request->query('filter_industry') ? $request->query('filter_industry') : null;
        $sortBy = null;
        $job_skills = null;
        
        if(!$industry){
            $industry = $request->filter_industry;
        }

        $job_type = $request->query('filter_job_types') ? $request->query('filter_job_types') : null;
        
        if(!$job_type){
           $job_type = $request->filter_job_types;
        }

        $location = $request->location ? $request->location  : null;
       

        $jobs = Job::activeJobsLimited($limit)->with(['company'])->where(function($query) use ($keyword){
            $query->where('jobs.title', 'LIKE', '%'.$keyword.'%');
            $query->orWhere('jobs.slug', 'LIKE', '%'.$keyword.'%');
            $query->orWhere('jobs.job_description', 'LIKE', '%'.$keyword.'%');
        });

        if($job_type) {
            $jobCategory = JobCategory::where('name', 'LIKE', '%'.$job_type.'%')->first();
            if(!empty($jobCategory)) {
             $jobs->where('category_id', $jobCategory->id);
            }
         }

         if($industry) {
            $jobs->where(function($query) use ($industry){
                $query->orWhere('jobs.title', 'LIKE', '%'.$industry.'%');
                $query->orWhere('jobs.slug', 'LIKE', '%'.$industry.'%');
                $query->orWhere('jobs.job_description', 'LIKE', '%'.$industry.'%');
                $query->orWhere('jobs.job_roles_json', 'LIKE', '%'.$industry.'%');
        });
    }


        $jobs->latest('created_at');

        if ($location) {
            $joblocation = JobLocation::where('location', 'like' , '%' . $location . '%')->first();
            if(!empty($joblocation)){
                $locationId = $joblocation->id;
                $jobs = $jobs->whereHas('location', function ($query) use ($locationId) {
                    $query->where('location_id', '=',  $locationId );
                 });
            }
        }

        $this->pagination = $jobs->paginate(10);
        $this->jobs = $jobs->get();
        $this->locations = JobLocation::all();
        $this->categories = JobCategory::all();
   
        $candidate_user_info = Auth::guard('candidate')->user();
        $user = null;
        if($candidate_user_info) {
            $id = $candidate_user_info->user_id;
            $user = User::find($id);
        }
       
        return view('front.job-search', $this->data)->with([
             'user' => $user , 'pagination' => $this->pagination,
             'keyword' => $keyword,
             'filter_sortby' => $sortBy,
             'locationSelected' => $location,
            'filter_industry' => $industry,
            'filter_job_types' => $job_type,
            'filter_job_skills' => $job_skills
         ]);
    }

    public function searchJobCategories(Request $request )
    {
     
        $limit = $request->query('limit') ? $request->query('limit') : null;
        $keyword = $request->input('keyword') ? $request->input('keyword') : null;
        $location = $request->location ? $request->location  : null;
     
        $industry = $request->query('filter_industry') ? $request->query('filter_industry') : null;
      
        if(!$industry){
            $industry = $request->filter_industry;
        }

        $job_skills = $request->filter_job_skills;

        $job_type = $request->query('filter_job_types') ? $request->query('filter_job_types') : null;
        
        if(!$job_type){
           $job_type = $request->filter_job_types;
        }

        $sortBy = $request->filter_sortby ? $request->filter_sortby : null;
        if(!$sortBy){
            $sortBy = $request->filter_sortby;
        }
     
        $jobs = Job::activeJobsLimited($limit)->with(['company'])->where(function($query) use ($keyword){
            $query->where('jobs.title', 'LIKE', '%'.$keyword.'%');
            $query->orWhere('jobs.slug', 'LIKE', '%'.$keyword.'%');
            $query->orWhere('jobs.job_description', 'LIKE', '%'.$keyword.'%');
    
        });

        if($job_type) {
           $jobCategory = JobCategory::where('name', 'LIKE', '%'.$job_type.'%')->first();
           if(!empty($jobCategory)) {
            $jobs->where('category_id', $jobCategory->id);
           }
        }

        if($industry) {
            $jobs->where(function($query) use ($industry){
                $query->orWhere('jobs.title', 'LIKE', '%'.$industry.'%');
                $query->orWhere('jobs.slug', 'LIKE', '%'.$industry.'%');
                $query->orWhere('jobs.job_description', 'LIKE', '%'.$industry.'%');
                $query->orWhere('jobs.job_roles_json', 'LIKE', '%'.$industry.'%');
        });
    }

        if($job_skills) {
            $skills = Skill::where('name', 'LIKE', '%'.$job_skills.'%')->first();
            $jobSkills = jobSkill::where('skill_id', $skills->id);
            
            if(!empty($jobSkills->get())){
                 $jobs->where(function ($query) use ($jobSkills) {
                    $jobSkills = $jobSkills->get();
                    foreach ($jobSkills as $jobSkill) {
                    $query->orWhere('id', '=', $jobSkill->job_id );
                   }
                });
            }
        }

        $jobs->latest('created_at');

        if($sortBy == 'createdAt'){
            $jobs->orderBy('start_date', 'desc');
        }

        if($sortBy == 'expiryDate'){
            $jobs->orderBy('end_date', 'desc');
        }
       
        if ($location) {
            $joblocation = JobLocation::where('location', 'like' , '%' . $location . '%')->first();
           if(!empty($joblocation)){
            $locationId = $joblocation->id;
            $jobs = $jobs->whereHas('location', function ($query) use ($locationId) {
                $query->where('location_id', '=',  $locationId );
             });
           }
        }

        $this->pagination = $jobs->paginate(10);
        $this->jobs = $jobs->get();
        $this->locations = JobLocation::all();
        $this->categories = JobCategory::all();
        $candidate_user_info = Auth::guard('candidate')->user();
        $user = null;
        if($candidate_user_info) {
            $id = $candidate_user_info->user_id;
            $user = User::find($id);
        }

        return view('front.job-search', $this->data)
        ->with([ 'user' => $user , 'pagination' =>$this->pagination,
        'keyword' => $keyword,
        'locationSelected' => $location,
        'filter_sortby' => $sortBy,
        'filter_industry' => $industry,
        'filter_job_types' => $job_type,
        'filter_job_skills' => $job_skills
        ]);
     }

    public function saveApplication(FrontJobApplication $request, sendgridEmail $emailService)
    {

        $candidate_user = Auth::guard('candidate')->user()->user;

        $candidate_user_info  = Auth::guard('candidate')->user();
        $candidate_user->fullname = $candidate_user->firstname.' '. $candidate_user->lastname;
        $candidate_user->email;

        $jobApplication = new JobApplication();
        $jobApplication->full_name =  $candidate_user->fullname;
        $jobApplication->job_id = $request->job_id;
        $jobApplication->status_id = 1; //applied status id
        $jobApplication->email = trim($candidate_user->email);
        $jobApplication->phone = $candidate_user_info->phone_number;
        $jobApplication->candidate_id = $candidate_user_info->candidate_id;
        $jobApplication->cover_letter = $request->cover_letter;
        $jobApplication->resume = $candidate_user_info->cv_url;
        $jobApplication->column_priority = 0;
        $jobApplication->relevant_years_experience = $request->relevant_years_experience;
        $jobApplication->job_role = $request->job_role;
        $jobTitle = Job::find($request->job_id)->title;

        if ($request->hasFile('resume')) {
            $jobApplication->resume =  $request->resume->hashName();
            $request->resume->store('user-uploads/resumes');
        }

        if ($request->hasFile('photo')) {
            $jobApplication->photo = $request->photo->hashName();
            $request->photo->store('user-uploads/candidate-photos');
        }


        $jobApplicationExists = JobApplication::where('email',  $jobApplication->email)
            ->where('job_id', '=', $jobApplication->job_id)
            ->first();
        
        if($jobApplicationExists) {
            return Reply::dataOnly(['status' => '201', 'msg' => 'You have already applied!' ]);
        }
        $jobApplication->save();
        $subject = 'Your application was sent for the ' .$jobTitle . ' job' ;
        $message = 'Application sent! You can view your application at the link below';
        $emailService->sendCandidateEmailOnApplication($candidate_user, $subject, $message);

        if (!empty($request->answer)) {
                foreach ($request->answer as $key => $value) {
                    $answer = new JobApplicationAnswer();
                    $answer->job_application_id = $jobApplication->id;
                    $answer->job_id = $request->job_id;
                    $answer->question_id = $key;
                    $answer->answer = $value;
                    $answer->save();
                }
            }

//        Notification::send($users, new NewJobApplication($jobApplication));

        return Reply::dataOnly(['status' => 'success', 'msg' => __('modules.front.applySuccessMsg')]);
    }
}
