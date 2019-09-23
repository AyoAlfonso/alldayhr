<?php

namespace App\Http\Controllers\Admin;

use App\ApplicationStatus;
use App\Helper\Reply;
use App\Http\Requests\InterviewSchedule\StoreRequest;
use App\Http\Requests\StoreJobApplication;
use App\Http\Requests\UpdateJobApplication;
use App\InterviewSchedule;
use App\InterviewScheduleEmployee;
use App\Job;
use App\JobApplication;
use App\JobApplicationAnswer;
use App\JobLocation;
use App\JobQuestion;
use App\Notifications\CandidateScheduleInterview;
use App\Notifications\ScheduleInterview;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Response;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Yajra\DataTables\Facades\DataTables;
use App\CandidateInfo;
use App\Company;
use App\CandidateWorkHistory;
use App\CandidateEducation;
use App\CandidateOlevel;
use App\CandidateOlevelResult;
use Illuminate\Support\Facades\Schema;
use App\JobApplicationTestGroup;

class AdminJobApplicationController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('menu.jobApplications');
        $this->pageIcon = 'icon-user';

        $universities = file_get_contents(base_path('resources/lang/en/nigerian_universities.json'));
        $nigerian_states = file_get_contents(base_path('resources/lang/en/nigerian_states.json'));
        $employee_job_titles = file_get_contents(base_path('resources/lang/en/employee_job_titles.json'));
        $nigerian_employers = file_get_contents(base_path('resources/lang/en/nigerian_employers.json'));
        $employee_skills = file_get_contents(base_path('resources/lang/en/employee_skills.json'));
        $university_courses = file_get_contents(base_path('resources/lang/en/university_courses.json'));

        $rawCandidateInfoQuery = new CandidateWorkHistory();
        $candidateEducationsQuery = CandidateEducation::select('institution as university', 'field_of_study as Major')->groupBy('institution')->get();

        $universities = $candidateEducationsQuery->map(function ($edu) {
            return ['university' => $edu->university];
        });
        $university_courses = $candidateEducationsQuery->map(function ($work) {
            return ['Major' => $work->Major];
        });

        $jobData = $rawCandidateInfoQuery
            ->select('title', 'industry', 'company', 'job_function')
            ->groupBy('title', 'industry', 'company', 'job_function')->get();
       
            $jobTitlesQuery = $jobData->map(function ($info) {
            return ['title' => $info->title];
        });

        $jobIndustryQuery = $jobData->map(function ($info) {
            return ['industry' => $info->industry];
        });
        $employersQuery = $jobData->map(function ($info) {
            return ['company' => $info->company];
        });
       
        $employeeJobFunctionsQuery = $jobData->map(function ($info) {
            return ['job_function' => $info->job_function];
        });
        $employeeSkillsQuery = CandidateInfo::select('skills')
            ->whereNotNull('skills')
            ->groupBy('skills')->get()->toArray();

        $employeeCertQuery = CandidateInfo::select('certifications')
        ->whereNotNull('certifications')
        ->groupBy('certifications')->get()->toArray();

        $employee_skills = file_get_contents(base_path('resources/lang/en/employee_skills.json'));

        $employee_skills = json_decode($employee_skills, true);
        $nigerian_employers = json_decode($nigerian_employers, true);
        $employee_job_titles = json_decode($employee_job_titles, true);
        $universities = json_decode($universities, true);
        $university_courses = json_decode($university_courses, true);

        foreach ($jobTitlesQuery as $key => $value) {
            $employee_job_titles[] = array(
                'Occupation' => $jobTitlesQuery[$key]['title'],
            );
        }

        foreach ($employersQuery as $key => $value) {
            $nigerian_employers[] = array(
                'Name' => $employersQuery[$key]['company'],
            );
        }

        foreach ($employeeJobFunctionsQuery as $key => $value) {
            $employee_job_titles[] = array(
                'Occupation' => $employeeJobFunctionsQuery[$key]['job_function'],
            );
        }

        foreach ($employeeSkillsQuery as $key => $value) {
            $tempSkills = $employeeSkillsQuery[$key]['skills'];
            $tempSkills = preg_split('/\s*,\s*/', str_replace('"', "", trim($tempSkills, "[]")));
            foreach ($tempSkills as $key => $value) {
                $employee_skills[] = array(
                    'skill_name' => $value,
                );
            }
        }
       
        foreach ($employeeCertQuery as $key => $value) {
            $tempCerts = $employeeCertQuery[$key]['certifications'];
            $tempCerts = preg_split('/\s*,\s*/', str_replace('"', "", trim($tempCerts, "[]")));
            foreach ($tempCerts as $key => $value) {
                $employee_certifications[] = array(
                    'certifications' => $value,
                );
            }
        }

        foreach ($jobIndustryQuery as $key => $value) {
            $tempIndustry = $jobIndustryQuery[$key]['industry'];
            $tempIndustry = preg_split('/\s*,\s*/', str_replace('"', "", trim($tempIndustry, "[]")));
            foreach ($tempIndustry as $key => $value) {
                $employee_industry[] = array(
                    'industry' => $value,
                );
            }
        }

        $employee_skills = array_unique($employee_skills, SORT_REGULAR);
        $employee_certifications = array_unique($employee_certifications, SORT_REGULAR);
        $jobIndustryQuery = array_unique($employee_industry, SORT_REGULAR);
        $universities = array_unique($universities, SORT_REGULAR);
        $university_courses = array_unique($university_courses, SORT_REGULAR);

        $nigerian_states = json_decode($nigerian_states, true);
        $activeCompanies = Company::all();
        $org_section_role = Job::where('company_id', 1)->get()->toArray();

        $this->employee_job_titles = $employee_job_titles;
        $this->employee_job_industry = $jobIndustryQuery;
        $this->universities = $universities;
        $this->states = $nigerian_states;
        $this->employee_skills = $employee_skills;
        $this->employee_certifications = $employee_certifications;
        $this->nigerian_employers = $nigerian_employers;
        $this->companies = $activeCompanies;
        $this->university_courses = $university_courses;
        $this->default_org_section_role = $org_section_role;
        // $this->pageTitle = $this->pageTitle;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!$this->user->can('view_job_applications'), 403);

        $this->boardColumns = ApplicationStatus::with(['applications', 'applications.schedule'])->get();
        $boardStracture = [];
        foreach ($this->boardColumns as $key => $column) {
            $boardStracture[$column->id] = [];
            foreach ($column->applications as $application) {
                $boardStracture[$column->id][] = $application->id;
            }
        }
        $this->boardStracture = json_encode($boardStracture);
        $this->currentDate = Carbon::now()->timestamp;

        return view('admin.job-applications.board', $this->data);
    }

    public function singleCompany($id)
    {
        abort_if(!$this->user->can('view_job_applications'), 403);

        if ($id) {
            $this->boardColumns = ApplicationStatus::all();
            $this->locations = JobLocation::all();
            $this->jobs = Job::all();
            $this->singleEntityId = $id;
            $this->singleEntityIdType = 'company';
            return view('admin.job-applications.index', $this->data);
        }
    }

    public function singleJob(Request $request , $id)
    {
        abort_if(!$this->user->can('view_job_applications'), 403);
        if ($id) {
            $this->boardColumns = ApplicationStatus
            ::where("status", "!=", "phone screen")->get();
            
            $this->locations = JobLocation::all();
            $this->jobs = Job::all();
            $this->singleEntityId = $id;
            $this->singleEntityIdType = 'job';
            $this->jobById =Job::find($id);
            if($request->type == 'ajax' ) {
                return Reply::dataOnly(['status' => 'success', 'data' =>  json_encode($this->jobById)], 200);
            }
            return view('admin.job-applications.index', $this->data);
        }
    }

    public function create()
    {
        abort_if(!$this->user->can('add_job_applications'), 403);
        $jobs = Job::activeJobs(); 
        $this->jobs = $jobs;
        $this->jobsPagination = $jobs->simplePaginate(10);
        return view('admin.job-applications.create', $this->data);
    }

    /**
     * @param $jobID
     * @return mixed
     * @throws \Throwable
     */
    public function jobQuestion($jobID)
    {
        $this->jobQuestion = JobQuestion::with(['question'])->where('job_id', $jobID)->get();
        $view = view('admin.job-applications.job-question', $this->data)->render();
        $count = count($this->jobQuestion);

        return Reply::dataOnly(['status' => 'success', 'view' => $view, 'count' => $count]);
    }


    public function edit($id)
    {
        abort_if(!$this->user->can('edit_job_applications'), 403);

        $this->statuses = ApplicationStatus::all();
        $this->application = JobApplication::find($id);
        $this->jobQuestion = JobQuestion::with(['question'])
            ->where('job_id', $this->application->job_id)->get();

        return view('admin.job-applications.edit', $this->data);
    }

    public function data(Request $request){

        abort_if(!$this->user->can('view_job_applications'), 403);

        $jobApplications = JobApplication::select('job_applications.id', 'job_applications.full_name', 'job_applications.resume', 'job_applications.phone',
            'job_applications.email', 'job_applications.candidate_id', 'jobs.title', 'job_locations.location', 'application_status.status'
            )
            ->with(['status', 'olevel', 'test_groups'])
            ->join('jobs', 'jobs.id', 'job_applications.job_id')
            ->join('candidate_infos', 'candidate_infos.candidate_id', 'job_applications.candidate_id')
            ->leftjoin('job_locations', 'job_locations.id', 'jobs.location_id')
            ->leftjoin('application_status', 'application_status.id', 'job_applications.status_id')
            ->whereNotNull('job_applications.status_id');
            // ->where('job_applications.status_id', '!=', 9); //Online test

        // Filter by company_id
        if ($request->singleEntityId != 'all' && $request->singleEntityId != '' && $request->singleEntityIdType == 'company') {
            $jobApplications = $jobApplications->where('jobs.company_id', $request->singleEntityId);
        }
        // Filter by job_id
        if ($request->singleEntityId != 'all' && $request->singleEntityId != '' && $request->singleEntityIdType == 'job') {
            $jobApplications = $jobApplications->where('job_applications.job_id', $request->singleEntityId);
        }

        // Filter by status
        if ($request->status != 'all' && $request->status != '') {
            $jobApplications = $jobApplications->where('job_applications.status_id', $request->status);
        }

        // Filter By jobs
        if ($request->jobs != 'all' && $request->jobs != '') {
            $jobApplications = $jobApplications->where('job_applications.job_id', $request->jobs);
        }
       
        // Filter by location
        if ($request->location != 'all' && $request->location != '') {
            $jobApplications = $jobApplications->where('jobs.location_id', $request->location);
        }

        // Filter by StartDate
        if ($request->startDate != null && $request->startDate != '') {
            $jobApplications = $jobApplications->where(DB::raw('DATE(job_applications.`created_at`)'), '>=', "$request->startDate");
        }

        // Filter by EndDate
        if ($request->endDate != null && $request->endDate != '') {
            $jobApplications = $jobApplications->where(DB::raw('DATE(job_applications.`created_at`)'), '<=', "$request->endDate");
        }

        if ($request->shortlisting == "shortlisting") {
   
            $skillsQuery = $request->skills;
            $skillsQuery = $skillsQuery ? explode(",", $skillsQuery) : array('');

            if ($skillsQuery[0] != '') {
                  $jobApplications->where(function ($query) use ($skillsQuery) {
                    foreach ($skillsQuery as $skill) {
                    $query->orWhere('skills', 'like', '%' . $skill . '%');
                    }
                 });
            }

            $candidateResidentialStateQuery = $request->candidateResidentialState; //
            $candidateResidentialStateQuery = $candidateResidentialStateQuery ? explode(",", $candidateResidentialStateQuery) : array('');
         
            $candidateStateofOriginQuery = $request->candidate_state_of_origin; //
            $candidateStateofOriginQuery = $candidateStateofOriginQuery ? explode(",", $candidateStateofOriginQuery) : array('');

            $candidatCertificationsQuery = $request->candidate_certifications; //
            $candidatCertificationsQuery = $candidatCertificationsQuery ? explode(",", $candidatCertificationsQuery) : array('');

           
            $candidate_age_lower_bound = $request->input('candidate_age_lower_bound') ? $request->input('candidate_age_lower_bound') : null;
            $candidate_age_higher_bound = $request->input('candidate_age_higher_bound') ? $request->input('candidate_age_higher_bound') : null;

            $candidate_experience_higher_bound = $request->input('candidate_experience_higher_bound') ? $request->input('candidate_experience_higher_bound') : null; 
            $candidate_experience_lower_bound = $request->input('candidate_experience_lower_bound') ? $request->input('candidate_experience_lower_bound') : null;

            $olevel_higher_bound = $request->input('olevel_higher_bound')?  $request->input('olevel_higher_bound') : null;
            $olevel_lower_bound = $request->input('olevel_lower_bound') ? $request->input('olevel_lower_bound') : null;
           
            $relevant_experience_higher_bound = $request->input('relevant_experience_higher_bound') ? $request->input('relevant_experience_higher_bound') : null;
            $relevant_experience_lower_bound = $request->input('relevant_experience_lower_bound') ?  $request->input('relevant_experience_lower_bound') : null;
           

            if ($candidateResidentialStateQuery[0] != '') {
                $jobApplications->whereIn('residence_state', $candidateResidentialStateQuery);
            }

            if ($relevant_experience_lower_bound != null && $relevant_experience_higher_bound != null) {
                $jobApplications->whereBetween('relevant_years_experience', [$relevant_experience_lower_bound, $relevant_experience_higher_bound]);
            }

            if ($olevel_higher_bound != null && $olevel_lower_bound != null) {
                $jobApplications = $jobApplications->whereHas('candidatescores',
                function ($query) use ($olevel_lower_bound, $olevel_higher_bound) {
                          $query->whereBetween('total', [$olevel_lower_bound, $olevel_higher_bound]);
                      });
                };
     
            if ($candidate_experience_lower_bound != null && $candidate_experience_higher_bound != null) {
                $jobApplications->whereBetween('experience_level', [$candidate_experience_lower_bound, $candidate_experience_higher_bound]);
            }

            if ($candidateStateofOriginQuery[0] != '') {
                $jobApplications->whereIn('state', $candidateStateofOriginQuery);
           }

               if ($candidatCertificationsQuery[0] != '') {
                $jobApplications->where(function ($query) use ($candidatCertificationsQuery) {
                    foreach ($candidatCertificationsQuery as $certifications) {
                    $query->orWhere('certifications', 'like', '%' . $certifications . '%');
                    }
                });
            }
            
            if ($candidate_age_lower_bound != null && $candidate_age_higher_bound != null) {
                $jobApplications->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE())'), array($candidate_age_lower_bound, $candidate_age_higher_bound));
            }
            

            $jobTitleQuery = $request->jobTitles;
            $jobTitleQuery = $jobTitleQuery ? explode(",", $jobTitleQuery) : array('');

            $industryQuery = $request->industry;
            $industryQuery = $industryQuery ? explode(",", $industryQuery) : array('');

            $companiesQuery = $request->companies;
            $companiesQuery = $companiesQuery ? explode(",", $companiesQuery) : array('');

            if ($jobTitleQuery[0] != "" || $industryQuery[0] != '' || $companiesQuery[0] != '') {
                $jobApplications = $jobApplications->whereHas('work',
                    function ($q) use ($jobTitleQuery,$industryQuery,$companiesQuery) {
                        
                        if ($jobTitleQuery[0] != ""){
                            $q->where(function ($query) use ($jobTitleQuery) {
                                foreach ($jobTitleQuery as $title) {
                                    $query->orWhere('title', 'like', '%' . $title . '%');
                                }
                            });    
                        }
                        if ($industryQuery[0] != '') {
                                    $q->where(function ($query) use ($industryQuery) {
                                        foreach ($industryQuery as $industry) {
                                            $query->orWhere('industry', 'like', '%' . $industry . '%');
                                        }
                                    });
                        }
                        if ($companiesQuery[0] != '') {
                                $q->where(function ($query) use ($companiesQuery) {
                                        foreach ($companiesQuery as $comp) {
                                            $query->orWhere('company', 'like', '%' . $comp . '%');
                                      }
                                });
                          }
                    }
                );
            }

            $universityQuery = $request->university;
            $universityQuery = $universityQuery ? explode(",", $universityQuery) : array('');

            $candidateCourseQuery = $request->candidateCourse;
            $candidateCourseQuery = $candidateCourseQuery ? explode(",", $candidateCourseQuery) : array('');

            $candidateDegreesQuery = $request->candidateDegrees;
            $candidateDegreesQuery = $candidateDegreesQuery ? explode(",", $candidateDegreesQuery) : array('');

            $candidateQualificationsQuery = $request->candidateQualifications;
            $candidateQualificationsQuery = $candidateQualificationsQuery ? explode(",", $candidateQualificationsQuery) : array('');

            if ($universityQuery[0] != "" || $candidateCourseQuery[0] != '' || $candidateDegreesQuery[0] != '' || $candidateQualificationsQuery[0] != '') {
                $jobApplications = $jobApplications->whereHas('education',
                    function ($q) use ($universityQuery,$candidateCourseQuery,$candidateDegreesQuery, $candidateQualificationsQuery ) {
                        
                        if ($universityQuery[0] != ""){
                            $q->where(function ($query) use($universityQuery) {
                                foreach ($universityQuery as $uni) {
                                    $query->orWhere('institution', 'like', '%' . $uni . '%');
                                }
                            });
                        }
                        if ($candidateCourseQuery[0] != '') {
                            $q->where(function ($query) use ($candidateCourseQuery) {
                                foreach ($candidateCourseQuery as $course) {
                                    $query->orWhere('field_of_study', 'like', '%' . $course . '%');
                                }
                            });
                          }

                         if ($candidateDegreesQuery[0] != '') {
                            $q->whereIn('grade', $candidateDegreesQuery);
                        }

                        if ($candidateQualificationsQuery[0] != '') {
                          $q->whereIn('qualification', $candidateQualificationsQuery);
    
                        }
                    });
            }

       
            $nyscStatusQuery =  $request->input("nysc_strict_result");
            if ($nyscStatusQuery == 'Completed') {
                 $jobApplications->where('nysc_status', '=', $nyscStatusQuery);
            }
        }

        return DataTables::of($jobApplications)
            ->addColumn('action', function ($row) {
                $action = '';
                if ($this->user->can('edit_job_applications')) {
                    $action .= '<a href="' . route('admin.job-applications.edit', [$row->id]) . '" class="btn btn-primary btn-circle"
                      data-toggle="tooltip" data-original-title="' . __('app.edit') . '"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }

                if ($this->user->can('delete_job_applications')) {
                    $action .= ' <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-row-id="' . $row->id . '" data-original-title="' . __('app.delete') . '"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }
                return $action;
            })
            ->editColumn('select_user', function ($row) {
                $test_taker_uri = 'noUri';
                    if(!empty($row->test_groups)){
                    $test_group = $row->test_groups->first();
                    $test_taker_uri =  $test_group['test_taker_uri'] ? $test_group['test_taker_uri'] : 'noUri';
                    }
                return '<input type="checkbox" class="cd-radio-input" id="' . $row->id . '" name="candidate_selected[]" value= "' . $row->id .'|'. $row->email .'|'. $test_taker_uri .'"  </input>';
                // return '<input type="checkbox" class="cd-radio-input" id="' . $row->id . '" name="candidate_selected[]" value= "' . $row->id . '" </input>';
            })
            ->editColumn('full_name', function ($row) {
                // return '<a href="'. route('admin.getCandidateProfile', [$row->candidate_id]) .'" data-row-id="'.$row->id.'">'.ucwords($row->full_name).'</a>';
                return '<a href="javascript:;" class="show-detail" data-widget="control-sidebar" data-slide="true" data-row-id="' . $row->id . '">' . ucwords($row->full_name) . '</a>';
            })
            ->editColumn('email', function ($row) {
                $email = $row->email ? $row->email : '- -';
                return ucfirst($email);
            })
            ->editColumn('resume', function ($row) {
                return '<a href="' . asset($row->resume) . '" target="_blank">' . __('app.view') . ' ' . __('modules.jobApplication.resume') . '</a>';
            })
            ->editColumn('phone', function ($row) {
                $phone = $row->phone ? $row->phone : '- -';
                return ucwords($phone);
            })
            ->editColumn('status', function ($row) {
                $status = is_object($row->status) ? $row->status->status : $row->status;
                return ucwords($status);
            })
            ->rawColumns(['action', 'select_user', 'resume', 'full_name'])
            ->addIndexColumn()
            ->make(true);

    }

    public function createSchedule(Request $request, $id)
    {
        abort_if(!$this->user->can('add_schedule'), 403);
        $this->candidates = JobApplication::all();
        $this->users = User::all();
        $this->scheduleDate = $request->date;
        $this->currentApplicant = JobApplication::findOrFail($id);
        return view('admin.job-applications.interview-create', $this->data)->render();

    }

    public function storeSchedule(StoreRequest $request)
    {
        abort_if(!$this->user->can('add_schedule'), 403);

        $dateTime = $request->scheduleDate . ' ' . $request->scheduleTime;
        $dateTime = Carbon::createFromFormat('Y-m-d H:i', $dateTime);

        // store Schedule
        $interviewSchedule = new InterviewSchedule();
        $interviewSchedule->job_application_id = $request->candidate;
        $interviewSchedule->schedule_date = $dateTime;
        $interviewSchedule->save();

        // Update Schedule Status
        $jobApplication = JobApplication::find($request->candidate);
        $jobApplication->status_id = 3;
        $jobApplication->save();

        if (!empty($request->employee)) {
            InterviewScheduleEmployee::where('interview_schedule_id', $interviewSchedule->id)->delete();
            foreach ($request->employee as $employee) {
                $scheduleEmployee = new InterviewScheduleEmployee();
                $scheduleEmployee->user_id = $employee;
                $scheduleEmployee->interview_schedule_id = $interviewSchedule->id;
                $scheduleEmployee->save();

                $user = User::find($employee);
                // Mail to employee for inform interview schedule
                Notification::send($user, new ScheduleInterview($jobApplication));
            }
        }

        // mail to candidate for inform interview schedule
        // Notification::send($jobApplication, new CandidateScheduleInterview($jobApplication, $interviewSchedule));

        return Reply::redirect(route('admin.interview-schedule.index'), __('menu.interviewSchedule') . ' ' . __('messages.createdSuccessfully'));
    }


    public function store(StoreJobApplication $request)
    {
        abort_if(!$this->user->can('add_job_applications'), 403);

        $jobApplication = new JobApplication();
        $jobApplication->full_name = $request->full_name;
        $jobApplication->job_id = $request->job_id;
        $jobApplication->status_id = 1; //applied status id
        $jobApplication->email = $request->email;
        $jobApplication->phone = $request->phone;
        $jobApplication->cover_letter = $request->cover_letter;
        $jobApplication->column_priority = 0;

        if ($request->hasFile('resume')) {
            $jobApplication->resume = $request->resume->hashName();
            $request->resume->store('user-uploads/resumes');
        }

        if ($request->hasFile('photo')) {
            $jobApplication->photo = $request->photo->hashName();
            $request->photo->store('user-uploads/candidate-photos');
        }
        $jobApplication->save();

        // Job Application Answer save
        if (isset($request->answer) && !empty($request->answer)) {
            JobApplicationAnswer::where('job_application_id', $jobApplication->id)->delete();

            foreach ($request->answer as $key => $value) {
                $answer = new JobApplicationAnswer();
                $answer->job_application_id = $jobApplication->id;
                $answer->job_id = $request->job_id;
                $answer->question_id = $key;
                $answer->answer = $value;
                $answer->save();
            }
        }

        return Reply::redirect(route('admin.job-applications.index'), __('menu.jobApplications') . ' ' . __('messages.createdSuccessfully'));
    }

    private function createTestGroup ($jobApplication) {
         $testTakerEntryExist = jobApplicationTestGroup::where('job_id', '=', $jobApplication->job_id
                )->where('job_application_id', '=', $jobApplication->id)->first();
                $target_job_application_same = false;
                if($testTakerEntryExist) {
                    $target_job_application_same = ($testTakerEntryExist->job_id == $jobApplication->job_id) ? true  : false;
                }

                if(!$target_job_application_same){
                    $testGroup = new jobApplicationTestGroup();
                    $testGroup->job_application_id = $jobApplication->id;
                    $testGroup->job_id = $jobApplication->job_id;
                    $testGroup->save();
                }
    }

    public function updateJobAppStatusById(Request $request, $id)
    {
        abort_if(!$this->user->can('edit_job_applications'), 403);
        $applicationStatus = ApplicationStatus::find($id);
         if($applicationStatus->status == 'online test'){
        
          $jobApplications = JobApplication::where('job_id', $request->jobId)->get();
            foreach ($jobApplications as $jobApplication){
                $jobApplication->status_id = $id;
                $jobApplication->save();
                self::createTestGroup($jobApplication);
            }
        }
        
        if ($id && !empty($request->jobIdArray)) {
            foreach ($request->jobIdArray as $jobId) {
                $jobApplication = JobApplication::find($jobId['applicationId']);
                $jobApplication->status_id = $id;
                $jobApplication->save();
                self::createTestGroup($jobApplication);
            }
            return Reply::dataOnly(['status' => 'success', 'message']);
        } else {
            return Reply::dataOnly(['status' => 'success',]);
        }
    }

    public function update(UpdateJobApplication $request, $id)
    {
        abort_if(!$this->user->can('edit_job_applications'), 403);

        $jobApplication = JobApplication::find($id);
        $jobApplication->full_name = $request->full_name;
        $jobApplication->status_id = $request->status_id;
        $jobApplication->email = $request->email;
        $jobApplication->phone = $request->phone;
        $jobApplication->cover_letter = $request->cover_letter;

        if ($request->hasFile('resume')) {
            $jobApplication->resume = $request->resume->hashName();
            $request->resume->store('user-uploads/resumes');
        }

        if ($request->hasFile('photo')) {
            $jobApplication->photo = $request->photo->hashName();
            $request->photo->store('user-uploads/candidate-photos');
        }

        $jobApplication->save();
        // Job Application Answer save
        if (isset($request->answer) && count($request->answer) > 0) {
            JobApplicationAnswer::where('job_application_id', $jobApplication->id)->delete();
            foreach ($request->answer as $key => $value) {
                $answer = new JobApplicationAnswer();
                $answer->job_application_id = $jobApplication->id;
                $answer->job_id = $jobApplication->job_id;
                $answer->question_id = $key;
                $answer->answer = $value;
                $answer->save();
            }
        }

        return Reply::redirect(route('admin.job-applications.index'), __('menu.jobApplications') . ' ' . __('messages.updatedSuccessfully'));
    }

    public function destroy($id)
    {
        abort_if(!$this->user->can('delete_job_applications'), 403);

        JobApplication::destroy($id);
        return Reply::success(__('messages.recordDeleted'));
    }

    public function show($id)
    {
        $this->application = JobApplication::with(['schedule', 'status', 'schedule.employee', 'schedule.comments.user'])->find($id);
        $this->candidate = CandidateInfo::where('candidate_id', $this->application->candidate_id)->first();
        $this->answers = JobApplicationAnswer::with(['question'])
            ->where('job_id', $this->application->job_id)
            ->where('job_application_id', $this->application->id)
            ->get();

        $view = view('admin.job-applications.show', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function updateIndex(Request $request)
    {
        $taskIds = $request->applicationIds;
        $boardColumnIds = $request->boardColumnIds;
        $priorities = $request->prioritys;

        if (!is_null($taskIds)) {
            foreach ($taskIds as $key => $taskId) {
                if (!is_null($taskId)) {

                    $task = JobApplication::find($taskId);
                    $task->column_priority = $priorities[$key];
                    $task->status_id = $boardColumnIds[$key];

                    $task->save();
                }
            }
        }

        return Reply::dataOnly(['status' => 'success']);
    }

    public function table()
    {
        abort_if(!$this->user->can('view_job_applications'), 403);

        $this->boardColumns = ApplicationStatus::all();
        $this->locations = JobLocation::all();
        $this->jobs = Job::all();
        $this->singleEntityId = '';
        $this->singleEntityIdType = 'job';

        return view('admin.job-applications.index', $this->data);
    }

    public function ratingSave(Request $request, $id)
    {
        abort_if(!$this->user->can('edit_job_applications'), 403);

        $application = JobApplication::findOrFail($id);
        $application->rating = $request->rating;
        $application->save();

        return Reply::success(__('messages.updatedSuccessfully'));
    }

    // Job Applications data Export
    public function export(Request $request, $status, $location,
                           $startDate, $endDate, $jobs, $type)
    {
        $total_education = CandidateEducation::select(DB::raw('count(*) as total'))->groupBy('candidate_id')->orderBy('total', 'desc')->first()->total;
        $total_work = CandidateWorkHistory::select(DB::raw('count(*) as total'))->groupBy('candidate_id')->orderBy('total', 'desc')->first()->total;
        $total_olevel = CandidateOlevel::select(DB::raw('count(*) as total'))->groupBy('candidate_id')->orderBy('total', 'desc')->first()->total;

        $jobApplications = JobApplication::select(
            'job_applications.id',
            'jobs.title',
            'job_applications.full_name',
            'job_applications.email',
            'job_applications.phone',
            'application_status.status',
            'job_applications.created_at',
            'candidate_infos.gender',
            'candidate_infos.date_of_birth', 'candidate_infos.state', 'candidate_infos.residence_state',
            'candidate_infos.lga', 'candidate_infos.certifications', 'candidate_infos.candidate_id',
            'candidate_infos.skills', 'candidate_infos.cv_url', 'candidate_infos.experience_level',
            'candidate_infos.nysc_status', 'candidate_infos.nysc_completion_year'
           )->join('candidate_infos', 'candidate_infos.candidate_id', 'job_applications.candidate_id')
            ->leftJoin('jobs', 'jobs.id', '=', 'job_applications.job_id')
            ->leftJoin('application_status', 'application_status.id', '=', 'job_applications.status_id')
            ->with(['work', 'education', 'olevel'])
            ->withCount(['work', 'education', 'olevel'])
            ->whereNotNull('job_applications.status_id');
            // ->where('job_applications.status_id', '!=', 9); //Online test
            
        if ($request->singleEntityId != 'all' && $request->singleEntityId != '' && $request->singleEntityIdType == 'company') {
            $jobApplications = $jobApplications->where('jobs.company_id', $request->singleEntityId);
        }
        if ($request->singleEntityId != 'all' && $request->singleEntityId != '' && $request->singleEntityIdType == 'job') {
            $jobApplications = $jobApplications->where('job_applications.job_id', $request->singleEntityId);
        }

        $skillsQuery = $request->skills;
        $skillsQuery = $skillsQuery ? explode(",", $skillsQuery) : array('');

        if ($skillsQuery[0] != '') {
              $jobApplications->where(function ($query) use ($skillsQuery) {
                foreach ($skillsQuery as $skill) {
                $query->orWhere('skills', 'like', '%' . $skill . '%');
                }
             });
        }

        $candidateResidentialStateQuery = $request->candidateResidentialState; //
        $candidateResidentialStateQuery = $candidateResidentialStateQuery ? explode(",", $candidateResidentialStateQuery) : array('');
     
        $candidateStateofOriginQuery = $request->candidate_state_of_origin; //
        $candidateStateofOriginQuery = $candidateStateofOriginQuery ? explode(",", $candidateStateofOriginQuery) : array('');

        $candidatCertificationsQuery = $request->candidate_certifications; //
        $candidatCertificationsQuery = $candidatCertificationsQuery ? explode(",", $candidatCertificationsQuery) : array('');

       
        $candidate_age_lower_bound = $request->input('candidate_age_lower_bound') ? $request->input('candidate_age_lower_bound') : null;
        $candidate_age_higher_bound = $request->input('candidate_age_higher_bound') ? $request->input('candidate_age_higher_bound') : null;

        $candidate_experience_higher_bound = $request->input('candidate_experience_higher_bound') ? $request->input('candidate_experience_higher_bound') : null; 
        $candidate_experience_lower_bound = $request->input('candidate_experience_lower_bound') ? $request->input('candidate_experience_lower_bound') : null;

        $olevel_higher_bound = $request->input('olevel_higher_bound')?  $request->input('olevel_higher_bound') : null;
        $olevel_lower_bound = $request->input('olevel_lower_bound') ? $request->input('olevel_higher_bound') : null;
       
        $relevant_experience_higher_bound = $request->input('relevant_experience_higher_bound') ? $request->input('relevant_experience_higher_bound') : null;
        $relevant_experience_lower_bound = $request->input('relevant_experience_lower_bound') ?  $request->input('relevant_experience_lower_bound') : null;
   
        if ($candidateResidentialStateQuery[0] != '') {
            $jobApplications->whereIn('residence_state', $candidateResidentialStateQuery);
        }
   
        if ($relevant_experience_lower_bound != null && $relevant_experience_higher_bound != null) {
            $jobApplications->whereBetween('relevant_years_experience', [$relevant_experience_lower_bound, $relevant_experience_higher_bound]);
        }

        // if ($olevel_higher_bound != null && $olevel_lower_bound != null && is_int($olevel_higher_bound) && is_int($olevel_lower_bound)) {
        //     $jobApplications->whereBetween('experience_level', [$olevel_lower_bound, $olevel_higher_bound]);
        // }

        if ($candidate_experience_lower_bound != null && $candidate_experience_higher_bound != null) {
            $jobApplications->whereBetween('experience_level', [$candidate_experience_lower_bound, $candidate_experience_higher_bound]);
        }

        if ($candidateStateofOriginQuery[0] != '') {
            $jobApplications->whereIn('state', $candidateStateofOriginQuery);
       }

           if ($candidatCertificationsQuery[0] != '') {
            $jobApplications->where(function ($query) use ($candidatCertificationsQuery) {
                foreach ($candidatCertificationsQuery as $certifications) {
                $query->orWhere('certifications', 'like', '%' . $certifications . '%');
                }
            });
        }
            
        if ($candidate_age_lower_bound != null && $candidate_age_higher_bound != null) {
            $jobApplications->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE())'), array($candidate_age_lower_bound, $candidate_age_higher_bound));
        }

        $jobTitleQuery = $request->jobTitles;
        $jobTitleQuery = $jobTitleQuery ? explode(",", $jobTitleQuery) : array('');

        $industryQuery = $request->industry;
        $industryQuery = $industryQuery ? explode(",", $industryQuery) : array('');

        $companiesQuery = $request->companies;
        $companiesQuery = $companiesQuery ? explode(",", $companiesQuery) : array('');

        if ($jobTitleQuery[0] != "" || $industryQuery[0] != '' || $companiesQuery[0] != '') {
            $jobApplications = $jobApplications->whereHas('work',
                function ($q) use ($jobTitleQuery,$industryQuery,$companiesQuery) {
                    
                    if ($jobTitleQuery[0] != ""){
                        $q->where(function ($query) use ($jobTitleQuery) {
                            foreach ($jobTitleQuery as $title) {
                                $query->orWhere('title', 'like', '%' . $title . '%');
                            }
                        });    
                    }

                    if ($industryQuery[0] != '') {
                                $q->where(function ($query) use ($industryQuery) {
                                    foreach ($industryQuery as $industry) {
                                        $query->orWhere('industry', 'like', '%' . $industry . '%');
                                    }
                                });
                    }

                    if ($companiesQuery[0] != '') {
                            $q->where(function ($query) use ($companiesQuery) {
                                    foreach ($companiesQuery as $comp) {
                                        $query->orWhere('company', 'like', '%' . $comp . '%');
                                  }
                            });
                      }
                });
        }
        

        $universityQuery = $request->university;
        $universityQuery = $universityQuery ? explode(",", $universityQuery) : array('');

        $candidateCourseQuery = $request->candidateCourse;
        $candidateCourseQuery = $candidateCourseQuery ? explode(",", $candidateCourseQuery) : array('');

        $candidateDegreesQuery = $request->candidateDegrees;
        $candidateDegreesQuery = $candidateDegreesQuery ? explode(",", $candidateDegreesQuery) : array('');

        $candidateQualificationsQuery = $request->candidateQualifications;
        $candidateQualificationsQuery = $candidateQualificationsQuery ? explode(",", $candidateQualificationsQuery) : array('');

        if ($universityQuery[0] != "" || $candidateCourseQuery[0] != '' || $candidateDegreesQuery[0] != '' || $candidateQualificationsQuery[0] != '') {
            $jobApplications = $jobApplications->whereHas('education',
                function ($q) use ($universityQuery,$candidateCourseQuery,$candidateDegreesQuery, $candidateQualificationsQuery ) {
                    
                    if ($universityQuery[0] != ""){
                        $q->where(function ($query) use($universityQuery) {
                            foreach ($universityQuery as $uni) {
                                $query->orWhere('institution', 'like', '%' . $uni . '%');
                            }
                        });
                    }

                    if ($candidateCourseQuery[0] != '') {
                        $q->where(function ($query) use ($candidateCourseQuery) {
                            foreach ($candidateCourseQuery as $course) {
                                $query->orWhere('field_of_study', 'like', '%' . $course . '%');
                            }
                        });
                      }

                     if ($candidateDegreesQuery[0] != '') {
                        $q->whereIn('grade', $candidateDegreesQuery);
                    }

                    if ($candidateQualificationsQuery[0] != '') {
                      $q->whereIn('qualification', $candidateQualificationsQuery);

                    }
                });
        }
        
        $nyscStatusQuery = $request->input('nysc_strict_result');
        if ($nyscStatusQuery != '') {
             $jobApplications->where('nysc_status', '=', 'Completed');
        }

        if ($status != 'all' && $status != '') {
            $jobApplications = $jobApplications->where('job_applications.status_id', $status);
        }

        // Filter  By Location
        if ($location != 'all' && $location != '') {
            $jobApplications = $jobApplications->where('jobs.location_id', $location);
        }

        // Filter  By Job
        if ($jobs != 'all' && $jobs != '') {
            $jobApplications = $jobApplications->where('job_applications.job_id', $jobs);
        }

        // Filter  By StartDate of job
        if ($startDate != null && $startDate != '' && $startDate != 0) {
            $jobApplications = $jobApplications->where(DB::raw('DATE(jobs.`start_date`)'), '>=', "$startDate");
        }

        // Filter  By EndDate of job
        if ($endDate != null && $endDate != '' && $endDate != 0) {
            $jobApplications = $jobApplications->where(DB::raw('DATE(jobs.`end_date`)'), '<=', "$endDate");
        }

        $jobApplications = $jobApplications->get();
        $exportArray = [];

        if ($type == 'csv') {
          
            $headers = [
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0'
                , 'Content-type' => 'text/csv'
                , 'Content-Disposition' => 'attachment; filename=Recruit Candidates.csv'
                , 'Expires' => '0'
                , 'Pragma' => 'public'
            ];
          
            $exportArray = ['S/N', 'Job Title', 'Name', 'Email', 'Mobile', 'Status', 'Registered On', 'Gender','DOB', 'State', 'L.G.A', 'Residence State',
                'Certifications', 'Skills', 'Resume', 'Experience Level', 'NYSC_Status', 'NYSC Completion Year'];

            $columWorkName = Schema::getColumnListing('candidate_work_histories');
            for ($i = 0; $i < $total_work; $i++) {
                foreach ($columWorkName as $columWorkNamedata) {
                    $exportArray[] = 'work_' . '' . $i . '_' . $columWorkNamedata;
                }
            }

            $columEduName = Schema::getColumnListing('candidate_educations');
            for ($j = 0; $j < $total_education; $j++) {
                foreach ($columEduName as $columnIndex => $columEduNamedata) {
                    $exportArray[] = 'education_' . '' . $j . '_' . $columEduNamedata;
                }
            }

            for ($j = 0; $j < $total_olevel; $j++) {
                $exportArray[] = 'exam_type_' . $j;
                for ($x = 0; $x < 7; $x++) {
                    $exportArray[] = $j . '_subject_' . $x;
                    $exportArray[] = $j . '_subject_' . $x . '_result';
                }
            }
            $columns = $exportArray;
            $callback = function () use ($jobApplications, $columns, $total_work, $total_olevel, $total_education) {
                $file = fopen('php://output', 'w');

                fputcsv($file, $columns);        
                foreach ($jobApplications as $jobApplication) {        
                    $rowdata = $jobApplication->only(['id', 'title', 'full_name', 'email', 'phone', 'status',
                        'created_at', 'gender', 'date_of_birth', 'state', 'lga',  'residence_state', 'certifications', 'skills', 'cv_url', 'experience_level'
                        , 'nysc_status', 'nysc_completion_year']);             
                    $wc = 0;
                    $wr_size = 0;
                    foreach ($jobApplication->work as $work) {
                        $wr_size = sizeof($work->toArray());
                        $rowdata = array_merge($rowdata, array_values($work->toArray()));
                        $wc++;
                    }
                    $total_rem_work = $total_work - $wc;
                    for ($y = 0; $y < $total_rem_work; $y++) {
                        $rowdata = array_merge($rowdata, array_fill(0, $wr_size, null));
                    }


                    $ec = 0;
                    $edu_size = 0;
                    foreach ($jobApplication->education as $education) {
                        $edu_size = sizeof($education->toArray());
                        $rowdata = array_merge($rowdata, array_values($education->toArray()));
                        $ec++;
                    }
                    $total_rem_edu = $total_education - $ec;

                    for ($y = 0; $y < $total_rem_edu; $y++) {
                        $rowdata = array_merge($rowdata, array_fill(0, $edu_size, null));
                    }

                    $olc = 0;
                    $olev_size = 0;
                    foreach ($jobApplication->olevel as $olevel) {
                        $olev_size = 3;
                        $rowdata[] = $olevel->type;
                        foreach ($olevel->results as $oresult) {
                            $rowdata = array_merge($rowdata, [$oresult->subject, $oresult->grade]);
                        }
                        $olc++;
                    }
                    $total_rem_olev = $total_olevel - $olc;

                    for ($y = 0; $y < $total_rem_olev; $y++) {
                        $rowdata = array_merge($rowdata, array_fill(0, $olev_size, null));
                    }
                    fputcsv($file, $rowdata);
                }
                fclose($file);
            };
            $response = new StreamedResponse($callback, 200, $headers);
            return $response;
            //return Response::stream($callback, 200, $headers)->sendContent();
        }
        exit;
        if ($type == 'xlsx') {

            $defaultColumnsArray = array();
            $workcount = [];
            $educationcount = [];
            $candidateOlevelsCount = [];
            $CandidateOlevels = [];

            foreach ($jobApplications as $row) {

                $candidate_id = $row->candidate_id;
                $candidateWorkHistoryQuery = CandidateWorkHistory::where('candidate_id', $candidate_id);
                $candidateEducationQuery = CandidateEducation::where('candidate_id', $candidate_id);
                $candidateOlevelQuery = CandidateOlevel::where('candidate_id', $candidate_id);
                array_push($CandidateOlevels, $candidateOlevelQuery->with('results')->get());
                if ($candidateWorkHistoryQuery && $candidateEducationQuery) {
                    $workcount[] = $candidateWorkHistoryQuery->count();
                    $educationcount[] = $candidateEducationQuery->count();
                    $candidateOlevelsCounts[] = $candidateOlevelQuery->count();
                }
            }
            sort($workcount);
            sort($educationcount);
            sort($candidateOlevelsCounts);


            $tempwork = [];
            array_push($tempwork, 'S/N', 'Job Title', 'Name', 'Email', 'Mobile', 'Status', 'Registered On', 'Gender', 'DOB', 'State', 'L.G.A',
                'Certifications', 'Candidate ID', 'Skills', 'Resume', 'Experience Level', 'NYSC_Status', 'NYSC Completion Year');

            $columWorkName = Schema::getColumnListing('candidate_work_histories');
            for ($i = 0; $i < count($workcount); $i++) {
                foreach ($columWorkName as $columWorkNamedata) {
                    $tempwork[] = 'work_' . '' . $i . '_' . $columWorkNamedata;
                }
            }

            $columEduName = Schema::getColumnListing('candidate_educations');
            for ($j = 0; $j < count($educationcount); $j++) {
                foreach ($columEduName as $columnIndex => $columEduNamedata) {
                    $tempwork[] = 'education_' . '' . $j . '_' . $columEduNamedata;
                }
            }

            $columOlevelName = Schema::getColumnListing('candidate_olevels');
            array_push($columOlevelName, 'scores');

            for ($k = 0; $k < count($candidateOlevelsCounts); $k++) {
                foreach ($columOlevelName as $columOlevelNamedata) {
                    $tempwork[] = 'exam_' . '' . $k . '_' . $columOlevelNamedata;
                }
            }

            $defaultColumnsArray[] = $tempwork;

            foreach ($jobApplications as $row) {
                $rowToArrayOriginal = $row->toArray();
                $rowToArray = [];
                foreach ($rowToArrayOriginal as $rowToArrayOriginalData) {
                    if (!is_array($rowToArrayOriginalData)) {
                        array_push($rowToArray, $rowToArrayOriginalData);
                    }
                }

                $remove = ['work', 'education', 'olevel'];

                $tempWork = $rowToArrayOriginal['work'];
                $tempWorkArray = [];
                foreach ($tempWork as $tempWorkData) {
                    array_push($tempWorkArray, $tempWorkData);
                }

                $expectedWork = (count($columWorkName)) * count($workcount);
                $currentWork = count(array_flatten($tempWorkArray));
                $workLeft = $expectedWork - $currentWork;
                $flattened_work_array = array_flatten($tempWorkArray);

                for ($l = 0; $l < $workLeft; $l++) {
                    array_push($flattened_work_array, null);
                }

                $tempEdu = $row->toArray()['education'];
                $tempEduArray = [];
                foreach ($tempEdu as $tempEduData) {
                    array_push($tempEduArray, $tempEduData);
                }

                $expectedEdu = (count($columEduName)) * count($educationcount);
                $currentEdu = count(array_flatten($tempEduArray));
                $eduLeft = $expectedEdu - $currentEdu;
                $flattened_edu_array = array_flatten($tempEduArray);
                for ($m = 0; $m < $eduLeft; $m++) {
                    array_push($flattened_edu_array, null);
                }

                $tempOlevelArray = [];
                foreach ($CandidateOlevels as $CandidateOlevelsRow) {
                    foreach ($CandidateOlevelsRow as $CandidateOlevel) {
                        if (!empty($CandidateOlevel->toArray()['results'])) {

                            $tempOlevelArrayRaw = $CandidateOlevel->toArray();
                            $tempOlevelsResults = $CandidateOlevel->toArray()['results'];

                            $tempOlevelResultsArray = [];
                            foreach ($tempOlevelsResults as $a => $data) {

                                $removeOlevelfields = ['id', 'uuid', 'olevel', 'created_at', 'updated_at'];
                                $filteredata = array_diff_key($data, array_flip($removeOlevelfields));
                                array_push($tempOlevelResultsArray, $filteredata);
                            }

                            $olevelString = json_encode($tempOlevelResultsArray);
                            $tempOlevelArrayRaw['results'] = $olevelString;
                            array_push($tempOlevelArray, $tempOlevelArrayRaw);

                        }
                    }
                }

                $CandidateOlevels = [];
                $expectedOlevel = (count($columOlevelName)) * count($candidateOlevelsCounts);
                $currentOlevel = count(array_flatten($tempOlevelArray));
                $olevelLeft = $expectedOlevel - $currentOlevel;
                $flattened_olevel_array = array_flatten($tempOlevelArray);
                for ($n = 0; $n < $olevelLeft; $n++) {
                    array_push($flattened_olevel_array, null);
                }

                $defaultColumnsArray[] = array_merge(array_diff_key($rowToArray, array_flip($remove)), $flattened_work_array, $flattened_edu_array, $flattened_olevel_array);
            }

            Excel::create('job-applications', function ($excel) use ($defaultColumnsArray) {
                $excel->setTitle('Job Applications');
                $excel->setCreator('Recruit')->setCompany($this->companyName);
                $excel->setDescription('job-applications file');

                $excel->sheet('CandidateInfo', function ($sheet) use ($defaultColumnsArray) {
                    $sheet->fromArray($defaultColumnsArray, null, 'A1', false, false);
                    $sheet->row(1, function ($row) {
                        $row->setFont(array(
                            'bold' => true
                        ));

                    });
                });
            })->download('xlsx');

        }
    }

}
