<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\JobApplicationTestGroup;
use App\taoGroupToJobs;
use App\Http\Controllers\Controller;
use App\Job;
use Yajra\DataTables\Facades\DataTables;
use App\Company;
use App\JobApplication;
use App\Helper\Reply;
use GuzzleHttp\Cookie\CookieJar;
use App\Mail\sendgridEmail;
use App\ApplicationStatus;

class AdminAssessmentController extends AdminBaseController
{

     public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('menu.jobApplications');
        $this->pageIcon = 'icon-user';
        $this->companies = Company::all();
        $this->totalJobs = Job::count();
        $this->activeJobs = Job::where('status', 'active')->count();
        $this->inactiveJobs = Job::where('status', 'inactive')->count();

        $applicationStatus =  ApplicationStatus::where('status', 'online test')->first();
        if($applicationStatus){
            $this->taoStatus  = $applicationStatus->id ;
        }else {
            $this->taoStatus = 9;
        }
        
        $this->setTTuri = config('taoconnector.TTuri') ? config('taoconnector.TTuri')  : 'http_2_tao_0_alldayhr_0_com_1_alldayhrtao_0_rdf_3_';
        $this->toaUrlPrefix = config('taoconnector.urlprefix') ? config('taoconnector.urlprefix') : 'http://tao.alldayhr.com' ;
        $this->toaSubdomain = config('taoconnector.toaSubdomain') ? config('taoconnector.toaSubdomain') : 'alldayhrtao';
        $this->client = new \GuzzleHttp\Client([
                'cookies' => true
            ]);
        $this->api_key = password_hash('q2pi804kps4pvqnrr2k65dtkg2', PASSWORD_DEFAULT);
        $this->jar = new \GuzzleHttp\Cookie\CookieJar();
        $this->credentials = array(
                'loginForm_sent' => 1,
                'login' =>  config('taoconnector.taoLogin') ? config('taoconnector.taoLogin')  : 'admin',
                'password' => config('taoconnector.taoPass') ? config('taoconnector.taoPass') : 'password123'
        );
     //TAO ADMIN LOGIN: Bad implementation but a dynamic solution will be implmented soon
    }


    public function getTestTakers(Request $request, $id) {
        $testGroup = new JobApplicationTestGroup();
        $this->jobById =Job::find($id);
        $this->singleEntityId = $id;
        $this->singleEntityIdType = 'job';
        $this->testGroupCount = $testGroup::where('job_id', '=',$id)->get()->count();


           $jobId = $id;
           $jobApplications = JobApplication::select('job_applications.id', 'job_applications.full_name', 'job_applications.resume', 'job_applications.job_id', 'candidate_infos.profile_image_url', 'job_applications.phone',
            'job_applications.email', 'job_applications.candidate_id', 'jobs.title', 'application_status.status'
            )
            ->with(['status', 'olevel','test_groups'])
            ->join('jobs', 'jobs.id', 'job_applications.job_id')
            ->join('job_application_test_groups', 'job_application_id','job_applications.id')
            ->join('candidate_infos', 'candidate_infos.candidate_id', 'job_applications.candidate_id')
            ->leftjoin('application_status', 'application_status.id', 'job_applications.status_id')
            ->whereNotNull('job_applications.status_id')
            ->where('jobs.id', '=', $jobId )
            ->where('job_applications.status_id', '=', $this->taoStatus)
            ->whereNotNull('test_taker_uri');
    
            $this->tts = $jobApplications->paginate(1);
    
        return view('admin.assessment.test-takers', $this->data)->with(
            ['tts' => $this->tts]
        );
   }

   public function getTestsOnTao(Request $request){
        /*Securing our connection with TAO*/

      
        $setTTuri = $this->setTTuri;
        $m = self::getTaoToken();
        if(!$m) {
            return Reply::error('Cannot connect to tao at this moment');
            exit(0);
        }

        $taoCookieToken = $m['Name'].'='. $m['Value'];
            $toaHeaders = ['ta_H9om7MJX' => $this->api_key,
                                    'X-Requested-With' => 'XMLHttpRequest',
                                    'Accept' => 'application/json',
                                    'Cookie' => $taoCookieToken
            ];

        $getTaoTestsUrl = $this->toaUrlPrefix .''. '/taoTests/Tests/getOntologyData';
        $getTaoTestsResponse = $this->client->get($getTaoTestsUrl, [
            'headers' => $toaHeaders,
            ]);

   
                  
        $taoTestsBody = $getTaoTestsResponse->getBody()->getContents();
        $taoTestsBody = json_decode($taoTestsBody, true);
      
        if($taoTestsBody['tree']){
             if($taoTestsBody['tree']){
                  return Reply::dataOnly(['status' => 'success', 'data' => $taoTestsBody['tree'] ]);
                //  return ();
             }

        }
      
        
   }


   public function getTestTakersData(Request $request) {
       
      //Confirm the status id of the online test
       
        $jobId =  $request->singleEntityId;
        $jobApplications = JobApplication::select('job_applications.id', 'job_applications.full_name', 'job_applications.resume', 'job_applications.job_id', 'candidate_infos.profile_image_url', 'job_applications.phone',
            'job_applications.email', 'job_applications.candidate_id', 'jobs.title', 'application_status.status'
            )
            ->with(['status', 'olevel','test_groups'])
            ->join('jobs', 'jobs.id', 'job_applications.job_id')
            ->join('candidate_infos', 'candidate_infos.candidate_id', 'job_applications.candidate_id')
            ->leftjoin('application_status', 'application_status.id', 'job_applications.status_id')
            ->whereNotNull('job_applications.status_id')
            ->where('jobs.id', '=', $jobId )
            ->where('job_applications.status_id', '=', $this->taoStatus); //Online test

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
        })
        ->editColumn('full_name', function ($row) {
            
             $profileImage = $row->profile_image_url ? $row->profile_image_url :  asset('/auth_assets/images/avatar.png');
             return '<div style="display:inline-block;margin: 0px 15px 0px 0px;" class="image"> <img style="width: 20px;height: 20px;" src="' . $profileImage . '" class="img-circle elevation-2" alt="User Image"> </div>
              <a href="javascript:;" class="show-detail" data-widget="control-sidebar" data-slide="true" data-row-id="' . $row->id . '">' . ucwords($row->full_name) . '</a>';
        })
        ->editColumn('test_status', function ($row) {
            // $phone = $row->phone ? $row->phone : '- -';
            $tt_delivery_status = 'Not Delivered';
            if(!empty($row->test_groups)){
              $test_group = $row->test_groups->first();
              $tt_delivery_status = $test_group['delivery_status'] != '' ?  $test_group['delivery_status'] : 'Not Delivered';
            }
            return ucwords($tt_delivery_status);
        })
        ->editColumn('test_platform', function ($row) {
            $test_taker_uri_status = 'Not Added';
            if(!empty($row->test_groups)){
              $test_group = $row->test_groups->first();
             $test_taker_uri_status = $test_group['test_taker_uri'] ? 'Added' : 'Not Added';
            }
            return ucwords($test_taker_uri_status);
        })
        ->editColumn('results', function ($row) {
            $test_taker_uri_status = 'Undefined';
            if(!empty($row->test_groups)){
              $test_group = $row->test_groups->first();
             $test_taker_uri_status = $test_group['delivery_status'] == 'Delivered' ? 'Result Pending' : 'Undefined';
            }
            return ucwords($test_taker_uri_status);
        })
        ->editColumn('total_score', function ($row) {
            return ucwords('No Scores Yet');
        })

        ->rawColumns(['action', 'select_user', 'total_score', 'full_name'])
        ->addIndexColumn()
        ->make(true);
   }


    public function getJobs()  {
        return view('admin.assessment.get-jobs', $this->data);
    }

    public function getJobsData()  {
               
            abort_if(!$this->user->can('view_jobs'), 403);

            $jobs = Job::where('id', '>', '0');
            // ->where('status', 'active'); PS: We could display based on expired, active, and etc.

            if (\request('filter_company') != "") {
                $jobs->where('company_id', \request('filter_company'));
            }

            if (\request('filter_status') != "") {
                $jobs->where('status', \request('filter_status'));
            }

            $jobs->get();

        return DataTables::of($jobs)
            ->addColumn('action', function ($row) {
                $action = '';

                if ($this->user->can('edit_jobs')) {
                    $action .= '<a href="' . route('admin.jobs.edit', [$row->id]) . '" class="btn btn-primary btn-circle"
                        data-toggle="tooltip" data-original-title="' . __('app.edit') . '"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }

                if ($this->user->can('delete_jobs')) {
                    $action .= ' <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                        data-toggle="tooltip" data-row-id="' . $row->id . '" data-original-title="' . __('app.delete') . '"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }
                return $action;
            })
            ->editColumn('title', function ($row) {
                return '<a href="'.asset('admin/job-assessment/tt/'.$row->id).'">'.ucfirst($row->title).'</a>';
            })
            ->editColumn('company_id', function ($row) {
                return ucfirst($row->company->company_name);
            })
            ->editColumn('location_id', function ($row) {
                return ucfirst($row->location->location) . ' (' . $row->location->country->country_code . ')';
            })
            ->editColumn('start_date', function ($row) {
                return $row->start_date->format('d M, Y');
            })
            ->editColumn('end_date', function ($row) {
                return $row->end_date->format('d M, Y');
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'active') {
                    return '<label class="badge bg-success">' . __('app.active') . '</label>';
                }
                if ($row->status == 'inactive') {
                    return '<label class="badge bg-danger">' . __('app.inactive') . '</label>';
                }
            })
            ->rawColumns(['status', 'action', 'company_id', 'title'])
            ->addIndexColumn()
            ->make(true);
            }

        
    public function getTestTakersOnTao(Request $request){

        $jobId =  $request->jobId;
        $keyword = $request->search; 
        $jobApplications = JobApplication::select('job_applications.id', 'job_applications.full_name', 'job_applications.resume', 'job_applications.job_id', 'candidate_infos.profile_image_url', 'job_applications.phone',
            'job_applications.email', 'job_applications.candidate_id', 'jobs.title', 'application_status.status'
            )
            ->with(['status', 'olevel','test_groups'])
            ->join('jobs', 'jobs.id', 'job_applications.job_id')
            ->join('candidate_infos', 'candidate_infos.candidate_id', 'job_applications.candidate_id')
            ->join('job_application_test_groups', 'job_application_id','job_applications.id')
            ->leftjoin('application_status', 'application_status.id', 'job_applications.status_id')
            ->whereNotNull('job_applications.status_id')
            ->where('jobs.id', '=', $jobId )
            ->where('job_applications.status_id', '=', $this->taoStatus)
            ->whereNotNull('test_taker_uri')
            ->where('full_name', 'like', '%' . $keyword . '%');

              $tts = $jobApplications->paginate(1);
        return Reply::dataOnly(['status' => 'success', 'data' => $tts ]);
    }



    public function getTestPlatformStatus()
        {
            abort_if(!$this->user->can('view_job_applications'), 403);
            return 1;
        }

        public function getTestDeliveryStatus()
        {
            abort_if(!$this->user->can('view_job_applications'), 403);
        }

        public function getTestResults()
        {
            abort_if(!$this->user->can('view_job_applications'), 403);
        }

        public function getTestAssignedExamScore()
        {
            abort_if(!$this->user->can('view_job_applications'), 403);
        }

    public function createDeliveryOnTao(Request $request){

        $setTTuri = $this->setTTuri;
        $job = Job::find($request->jobId);
        if($job) {
            $jobTitle  = $job->title;
            $groupName = 'Job Group '.$request->jobId.':'.$jobTitle;
        } else {
           return Reply::error('Job not found');
        }
        $m = self::getTaoToken();
        if(!$m) {
            return Reply::error('Cannot connect to tao at this moment');
            exit(0);
        }

        $tao_deliveries_subclass_name = 'Job Deliveries ' .$request->jobId.':'. $jobTitle;
        $tao_delivery_name = 'Job Delivery ' .$request->jobId.':'. $jobTitle;
        $taoHiddenDeliveriesSubClass = taoGroupToJobs::where('tao_sub_class', $tao_deliveries_subclass_name)->first();
    
        $tao_ttakers_subclass_name = 'Job Takers ' .$request->jobId.':'. $jobTitle;
        $taoHiddenGroupSubClass = taoGroupToJobs::where('tao_sub_class', 'tao_hidden_class_group')->first();
        
        if($taoHiddenDeliveriesSubClass){
          $classUri = $taoHiddenDeliveriesSubClass->tao_group_uri;
        }
       
        $candidateChecked =  $request->candidateChecked;
        $excludeUsers = $request->excludeUsers; 
        $end_date = $request->end_date; 
        $start_date = $request->start_date; 

       
        $taoDeliveryData =  $request->taoDeliveryData; 

        $keywords = preg_split("/[\s,=,&]+/", $taoDeliveryData);
        $taoDeliveryDataArr = array();

        for ($i=0; $i< sizeof($keywords); $i++) {
           $taoDeliveryDataArr[$keywords[$i]] = $keywords[++$i];
        }

        $tao_test = $taoDeliveryDataArr['available_tao_tests'];
        $tao_delivery_title =  $taoDeliveryDataArr['tao_delivery_title'];


        $taoCookieToken = $m['Name'].'='. $m['Value'];
        $toaHeaders = ['ta_H9om7MJX' => $this->api_key,
                                    'X-Requested-With' => 'XMLHttpRequest',
                                    'Accept' => 'application/json',
                                    'Cookie' => $taoCookieToken
            ];
        if ($taoHiddenDeliveriesSubClass && $taoHiddenDeliveriesSubClass->delivery_name == $tao_delivery_name ){ 
            $delDeliveriesUrl = $this->toaUrlPrefix .''. '/taoTestTaker/TestTaker/deleteDelivery';
            $delDeliveriesUrlApiRes = $this->client->post($delDeliveriesUrl, [
                'headers' => $toaHeaders,
                'form_params' => [
                        'deliveryUri' =>  $taoHiddenDeliveriesSubClass->delivery_uri,
                    ],
            
                ]);
        }


        
        $uuidSubClass = explode("_i", $classUri)[1];
        $uuidTaoTest = explode("_i", $tao_test)[1];
        $uuidsubClass_a = $uuidSubClass;
         
        $uuidTaoTest = $this->toaUrlPrefix.'/'.$this->toaSubdomain.'.rdf#i'. $uuidTaoTest;
        $uuidSubClass = $this->toaUrlPrefix.'/'.$this->toaSubdomain.'.rdf#i'. $uuidSubClass;
     
        $publishDeliveriesUrl = $this->toaUrlPrefix .''. '/taoTestTaker/TestTaker/generate';
        $publishDeliveriesApiReponse = $this->client->post($publishDeliveriesUrl, [
                'headers' => $toaHeaders,
                'form_params' => [
                        'test' => $uuidTaoTest,
                        'custom_label' => $tao_delivery_name,
                        'delivery-uri' => $uuidSubClass,

                    ],
                ]);
      
            $publishDeliveriesBody = $publishDeliveriesApiReponse->getBody()->getContents();
            $publishDeliveriesBody = json_decode($publishDeliveriesBody, true);

          
            $uuidDeliveryName = $tao_delivery_name;
           
    //    dd($publishDeliveriesBody);
        if(array_key_exists('uriResource', $publishDeliveriesBody) &&  $publishDeliveriesBody['uriResource']){

            $uuidDelivery = explode('#i',$publishDeliveriesBody['uriResource'])[1];
            $uuidDeliveryUri =  $publishDeliveriesBody['uriResource'];

            $taoHiddenDeliveriesSubClass->delivery_uri = $uuidDeliveryUri;
            $taoHiddenDeliveriesSubClass->delivery_name = $uuidDeliveryName;
            $taoHiddenDeliveriesSubClass->save();

            $publishDeliveriesUrl = $this->toaUrlPrefix .''. '/taoDeliveryRdf/DeliveryMgmt/editDelivery';

            $publishDeliveriesApiReponse = $this->client->post($publishDeliveriesUrl, [
                'headers' => $toaHeaders,
                'form_params' => [
                        'form_1_sent' => 1,
                        'tao.forms.instance' => 1,
                        'http_2_www_0_w3_0_org_1_2000_1_01_1_rdf-schema_3_label' => $uuidDeliveryName,
                        'id' => $uuidDeliveryUri,
                        'http_2_www_0_tao_0_lu_1_Ontologies_1_TAODelivery_0_rdf_3_CustomLabel' => $tao_delivery_title,
                        'http_2_www_0_tao_0_lu_1_Ontologies_1_TAODelivery_0_rdf_3_Maxexec' => 1,
                        'http_2_www_0_tao_0_lu_1_Ontologies_1_TAODelivery_0_rdf_3_PeriodStart'=> $start_date,
                        'http_2_www_0_tao_0_lu_1_Ontologies_1_TAODelivery_0_rdf_3_PeriodEnd'=>	$end_date,
                        'http_2_www_0_tao_0_lu_1_Ontologies_1_TAODelivery_0_rdf_3_DisplayOrder' => null,
                        'classUri' => $classUri,
                        'uri' =>  $this->setTTuri.'i'. $uuidDelivery,
                    ],
                ]);

            $taoGroupToJob = taoGroupToJobs::where('job_id', $request->jobId)->first(); /*PS:another way to get this 1-1 grouptojob Relationship*/
      
            $existingGrpUri =  $taoGroupToJob->tao_group_uri;
            $existingGrpUri_a = explode('#',$existingGrpUri)[1];
       
            $assignGroupToDeliveriesUrl = $this->toaUrlPrefix .''. '/tao/GenerisTree/setValues';
            $assignGroupToDeliveryReponse = $this->client->post($assignGroupToDeliveriesUrl, [
                'headers' => $toaHeaders,
                'form_params' => [
                'resourceUri'=> $existingGrpUri,
                            'propertyUri' => 'http://www.tao.lu/Ontologies/TAOGroup.rdf#Deliveries',
                            'instances'	=> '["'.$this->setTTuri.'i'.$uuidDelivery.'"]',
                            'uri' => $this->setTTuri.''.$existingGrpUri_a,
                            'classUri'	=> $this->setTTuri.'i'.$uuidsubClass_a
                    ]
                ]
            );

          $excludeUserArray = '[';
            if($excludeUsers != null && count($excludeUsers) > 0){
                    foreach($excludeUsers as $excludeUser){
                    $excludeUserArray .= ',"'.$excludeUser.'"';
                }
              
                $excludeUserArray = preg_replace('/,/', '', $excludeUserArray,1);
            }
              $excludeUserArray .= ']';

              self::excludeUser($toaHeaders, $uuidDelivery, $excludeUserArray, $excludeUsers, $request);
        
            $instanceForTestTakers = '[';
            $taoGroupToJob = taoGroupToJobs::where('job_id', $request->jobId)
                ->where('tao_group_name', $groupName)
                ->where('tao_sub_class', '!=', 'tao_hidden_class_group')->first();
       
            if($taoGroupToJob) {
                  
            $existingGrpUri = $taoGroupToJob->tao_group_uri;
        
            $groupTestTakersUrl = $this->toaUrlPrefix .''. '/taoTestTaker/TestTaker/getGrpTestTakers';
            $groupTestTakersApiResponse = $this->client->post($groupTestTakersUrl, [
                'headers' => $toaHeaders,
                'form_params' => [
                         'tao_group' => $existingGrpUri
                    ],
                ]);
    
       
        $groupTestTakersBody = $groupTestTakersApiResponse->getBody()->getContents();
        $groupTestTakersBody = json_decode($groupTestTakersBody, true);
            if(count($groupTestTakersBody) > 0) {
              
            foreach($groupTestTakersBody as $oldtt){
                $setCurrentUserId = $oldtt['uriResource'];
                
          $testTakerEntry = jobApplicationTestGroup::where('job_id', $request->jobId)
                ->where('test_taker_uri', $setCurrentUserId)->first();
 
       
            $application = JobApplication::where('id', $testTakerEntry->job_application_id)->first();
        
           if($application){
                $names = explode(' ' , $application->full_name);
                $tao_tt = $testTakerEntry->test_taker_uri;
                $uuidUser = explode('#', $tao_tt)[1];
                $instanceForTestTakers .= ',"'.$setTTuri.''.$uuidUser.'"';
              }
            }
        }
    }

    
        $instanceForTestTakers .= ']';
        $instanceForTestTakers = preg_replace('/,/', '', $instanceForTestTakers,1);
        self::addTestTakersToGroup($toaHeaders, $taoHiddenGroupSubClass->tao_group_uri, $existingGrpUri, $instanceForTestTakers);
     return Reply::dataOnly(['status' => 'success', 'data' => $excludeUsers ]);
      }

         return Reply::dataOnly(['status' => 'error', ]);
        
     
    }



    private function excludeUser($toaHeaders, $uuidDelivery, $excluded_test_takers_uris, $excludeUsers,  $request){
        $uri =  $this->toaUrlPrefix. '/'.$this->toaSubdomain.'.rdf#i'. $uuidDelivery;
       
            if(!empty($excludeUsers)) {
                foreach($excludeUsers as $excludeUser ) {
                
                $excludedTestTaker = jobApplicationTestGroup::where('job_id', $request->jobId)
                    ->where('test_taker_uri', $excludeUser)->first();
                    if($excludedTestTaker) {
                        $excludedTestTaker->delivery_status = 'Excluded';
                        $excludedTestTaker->save();
                 }
            } 
         }
        
        $nonExcludedTestTakers = jobApplicationTestGroup::where('job_id', $request->jobId)
        ->where('delivery_status', '!=' , 'Excluded')->get();

    
         if(count($nonExcludedTestTakers) > 0) {
            $nonExcludedTestTakers->each(function ($item) {
                $item->update(['delivery_status'=>'Delivered']);
            });
        }


        $saveExcludedUrl = $this->toaUrlPrefix .''. '/taoDeliveryRdf/DeliveryMgmt/saveExcluded';
        $saveExcludedUrlResponse = $this->client->post($saveExcludedUrl, [
            'headers' => $toaHeaders,
            'form_params' => [
                    'uri' => $uri,
                    'excluded' => $excluded_test_takers_uris
              ]
            ]
        );
    }
    private function getTaoToken(){
        $loginRequrl = $this->toaUrlPrefix .''. '/tao/Main/login';
        $this->client->post($loginRequrl, [
                'form_params'=> [ 
                    'loginForm_sent' => 1,
                    'login' =>  config('taoconnector.taoLogin') ? config('taoconnector.taoLogin')  : 'admin',
                    'password' => config('taoconnector.taoPass') ? config('taoconnector.taoPass') : 'password123'
                    ]
            ]);
      
        $cookieJar = $this->client->getConfig('cookies');
        $cookieJar->toArray();
     
        $m = count($cookieJar->toArray()) > 1 ? $cookieJar->toArray()[0] : null;
        return $m;
    }

    public function createTestTakers(Request $request)
    {
        abort_if(!$this->user->can('view_job_applications'), 403);
        
        /*Securing our connection with TAO*/
        $setTTuri = $this->setTTuri;
        $job = Job::find($request->jobId);
        if($job) {
            $jobTitle  = $job->title;
            $groupName = 'Job Group '.$request->jobId.':'.$jobTitle;
        } else {
           return Reply::error('Job not found');
        }
        $m = self::getTaoToken();
        if(!$m) {
            return Reply::error('Cannot connect to tao at this moment');
            exit(0);
        }

        $taoCookieToken = $m['Name'].'='. $m['Value'];
            $toaHeaders = ['ta_H9om7MJX' => $this->api_key, 
                                    'X-Requested-With' => 'XMLHttpRequest',
                                    'Accept' => 'application/json',
                                    'Cookie' => $taoCookieToken
            ];

        /*
        Creating Hidden Instance Classes
        */
        $tao_ttakers_subclass_name = 'Job Takers ' .$request->jobId.':'. $jobTitle;
        $tao_grp_subclass_name = $groupName;
        $tao_deliveries_subclass_name = 'Job Deliveries ' .$request->jobId.':'. $jobTitle;
        $instanceForTestTakers = '[';

        $tao_hidden_class_tt = 'tao_hidden_class_tt';
        $tao_hidden_class_group = 'tao_hidden_class_group';
        $tao_hidden_class_deliveries = 'tao_hidden_class_deliveries';

        $taoHiddenTTRootClass = taoGroupToJobs::where('tao_sub_class', $tao_hidden_class_tt)->first();
        $taoHiddenGroupRootClass = taoGroupToJobs::where('tao_sub_class', $tao_hidden_class_group)->first();
        $taoHiddenDeliveriesRootClass = taoGroupToJobs::where('tao_sub_class', $tao_hidden_class_deliveries)->first();
       
        $taoHiddenTTSubClass = taoGroupToJobs::where('tao_sub_class', $tao_ttakers_subclass_name)->first();
        // $taoHiddenGroupSubClass = taoGroupToJobs::where('tao_sub_class', $tao_grp_subclass_name)->first();
        $taoHiddenDeliveriesSubClass = taoGroupToJobs::where('tao_sub_class', $tao_deliveries_subclass_name)->first();

        if(!$taoHiddenTTRootClass) {
           $createTTSubClassRes =  self::createTTSubClass($toaHeaders);
           $taoGrpToJob = new taoGroupToJobs();
            
            $taoGrpToJob->job_id = $createTTSubClassRes['uri'];
            //  'class_'.''.$rand = substr(md5(microtime()),rand(0,26),5);
            $taoGrpToJob->tao_group_uri = $createTTSubClassRes['uri'];
            $taoGrpToJob->tao_group_name = $tao_hidden_class_tt;
            $taoGrpToJob->tao_sub_class = $tao_hidden_class_tt;
            self::editClassLabel($createTTSubClassRes['uri'], $toaHeaders, $tao_hidden_class_tt);
              
            $createTTSubSubClassRes = self::createTTSubClass($toaHeaders, $createTTSubClassRes['uri']);
            self::editClassLabel($createTTSubSubClassRes['uri'], $toaHeaders, $tao_ttakers_subclass_name);
            $taoGrpToJob->job_id = $createTTSubSubClassRes['uri'];
            $taoGrpToJob->save();
            self::savetaoGrpToJob($tao_ttakers_subclass_name, $createTTSubSubClassRes, 'tao_hidden_sub_tt');
        }

        $taoHiddenTTRootClass = taoGroupToJobs::where('tao_sub_class', $tao_hidden_class_tt)->first();
        $taoHiddenTTSubClass = taoGroupToJobs::where('tao_sub_class', $tao_ttakers_subclass_name)->first();

        if($taoHiddenTTRootClass && !$taoHiddenTTSubClass){
                $taoGrpToJob = new taoGroupToJobs();
                $taoGrpToJob->tao_group_uri =  $taoHiddenTTRootClass->tao_group_uri;
                // $taoGrpToJob->tao_group_name = $tao_ttakers_subclass_name;
                $taoGrpToJob->tao_sub_class = $tao_ttakers_subclass_name;
                //  'tao_hidden_sub_tt';
                
                $createTTSubSubClassRes = self::createTTSubClass($toaHeaders, $taoHiddenTTRootClass->tao_group_uri);
                self::editClassLabel($createTTSubSubClassRes['uri'], $toaHeaders, $tao_ttakers_subclass_name);
                $taoGrpToJob->job_id = $createTTSubSubClassRes['uri'];
                $taoGrpToJob->save();
        }
        if(!$taoHiddenGroupRootClass) {
            $createGroupSubClass =  self::createGroupSubClass($toaHeaders);
            self::savetaoGrpToJob($tao_hidden_class_group, $createGroupSubClass, $tao_grp_subclass_name);
            self::editClassLabel($createGroupSubClass['uri'], $toaHeaders, $tao_hidden_class_group);
        }

        if(!$taoHiddenDeliveriesRootClass) {

            $createDeliverySubClass = self::createDeliverySubClass($toaHeaders);
            $taoGrpToJob = new taoGroupToJobs();
            $taoGrpToJob->tao_group_uri = $createDeliverySubClass['uri'];
            $taoGrpToJob->tao_group_name = $tao_hidden_class_deliveries;
            $taoGrpToJob->tao_sub_class = $tao_hidden_class_deliveries;
            self::editClassLabel($createDeliverySubClass['uri'], $toaHeaders, $tao_hidden_class_deliveries);

            $createTTSubSubClassRes = self::createTTSubClass($toaHeaders, $createDeliverySubClass['uri']);
            self::editClassLabel($createTTSubSubClassRes['uri'], $toaHeaders,'Job Deliveries:' .$request->jobId.':'. $jobTitle);
            $taoGrpToJob->job_id = $createTTSubSubClassRes['uri'];
            $taoGrpToJob->save();
            self::savetaoGrpToJob($tao_deliveries_subclass_name, $createTTSubSubClassRes, 'tao_hidden_sub_deliveries');
            
        }

        $taoHiddenDeliveriesRootClass = taoGroupToJobs::where('tao_sub_class', $tao_hidden_class_deliveries)->first();
        $taoHiddenDeliveriesSubClass = taoGroupToJobs::where('tao_sub_class', $tao_deliveries_subclass_name)->first();

        if($taoHiddenDeliveriesRootClass && !$taoHiddenDeliveriesSubClass){

                $taoGrpToJob = new taoGroupToJobs();
                $taoGrpToJob->tao_group_uri =  $taoHiddenDeliveriesRootClass->tao_group_uri;
                $taoGrpToJob->tao_sub_class = $tao_deliveries_subclass_name;
                
                $createTTSubSubClassRes = self::createTTSubClass($toaHeaders, $taoHiddenDeliveriesRootClass->tao_group_uri);
                self::editClassLabel($createTTSubSubClassRes['uri'], $toaHeaders, $tao_deliveries_subclass_name);
                $taoGrpToJob->job_id = $createTTSubSubClassRes['uri'];
                $taoGrpToJob->save();
        }

        $taoHiddenTTSubClass = taoGroupToJobs::where('tao_sub_class', $tao_ttakers_subclass_name)->first();
        $taoHiddenGroupSubClass = taoGroupToJobs::where('tao_sub_class', 'tao_hidden_class_group')->first();
        $taoHiddenDeliveriesSubClass = taoGroupToJobs::where('tao_sub_class', $tao_deliveries_subclass_name)->first();

       /* Format:'AllDayrHR_taoH9om7MJX_EntityType'*/

       /*
       * Creating Groups for Jobs 1-1-1 
       */
        $taoGroupToJob = taoGroupToJobs::where('job_id', $request->jobId)
        ->where('tao_group_name', $groupName)->where('tao_sub_class', '!=', 'tao_hidden_class_group')->first();

        /**Create A fresh group*/
        $addGroupRequrl = $this->toaUrlPrefix .''. '/taoTestTaker/TestTaker/addGroup';
        $groupApiResponse = $this->client->post($addGroupRequrl, [
                'headers' => $toaHeaders,
                'form_params' => [
                         'group_name' => $groupName,
                    ],
                ]);
        $groupApiBody = $groupApiResponse->getBody();
        $groupApiStatus = $groupApiResponse->getStatusCode();
       
        $groupApiBody = json_decode($groupApiBody, true);
        
        if(array_key_exists("uri",$groupApiBody)){
            $targetGroup = $groupApiBody['uri'];
        }

        if(array_key_exists("uriResource",$groupApiBody)){
          $targetGroup = $groupApiBody['uriResource'];
        }
         /** Hide job group*/
        self::moveGroup($toaHeaders, $targetGroup, $taoHiddenGroupSubClass->tao_group_uri);

        if($taoGroupToJob) {
                  
            $existingGrpUri = $taoGroupToJob->tao_group_uri;
        
            $groupTestTakersUrl = $this->toaUrlPrefix .''. '/taoTestTaker/TestTaker/getGrpTestTakers';
            $groupTestTakersApiResponse = $this->client->post($groupTestTakersUrl, [
                'headers' => $toaHeaders,
                'form_params' => [
                         'tao_group' => $existingGrpUri
                    ],
                ]);
    
       
        $groupTestTakersBody = $groupTestTakersApiResponse->getBody()->getContents();
        $groupTestTakersBody = json_decode($groupTestTakersBody, true);
        
       /**Get all the test takers in the group formerly */
        if(count($groupTestTakersBody) > 0) {
              
           foreach($groupTestTakersBody as $oldtt){
              if (array_key_exists("uri",$oldtt)) {
                $setCurrentUserId = $oldtt['uri'];
              }

            if (array_key_exists("uriResource",$oldtt)) {
                $setCurrentUserId = $oldtt['uriResource'];
            }
                        
        $testTakerEntry = jobApplicationTestGroup::where('job_id', $request->jobId)
                ->where('test_taker_uri', $setCurrentUserId)->first();


        if($testTakerEntry){
            $application = JobApplication::where('id',$testTakerEntry->job_application_id)->first();
            $names = explode(' ' , $application->full_name);
        }

        if($application) {

             /*Delete former instances*/
            $delTestTakersUrl = $this->toaUrlPrefix .''. '/taoTestTaker/TestTaker/deleteTestTaker';
            $this->client->post($delTestTakersUrl, [
                'headers' => $toaHeaders,
                'form_params' => [
                            'uri' => $setCurrentUserId,
                            'classUri' => 'http://www.tao.lu/Ontologies/TAOSubject.rdf#Subject',
                            'id' => $setCurrentUserId
                    ],
                ]);
            
            $addInstanceRequrl = $this->toaUrlPrefix .''. '/taoTestTaker/TestTaker/addInstance';
                                $ttApiResponse = $this->client->post($addInstanceRequrl,  [
                                                            'headers' => $toaHeaders,
                                                            'form_params' => [
                                                                'instanceName' => $application->full_name,
                                                                'id'=>'http://www.tao.lu/Ontologies/TAOSubject.rdf#Subject',
                                                                'classUri'=>'http_2_www_0_tao_0_lu_1_Ontologies_1_TAOSubject_0_rdf_3_Subject',
                                                                'type'=>'instance'
                                                                ]
                                                        ]);
                        
            $ttApiBody = $ttApiResponse->getBody()->getContents();
            $ttApiBody = json_decode($ttApiBody, true);

            $tao_tt = $ttApiBody['uri'];
       
       
            $uuidUser = explode('#', $ttApiBody['uri'])[1]; 
            $user_login = 'tt_user'.''. $application->job_id.''.$names[1].''.$application->id;
            $user_pass = 'tt_'.''.$application->job_id. ''.$names[1].''.$application->id; 
            /*This is still under review. We are trying to avoid  using random passwords.
            base_convert(uniqid('pass', true), 10, 36);*/

            /*
            Create new tt instances
            */
            $setTestTakersUrl = $this->toaUrlPrefix .''. '/taoTestTaker/TestTaker/addMultipleTestTakers';
            $this->client->post($setTestTakersUrl, [
                    'headers' => $toaHeaders,
                    'form_params' => [
                        
                                    'uri' => $setTTuri.''.$uuidUser, 
                                
                                    'classUri'=>'http_2_www_0_tao_0_lu_1_Ontologies_1_TAOSubject_0_rdf_3_Subject',
                                    'id'=>$ttApiBody['uri'],
                                'property_user_login'=> $user_login,
                                'property_user_password' => $user_pass,
                                'property_user_lastname' => $names[1],
                                'property_user_firstname' => $names[0],
                                'property_user_mail'=> $application->email,
                                'property_user_uilg' => 'http://www.tao.lu/Ontologies/TAO.rdf#Langen-US',
                                'property_user_roles'=>'http://www.tao.lu/Ontologies/TAO.rdf#DeliveryRole',
                                'property_user_deflg'=>'http://www.tao.lu/Ontologies/TAO.rdf#Langen-US'
                        ],
                    ]);
                
                if($testTakerEntry->id){
                    
                        $testTakerEntry->group_id = $targetGroup;
                        $testTakerEntry->test_taker_uri = $tao_tt;
                        $testTakerEntry->save();
                    }

                    self::moveTentantToGroup($toaHeaders, $tao_tt, $taoHiddenTTSubClass->job_id);
                    $instanceForTestTakers .= ',"'.$setTTuri.''.$uuidUser.'"';
                        }
            
                    }
            }

         /*Delete former group*/
            $deleteGroupRequrl = $this->toaUrlPrefix .''. '/taoTestTaker/TestTaker/deleteGroup';
                $this->client->post($deleteGroupRequrl, [
                    'headers' => $toaHeaders,
                    'form_params' => [
                            'tao_group' => $existingGrpUri
                        ],
                    ]);
            $taoGroupToJob->delete();
        }
       

        /*Take new incoming test takers and add them to new group*/
        $newTestTakers = JobApplication::where('job_id' , $request->jobId)->with(['test_groups'])->get();
         foreach($newTestTakers as $application) {
            $test_group = $application->test_groups->first();
            if(!$test_group['test_taker_uri']) {
                $names = explode(' ' , $application->full_name);
                $addInstanceRequrl = $this->toaUrlPrefix .''. '/taoTestTaker/TestTaker/addInstance';
                $ttApiResponse = $this->client->post($addInstanceRequrl,  [
                                            'headers' => $toaHeaders,
                                            'form_params' => [
                                                'instanceName' => $application->full_name,
                                                 'id'=>'http://www.tao.lu/Ontologies/TAOSubject.rdf#Subject',
                                                 'classUri'=>'http_2_www_0_tao_0_lu_1_Ontologies_1_TAOSubject_0_rdf_3_Subject',
                                                 'type'=>'instance'
                                                ]
                                        ]);
        
          $ttApiBody = $ttApiResponse->getBody()->getContents();
          $ttApiBody = json_decode($ttApiBody, true);
           
            $uuidUser = explode('#', $ttApiBody['uri'])[1];
            $user_login = 'tt_user'.''. $application->job_id.''.$names[1].''.$application->id;
            $user_pass = 'tt_'.''.$application->job_id. ''.$names[1].''.$application->id; 
            
            //This is still under review. We are trying to avoid  using random passwords.
            /*.base_convert(uniqid('pass', true), 10, 36);*/
            
            $setTestTakersUrl = $this->toaUrlPrefix .''. '/taoTestTaker/TestTaker/addMultipleTestTakers';
        
            $setTestTakersResponse =$this->client->post($setTestTakersUrl, [
                'headers' => $toaHeaders,
                'form_params' => [
    
                                'uri' => $setTTuri.''.$uuidUser,
                                'classUri'=>'http_2_www_0_tao_0_lu_1_Ontologies_1_TAOSubject_0_rdf_3_Subject',
                                'id'=>$ttApiBody['uri'],
                                'property_user_login'=> $user_login,
                                'property_user_password' => $user_pass,
                                'property_user_lastname' => $names[1],
                                'property_user_firstname' => $names[0],
                                'property_user_mail'=> $application->email,
                                'property_user_uilg' => 'http://www.tao.lu/Ontologies/TAO.rdf#Langen-US',
                                'property_user_roles'=>'http://www.tao.lu/Ontologies/TAO.rdf#DeliveryRole',
                                'property_user_deflg'=>'http://www.tao.lu/Ontologies/TAO.rdf#Langen-US'
                        ],
                ]);

            /**
             * Redeclaring variable. Inclination is that whoever is reading this shouldn't
             *  have to go back up in function to see the definiton.
             */

            if(array_key_exists("uri",$groupApiBody)){
                $targetGroup = $groupApiBody['uri'];
            }

            if(array_key_exists("uriResource",$groupApiBody)){
            $targetGroup = $groupApiBody['uriResource'];
            }

            // $targetGroup = $groupApiBody['uriResource'];
            $tao_tt = $ttApiBody['uri'];
            
            self::moveTentantToGroup($toaHeaders, $tao_tt, $taoHiddenTTSubClass->job_id);
            $instanceForTestTakers .= ',"'.$setTTuri.''.$uuidUser.'"';
            
            $testTakerEntry = jobApplicationTestGroup::where('job_id', $application->job_id)->where('job_application_id', $application->id)->first();
            if($testTakerEntry){
             
                     if($testTakerEntry->id){
                        $testTakerEntry->group_id = $targetGroup;
                        $testTakerEntry->test_taker_uri = $tao_tt;
                        $testTakerEntry->save();
                      }
                    }
           }
        }

          $instanceForTestTakers .= ']';
          $instanceForTestTakers = preg_replace('/,/', '', $instanceForTestTakers,1);
          self::addTestTakersToGroup($toaHeaders, $taoHiddenGroupSubClass->tao_group_uri, $targetGroup, $instanceForTestTakers);

        $taoGrpToJob = new taoGroupToJobs();
        $taoGrpToJob->job_id = $request->jobId;
        $taoGrpToJob->tao_group_uri = $targetGroup;
        $taoGrpToJob->tao_group_name = $groupName;
        $taoGrpToJob->save();
        return Reply::dataOnly(['status' => 'success', 'message' => __('messages.jobTakersCreated') ]);
    }

   public function sendLogin(Request $request, sendgridEmail $emailService) {

      try {
          
            $newTestTakers = $request['ttArray'];
            foreach($newTestTakers as $candidate) {
            
            if( $candidate['uri'] == 'noUri') {
                $uuidUser = $candidate['uri'];
                $user_login = 'tt_user'.''. $application->job_id.''.$names[1].''.$application->id;
                $user_pass = 'tt_'.''.$application->job_id. ''.$names[1].''.$application->id; 
                        
                $application =  JobApplication::where('id',$candidate['applicationId'])->first();
             if($application) {
                $emailService->sendTaoLogin($application, $user_pass, $user_login);
                
               }
            }
         } 
       }  catch(\Exception $e) {
        return Reply::error($e);
       }
   }


   private function getDeliveryTestTakers(Request $request){
      $getAssignedUsersUrl = $this->toaUrlPrefix .''. '/taoTestTaker/TestTaker/getAssignedUsers';
        $getAssignedUsersUrlRes =  $this->client->post($getAssignedUsersUrl, [
                'headers' => $toaHeaders,
                'form_params' => [
                              'deliveryUri'=> $request->deliveryUri
                              ]
                ]);
       return $getAssignedUsersUrlRes->getBody()->getContents();
   }


    protected function addTestTakerToSubClass ($toaHeaders, $newJobSubClassUri, $ttApiBody) {
        //Always Add test takers to sublacss before adding them to a group
       
        $uuidUser = explode('#', $ttApiBody['uri'])[1];
        $addTTakerToSubCUrl = $this->toaUrlPrefix .''. '/taoTestTaker/TestTaker/editSubject';
        $addTTakerToSubCResponse =  $this->client->post($addTTakerToSubCUrl, [
                'headers' => $toaHeaders,
                'form_params' => [
                              'uri' => 'http_2_tao_0_alldayhr_0_com_1_alldayhrtao_0_rdf_3_'.''.$uuidUser,
                              'classUri' =>	'http_2_www_0_tao_0_lu_1_Ontologies_1_TAOSubject_0_rdf_3'.''.strstr($newJobSubClassUri, '_i'),
                              'id'=> $ttApiBody['uri']]
                ]);
    }

    protected function addMultipleTestTakers ($uuidUser,$toaHeaders,$ttApiBody, $userLogin, $userPass, $names, $application)
     {
         //[Make efficient] 
        $setTestTakersUrl = $this->toaUrlPrefix .''. '/taoTestTaker/TestTaker/addMultipleTestTakers';
           $testTakerResponse =  $this->client->post($setTestTakersUrl, [
                'headers' => $toaHeaders,
                'form_params' => [
                                'uri' => 'http_2_tao_0_alldayhr_0_com_1_alldayhrtao_0_rdf_3_'.''.$uuidUser,
                                'classUri'=>'http_2_www_0_tao_0_lu_1_Ontologies_1_TAOSubject_0_rdf_3_Subject',
                                'id'=>$ttApiBody['uri'],
                                'property_user_login'=> $userLogin,
                                'property_user_password' => $userPass,
                                'property_user_lastname' => $names[1],
                                'property_user_firstname' => $names[0],
                                'property_user_mail'=> $application->email,
                                'property_user_uilg' => 'http://www.tao.lu/Ontologies/TAO.rdf#Langen-US',
                                'property_user_roles'=>'http://www.tao.lu/Ontologies/TAO.rdf#DeliveryRole',
                                'property_user_deflg'=>'http://www.tao.lu/Ontologies/TAO.rdf#Langen-US'
                        ],
                ]);
          return $testTakerResponse->getBody()->getContents();
    }
       
    protected function addTAOInstance($toaHeaders,$application){

        //[Make efficient] 
        $addInstanceRequrl = $this->toaUrlPrefix .''. '/taoTestTaker/TestTaker/addInstance';
            $ttApiResponse = $this->client->post($addInstanceRequrl,  [
                        'headers' => $toaHeaders,
                        'form_params' => [
                                'instanceName' => $application->full_name,
                                'id'=>'http://www.tao.lu/Ontologies/TAOSubject.rdf#Subject',
                                'classUri'=>'http_2_www_0_tao_0_lu_1_Ontologies_1_TAOSubject_0_rdf_3_Subject',
                                'type'=>'instance'
                            ]
                    ]);
        return $ttApiResponse->getBody()->getContents();
    }

    protected function moveGroup($toaHeaders, $uri, $destinationClassUri) {
       
        $uuidSubClass =  explode("_i", $destinationClassUri)[1];
        //[Make efficient]
        $moveTenantRequrl = $this->toaUrlPrefix .''. '/taoGroups/Groups/moveResource';
            return $moveTenantResponse =  $this->client->post($moveTenantRequrl, [
                    'headers' => $toaHeaders,
                    'form_params' => [
                                'uri' => $uri,
                                'destinationClassUri' => $this->toaUrlPrefix.'/'.$this->toaSubdomain.'.rdf#i'. $uuidSubClass
                            
                        ],
           ]);
        }

    protected function moveInstance($toaHeaders, $uri, $destinationClassUri) {
       
        $uuidSubClass =  explode("_i", $destinationClassUri)[1];
        //[Make efficient]
        $moveTenantRequrl = $this->toaUrlPrefix .''. '/taoTestTaker/TestTaker/moveInstance';
            return $moveTenantResponse =  $this->client->post($moveTenantRequrl, [
                    'headers' => $toaHeaders,
                    'form_params' => [
                                'uri' => $uri,
                                'destinationClassUri' => $this->toaUrlPrefix.'/'.$this->toaSubdomain.'.rdf#i'. $uuidSubClass
                            
                        ],
           ]);
        }

    protected function moveTentantToGroup($toaHeaders, $uri, $destinationClassUri) {
  
     $uuidSubClass =  explode("_i", $destinationClassUri)[1];

     $moveResourceRequrl = $this->toaUrlPrefix .''. '/taoTestTaker/TestTaker/moveResource';
       return $moveResourceRes =  $this->client->post($moveResourceRequrl, [
                    'headers' => $toaHeaders,
                    'form_params' => [
                                'uri' => $uri,
                                'destinationClassUri' => $this->toaUrlPrefix.'/'.$this->toaSubdomain.'.rdf#i'. $uuidSubClass
                            
                        ],
           ]);
 }

    protected function moveResource($toaHeaders, $uri, $destinationClassUri) {
  
     $uuidSubClass =  explode("_i", $destinationClassUri)[1];

     $moveResourceRequrl = $this->toaUrlPrefix .''. '/taoTestTaker/TestTaker/moveResource';
       return $moveResourceRes =  $this->client->post($moveResourceRequrl, [
                    'headers' => $toaHeaders,
                    'form_params' => [
                                'uri' => $uri,
                                'destinationClassUri' => $this->toaUrlPrefix.'/'.$this->toaSubdomain.'.rdf#i'. $uuidSubClass
                            
                        ],
           ]);
 }
      
    protected function addTestTakersToGroup($toaHeaders, $subSubClassUri, $targetGroup, $uuidUsers) {
        $uuidTargetGroup = explode('#', $targetGroup)[1];
        
        $moveTenantRequrl = $this->toaUrlPrefix .''. '/tao/GenerisTree/setReverseValues';
            return $moveTenantResponse =  $this->client->post($moveTenantRequrl, [
            'headers' => $toaHeaders,
            'form_params' => [
                        'resourceUri' => $targetGroup,
                        'propertyUri'=>	'http://www.tao.lu/Ontologies/TAOGroup.rdf#member',
                        'instances'	=>  $uuidUsers,
                        'uri' =>  $this->setTTuri.''.$uuidTargetGroup,
                        'classUri'	=> $subSubClassUri,
                    ]
            ]
        );
    }


    

    /*CREATING CLASSES*

    /* Creating Assembled Delivery Classes*/
    protected function createDeliverySubClass($toaHeaders) {

                $addDeliverySubClassUrl = $this->toaUrlPrefix .''. '/taoDeliveryRdf/DeliveryMgmt/addSubClass';
                 $addDeliverySubClassResponse =  $this->client->post($addDeliverySubClassUrl, [
                    'headers' => $toaHeaders,
                    'form_params' => [
                                'classUri'	=> 'http_2_www_0_tao_0_lu_1_Ontologies_1_TAODelivery_0_rdf_3_AssembledDelivery',
                                'type' => 'class',
                                'id' => 'http://www.tao.lu/Ontologies/TAODelivery.rdf#AssembledDelivery'
                            ]
            ]);
          $addDeliverySubClassResponse = $addDeliverySubClassResponse->getBody()->getContents();
        return json_decode($addDeliverySubClassResponse, true);
      }

    /** Creating Test Taker classes*/
    protected function createTTSubClass($toaHeaders, $parentUri = null) {

                 $classUri = 'http_2_www_0_tao_0_lu_1_Ontologies_1_TAOSubject_0_rdf_3_Subject';
                 $type = 'class';
                 $id = 'http://www.tao.lu/Ontologies/TAOSubject.rdf#Subject';
               
                 if($parentUri){
                    $uuidSubClass =  explode("_i", $parentUri)[1];
                    $classUri = $parentUri;
                    $id = $this->toaUrlPrefix.'/'.$this->toaSubdomain.'.rdf#i'. $uuidSubClass;
                    $type = 'class';
                  
                }

                $addTestTakerSubClassUrl = $this->toaUrlPrefix .''. '/taoTestTaker/TestTaker/addSubClassadhoc';
                  $addTestTakerSubClassResponse =  $this->client->post($addTestTakerSubClassUrl, [
                    'headers' => $toaHeaders,
                    'form_params' => [
                            'classUri'=> $classUri,
                            'id' => $id,
                            'type' => $type
                         ]
            ]);
            
               $addTestTakerSubClassResponse = $addTestTakerSubClassResponse->getBody()->getContents();
        return json_decode($addTestTakerSubClassResponse, true);
      }

    /** Creating Group classes */
    protected function createGroupSubClass($toaHeaders) {
                $addGroupSubClassUrl = $this->toaUrlPrefix .''. '/taoGroups/Groups/addSubClass';
                  $addGroupSubClassResponse =  $this->client->post($addGroupSubClassUrl, [
                    'headers' => $toaHeaders,
                    'form_params' => [
                            'classUri'=> 'http_2_www_0_tao_0_lu_1_Ontologies_1_TAOGroup_0_rdf_3_Group',
                            'id' => 'http://www.tao.lu/Ontologies/TAOGroup.rdf#Group',
                            'type' => 'class'
                         ]
            ]);
        
            $addGroupSubClassResponse = $addGroupSubClassResponse->getBody()->getContents();
        return json_decode($addGroupSubClassResponse, true);
      }

      protected function editClassLabel($subClassUri, $toaHeaders, $label){
             $addGroupSubClassUrl = $this->toaUrlPrefix .''. '/taoTestTaker/TestTaker/editClassLabel';
                  $addGroupSubClassResponse =  $this->client->post($addGroupSubClassUrl, [
                    'headers' => $toaHeaders,
                    'form_params' => [
                            'classUri'	=> $subClassUri,
                            'http_2_www_0_w3_0_org_1_2000_1_01_1_rdf-schema_3_label' => $label,
                            'form_1_sent' => 1,
                         ]
            ]);
      }
      protected function savetaoGrpToJob($tao_hidden_class_name, $uriBody, $hiddenType){
            $taoGrpToJob = new taoGroupToJobs();
            $taoGrpToJob->job_id = $uriBody['uri'];
            $taoGrpToJob->tao_group_uri = $uriBody['uri'];
            $taoGrpToJob->tao_group_name = $hiddenType;
            $taoGrpToJob->tao_sub_class = $tao_hidden_class_name;
            $taoGrpToJob->save();
      }

      public function deleteDeliveryOnTao(Request $request) {

        
        $setTTuri = $this->setTTuri;
        $job = Job::find($request->jobId);
        if($job) {
            $jobTitle  = $job->title;
        } else {
           return Reply::error('Job not found');
        }
        $m = self::getTaoToken();
      

        if(!$m) {
            return Reply::error('Cannot connect to tao at this moment');
            exit(0);
        }

              $taoCookieToken = $m['Name'].'='. $m['Value'];
            $toaHeaders = ['ta_H9om7MJX' => $this->api_key,
                                    'X-Requested-With' => 'XMLHttpRequest',
                                    'Accept' => 'application/json',
                                    'Cookie' => $taoCookieToken
            ];
        $tao_deliveries_subclass_name = 'Job Deliveries ' .$request->jobId.':'. $jobTitle;
        $taoHiddenDeliveriesSubClass = taoGroupToJobs::where('tao_sub_class', $tao_deliveries_subclass_name)->first();

        if($taoHiddenDeliveriesSubClass && $taoHiddenDeliveriesSubClass->delivery_uri) {

            $delDeliveriesUrl = $this->toaUrlPrefix .''. '/taoTestTaker/TestTaker/deleteDelivery';
            $delDeliveriesUrlApiRes = $this->client->post($delDeliveriesUrl, [
                'headers' => $toaHeaders,
                'form_params' => [
                        'deliveryUri' =>  $taoHiddenDeliveriesSubClass->delivery_uri,
                    ],
            
                ]);

                $delDeliveriesUrlResBody = $delDeliveriesUrlApiRes->getBody()->getContents();
                $delDeliveriesUrlResBody =  json_decode($delDeliveriesUrlResBody, true);
       
                $testTakersToReset = jobApplicationTestGroup::where('job_id', $request->jobId)->get();

                
                    if(count($testTakersToReset) > 0) {
                        $testTakersToReset->each(function ($item) {
                            $item->update(['delivery_status'=> '' ]);
                        });
                    }
                    $taoHiddenDeliveriesSubClass->delivery_uri = null;
                    $taoHiddenDeliveriesSubClass->delivery_name = null;
                    $taoHiddenDeliveriesSubClass->save();
             return Reply::dataOnly(['status' => 'success', 'message' => 'Delivery deleted' ]);
        } else {
           return Reply::dataOnly(['status' => 'error', 'message' => 'No delivery is associated to this job yet' ]);
        }
         
      } 

}
