<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use App\Mail\sendgridEmail;
use App\Http\Controllers\Admin\AdminBaseController;
use App\ThemeSetting;
use App\CompanySetting;
use App\CandidateOlevel;
use Response;
use App\Company;
use App\JobLocation;
use App\Job;
use App\JobApplication;
use App\CandidateInfo;
use Carbon\Carbon;
use App\CandidateWorkHistory;
use Yajra\DataTables\Facades\DataTables;
use App\CandidateEducation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends AdminBaseController
{
    private $candidate; use AuthenticatesUsers;

    public function __construct()
    {
        $this->pageTitle =  __('menu.candidatePool');
        $this->adminTheme = ThemeSetting::first();
        $this->global = CompanySetting::first();

        $this->user = auth()->user();

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

        $this->boardColumns = CandidateWorkHistory::all();
        $this->locations = JobLocation::all();
        $this->jobs = Job::all();
        $this->singleEntityId = '';
        $this->singleEntityIdType = 'job';
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
        $this->companyName = $this->global->company_name;
    }
  
    public function showDashboardPage(Request $request) {
        $candidateUser = Auth::User();
              return view('candidate.account.dashboard')
                ->with(['candidateUser' => $candidateUser]);
    }

    public function showCandidatesPage(Request $request) {
        $this->user = Auth::User();
        return view('candidate.account.candidates', $this->data);
    }

    public function showCandidatesData(Request $request) {
        // abort_if(!$this->user->can('view_jobs'), 403);

        $results = $this->shortlistcandidate($request);
      
        return DataTables::of($results)
            ->editColumn('select_user', function ($row) {
         
                return '<input type="checkbox" class="cd-radio-input" id="' . $row->id . '" name="candidate_selected[]" value= "' . $row->id . '" </input>';
            })
            ->editColumn('first_name', function ($row) {
            return '<a href="'.asset('candidate/profile/'.$row->candidate_id).'" target="_blank">'.ucfirst($row->firstname).'</a>';
             
            })
            ->editColumn('last_name', function ($row) {
                return '<a href="'.asset('candidate/profile/'.$row->candidate_id).'" target="_blank">'.ucfirst($row->lastname).'</a>';
             
            })
            ->editColumn('email', function ($row) {
              
                $email = $row->email ? $row->email : '- -';
                return ucfirst($email);
            })
            ->editColumn('resume', function ($row) {
                if ($row->cv_url) {
                    return '<a href="' . asset($row->cv_url) . '" target="_blank">' . __('app.view') . ' ' . __('modules.jobApplication.resume') . '</a>';
                } else {
                    return '- -';
                }
                
            })
            ->editColumn('phone', function ($row) {
               
                $phone_number = $row->phone_number ? $row->phone_number : '- -';
                return ucwords($phone_number);
            })
            ->editColumn('job_title', function ($row) {
                $work = $row->toArray()['work'];
                    if (!empty($work)) {
                        foreach ($work as $key => $value) {
                          $job_title = ($work[$key]['current']) == 1 ?  $work[$key]['title'] : $work[0]['title'];
                        }
                    } else {
                        $job_title = '- -';
                    }
                    $job_title = '- -';
                return ucwords($job_title);
                
            })
            ->rawColumns(['select_user', 'resume', 'first_name', 'last_name'])
            ->addIndexColumn()
            ->make(true);
            
    }

    public function getOrganisationJobs(Request $request){
        $org = trim($request->org);
        $openRoles = Job::where('company_id', $org)->get()->toArray();
        return response()->json(['response' => $openRoles ], 200);
    }

    public function getCandidateProfile(Request $request){
        $id = trim($request->id);
        $AdminUser = Auth::User();
        
        $candidateProfile = CandidateInfo::where('candidate_id', $id);
        if($candidateProfile->get()->isEmpty()) {
            return redirect()->back()->withErrors(['error'=>'Candidate does not have candidate info yet']);
        }
        $candidateProfile = $candidateProfile->with(['user', 'education', 'work', 'documents', 'documents.type',])->get()->first()->toArray();
        $candidateOlevels =  CandidateOlevel::where('candidate_id', $candidateProfile['candidate_id'])->with('results')->get()->toArray();
        return view('candidate.account.candidate')
            ->with(['candidateUser' => $candidateProfile, 'candidateOlevels' => $candidateOlevels,
            'pageTitle' =>  $this->pageTitle, 'adminTheme' => $this->adminTheme, 'user' => $AdminUser, 'global' => $this->global,
            'companyName' => $this->companyName
            ]);

    }

    public function sendemailtocandidate(Request $request, sendgridEmail $emailService){
        $data = $request;
        if(empty($data)){
            return response()->json(['response' => 0 ], 400);
        }
        if(!empty($data)){
            $subject = $request->input('subject');
            $message = $request->input('message');
            $candidateChecked = $request->input('candidateChecked');

           /*
           From Job Application Context
           */
           if($request->src == "jobapplication") {
           foreach ($candidateChecked as $jobApplicationId) {
            $jobApplication = JobApplication::where('id', $jobApplicationId)->first();
            if($jobApplication){
                $userExists = User::where('email',  $jobApplication->email)->first();
                if($userExists) {
                     $emailService->sendPoolCandidateEmail($userExists, $subject, $message);
                }
             }
           }
           return response()->json(['response' => 1 ], 200);
         } else  {
                /*
                From Candidate Info Context
                */
                foreach ($candidateChecked as $candidate) {
                    $userExists = User::where('id', $candidate)->first();
                    if($userExists) {
                        $emailService->sendPoolCandidateEmail($userExists, $subject, $message);
                }
             }
            return response()->json(['response' => 1 ], 200);
         }
      }
    }

    public function assignjobtocandidate(Request $request) {
        $data = $request;
        if(empty($data)){
            return response()->json(['response' => 0 ], 400);
        }
        if(!empty($data)){
            $candidateChecked = $data->input('candidateChecked');
            $org_section_role = $data->input('org_section_role');
            $target_org =  $data->input('target_org');

            foreach ($candidateChecked as $candidate) {
                $userExists = User::where('id', $candidate)->first();
                $candidateInfo = CandidateInfo::where('user_id', $userExists->id)->first();
                
                if($userExists->id && $candidateInfo->candidate_id) {
                    
                    $jobApplicationExits = JobApplication::where('email', '=',  $userExists->email)->where('job_id', '=', $org_section_role)->first();
                    $target_job_same = false;
                    if($jobApplicationExits) {
                     $target_job_same = ($jobApplicationExits->job_id == $org_section_role) ? true  : false;
                    }
                     if(!$target_job_same) {
                        $jobApplication = new JobApplication();
                        $jobApplication->full_name = $userExists->firstname .' '.  $userExists->lastname;
                        $jobApplication->job_id = $org_section_role;
                        $jobApplication->candidate_id = $candidateInfo->candidate_id;
                        $jobApplication->status_id = 1;
                        $jobApplication->email = trim($userExists->email);
                        $jobApplication->column_priority = 0;
                        $jobApplication->save();
                     }
                 }
           }
        return response()->json(['response' => 1 ], 200);
        }
    }

    public function shortlistcandidate($request){
     
       $users = CandidateInfo::join('users', 'users.id', '=', 'candidate_infos.user_id')->with(['work']);
       $skillsQuery = $request->skills;
       $skillsQuery = $skillsQuery ? explode(",", $skillsQuery) : array('');
       if ($skillsQuery[0] != '') {
             $users->where(function ($query) use ($skillsQuery) {
               foreach ($skillsQuery as $skill) {
               $query->orWhere('skills', 'like', '%' . $skill . '%');
               }
            });
       }

       $jobTitleQuery = $request->jobTitles;
       $jobTitleQuery = $jobTitleQuery ? explode(",", $jobTitleQuery) : array('');

       $industryQuery = $request->industry;
       $industryQuery = $industryQuery ? explode(",", $industryQuery) : array('');

       $companiesQuery = $request->companies;
       $companiesQuery = $companiesQuery ? explode(",", $companiesQuery) : array('');

    if ($jobTitleQuery[0] != "" || $industryQuery[0] != '' || $companiesQuery[0] != '') {
        $users = $users->whereHas('work',
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
        $users->whereIn('residence_state', $candidateResidentialStateQuery);
    }

    if ($relevant_experience_lower_bound != null && $relevant_experience_higher_bound != null) {
        $users->whereBetween('relevant_years_experience', [$relevant_experience_lower_bound, $relevant_experience_higher_bound]);
    }

    if ($olevel_higher_bound != null && $olevel_lower_bound != null) {
        $users = $users->whereHas('candidatescores',
        function ($query) use ($olevel_lower_bound, $olevel_higher_bound) {
                  $query->whereBetween('total', [$olevel_lower_bound, $olevel_higher_bound]);
              });
        };

    if ($candidate_experience_lower_bound != null && $candidate_experience_higher_bound != null) {
        $users->whereBetween('experience_level', [$candidate_experience_lower_bound, $candidate_experience_higher_bound]);
    }

    if ($candidateStateofOriginQuery[0] != '') {
        $users->whereIn('state', $candidateStateofOriginQuery);
   }

       if ($candidatCertificationsQuery[0] != '') {
        $users->where(function ($query) use ($candidatCertificationsQuery) {
            foreach ($candidatCertificationsQuery as $certifications) {
            $query->orWhere('certifications', 'like', '%' . $certifications . '%');
            }
        });
    }
        
    if ($candidate_age_lower_bound != null && $candidate_age_higher_bound != null) {
        $users->whereBetween(DB::raw('TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE())'), array($candidate_age_lower_bound, $candidate_age_higher_bound));
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
        $users = $users->whereHas('education',
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


    $jobTitleQuery = $request->jobTitles;
    $jobTitleQuery = $jobTitleQuery ? explode(",", $jobTitleQuery) : array('');

    $industryQuery = $request->industry;
    $industryQuery = $industryQuery ? explode(",", $industryQuery) : array('');

    $companiesQuery = $request->companies;
    $companiesQuery = $companiesQuery ? explode(",", $companiesQuery) : array('');

    if ($jobTitleQuery[0] != "" || $industryQuery[0] != '' || $companiesQuery[0] != '') {
        $users = $users->whereHas('work',
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

    $nyscStatusQuery =  $request->input("nysc_strict_result");
    if ($nyscStatusQuery == 'Completed') {
         $users->where('nysc_status', '=', $nyscStatusQuery);
    }
    return $users;
 
        /** USERS JSON RESPONSE */
        if($users) {
          return response()->json(['response' => $users ], 200);
        }
        
        return response()->json(['response' => 0 ], 400);
    }

    public function downloadCandidateCSV(Request $request) {
        $total_education = CandidateEducation::select(DB::raw('count(*) as total'))->groupBy('candidate_id')->orderBy('total', 'desc')->first()->total;
        $total_work = CandidateWorkHistory::select(DB::raw('count(*) as total'))->groupBy('candidate_id')->orderBy('total', 'desc')->first()->total;
        $total_olevel = CandidateOlevel::select(DB::raw('count(*) as total'))->groupBy('candidate_id')->orderBy('total', 'desc')->first()->total;

        $results = $this->shortlistcandidate($request)->get();
        $exportArray = [];
            $headers = [
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0'
                , 'Content-type' => 'text/csv'
                , 'Content-Disposition' => 'attachment; filename=Recruit Candidates.csv'
                , 'Expires' => '0'
                , 'Pragma' => 'public'
            ];

            $exportArray = ['S/N', 'First Name', 'Last Name', 'Email', 'Mobile', 'Status', 'Registered On', 'DOB', 'State', 'L.G.A',
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
             
            $callback = function () use ($results, $columns, $total_work, $total_olevel, $total_education) {
                $file = fopen('php://output', 'w');

                fputcsv($file, $columns);    
                 
                foreach ($results as $result) {
                      
                    $rowdata = $result->only(['id', 'firstname', 'lastname', 'email', 'phone_number', 'status',
                        'created_at', 'date_of_birth', 'state', 'lga', 'certifications', 'skills', 'cv_url', 'experience_level'
                        , 'nysc_status', 'nysc_completion_year']);
                    
                    $wc = 0;
                    $wr_size = 0;
                    foreach ($result->work as $work) {
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
                    foreach ($result->education as $education) {
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
                    foreach ($result->olevel as $olevel) {
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
        
        exit;
    }

    public function logout(Request $request)
    {
        Auth::guard('candidate')->logout();
        //$request->session()->invalidate();
        return redirect('/candidate/login');
    }
    /*
    Only for testing purposes
    */
    public function testMail( sendgridEmail $emailService){
        $emailService->testMessage();
    }

    public function getCandidate($candidate){
        return $candidate->join('role_user', 'role_user.user_id', '=', 'users.id')
        ->where('role_user.role_id', 2)
        ->select('users.id', 'users.name', 'users.email', 'users.created_at', 'users.verified')
        ->distinct('users.id')
        ->get();
        }
}
