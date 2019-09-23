@extends('layouts.front')

@section('header-text')
    <h1 class="hidden-sm-down">{{ ucwords($job->title) }}</h1>
    <h5 class="hidden-sm-down"><i class="icon-map-pin"></i> {{ ucwords($job->location->location) }}</h5>
@endsection

@section('content')

    <div class="container">
        <div class="row">

            <div class="col-md-12 fs-12 pt-50 pb-10 bb-1 mb-20">
                <a class="text-dark"
                   href="{{ route('jobs.jobOpenings', ['limit' => 5]) }}">@lang('modules.front.jobOpenings')</a> &raquo; <span
                        class="theme-color">{{ ucwords($job->title) }}</span>
            </div>

            <div class="col-md-8">
                <div class="row gap-y">
                    <div class="col-md-12">
                        <h2>{{ ucwords($job->title) }}</h2>
                        @if($job->company->show_in_frontend == 'true')
                            <small class="company-title">@lang('app.by') {{ ucwords($job->company->company_name) }}</small>
                        @endif
                        <p>{{ ucwords($job->category->name) }}</p>

                        @if(count($job->skills) > 0)
                            <h6>@lang('menu.skills')</h6>
                            <div class="gap-multiline-items-1">
                                @foreach($job->skills as $skill)
                                    <span class="badge badge-secondary">{{ $skill->skill->name }}</span>
                                @endforeach
                            </div>
                        @endif

                        <h4 class="theme-color mt-20">@lang('modules.jobs.jobDescription')</h4>

                        <div>
                            {!! $job->job_description !!}
                        </div>

                        <h4 class="theme-color mt-20">@lang('modules.jobs.jobRequirement')</h4>

                        <div>
                            {!! $job->job_requirement !!}
                        </div>

                        <div class="my-30 text-center">
                            @if(Auth::guard('candidate')->check())
                                @if(!Auth::guard('candidate')->user()->HasAppliedJob($job->id))
                                    <a class="btn btn-lg btn-primary theme-background"
                                       href="{{ route('jobs.jobApply', $job->slug) }}">@lang('modules.front.applyForJob')</a>
                                @else
                                    <p class="btn btn-lg btn-success"
                                       >Already Applied</p>

                                @endif
                            @else
                                <a class="btn btn-lg btn-primary theme-background"
                                   href="{{ route('jobs.jobApply', $job->slug) }}">@lang('modules.front.applyForJob')</a>

                            @endif
                        </div>

                    </div>

                </div>

            </div>

            <div class="col-md-4 hidden-sm-down">
                <div class="sidebar">
                    @if(Auth::guard('candidate')->check())
                        @if(!Auth::guard('candidate')->user()->HasAppliedJob($job->id))
                            <a class="btn btn-lg btn-primary theme-background my-10" href="{{ route('jobs.jobApply', $job->slug) }}">@lang('modules.front.applyForJob')</a>
                        @else
                            <p class="btn btn-lg btn-success d-block my-10">Already Applied</p>
                        @endif
                    @else
                        <a class="btn btn-lg btn-primary theme-background my-10"
                           href="{{ route('jobs.jobApply', $job->slug) }}">@lang('modules.front.applyForJob')</a>
                    @endif

                    <div class="b-1 border-light mt-20 text-center">
                        <span class="fs-12 fw-600">@lang('modules.front.shareJob')</span>

                        <div class="social social-boxed social-colored social-cycling text-center my-10">
                            <a class="social-linkedin"
                               href="https://www.linkedin.com/shareArticle?mini=true&url={{ route('jobs.jobDetail', [$job->slug]) }}&title={{ urlencode(ucwords($job->title)) }}&source=LinkedIn"
                            ><i class="fa fa-linkedin"></i></a>
                            <a class="social-facebook"
                               href="https://www.facebook.com/sharer/sharer.php?u={{ route('jobs.jobDetail', [$job->slug]) }}"
                            ><i class="fa fa-facebook"></i></a>
                            <a class="social-twitter"
                               href="https://twitter.com/home?status={{ route('jobs.jobDetail', [$job->slug]) }}"
                            ><i class="fa fa-twitter"></i></a>
                            <a class="social-gplus"
                               href="https://plus.google.com/share?url={{ route('jobs.jobDetail', [$job->slug]) }}"
                            ><i class="fa fa-google-plus"></i></a>
                        </div>

                    </div>


                </div>
            </div>

        </div>
    </div>
@endsection
