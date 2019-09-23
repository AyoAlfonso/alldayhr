
@extends('layouts.front-adhoc')
@push('head-script')
        <link rel="stylesheet" href="{{ asset('auth_assets/landing/css/landing_.css') }}" >
@endpush 
@section('content')

         <div class="board_wrapper board_wrapper-gr">
         <div class="container board">
            <h1 class="h1">Let's help you find your dream job</h1>
            <h4 class="h4">
                Explore and apply for the very best jobs by the
                most prestigious companies.
            </h4>
        <form id="main_search" method="POST" action="{{ route('jobs.searchJobCategories') }}">
            @csrf
            <div class="inputz">
                        <span class="state">
                           <select name="location">
                                <option value="">Anywhere</option>
                           @foreach($locations as $location)
                                   <option value="{{  $location["location"] }}"> {{ $location["location"] }}</option>
                               @endforeach
                            </select>
                        </span>
                <span id="search_input" class="txt">
                            <input type="text" autocomplete="nope" name="keyword" placeholder="Search for a Job, Skill or Company" />
                         <span class="main_search_btn">
                                       <i class=" icon fa fa-search"></i>
                            </span>
                        </span>

            </div>
        </form>
        </div>
        </div>
        <div class="board_wrapper">

            <div class="container board">



                <div class="recom">
                    <span class="h4r">Recommended</span>
                    <small class="letter">based on the most recent job listings </small>
                </div>
                <div class="container-fluid job-wrapper">

                    @foreach($jobs as $job)
                      <a href="{{ route('jobs.jobDetail', $job->slug) }}">
                     <div class="col-sm-6 x12">
                     
                        <div class="rect">
                            <p>
                                <span class="h5 title">{{ ucwords($job->title) }}</span>
                                <span class="avater">
                                     <img src= "{{ $job->company->logo_url}}" />
                                </span>
                            </p>
                            <span class="addr"> 
                                  {{ ucwords($job->company->company_name) }}
                              &nbsp;</span>
                             <span style="color: #282828;opacity: 0.6;font-size: 20px;vertical-align: top;margin-top: -5px;">  | </span>
                             <span style="margin-left: 5px;" class="addr">
                             &nbsp;{{ ucwords($job->location->location)}}
                             </span>
                            {{-- <p class="addr_a">
                                    {{  strip_tags($job->job_description) }}
                            </p> --}}
                            <div class="job_date"> Posted {{ $job->start_date->format('d M') }}</div>
                            <p class="apply">
                                <span class="category">
                                {{ ucwords($job->category->name) }}
                                </span>


                       @if(Auth::guard('candidate')->check())
                              @if(Auth::guard('candidate')->user()->HasAppliedJob($job->id))
                             <button class="btn aply expired-btn">Already Applied</button>
                             @elseif( strtotime($job->end_date->format('Y-m-d')) < strtotime(Carbon\Carbon::now()->format('Y-m-d')) )
                             <a>  <button class="btn aply expired-btn">Expired</button>  </a>
                             @elseif(!Auth::guard('candidate')->user()->HasAppliedJob($job->id))
                              <a href="{{ route('jobs.jobApply', $job->slug) }}"> <button class="btn aply"> @lang('modules.front.applyForJob')</button> </a>
                             @endif
                        @elseif( strtotime($job->end_date->format('Y-m-d')) < strtotime(Carbon\Carbon::now()->format('Y-m-d')) )
                        <a>  <button class="btn aply expired-btn">Expired</button>  </a>
                        @else
                            <a href="{{ route('jobs.jobApply', $job->slug) }}"> <button class="btn aply"> @lang('modules.front.applyForJob')</button> </a>
                        @endif
                              <br class="clear" />
                            </p>
                        </div>
                       
                     </div>
                       </a>
                    @endforeach

                </div>

                <div class="show">
                    <a href="{{ route('jobs.getJobCategories') }}"> 
                    <button class="btn">show more jobs</button>
                    </a>
                </div>
            </div>


        </div>


        <div class="board_wrapper2">
            <h3 class="h3 prx">The Process</h3>
            <div class="container board2">

                <div class="col-sm-4 s4">
                    <div class="svg description">
                        <img src="{{ asset('auth_assets/landing/assets/p1.svg')}}" />
                    </div>
                    <div class="description">
                        <h3 class="h3">Build your profile</h3>
                        <span>

                            Set up your account
                            to get started
                        </span>
                    </div>

                </div>

                <div class="col-sm-4 s4">
                    <div class="svg-x description">
                        <img src="{{ asset('auth_assets/landing/assets/p2.svg')}}"  />
                    </div>
                    <div class="description">
                        <h3 class="h3">Apply to jobs</h3>
                        <span>

                            Find the best roles and companies for you
                        </span>
                    </div>
                </div>
                <div class="col-sm-4 s4">
                    <div class="svg description">
                        <img src="{{ asset('auth_assets/landing/assets/p3.svg')}}" />
                    </div>
                    <div class="description">
                        <h3 class="h3">Get Hired</h3>
                        <span>

                            Begin your journey to a fulfilling career
                        </span>
                    </div>

                </div>

            </div>
        </div>
        <div class="board_wrapper3">

            <div class="container board">
                <div style="text-align:center">
                    <div class="desc">
                        We can keep you in the
                        loop on available jobs
                    </div>

                    <div class="desc2">
                        Get the latest job postings tailored to
                        your interests directly in your email.
                    </div>

                </div>
                <div style="display:none" class="input">
                    <div class="input_wrap">
                        <span class="sp1">
                            <input type="text" placeholder="Your Email Address" />
                        </span>
                        <span class="sp2">
                            <button>Subscribe</button>
                        </span>

                    </div>
                </div>



            </div>

        </div>
    @endsection

