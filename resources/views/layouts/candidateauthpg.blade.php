<!DOCTYPE html>
<html lang="en">
    <head>
        <title>AlldayHR</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!--===============================================================================================-->
        <link rel="icon" type="image/png" href="{{URL::asset('auth_assets/images/icons/favicon.png')}}"/>
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="{{URL::asset('auth_assets/vendor/bootstrap/css/bootstrap.min.css')}}">
        <!--===============================================================================================-->
        <link rel="stylesheet" type="text/css" href="{{URL::asset('auth_assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
        <!--===============================================================================================-->
        @yield('styles')
        <link href='http://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
        <!--===============================================================================================-->
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
        @yield('contents')
        <!--===============================================================================================-->
        <script src="{{URL::asset('auth_assets/vendor/jquery/jquery-3.2.1.min.js')}}"></script>
        <script src="{{URL::asset('auth_assets/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
        <script src="{{URL::asset('auth_assets/vendor/tilt/tilt.jquery.min.js')}}"></script>
        @yield('scripts')
    </body>
</html>
