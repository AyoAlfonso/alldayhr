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
    <link rel="stylesheet" href="{{ asset('auth_assets/css/main.css') }}">
    <link href="{{ asset('assets/node_modules/sweetalert/sweetalert.css') }}" rel="stylesheet">

    <link href="{{ asset('front/assets/css/custom.css') }}" rel="stylesheet">
    <!-- Favicons -->
    <link rel="icon" type="image/png" href="{{URL::asset('auth_assets/images/icons/favicon.png')}}"/>
    <link rel="manifest" href="{{ asset('favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">

    <style>
        @font-face {
            font-family: ubuntu-bold;
            src: url({{ asset('fonts/Ubuntu-B.ttf?e0008b580192405f144f2cb595100969')}});
        }

        @font-face {
            font-family: ubuntu-light;
            src: url({{ asset('fonts/Ubuntu-L.ttf?8571edb1bb4662f1cdba0b80ea0a1632')}});
        }

        @font-face {
            font-family: ubuntu-medium;
            src: url({{ asset('fonts/Ubuntu-M.ttf?785d8031758d1fac400213600066aee6')}});
        }
        body {
            font-family: "ubuntu-light";
        }
    </style>
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

<body style="background-color: #F7F8FC;">

<nav class="navbar navbar-default" role="navigation" style="background-color: #fff;">

    <div class="row">

        <div style="margin-left:6%; display: inline-block;" class="col-md-2">
            <a href="{{url('/')}}">
                    <img src="{{ asset('/auth_assets/images/LogoAllDayHr.png')}}" style="width: 80px;
                                 margin-top: 2.0%;
                                 " alt="alldayhr-outsource-img">
            </a>
        </div>




            <div class="" style="margin-left: 60%; margin-top: 1%">

                <div class="col-md-* show-horizontal-menu float-right">
                    @if(Auth::guard('candidate')->check())
                        <div class="dropdown">
                            <a href="javascript:;" class="btn btn-sm dropdown-toggle bg-transparent border-0 text-capitalize" id="dropdownMenuButton"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"  style="font-weight: normal;">
                                <div class="d-inline">
                                    <img class="rounded-circle w-30 h-30" src="{{!empty(Auth::guard('candidate')->user()->profile_image_url) ? Auth::guard('candidate')->user()->profile_image_url : URL::to('assets/images/profile_placeholder2.png')}}" >
                                </div>
                                {{Auth::guard('candidate')->user()->user->firstname.' '.Auth::guard('candidate')->user()->user->lastname}}
                            </a>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item text-dark" href="{{route('profile.candidateDashboard')}}">Dashboard</a>
                                <a class="dropdown-item text-dark" href="{{route('profile.candidateProfile')}}">Profile</a>
                                <a class="dropdown-item text-dark" href="{{route('candidate.logout')}}">Logout</a>
                            </div>
                        </div>
                    @endif


             </div>
            </div>

            <div class="col-md-4 hamburger_main notfication-btn">
                <label class="">
                    <input type="checkbox">
                    <span class="menu">
            <span class="hamburger"> </span>
            </span>

                    <ul>
                        <li>
                            @if(Auth::guard('candidate')->check())
                                <div class="dropdown">
                                    <a href="javascript:;" class="btn btn-sm dropdown-toggle bg-transparent border-0 text-capitalize" id="dropdownMenuButton"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div class="d-inline">
                                            <img class="rounded-circle w-30 h-30" src="{{!empty(Auth::guard('candidate')->user()->profile_image_url) ? Auth::guard('candidate')->user()->profile_image_url : URL::to('assets/images/profile_placeholder2.png')}}" >
                                        </div>
                                        {{Auth::guard('candidate')->user()->user->firstname.' '.Auth::guard('candidate')->user()->user->lastname}}
                                    </a>

                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item text-dark" href="{{route('profile.candidateDashboard')}}">Dashboard</a>
                                        <a class="dropdown-item text-dark" href="{{route('profile.candidateProfile')}}">Profile</a>
                                        <a class="dropdown-item text-dark" href="{{route('candidate.logout')}}">Logout</a>
                                    </div>
                                </div>
                            @endif
                        </li>

                    </ul>
                </label>

            </div>

        </div>
    </div>

</nav>


<!-- Main container -->
<main class="main-content" style="background-color: #F7F8FC; margin-bottom: 10px;">

    @yield('content')

</main>
<!-- END Main container -->







<!-- Scripts -->
<!--Custom JavaScript -->
<script src="{{ asset('assets/node_modules/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('front/assets/js/core.min.js') }}"></script>
<script src="{{ asset('front/assets/js/thesaas.min.js') }}"></script>

<script src="{{ asset('froiden-helper/helper.js') }}"></script>
<script src="{{ asset('assets/node_modules/toast-master/js/jquery.toast.js') }}"></script>

@stack('footer-script')

</body>
</html>
