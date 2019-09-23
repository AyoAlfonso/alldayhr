<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- User Profile-->
        <div class="user-profile">
            <div class="user-pro-body">
                <div>
                    <img src="{{ $user->profile_image_url  }}" alt="user-img" class="img-circle">
                    <div class="dropdown">
                        <a href="javascript:void(0)" class="dropdown-toggle u-dropdown link hide-menu" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ ucwords($user->name) }} <span class="caret"></span></a>
                        <div class="dropdown-menu animated flipInY">
                            <!-- text-->
                            <a href="{{ route('admin.profile.index') }}" class="dropdown-item"><i class="ti-user"></i> @lang('menu.myProfile')</a>
                            <div class="dropdown-divider"></div>
                            <!-- text-->
                            <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
                            ><i class="fa fa-power-off"></i> @lang('logout')</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                            <!-- text-->
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li> <a class="waves-effect waves-dark" href="{{ route('admin.dashboard') }}"><i class="icon-speedometer"></i><span class="hide-menu">@lang('menu.dashboard')</span></a>
                </li>

                @permission('view_category')
                <li> <a class="waves-effect waves-dark" href="{{ route('admin.job-categories.index') }}"><i class="icon-grid"></i><span class="hide-menu">@lang('menu.jobCategories')</span></a>
                </li>
                @endpermission

                @permission('view_skills')
                <li> <a class="waves-effect waves-dark" href="{{ route('admin.skills.index') }}"><i class="icon-grid"></i><span class="hide-menu">@lang('menu.skills')</span></a>
                </li>
                @endpermission

                @permission('view_locations')
                <li> <a class="waves-effect waves-dark" href="{{ route('admin.locations.index') }}"><i class="icon-location-pin"></i><span class="hide-menu">@lang('menu.locations')</span></a>
                </li>
                @endpermission

                @permission('view_jobs')
                <li> <a class="waves-effect waves-dark" href="{{ route('admin.jobs.index') }}"><i class="icon-badge"></i><span class="hide-menu">@lang('menu.jobs')</span></a>
                </li>
                @endpermission

                @permission('view_job_applications')
                <li> <a class="waves-effect waves-dark" href="{{ route('admin.job-applications.index') }}"><i class="icon-user"></i><span class="hide-menu">@lang('menu.jobApplications')</span></a>
                </li>
                @endpermission

                @permission('view_candidate_pool')
                <li> <a class="waves-effect waves-dark" href="{{ route('admin.showCandidatePool') }}"><i class="icon-group"></i><span class="hide-menu">@lang('menu.candidatePool')</span></a>
                </li>
                @endpermission

                @permission('view_team')
                <li> <a class="waves-effect waves-dark" href="{{ route('admin.team.index') }}"><i class="icon-people"></i><span class="hide-menu">@lang('menu.team')</span></a>
                </li>
                @endpermission

                @permission('manage_settings')
                <li> <a class="has-arrow waves-effect waves-dark" href="{{ route('admin.settings.index') }}" aria-expanded="false"><i class="icon-settings"></i><span class="hide-menu">@lang('menu.settings')</span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li> <a href="{{ route('admin.settings.index') }}">@lang('menu.companySettings')</a></li>
                        <li> <a href="{{ route('admin.role-permission.index') }}">@lang('menu.rolesPermission')</a></li>
                        <li> <a href="{{ route('admin.language-settings.index') }}">@lang('app.language') @lang('menu.settings')</a></li>
                        <li> <a href="{{ route('admin.theme-settings.index') }}">@lang('menu.themeSettings')</a></li>
                        <li> <a href="{{ route('admin.update-application.index') }}">@lang('menu.updateApplication')</a></li>
                    </ul>
                </li>
                @endpermission

            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>