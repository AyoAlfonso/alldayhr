<?php

namespace App\Http\Controllers\Candidate\API;

use App\CandidateDocument;
use App\CandidateEducation;
use App\CandidateInfo;
use App\CandidateOlevel;
use App\CandidateWorkHistory;
use App\Helpers\CandidateRequestFormatter;
use App\Helpers\CandidateValidator;
use App\Helpers\General;
use App\Http\Controllers\Controller;
use App\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidateProfileController extends Controller
{
    private $profile;
    private $education;
    private $work;
    private $doc;
    private $olevel;
    private $candidate;
    private $request;

    public function __construct(Request $request, CandidateInfo $profile, CandidateEducation $education, CandidateWorkHistory $work, CandidateDocument $doc, CandidateOlevel $olevel)
    {
        parent::__construct();
        $this->request = $request;
        $this->profile = $profile;
        $this->education = $education;
        $this->work = $work;
        $this->doc = $doc;
        $this->olevel = $olevel;
        $this->middleware(function ($request, $next) {
            $this->candidate = Auth::guard('candidate')->user();
            if (Auth::guard('api')->check()) {
                $this->candidate = Auth::guard('api')->user()->candidate;
            }
            return $next($request);
        });
    }

    public function postUpdateImage(Request $request)
    {
        $this->validate($request, CandidateValidator::UpdateProfileImage());
        $user = $this->candidate;
        if ($profile = $user->updateImage($request))
            return response()->json($this->genereateSuccessRes());
        return response()->json(['error' => 'Sorry an error occurred, please try again.'], 400);
    }

    public function postResume(Request $request)
    {
        $this->validate($request, CandidateValidator::UpdateCV());
        $user = $this->candidate;
        if ($profile = $user->updateCV($request))
            return response()->json($this->genereateSuccessRes());
        return response()->json(['error' => 'Sorry an error occurred, please try again.'], 400);
    }

    public function getProfileInfo()
    {
        return response()->json($this->genereateSuccessRes());

    }

    public function postProfileInfo(Request $request)
    {
        $this->validate($request, CandidateValidator::UpdateProfileDetails());
        $user = $this->candidate;
        if ($profile = $user->updateDetails(CandidateRequestFormatter::updateProfileDetails($request->all())))
            return response()->json($this->genereateSuccessRes('Personal info updated successfully.'));
        return response()->json(['error' => 'Sorry an error occurred, please try again.'], 400);
    }
    public function postProfileOther(Request $request)
    {
        $this->validate($request, CandidateValidator::UpdateProfileSkills());
        $user = $this->candidate;
        if ($profile = $user->updateDetails(CandidateRequestFormatter::updateProfileDetails($request->all())))
            return response()->json($this->genereateSuccessRes('Skills and others updated successfully.'));
        return response()->json(['error' => 'Sorry an error occurred, please try again.'], 400);
    }

    public function postCreateEducation(Request $request)
    {
        CandidateValidator::UpdateEducation($request->all())->validate();
        if ($this->education->createEducation(CandidateRequestFormatter::addCandidateID($request->only('institution', 'qualification', 'field_of_study', 'grade', 'from_year', 'to_year'))))
            return response()->json($this->genereateSuccessRes());
        return response()->json(['error' => 'Sorry an error occurred, please try again.'], 400);
    }

    public function postUpdateEducation(Request $request)
    {
        $id = $request->input('uuid');
        CandidateValidator::UpdateEducation($request->all())->validate();
        $edu = $this->education->checkEducationID($id);
        if ($edu->updateEducation($id, CandidateRequestFormatter::addCandidateID($request->only('institution', 'qualification', 'field_of_study', 'grade', 'from_year', 'to_year'))))
            return response()->json($this->genereateSuccessRes());
        return response()->json(['error' => 'Sorry an error occurred, please try again.'], 400);
    }

    public function deleteEducation(Request $request,$education)
    {
        $edu = $this->education->checkEducationID($education);
        if ($edu->delete())
            return response()->json($this->genereateSuccessRes());
        return response()->json(['error' => 'Sorry an error occurred, please try again.'], 400);
    }

    public function postCreateWork(Request $request)
    {
        CandidateValidator::UpdateWork($request->all())->validate();
        if ($this->work->createWork(CandidateRequestFormatter::addCandidateID($request->only('title', 'company', 'location','industry', 'from_year', 'from_month', 'to_year', 'to_month', 'current', 'achievements', 'job_function'))))
            return response()->json($this->genereateSuccessRes());
        return response()->json(['error' => 'Sorry an error occurred, please try again.'], 400);
    }

    public function postUpdateWork(Request $request)
    {
        $id = $request->input('uuid');
        CandidateValidator::UpdateWork($request->all())->validate();
        $work = $this->work->checkWorkID($id);
        if ($work->updateWork($id, CandidateRequestFormatter::addCandidateID($request->only('title', 'company', 'industry','location', 'from_year', 'from_month', 'to_year', 'to_month', 'current', 'achievements', 'job_function'))))
            return response()->json($this->genereateSuccessRes());
        return response()->json(['error' => 'Sorry an error occurred, please try again.'], 400);
    }

    public function deleteWork(Request $request,$work)
    {
        $work = $this->work->checkWorkID($work);
        if ($work->delete())
            return response()->json($this->genereateSuccessRes());
        return response()->json(['error' => 'Sorry an error occurred, please try again.'], 400);
    }

    public function postCreateDocument(Request $request)
    {
        $this->validate($request, CandidateValidator::addDocument());
        if ($this->doc->createDoc($request))
            return response()->json($this->genereateSuccessRes());
        return response()->json(['error' => 'Sorry an error occurred, please try again.'], 400);
    }

    public function deleteDocument(Request $request,$document)
    {
        $doc = $this->doc->checkDocID($document);
        if ($doc->delete())
            return response()->json($this->genereateSuccessRes());
        return response()->json(['error' => 'Sorry an error occurred, please try again.'], 400);
    }

    public function postCreateOlevel(Request $request)
    {
        CandidateValidator::addOlevel($request)->validate();
        if ($this->olevel->createOlevel($request))
            return response()->json($this->genereateSuccessRes());
        return response()->json(['error' => 'Sorry an error occurred, please try again.'], 400);
    }
    public function postUpdateOlevel(Request $request)
    {
        $id = $request->input('uuid');
        CandidateValidator::addOlevel($request)->validate();
        $olevel = $this->olevel->checkOlevelID($id);
        if ($olevel->updateOlevel($request))
            return response()->json($this->genereateSuccessRes());
        return response()->json(['error' => 'Sorry an error occurred, please try again.'], 400);
    }

    public function deleteOlevel(Request $request,$olevel)
    {
        $olevel = $this->olevel->checkOlevelID($olevel);
        if ($olevel){
            $olevel->results()->delete();
            $olevel->delete();
            return response()->json($this->genereateSuccessRes());
        }
        return response()->json(['error' => 'Sorry an error occurred, please try again.'], 400);
    }
    
    private function genereateSuccessRes($message = null){
        $default_tab = null;
        $job_requirements = null;
        if($this->request->get('job_slug')){
            $job = Job::where('slug', $this->request->get('job_slug'))->first();
            if($job){
                $job_req = $this->candidate->jobApplicationValidation($job);
                $default_tab = $job_req ? collect($job_req)->where('value','=',false)->first()->type : null;
               if($job_req){
                   //$job_requirements = $job_req;
                   //$job_requirements = collect($job_req)->sortBy('value')->values();
                   $job_requirements = collect($job_req)->filter(function ($item) {
                       return $item->value === false;
                   })->sortBy('value')->values();

               }

            }
        }
        return ['success' => true,'message'=>$message, 'user' => $this->candidate->getAllProfile(),'bootstrap'=>General::generateBootstrapData(),'default_tab'=>$default_tab,'job_requirements'=>$job_requirements];
    }
}
