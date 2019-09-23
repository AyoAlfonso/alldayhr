<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="keywords" content="">

    <title>{{ $pageTitle }}</title>

    <style>
        :root {
            --main-color: {{ $frontTheme->primary_color }};
        }

        {!! $frontTheme->front_custom_css !!}
    </style>

    <!-- Styles -->
    <link href="{{ asset('froiden-helper/helper.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/node_modules/toast-master/css/jquery.toast.css') }}" rel="stylesheet">

    <link href="{{ asset('front/assets/css/core.min.css') }}" rel="stylesheet">
    <link href="{{ asset('front/assets/css/thesaas.min.css') }}" rel="stylesheet">
    <link href="{{ asset('front/assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('front/assets/css/custom.css') }}" rel="stylesheet">

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="{{URL::asset('auth_assets/images/icons/favicon.png')}}"/>
    <link rel="manifest" href="{{ asset('favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <!-- Hotjar Tracking Code for select.alldayhr.com -->
    <script>
        (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:1396639,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    </script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-143633246-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-143633246-1');
    </script>
</head>

<body>
<div class="container-fluid wrapperContainer font_ x12">

<!-- Topbar -->
<nav class="topbar topbar-inverse topbar-expand-md">
    <div class="container">

        <div class="topbar-left">
            {{-- <button class="topbar-toggler">&#9776;</button> --}}
            <a class="topbar-brand" href="{{ url('/') }}">
                <img src="{{ $global->logo_url }}" class="logo-inverse" alt="home"/>
            </a>
        </div>

        <div class="topbar-right">

            @if(Auth::guard('candidate')->check())
                <div class="dropdown">
                    <a href="javascript:;" class="btn btn-sm dropdown-toggle bg-transparent border-0 text-capitalize" id="dropdownMenuButton"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="d-inline">
                            <img class="rounded-circle w-30 h-30" src="{{!empty(Auth::guard('candidate')->user()->profile_image_url) ? Auth::guard('candidate')->user()->profile_image_url : URL::to('assets/images/profile_placeholder2.png')}}" >
                        </div>
                        {{-- {{Auth::guard('candidate')->user()->user->firstname.' '.Auth::guard('candidate')->user()->user->lastname}} --}}
                    </a>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item text-dark" href="{{route('profile.candidateDashboard')}}">Dashboard</a>
                        <a class="dropdown-item text-dark" href="{{route('profile.candidateProfile')}}">Profile</a>
                        <a class="dropdown-item text-dark" href="{{route('candidate.logout')}}">Logout</a>
                    </div>
                </div>
            @else
                <div class="d-inline-flex ">
                    <a class="btn btn-sm btn-primary mr-4" href="{{ route('candidate.candidatesignup') }}"
                       style="background: white;color: #1579d0;">Sign Up</a>
                    <a class="btn-home btn btn-sm btn-primary mr-4" href="{{ route('candidate.candidatelogin') }}">Login<i
                                class="fa fa-arrow-right"></i></a>
                </div>
            @endif
        </div>
    </div>
</nav>
<!-- END Topbar -->


<!-- Header -->
<header class="header header-inverse" style="background-image: url({{ asset('front/assets/img/header-bg.jpg') }})"
        data-overlay="8">
    <div class="container text-center">

        <div class="row">
            <div class="col-12 col-lg-8 offset-lg-2">

                @yield('header-text')

            </div>
        </div>

    </div>
</header>
<!-- END Header -->


<!-- Main container -->
<main class="main-content">

    @yield('content')

</main>
<!-- END Main container -->


<!-- Footer -->
<footer class="site-footer">
    <div class="container">
        <div class="row gap-y align-items-center">
            <div class="col-12 col-lg-3">
                &copy; {{ \Carbon\Carbon::today()->year }} @lang('app.by') {{ $companyName }}

            </div>
        </div>
    </div>
</footer>
<!-- END Footer -->


<!-- Scripts -->
<script src="{{ asset('front/assets/js/core.min.js') }}"></script>
<script src="{{ asset('front/assets/js/thesaas.min.js') }}"></script>
<script src="{{ asset('front/assets/js/script.js') }}"></script>
<script src="{{ asset('froiden-helper/helper.js') }}"></script>
<script src="{{ asset('assets/node_modules/toast-master/js/jquery.toast.js') }}"></script>
<script>

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('jobs.saveApplication')}}',
            container: '#createForm',
            type: "POST",
            file: true,
            redirect: true,
            // data: $('#createForm').serialize(),
            success: function (response) {
                if (response.status == 'success') {
                    var successMsg = '<div class="alert alert-success my-100" role="alert">' +
                        response.msg + ' <a class="" href="{{ route('jobs.jobOpenings') }}">@lang("app.view") @lang("modules.front.jobOpenings") <i class="fa fa-arrow-right"></i></a>'
                    '</div>';
                    $('.main-content .container').html(successMsg);
                }
            },
            error: function (response) {
                handleFails(response);
            }
        })
    });

</script>
@stack('footer-script')
    </div>
  </body>
</html>
