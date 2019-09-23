@extends('layouts.front-adhoc')

@push('head-script')
    <link rel="stylesheet" href="{{ asset('auth_assets/landing/css/landing_.css') }}" >
    <link rel="stylesheet" href="{{ asset('auth_assets/landing/css/sorting_.css') }}"> 
        @if($job->slug== 'nb-01')
         <script>
            /*Bad practice*/
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window,document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '375551203136414'); 
            fbq('track', 'PageView');
        </script>
        <noscript>
        <img height="1" width="1" 
        src="https://www.facebook.com/tr?id=375551203136414&ev=PageView
        &noscript=1"/>
      </noscript>
    @endif
@endpush

@section('content')

        <div class="container-fluid" style="background:#f8f7ff">
            <div class="container">
                <div class="customWrap">
                    <div class="jmbtr" style="background:#fff">
                        <div class="role_div">
                            <p class="role">
                              {{ ucwords($job->title) }}
                            </p>
                        @if($job->company->show_in_frontend == 'true')
                            <h4 class="ad_n"> {{ ucwords($job->company->company_name) }} &nbsp;|&nbsp; {{ ucwords($job->location->location) }}</h4>
                        @endif
                        <span class="job_date_lg"> Posted {{ $job->start_date->format('d M') }}</span>
                        </div>

                        <p class="rolemg">
                            <img  src= "{{ $job->company->logo_url}}" style="max-width:100%;max-height:100%" />
                        </p>
                        <div>
                            <span>
                               
                                <label class="tch" style="background:#eae9f9">
                                    <a style="cursor:pointer;color: #3f36be;" href="{{route('jobs.getJobCategories', 'filter_job_types='.'' . ucwords($job->category->name) )}}" >
                                {{ ucwords($job->category->name) }}
                                 </a>
                                </label>
                                {{-- <label class="ft">Full Time</label> --}}
                               
                            </span>
                        </div>
                        <hr />
                        <div class="ovr">
                            <h6>Description</h6>
                            <p >
                            <div >
                                {!! nl2br($job->job_description) !!}
                            </div>
                            </p>
                        </div>
                    

                        <div class="ovr req">
                            <h6>Requirements</h6>
                          <p style="font-weight: 500;">
                            {!! nl2br($job->job_requirement) !!}
                            </p>
                        </div>
                        <div style="text-align: center;padding:60px 0px">
                    
                                    
                       @if(Auth::guard('candidate')->check())
                        @if(Auth::guard('candidate')->user()->HasAppliedJob($job->id))
                         <button class="btn aply expired-btn job-btn-lg">Already Applied</button>
                         @elseif( strtotime($job->end_date->format('Y-m-d')) < strtotime(Carbon\Carbon::now()->format('Y-m-d')) )
                         <a>  <button class="btn aply expired-btn job-btn-lg">Expired</button>  </a>
                         @elseif(!Auth::guard('candidate')->user()->HasAppliedJob($job->id))
                          <a href="{{ route('jobs.jobApply', $job->slug) }}"> <button class="btn aply blue-aply-btn job-btn-lg"> @lang('modules.front.applyForJob')</button> </a>
                         @endif 
                    @elseif( strtotime($job->end_date->format('Y-m-d')) < strtotime(Carbon\Carbon::now()->format('Y-m-d')) )
                    <a>  <button class="btn aply expired-btn job-btn-lg">Expired</button>  </a>
                    @else
                        <a href="{{ route('jobs.jobApply', $job->slug) }}"> <button class="btn aply blue-aply-btn job-btn-lg"> @lang('modules.front.applyForJob')</button> </a>
                    @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="customWrap">
                    <div class="rltd">Related Jobs</div>
                    <div class=" container-fluid job-wrapper" style="padding-left:0px;padding-right:0px">
                   
                     @foreach($relatedJobs as $job)
                      <a href="{{ route('jobs.jobDetail', [$job->slug]) }}">
                        <div class="col-sm-6 x12" style="padding-right:0px">
                            <div class="rect">
                                <p>
                                    <span style="margin-right: auto;" class="h5 title"> {{ ucwords($job->title) }} </span>
                                    <span class="avater">
                                   
                                        <img src="{{ $job->company->logo_url}}" />
                                    </span>
                                </p>
                                <span class="addr"> 
                                  {{ ucwords($job->company->company_name) }}
                              &nbsp;</span>
                             <span style="color: #282828;opacity: 0.6;font-size: 20px;vertical-align: top;margin-top: -5px;">  | </span>
                             <span style="margin-left: 5px;" class="addr">
                             &nbsp;{{ ucwords($job->location->location)}}
                             </span>
                                <p class="addr_a">
                                 {{  strip_tags($job->job_description) }}
                                </p>
                                <span class="job_date"> Posted {{ $job->start_date->format('d M') }}</span>
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
                    <a>
                    @endforeach
                    </div>
                    <div>
                        <div class="show">
                        <a href="{{ route('jobs.getJobCategories') }}"> 
                            <button class="btn dts">Browse more jobs</button>
                            <a/>
                        </div>
                    </div>

                <script>
                $(".top_search").css('display' , "table-cell")
       
                </script>
                </div>
@endsection


