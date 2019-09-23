@extends('layouts.front')

@section('header-text')
    <h1 class="hidden-sm-down"><i class="icon-ribbon"></i> @lang('modules.front.homeHeader') </h1>
    <h4 class="hidden-sm-up"><i class="icon-ribbon"></i> @lang('modules.front.homeHeader') </h4>
@endsection

@section('content')

    <!--
    |‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒
    | Working at TheThemeio
    |‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒

    <section class="section bg-gray py-120">
        <div class="container">

            <div class="row gap-y align-items-center">

                <div class="col-12">
                    <h3>@lang('modules.front.jobOpeningHeading')</h3>
                    <p>@lang('modules.front.jobOpeningText')</p>

                </div>

            </div>

        </div>
    </section>

!-->



    <!--
    |‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒
    | Open positions
    |‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒‒
    !-->
    <section class="section">
        <div class="container">
            <header class="section-header">
                <h2>@lang('modules.front.jobOpenings')</h2>
                <hr>
            </header>


            <div data-provide="shuffle">
                <div class="text-center gap-multiline-items-2 job-filters" data-shuffle="filter">
                    <button class="btn btn-w-md btn-outline btn-round btn-primary active" data-shuffle="button">All
                    </button>
                    @foreach($locations as $location)
                        <button class="btn btn-w-md btn-outline btn-round btn-primary" data-shuffle="button"
                                data-group="{{ $location->location }}">{{ ucwords($location->location) }}</button>
                    @endforeach
                    <p>&nbsp;</p>
                    @foreach($categories as $category)
                        <button class="btn btn-xs btn-outline btn-round btn-dark" data-shuffle="button"
                                data-group="{{ $category->name }}">{{ ucwords($category->name) }}</button>
                    @endforeach
                </div>

                <br><br>

                <div class="row gap-y" data-shuffle="list">

                    @foreach($jobs as $job)
                        <div class="col-12 col-md-6 col-lg-4 portfolio-2" data-shuffle="item"
                             data-groups="{{ $job->location->location.','.$job->category->name }}">
                            <a href="{{ route('jobs.jobDetail', [$job->slug]) }}" class="job-opening-card">
                                <div class="card card-bordered">
                                    <div class="card-block">
                                        <h5 class="card-title">{{ ucwords($job->title) }}</h5>
                                        @if($job->company->show_in_frontend == 'true')
                                            <small class="company-title">@lang('app.by') {{ ucwords($job->company->company_name) }}</small>
                                        @endif
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <span class="fw-600 fs-12 text-info">{{ ucwords($job->location->location).', '.ucwords($job->location->country->country_name) }}</span>
                                            </div>
                                            <div class="col-sm-4 text-right">
                                                <span class="fw-600 fs-12 text-info">{{ ucwords($job->category->name) }}</span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach

                </div>

            </div>


        </div>
    </section>

@endsection
