<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" href="{{URL::asset('auth_assets/images/icons/favicon.png')}}"/>
    <link rel="manifest" href="{{ asset('favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@lang('app.adminPanel') | {{ $pageTitle }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    <!-- Simple line icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.css">

    <!-- Themify icons -->
    <link rel="stylesheet" href="{{ asset('assets/icons/themify-icons/themify-icons.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->

    <link href="{{ asset('froiden-helper/helper.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/node_modules/toast-master/css/jquery.toast.css') }}" rel="stylesheet">

    <link rel='stylesheet prefetch' href='//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/css/bootstrap-select.min.css'>

    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link href="{{ asset('assets/node_modules/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/node_modules/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('auth_assets/css/adminlite_.css') }}">

    @stack('head-script')

    <link rel='stylesheet prefetch'
          href='//cdnjs.cloudflare.com/ajax/libs/flag-icon-css/0.8.2/css/flag-icon.min.css'>

    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style>
        :root {
            --main-color: {{ $adminTheme->primary_color }};
        }

        {!! $adminTheme->admin_custom_css !!}
    </style>

    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
            </li>
        </ul>
        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">

            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown" id="top-notification-dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fa fa-bell-o"></i>
                    @if(count($user->unreadNotifications) > 0)
                        <span class="badge badge-warning navbar-badge ">{{ count($user->unreadNotifications) }}</span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                    @foreach ($user->unreadNotifications as $notification)
                        @if(isset($notification->data['data']['full_name']))
                        <a href="{{ route('admin.job-applications.index') }}" class="dropdown-item text-sm">
                            <i class="fa fa-users mr-2"></i><span class="text-truncate" style="overflow-y: hidden" title="{{ ucwords($notification->data['data']['full_name']).' '.__('modules.jobApplication.appliedFor').' '.ucwords($notification->data['data']['job']['title']) }}">
                                {{ ucwords(str_limit($notification->data['data']['full_name'], $limit = 7, $end = '..'))}}
                                {{__('modules.jobApplication.appliedFor')}}
                                {{ ucwords(str_limit($notification->data['data']['job']['title'], $limit = 7, $end = '..')) }} </span>
                            <span class="float-right text-muted text-sm">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->data['data']['created_at'])->diffForHumans() }}</span>
                            <div class="clearfix"></div>
                        </a>
                        <div class="dropdown-divider"></div>
                        @endif
                    @endforeach
                    <a id="mark-notification-read" href="javascript:void(0);" class="dropdown-item dropdown-footer">@lang('app.markNotificationRead') <i class="fa fa-check"></i></a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link  waves-effect waves-light" href="{{ route('logout') }}" title="Logout" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();"
                ><i class="fa fa-power-off"></i>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </a>

            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    @include('sections.left-sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" >

        @include('sections.breadcrumb-ass')

        <!-- Main content -->
        <section class="content">

            @yield('content')

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    {{--Ajax Modal--}}
    <div class="modal" id="application-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> @lang('app.cancel')</button>
                    <button type="button" class="btn btn-success"><i class="fa fa-check"></i> @lang('app.save')</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}


    <footer class="main-footers">
{{--        &copy; {{ \Carbon\Carbon::today()->year }} @lang('app.by') {{ $companyName }}--}}
    </footer>

    @include('sections.right-sidebar')
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/node_modules/popper/popper.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.js') }}"></script>

<!-- SlimScroll -->
<script src="{{ asset('assets/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('assets/plugins/fastclick/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>

<script src="{{ asset('js/sidebarmenu.js') }}"></script>
<script src='//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.2/js/bootstrap-select.min.js'></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.all.js"></script>
{{-- <script src="https://unpkg.com/sweetalert2@7.8.2/dist/sweetalert2.all.js"></script> --}}

<script src="{{ asset('froiden-helper/helper.js') }}"></script>
<script src="{{ asset('assets/node_modules/toast-master/js/jquery.toast.js') }}"></script>
<link type="text/css" rel="stylesheet" href="{{ asset('auth_assets/css/flexdatalist.css') }}">
<script src="{{ asset('auth_assets/js/flexdatalist.js') }}"></script>
<script src="{{ asset('auth_assets/js/flexdatalistadhoc.js') }}"></script>

<script>
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
 $('.flexdatalist').flexdatalist({
     valuesSeparator: ',',
     searchByWord: true,
     selectionRequired: 0,
     minLength: 0,
     limitOfValues: 6,
     searchContain: true,
     maxShownResults: 5000,
     noResultsText: 'Type in directly to search...'
});
$('#sendEmailJobModal').on("hidden.bs.modal", function() {
    $("#main-sidebar").removeClass('overlay-sidebar-cd');
});

$('#sendEmailJobModal').on("shown.bs.modal", function() {
        $("#main-sidebar").addClass('overlay-sidebar-cd');
});

$('#assignToJobModal').on("hidden.bs.modal", function() {
    $("#main-sidebar").removeClass('overlay-sidebar-cd');
});

$('#assignToJobModal').on("shown.bs.modal", function() {
        $("#main-sidebar").addClass('overlay-sidebar-cd');
});

$("#assign_candidate_to_org").change(function(){
    var org = this.value;
    $.ajax({ 
        url: '{!! route('admin.getOrganisationJobs') !!}?org=' + org,
        success: function(response){
        let data = response.response;
        let option = '';
        data.forEach((datum, i )=>{
            option += '<option value="'+ datum['id'] + '">' + datum['title'] + '</option>';
       })
       $('#assign_candidate_to_org_section_role').empty();
       $('#assign_candidate_to_org_section_role').append(option);
    },
    error: function(){
    }
 });
});

</script>

<script>

function applyShortlistingFilter () {
    runFlex0();
   document.getElementById('onShortlistCandidatesform')?  document.getElementById('onShortlistCandidatesform').submit() : null;
}

  function getShortlistingParams() {
            
            let skills = $("input[name=skills]").val()? $("input[name=skills]").val(): '';
            let companies = $("input[name=companies]").val() ?  $("input[name=companies]").val() : ''
            let jobTitles = $("input[name=jobTitles]").val() ? $("input[name=jobTitles]").val() : ''
            let industry = $("input[name=industry]").val() ? $("input[name=industry]").val() : ''
            let keyword = $("input[name=keyword]").val() ?  $("input[name=keyword]").val() : ''
            let university = $("input[name=university]").val() ?  $("input[name=university]").val() : ''
            let candidateCourse = $("input[name=candidateCourse]").val() ?  $("input[name=candidateCourse]").val() : ''
            let candidateDegrees = $("input[name=candidateDegrees]").val() ?  $("input[name=candidateDegrees]").val() : ''
            let candidateQualifications = $("input[name=candidateQualifications]").val() ?  $("input[name=candidateQualifications]").val() : ''
            let candidate_state_of_origin = $("input[name=candidate_state_of_origin]").val() ?  $("input[name=candidate_state_of_origin]").val() : ''
            let candidateResidentialState = $("input[name=candidateResidentialState]").val() ?  $("input[name=candidateResidentialState]").val() : ''
            let candidate_certifications = $("input[name=candidate_certifications]").val() ?  $("input[name=candidate_certifications]").val() : ''
            let candidate_age_higher_bound =  document.querySelector("#candidate_age_higher_bound").value ? document.querySelector("#candidate_age_higher_bound").value : ''
            let candidate_age_lower_bound = document.querySelector("#candidate_age_lower_bound").value ? document.querySelector("#candidate_age_lower_bound").value : ''
            let candidate_experience_higher_bound = document.querySelector("#candidate_experience_higher_bound").value  ? document.querySelector("#candidate_experience_higher_bound").value :  ''
            let candidate_experience_lower_bound =  document.querySelector("#candidate_experience_lower_bound").value ? document.querySelector("#candidate_experience_lower_bound").value : ''
            let relevant_experience_higher_bound =  document.querySelector("#relevant_experience_higher_bound").value ?  document.querySelector("#relevant_experience_higher_bound").value : ''
            let relevant_experience_lower_bound = document.querySelector("#relevant_experience_lower_bound").value  ?  document.querySelector("#relevant_experience_lower_bound").value  : ''
            let olevel_lower_bound = document.querySelector("#olevel_lower_bound").value ?  document.querySelector("#olevel_lower_bound").value : ''
            let olevel_higher_bound = document.querySelector("#olevel_higher_bound").value ? document.querySelector("#olevel_higher_bound").value : ''
            let nysc_strict_result = $("input[name=nysc_strict_result]").val() ?  $("input[name=nysc_strict_result]").val() : ''
            
            return {
                skills,
                companies,
                jobTitles,
                industry,
                keyword,
                university,
                candidateQualifications,
                candidateDegrees,
                candidateResidentialState,
                candidateCourse,
                candidate_age_higher_bound,
                candidate_age_lower_bound,
                candidate_experience_higher_bound,
                candidate_experience_lower_bound,
                relevant_experience_lower_bound,
                relevant_experience_higher_bound,
                candidate_state_of_origin,
                candidate_certifications,
                olevel_higher_bound,
                olevel_lower_bound,
                nysc_strict_result
            };
        } 
</script>

<script>

function onAssignCandidate () {
    let candidateChecked = [];
    let candidates = $('.cd-radio-input');
    const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
  for( var h=0;h<candidates.length;h++) {
    if (candidates[h].checked) {
         candidateChecked.push(candidates[h].value);
        }
    }

    let target_org = document.getElementById("assign_candidate_to_org").value;
    let org_section_role = document.getElementById("assign_candidate_to_org_section_role").value;
    
    function toastMessageFunc(data) {
        Toast.fire({
        type: 'error',
        title: `You didn't put in a ${data}`
        })
        }

    if (candidateChecked.length < 1) {
          toastMessageFunc('candidate');
        return;
    }

    if (!org_section_role) {
    toastMessageFunc('job in particular');
    return;
    }

  $.easyAjax({
        url: '{{route("admin.assignjobtocandidate")}}',
        type: 'POST',
        data: {
            candidateChecked,
            target_org,
            org_section_role
        },
        crossDomain: true,
        success: function(response){
        let data = response.response;
        let option = '';

        $.unblockUI();
        $("#assignToJobModal").modal('hide');
        Toast.fire({
        type: 'success',
        title: 'Candidates have been assigned to job!'
        })
    },
    error: function(error){
          Toast.fire({
        type: 'error',
        title: `Couldn't assign candidates to job`
        })
        }
        });
    }

    $('body').on('click', '.right-side-toggle', function () {
        $("body").removeClass("control-sidebar-slide-open");
    })

    $(function () {
        $('.selectpicker').selectpicker();
    });

    $('.language-switcher').change(function () {
        var lang = $(this).val();
        $.easyAjax({
            url: '{{ route("admin.language-settings.change-language") }}',
            data: {'lang': lang},
            success: function (data) {
                if (data.status == 'success') {
                    window.location.reload();
                }
            }
        });
    });

    $('#mark-notification-read').click(function () {
        var token = '{{ csrf_token() }}';
        $.easyAjax({
            type: 'POST',
            url: '{{ route("mark-notification-read") }}',
            data: {'_token': token},
            success: function (data) {
                if (data.status == 'success') {
                    $('.top-notifications').remove();
                    $('#top-notification-dropdown .notify').remove();
                    window.location.reload();
                }
            }
        });

    });
</script>

@stack('footer-script')

</body>
</html>
