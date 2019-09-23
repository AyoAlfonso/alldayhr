
@extends('layouts.app')
@push('head-script')

  <link rel="stylesheet" href="{{ asset('auth_assets/css/main.css') }}">
  <link rel="stylesheet" href="//cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.bootstrap.min.css">
  <link rel="stylesheet" href="//cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
  <link rel="stylesheet" href="{{ asset('assets/plugins/iCheck/all.css') }}">
  <link rel="stylesheet" href="{{ asset('auth_assets/css/flexdatalist.css') }}">
  <link href="{{ asset('auth_assets/candidateprofile/css/main.min.css') }}" rel="stylesheet">

  <style>
    .mb-20{
      margin-bottom: 20px
    }
    .datepicker{
      z-index: 9999 !important;
    }
    .select2-container--default .select2-selection--single, .select2-selection .select2-selection--single {
      width: 250px;
    }
  </style>
@endpush

@section('content')

  <div >
    <main>
      <section>
        <div class="row px-5 pt-4 m-0">
          <div class="col-md-5 d-flex flex-column">
            <div class="rounded shadow-sm bg-white mb-4 py-4 px-3">
              <div class="d-flex flex-column align-items-center">
              <img src="{{$candidateUser['profile_image_url'] ? $candidateUser['profile_image_url'] : asset('/auth_assets/images/avatar.png') }}" style="width: 50px;height: 50px;" class="border-circle" alt="Profile Image">
                <h5 class="text-dark display-8 font-weight-normal">

                 {{ $candidateUser['user']['firstname']. '  ' . $candidateUser['user']['lastname']}}
               
                </h5>
                <h6 class="text-grey display-9 font-weight-normal">
                    {{$candidateUser['work'] ? $candidateUser['work'][0]['title'] : null}}
                </h6>
                <hr>
                <p class="text-grey display-10 font-weight-normal pb-0 mb-0">
                 
                </p>
              </div>
            </div>
            <div class="rounded shadow-sm bg-white mb-4 py-3 px-3">
              <p class="text-grey display-10 font-weight-normal pb-0 mb-0">
                Contact Information
              </p>
              <hr>
              <div class="row">
                <div class="col-md-4 pl-3 pt-4 px-0 text-dark-grey display-9">
                  <p>Email Address</p>
                  <p>Phone Number</p>
                  <p>Current Address</p>
            
                </div>
                <div class="col-md-8 pt-4 pl-5 display-9">
                  <p> {{$candidateUser['user']['email']}} </p>
                  <p> {{$candidateUser['phone_number']}} </p>
                  <p>{{$candidateUser['street'] }}</p>
                
                </div>
              </div>
            </div>
            <div class="rounded shadow-sm bg-white mb-4 py-3 px-3">
              <p class="text-grey display-10 font-weight-normal pb-0 mb-0">
                Uploaded Documents
              </p>
              <hr>

              <div class="d-flex justify-content-between">
                @if(count($candidateUser['documents']) < 1) 
                <div>No Documents Uploaded</div>
                 @else 

                   @foreach($candidateUser['documents'] as $index => $docs)
                    <div class="d-flex align-items-center flex-column">
                        <a href="{{$docs['doc_url']}}" target="_blank">
                        <div class="d-flex bg-primary border-circle align-items-center icon-circle justify-content-center">
                            <img src="{{ asset('/auth_assets/candidateprofile/img/doc-icon.svg')}}" alt="resume icon">
                        </div>
                        </a>
                        <p class="pt-2 text-darker-grey display-10 font-weight-normal pb-0 mb-0">
                          {{$docs['type']['name']}}
                        </p>
                         </div>
                    @endforeach  
                  @endif
              
              </div>
            </div>
          </div>
          <div class="col-md-7 full-height rounded shadow-sm bg-white py-2">
            <nav>
              <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                <a class="nav-item nav-link text-grey display-9 active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Personal Details</a>
                <a class="nav-item nav-link text-grey display-9" id="nav-education-tab" data-toggle="tab" href="#nav-education" role="tab" aria-controls="nav-profile" aria-selected="false">Education</a>
                <a class="nav-item nav-link text-grey display-9" id="nav-prevemployer-tab" data-toggle="tab" href="#nav-prevemployer" role="tab" aria-controls="nav-prevemployer" aria-selected="false">Work History</a>
                <a class="nav-item nav-link text-grey display-9" id="nav-reference-tab" data-toggle="tab" href="#nav-reference" role="tab" aria-controls="nav-reference" aria-selected="false">O'Levels</a>
                <a class="nav-item nav-link text-grey display-9" id="nav-next-tab" data-toggle="tab" href="#nav-next" role="tab" aria-controls="nav-next" aria-selected="false">NYSC Data</a>
              </div>
            </nav>
            <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent" style="height: 370px;overflow-Y: scroll;overflow-x: hidden;">
              <div class="tab-pane fade active show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                <div class="row pl-3">
                  <div class="col-6 col-md-12 pl-4 pt-4 px-0 text-dark-grey display-9">
                    <div class="header-ubuntu-adhoc row"> 
                       <span class="col-md-6">Gender</span>
                       <p class="col-md-6 text-dark-g1">{{$candidateUser['gender'] ? $candidateUser['gender'] : 'N/A' }}</p>
                     </div>

                  <div class="header-ubuntu-adhoc row"> 
                       <span class="col-md-6">Othername</span>
                       <p class="col-md-6 text-dark-g1">{{$candidateUser['othername'] ? $candidateUser['othername'] : 'N/A' }}</p>
                  </div>
                  <div class="header-ubuntu-adhoc row"> 
                       <span class="col-md-6">Marital Status</span>
                       <p class="col-md-6 text-dark-g1">{{$candidateUser['marital_status'] ? $candidateUser['marital_status'] : 'N/A' }}</p>
                  </div>
                  <div class="header-ubuntu-adhoc row"> 
                       <span class="col-md-6">Date of Birth</span>
                       <p class="col-md-6 text-dark-g1">{{$candidateUser['date_of_birth'] ? $candidateUser['date_of_birth'] : 'N/A' }}</p>
                  </div>
                  <div class="header-ubuntu-adhoc row"> 
                       <span class="col-md-6">State of Origin</span>
                       <p class="col-md-6 text-dark-g1">{{$candidateUser['state'] ? $candidateUser['state'] : 'N/A' }}</p>
                  </div>
                  <div class="header-ubuntu-adhoc row"> 
                       <span class="col-md-6">Residential State</span>
                       <p class="col-md-6 text-dark-g1">{{$candidateUser['residence_state'] ? $candidateUser['residence_state'] : 'N/A' }}</p>
                  </div>
                  <div class="header-ubuntu-adhoc row"> 
                       <span class="col-md-6">L.G.A of Origin</span>
                       <p class="col-md-6 text-dark-g1">{{$candidateUser['lga'] ? $candidateUser['lga'] : 'N/A' }}</p>
                  </div>
                  <div class="header-ubuntu-adhoc row"> 
                       <span class="col-md-6">L.G.A of Residence</span>
                       <p class="col-md-6 text-dark-g1">{{$candidateUser['residence_lga'] ? $candidateUser['residence_lga'] : 'N/A' }}</p>
                  </div>
                  <div class="header-ubuntu-adhoc row"> 
                       <span class="col-md-6">Langauges</span>
                      
                       @if($candidateUser['languages']==null)
                      <p class="col-md-5 text-dark-g1"> N/A </p>
                    @elseif($candidateUser['languages']!=null)
                      <p class="col-md-5 text-dark-g1">
                        <?php $languages = json_decode($candidateUser['languages'] , true); ?>
                        {{ ucfirst(implode(', ', $languages)) }}
                      </p>
                    @endif
                  </div>
                  <div class="header-ubuntu-adhoc row"> 
                       <span class="col-md-6">Certifications</span>
                       @if($candidateUser['certifications']==null)
                        <p class="col-md-5 text-dark-g1"> N/A </p>
                    @elseif($candidateUser['certifications']!=null)
                        <p class="col-md-5 text-dark-g1">
                          <?php $certifications = json_decode($candidateUser['certifications'] , true); ?>
                          {{ ucfirst(implode(', ', $certifications)) }}
                       </p>
                    @endif
                       
                  </div>
                  <div class="header-ubuntu-adhoc row"> 
                       <span class="col-md-6">Skills</span>
                            @if($candidateUser['skills']==null)
                       <p class="col-md-5 text-dark-g1"> N/A </p>
                    @elseif($candidateUser['skills']!=null)
                       <p class="col-md-5 text-dark-g1">
                        <?php $skills = json_decode($candidateUser['skills'] , true); ?>
                        {{ ucfirst(implode(', ', $skills)) }}
                    </p>
                    @endif
                  </div>

                  <div class="header-ubuntu-adhoc row"> 
                       <span class="col-md-6">Nationality</span>
                        <p class="col-md-6 text-dark-g1">{{$candidateUser['nationality'] ? $candidateUser['nationality'] : 'N/A' }}</p>
                  </div>
                  <div class="header-ubuntu-adhoc row"> 
                       <span class="col-md-6">Overall Experience</span>
                       <p class="col-md-6 text-dark-g1">{{$candidateUser['experience_level'] ? $candidateUser['experience_level'] : 'N/A' }}</p>
                  </div>
                   
                  </div>

                  
                </div>
              </div>
              <div class="tab-pane fade" id="nav-education" role="tabpanel" aria-labelledby="nav-education-tab">
               
            @if(count($candidateUser['education']) > 0)
             @foreach($candidateUser['education'] as $index => $education)
                <div class="row pl-3">
                  <div class="col-md-12 pl-4 pt-4 px-0 text-dark-grey display-9">
                    <div class="header-ubuntu-adhoc row"> 
                        <span class="col-md-6">Institution Name</span>
                        <p class="col-md-6 text-dark-g1">{{$education['institution'] ? $education['institution'] : 'N/A' }}</p>
                    </div>
                    <div class="header-ubuntu-adhoc row"> 
                      <span class="col-md-6">Admission Year</span>
                      <p class="col-md-6 text-dark-g1">{{$education['from_year'] ? $education['from_year']: 'N/A' }}</p>
                    </div>
                    <div class="header-ubuntu-adhoc row"> 
                      <span class="col-md-6">Graduation Year</span>
                      <p class="col-md-6 text-dark-g1">{{$education['to_year'] ? $education['to_year'] : 'N/A' }}</p>
                    </div>

                    <div class="header-ubuntu-adhoc row"> 
                      <span class="col-md-6">Course of Study</span>
                      <p class="col-md-6 text-dark-g1">{{$education['field_of_study'] ?$education['field_of_study']: 'N/A' }}</p>
                    </div>

                    <div class="header-ubuntu-adhoc row"> 
                      <span class="col-md-6">Qualification</span>
                      <p class="col-md-6 text-dark-g1">{{$education['qualification'] ? $education['qualification'] : 'N/A' }}</p>
                    </div>
                    <div class="header-ubuntu-adhoc row"> 
                      <span class="col-md-6">Academic Grade</span>
                      <p class="col-md-6 text-dark-g1">{{$education['grade'] ? $education['grade'] : 'N/A' }}</p>
                    </div>
                  </div>
                </div>
             @endforeach
             @endif

            @if(empty($candidateUser['education']))
                  <div class="row pl-3">
                  <div class="col-md-12 pl-4 pt-4 px-0 text-dark-grey display-9">
                    <div class="header-ubuntu-adhoc row"> 
                        <span class="col-md-6">Institution Name</span>
                        <p class="col-md-6 text-dark-g1">N/A</p>
                    </div>
                    <div class="header-ubuntu-adhoc row"> 
                      <span class="col-md-6">Admission Year</span>
                      <p class="col-md-6 text-dark-g1">N/A</p>
                    </div>
                    <div class="header-ubuntu-adhoc row"> 
                      <span class="col-md-6">Graduation Year</span>
                       <p class="col-md-6 text-dark-g1">N/A</p>
                    </div>

                    <div class="header-ubuntu-adhoc row"> 
                      <span class="col-md-6">Course of Study</span>
                        <p class="col-md-6 text-dark-g1">N/A</p>
                    </div>

                    <div class="header-ubuntu-adhoc row"> 
                      <span class="col-md-6">Qualification</span>
                         <p class="col-md-6 text-dark-g1">N/A</p>
                    </div>
                    <div class="header-ubuntu-adhoc row"> 
                      <span class="col-md-6">Academic Grade</span>
                        <p class="col-md-6 text-dark-g1">N/A</p>
                     </div>
                  </div>
                </div>
           
             @endif
              </div>

              <div class="tab-pane fade" id="nav-prevemployer" role="tabpanel" aria-labelledby="nav-prevemployer-tab">

                     @if(count($candidateUser['work']) > 0)
                       @foreach($candidateUser['work'] as $index => $work)
                      <div class="row pl-3">
                        <div class="col-md-12 pl-4 pt-4 px-0 text-dark-grey display-9">
                          <div class="header-ubuntu-adhoc row"> 
                            <span class="col-md-6">Previous Organization Name</span>
                              <p class="col-md-6 text-dark-g1">{{$work['company']}}</p>
                          </div>
                          
                          <div class="header-ubuntu-adhoc row"> 
                            <span class="col-md-6">Job Title</span>
                              <p class="col-md-6 text-dark-g1">{{$work['title']}}</p>
                          </div>
                          
                          <div class="header-ubuntu-adhoc row"> 
                            <span class="col-md-6">Job Industry</span>
                              <p class="col-md-6 text-dark-g1">{{$work['industry']}}</p>
                          </div>

                          <div class="header-ubuntu-adhoc row"> 
                            <span class="col-md-6">Job Function</span>
                              <p class="col-md-6 text-dark-g1">{{$work['job_function']}}</p>
                          </div>

                          <div class="header-ubuntu-adhoc row"> 
                            <span class="col-md-6">Job Achievements</span>
                              <p class="col-md-6 text-dark-g1">{{$work['achievements']}}</p>
                          </div>

                          <div class="header-ubuntu-adhoc row"> 
                            <span class="col-md-6">Time Period</span>
                              <p class="col-md-6 text-dark-g1">{{date('M/Y',strtotime('01/'.$work['from_month'].'/'.$work['from_year']))}} - {{date('M/Y',strtotime('01/'.$work['to_month'].'/'.$work['to_year']))}}</p>
                          </div>

                           <div class="header-ubuntu-adhoc row"> 
                            <span class="col-md-6">Current</span>
                              <p class="col-md-6 text-dark-g1">{{$work['current'] == 1 ?  'Currently working here' : '' }}</p>
                          </div>

                       </div>
                      </div>
                    @endforeach
                    @endif

                    @if(empty($candidateUser['work']))
                      <div class="row pl-3">
                      <div class="header-ubuntu-adhoc row"> 
                            <span class="col-md-6">Previous Organization Name</span>
                              <p class="col-md-6 text-dark-g1">N/A</p>
                          </div>
                          
                          <div class="header-ubuntu-adhoc row"> 
                            <span class="col-md-6">Job Title</span>
                              <p class="col-md-6 text-dark-g1">N/A</p>
                          </div>
                          
                          <div class="header-ubuntu-adhoc row"> 
                            <span class="col-md-6">Job Industry</span>
                              <p class="col-md-6 text-dark-g1">N/A</p>
                          </div>

                          <div class="header-ubuntu-adhoc row"> 
                            <span class="col-md-6">Job Function</span>
                             <p class="col-md-6 text-dark-g1">N/A</p>
                          </div>

                          <div class="header-ubuntu-adhoc row"> 
                            <span class="col-md-6">Job Achievements</span>
                              <p class="col-md-6 text-dark-g1">N/A</p>
                          </div>

                          <div class="header-ubuntu-adhoc row"> 
                            <span class="col-md-6">Time Period</span>
                             <p class="col-md-6 text-dark-g1">N/A</p>
                              </div>

                           <div class="header-ubuntu-adhoc row"> 
                            <span class="col-md-6">Current</span>
                            <p class="col-md-6 text-dark-g1">N/A</p>
                          </div>
                      </div>
             @endif
              </div>

              <div class="tab-pane fade" id="nav-reference" role="tabpanel" aria-labelledby="nav-reference-tab">
              @if(count($candidateOlevels)> 0)
                  <div class="row pl-3">
               @foreach($candidateOlevels as $index => $olevel)

               @if(count($olevel['results']) > 0)

                  <div class="col-12">  </div>
                  <div class="col-6 col-md-4 pl-4 pt-4 px-0 display-9">
                 <p class="header-ubuntu-adhoc">Olevel Type</p>
                @foreach($olevel['results'] as $index => $result)
                        <p>{{$result['subject']}}</p>
                @endforeach
                    
                  </div>
                  <div class="col-6 col-md-8 pt-4 pl-5 display-9">
                  <p class="header-ubuntu-adhoc">{{$olevel['type']}} </p>
                 @foreach($olevel['results'] as $index => $result)
                         <p class="text-dark-g1">{{$result['grade']}}</p>
                 @endforeach
                  </div>
                  @endif
                  @endforeach
              @endif

                </div>
                      <hr>
                </div>

              <div class="tab-pane fade " id="nav-next" role="tabpanel" aria-labelledby="nav-next-tab">
                <div class="row pl-3">
                  <div class="col-6 col-md-12 pl-4 pt-4 px-0 text-dark-grey display-9">
                    <div class="header-ubuntu-adhoc row"> 
                        <span class="col-md-6">NYSC Status</span>
                        <p class="col-md-6 text-dark-g1">{{$candidateUser['nysc_status'] ? $candidateUser['nysc_status'] : 'N/A' }}</p>
                      </div>
                    <div class="header-ubuntu-adhoc row"> 
                        <span class="col-md-6">NYSC Completion Year</span>
                        <p class="col-md-6 text-dark-g1">{{$candidateUser['nysc_completion_year'] ? $candidateUser['nysc_completion_year'] : 'N/A'}}</p>
                    </div>
                     <div class="header-ubuntu-adhoc row"> 
                        <span class="col-md-6">NYSC ID</span>
                        <p class="col-md-6 text-dark-g1">{{$candidateUser['nysc_other_info'] ? $candidateUser['nysc_other_info']  : 'N/A'}}</p>
                      </div>


                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>

  @push('scripts')
    <script src="{{ asset('auth_assets/candidateprofile/js/main.min.js') }}"></script>
  @endpush

  @endsection

  </body>
  </html>

