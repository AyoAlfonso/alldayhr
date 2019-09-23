
@extends('layouts.front-adhoc')

@push('head-script')
    <link rel="stylesheet" href="{{ asset('auth_assets/landing/css/landing_.css') }}" >
    <link rel="stylesheet" href="{{ asset('auth_assets/landing/css/sorting_.css') }}" >

@endpush

@section('content')

    <div class="board_wrapper">
        <div class="container board top_banner">
           <form id="main_search" method="POST" action="{{ route('jobs.searchJobCategories') }}">
                        @csrf
          
            <div class="inputz input_sort">
                    <span class="state">
                         <select id="location" name="location">
                            <option value="">Anywhere</option>
                            @foreach($locations as $location)
                        <option value="{{  $location["location"] }}"> {{ $location["location"] }}</option>
                        @endforeach
                        </select>
                    </span>
                    
                <span class="search_input txt">
                 <input type="text" autocomplete="nope" name="keyword" value="" placeholder="Enter Keyword">
                    <span class="main_search_btn"> <i class="icon fa fa-search"></i> </span>
                  
                 </span>
            </div>

            <div class="container">

                <div class="container-fluid job-wrapper">
            

                    <div class="col-sm-3">
                            <span class="custom-dropdown">
                                <span style="color:#000;display:inline-block;background:#fff;color:#47505a;font-weight: bold;">Sort by:</span>
                                <select class="nm-adhoc" name="filter_sortby">
                                    <option value=""> Preference </option>
                                    <option value="createdAt">Start Date </option>
                                    <option value="expiryDate">Expiry Date</option>
                                </select>
                            </span>
                    </div>
     
                    <div class="col-sm-3">
                            <span class="custom-dropdown">
                                <span style="color:#000;display:inline-block;background:#fff;color:#47505a;font-weight: bold;">Industry:</span>
                                <select class="nm-adhoc" name="filter_industry">
                                   <option value=""> Enter Industry </option>
                              @foreach ($employee_industry as $item)
                                  <option value="{{$item['industry']}}" > {{$item['industry']}} </option>
                              @endforeach
                                </select>
                            </span>
                    </div>
                
                 
                    <div class="col-sm-3">
                            <span class="custom-dropdown">
                                <span style="color:#000;display:inline-block;background:#fff;color:#47505a;font-weight: bold;">Job Type:</span>
                                <select class="nm-adhoc" name="filter_job_types">
                                <option value=""> Job Type </option>
                              @foreach ($job_categories as $item)
                                <option value="{{$item['name']}}" > {{$item['name']}} </option>
                              @endforeach
                                </select>
                            </span>
                    </div>
                
                 
                    <div class="col-sm-3">
                            <span class="custom-dropdown">
                                <span style="color:#000;display:inline-block;background:#fff;color:#47505a;font-weight: bold;">Job Skills:</span>
                                <select class="nm-adhoc" name="filter_job_skills">
                                <option value=""> Job Skills </option>
                              @foreach ($job_skills as $item)
                                <option value="{{$item['name']}}" > {{$item['name']}} </option>
                              @endforeach
                                </select>
                            </span>
                    </div>
                
                  </div> 
                      @if(sizeof($jobs) < 1) 
                        <div class="no-results">
                                No results found {{ $keyword ?  "for '"." $keyword "."' jobs" : null }} 
                            </div>
                        @endif

            </div>
              </form>
        </div>


        <div class="container">
        
            <div style="" class="container-fluid job-wrapper">
             @foreach($jobs as $job)

              <a href="{{ route('jobs.jobDetail', [$job->slug]) }}">
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
                            <p class="addr_a">
                                    {{ strip_tags($job->job_description) }}
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

            <div class="show">
              {{$pagination->links('vendor.pagination.bootstrap-4-home')}}
            </div>
        </div>
    </div>
    <script>
    $( document ).ready(function() {
        let locationElement  = document.getElementById('location');
        locationElement.value = ("{{$locationSelected}}" ?  "{{$locationSelected}}" : "" );
    })
       
        let filterInputs = document.getElementsByClassName("nm-adhoc");
    [].forEach.call(filterInputs, function (el) {
        if(el) {
        (el.name == 'filter_sortby') ? el.value = "{{$filter_sortby}}" : null;
        (el.name == 'filter_industry') ? el.value = "{{$filter_industry}}": null;
        (el.name == 'filter_job_types') ? el.value = "{{$filter_job_types}}":null;
        (el.name == 'filter_job_skills') ? el.value = "{{$filter_job_skills}}": null;
        }
    });
    </script>
@endsection



