<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0'/>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <base href = "{{URL::to('')}}/" />
    <title> @yield('title') | AlldayHR</title>

    <!-- Styles -->
    <link href="{{ asset('froiden-helper/helper.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/node_modules/toast-master/css/jquery.toast.css') }}" rel="stylesheet">

    <link href="{{ asset('front/assets/css/core.min.css') }}" rel="stylesheet">
    <link href="{{ asset('front/assets/css/thesaas.min.css') }}" rel="stylesheet">
    <link href="{{ asset('front/assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('front/assets/css/custom.css') }}" rel="stylesheet">
    <link href="{{URL::to('')}}{{ mix('css/app.css') }}" rel="stylesheet">

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="{{URL::asset('auth_assets/images/icons/favicon.png')}}"/>
    <link rel="manifest" href="{{ asset('favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <style>
        @font-face {
            font-family: ubuntu-bold;
            src: url({{ asset('fonts/Ubuntu-B.ttf?e0008b580192405f144f2cb595100969')}}) format('truetype');
        }

        @font-face {
            font-family: ubuntu-light;
            src: url({{ asset('fonts/Ubuntu-L.ttf?8571edb1bb4662f1cdba0b80ea0a1632')}}) format('truetype');
        }

        @font-face {
            font-family: ubuntu-medium;
            src: url({{ asset('fonts/Ubuntu-M.ttf?785d8031758d1fac400213600066aee6')}}) format('truetype');
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

<body>
<header>
<nav class="navbar navbar-expand-lg navbar-light bg-white py-2 px-md-5">
  <a class="navbar-brand" href="{{url('/') }}">
    <img src="{{ asset('assets/images/logo-main.png') }}" width="80" height="40" alt="">
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse mx-md-4" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      
    </ul>
    <ul class="navbar-nav">


        <li class="nav-item">
            @if(Auth::guard('candidate')->check())
                <div class="dropdown">
                    <a href="javascript:;" class="btn btn-sm dropdown-toggle bg-transparent border-0 nav-link text-capitalize" id="dropdownMenuButton"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                        <div class="d-inline">
                            <img class="rounded-circle w-25px h-25px" src="{{!empty(Auth::guard('candidate')->user()->profile_image_url) ? Auth::guard('candidate')->user()->profile_image_url : URL::to('assets/images/profile_placeholder2.png')}}" >
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
      {{--<li class="nav-item">--}}
        {{--<a class="nav-link" href="{{route('candidate.logout')}}">Logout</a>--}}
      {{--</li>--}}
    </ul>
  </div>
</nav>
</header>
<!-- Main container -->
<main class="main-content">

    @yield('content')

</main>
<!-- END Main container -->


<!-- Footer -->

<!-- END Footer -->



<!-- Scripts
<script src="{{ asset('front/assets/js/core.min.js') }}"></script>
<script src="{{ asset('front/assets/js/thesaas.min.js') }}"></script>
<script src="{{ asset('front/assets/js/script.js') }}"></script>
<script src="{{ asset('froiden-helper/helper.js') }}"></script>
<script src="{{ asset('assets/node_modules/toast-master/js/jquery.toast.js') }}"></script>
-->
<script src="{{URL::to('')}}{{ mix('js/app.js') }}"></script>

@stack('footer-script')

</body>
</html>
