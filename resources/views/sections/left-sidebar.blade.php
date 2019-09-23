<!-- Main Sidebar Container -->
<aside id="main-sidebar" class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <img src="{{ $global->logo_url }}"
             alt="AdminLTE Logo"
             class="brand-image img-fluid">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ $user->profile_image_url  }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="{{ route('admin.profile.index') }}" class="d-block">{{ ucwords($user->name) }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" id="sidebarnav" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <i class="nav-icon icon-speedometer"></i>
                        <p>
                            @lang('menu.dashboard')
                        </p>
                    </a>
                </li>

                @permission('view_category')
                <li class="nav-item">
                    <a href="{{ route('admin.job-categories.index') }}" class="nav-link">
                        <i class="nav-icon icon-grid"></i>
                        <p>
                            @lang('menu.jobCategories')
                        </p>
                    </a>
                </li>
                @endpermission

                @permission('view_skills')
                <li class="nav-item">
                    <a href="{{ route('admin.skills.index') }}" class="nav-link">
                        <i class="nav-icon icon-grid"></i>
                        <p>
                            @lang('menu.skills')
                        </p>
                    </a>
                </li>
                @endpermission

                @permission('view_company')
                <li class="nav-item">
                    <a href="{{ route('admin.company.index') }}" class="nav-link">
                        <i class="nav-icon icon-film"></i>
                        <p>
                            @lang('menu.companies')
                        </p>
                    </a>
                </li>
                @endpermission

                @permission('view_locations')
                <li class="nav-item">
                    <a href="{{ route('admin.locations.index') }}" class="nav-link">
                        <i class="nav-icon icon-location-pin"></i>
                        <p>
                            @lang('menu.locations')
                        </p>
                    </a>
                </li>
                @endpermission

                @permission('view_jobs')
                <li class="nav-item">
                    <a href="{{ route('admin.jobs.index') }}" class="nav-link">
                        <i class="nav-icon icon-badge"></i>
                        <p>
                            @lang('menu.jobs')
                        </p>
                    </a>
                </li>
                @endpermission

                @permission('view_job_applicationss')
                <li class="nav-item">
                    <a href="{{ route('admin.job-applications.index') }}" class="nav-link">
                        <i class="nav-icon icon-user"></i>
                        <p>
                            @lang('menu.jobApplications')
                        </p>
                    </a>
                </li>
                @endpermission

                @permission('view_assessments')
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon icon-check"></i>
                        <p>
                            @lang('menu.assessment')
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.candidate-assessment.get-jobs') }}" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p> @lang('menu.onlineTest')</p>
                            </a>
                        </li>
                 
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>@lang('menu.jobInterview')</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endpermission

                @permission('view_candidate_pool')
                <li class="nav-item">
                    <a href="{{ route('admin.showCandidatePool') }}" class="nav-link">
                        <i class="nav-icon icon-pie-chart"></i>
                        <p>
                            @lang('menu.candidatePool')
                        </p>
                    </a>
                </li>
                @endpermission


                @permission('view_schedule')
                <li class="nav-item">
                    <a href="{{ route('admin.interview-schedule.index') }}" class="nav-link">
                        <i class="nav-icon icon-calendar"></i>
                        <p>
                            @lang('menu.interviewSchedule')
                        </p>
                    </a>
                </li>
                @endpermission

                @permission('view_team')
                <li class="nav-item">
                    <a href="{{ route('admin.team.index') }}" class="nav-link">
                        <i class="nav-icon icon-people"></i>
                        <p>
                            @lang('menu.team')
                        </p>
                    </a>
                </li>
                @endpermission

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon icon-settings"></i>
                        <p>
                            @lang('menu.settings')
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.profile.index') }}" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p> @lang('menu.myProfile')</p>
                            </a>
                        </li>
                        @permission('manage_settings')
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.index') }}" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>@lang('menu.businessSettings')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.role-permission.index') }}" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>@lang('menu.rolesPermission')</p>
                            </a>
                        </li>
                        <li style="display:none" class="nav-item">
                            <a href="{{ route('admin.language-settings.index') }}" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>@lang('app.language') @lang('menu.settings')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.theme-settings.index') }}" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>@lang('menu.themeSettings')</p>
                            </a>
                        </li>
                        <li style="display:none"  class="nav-item">
                            <a href="{{ route('admin.update-application.index') }}" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>@lang('menu.updateApplication')</p>
                            </a>
                        </li>
                        <li style="display:none"  class="nav-item">
                            <a href="{{ route('admin.smtp-settings.index') }}" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>@lang('menu.mailSetting')</p>
                            </a>
                        </li>
                        @endpermission

                    </ul>
                </li>

                <li class="nav-header">MISCELLANEOUS</li>
                <li class="nav-item">
                    <a href="{{ url('/') }}" target="_blank" class="nav-link">
                        <i class="nav-icon fa fa-external-link"></i>
                        <p>Front Website</p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
