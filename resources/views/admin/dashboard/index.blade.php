@extends('layouts.app')

@section('content')
    <div class="row">

        <div class="col-md-12">
            @if(isset($lastVersion))

                <div class="alert alert-info col-md-12">
                    <div class="col-md-10"><i class="ti-gift"></i> @lang('modules.update.newUpdate') <label class="label label-success">{{ $lastVersion }}</label>
                    </div>
                </div>
            @endif
        </div>

        <!-- Column -->
        <div class="col-md-6 col-lg-4 col-xlg-2">
            <div class="card">
                <div class="box bg-dark text-center">
                    <h1 class="font-light text-white">{{ $totalCompanies }}</h1>
                    <h6 class="text-white">@lang('modules.dashboard.totalCompanies')</h6>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-4 col-xlg-2">
            <div class="card">
                <div class="box bg-info text-center">
                    <h1 class="font-light text-white">{{ $totalOpenings }}</h1>
                    <h6 class="text-white">@lang('modules.dashboard.totalOpenings')</h6>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-md-6 col-lg-4 col-xlg-2">
            <div class="card">
                <div class="box bg-primary text-center">
                    <h1 class="font-light text-white">{{ $totalApplications }}</h1>
                    <h6 class="text-white">@lang('modules.dashboard.totalApplications')</h6>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-md-6 col-lg-4 col-xlg-2">
            <div class="card">
                <div class="box bg-success text-center">
                    <h1 class="font-light text-white">{{ $totalHired }}</h1>
                    <h6 class="text-white">@lang('modules.dashboard.totalHired')</h6>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-md-6 col-lg-4 col-xlg-2">
            <div class="card">
                <div class="box bg-dark text-center">
                    <h1 class="font-light text-white">{{ $totalRejected }}</h1>
                    <h6 class="text-white">@lang('modules.dashboard.totalRejected')</h6>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-md-6 col-lg-4 col-xlg-2">
            <div class="card">
                <div class="box bg-danger text-center">
                    <h1 class="font-light text-white">{{ $newApplications }}</h1>
                    <h6 class="text-white">@lang('modules.dashboard.newApplications')</h6>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-md-6 col-lg-4 col-xlg-2">
            <div class="card">
                <div class="box bg-warning text-center">
                    <h1 class="font-light text-white">{{ $shortlisted }}</h1>
                    <h6 class="text-white">@lang('modules.dashboard.shortlistedCandidates')</h6>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-md-6 col-lg-4 col-xlg-2">
            <div class="card">
                <div class="box bg-primary text-center">
                    <h1 class="font-light text-white">{{ $totalTodayInterview }}</h1>
                    <h6 class="text-white">@lang('modules.dashboard.todayInterview')</h6>
                </div>
            </div>
        </div>
    </div>
@endsection