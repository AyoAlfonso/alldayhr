<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="c-token" content="{!! csrf_token() !!}" />
    <title>{{ $pageTitle }}</title>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('auth_assets/landing/css/general_.css') }}" >
     <link rel="stylesheet" href="{{ asset('auth_assets/css/flexdatalist.css') }}">
    
     @stack('head-script')

    <!-- jQuery library -->
    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="{{ asset('auth_assets/landing/css/details_.css') }}" >
    <script src="{{ asset('auth_assets/js/flexdatalist.js') }}"></script>
    <script src="{{ asset('auth_assets/js/flexdatalistadhoc.js') }}"></script>

  
    <!-- Favicons -->
    <link rel="icon" type="image/png" href="{{URL::asset('auth_assets/images/icons/favicon.png')}}"/>
    <link rel="manifest" href="{{ asset('favicon/manifest.json') }}">

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
<nav class="navbar navbar-inverse wrapper-navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{route('jobs.jobOpenings')}}">
                <img src="{{ $global->logo_url }}" style="max-height:100%" />
            </a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
          <form id="top_search" method="POST" action="{{ route('jobs.searchJobCategories') }}">
            @csrf
          <div class="nav navbar-nav">
                        <div class="top_search">
                            <span class="search_input">
                  
                                 <input type="text" name="keyword"  />
                                 <span class="top_search_btn">
                                   <i class=" icon fa fa-search"></i>
                                <span>
                            </span>
                        </div>
                    </div>
            </form>
            <ul class="nav navbar-nav">

            </ul>
            <ul class="nav navbar-nav navbar-right custom_navbar">
                <li><a href="{{route('jobs.getJobCategories')}}"><span class="v_cent"></span>Find a Job</a></li>
                <li>
                <a href="#" class="dropdown-toggle bg-transparent" id="categoryDropdownMenuBtn"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           <span class="v_cent"></span>Category
                           
                    </a>
                   
                          <div class="dropdown-menu" style="min-width: 280px;" aria-labelledby="categoryDropdownMenuBtn">
                            @foreach ($job_categories as $item)
                              <a class="ft-w-500 dropdown-item text-dark"
                               href="{{route('jobs.getJobCategories', 'filter_job_types='.'' . $item['name'] )}}" > {{$item['name']}} </a>
                            @endforeach
                            </div>
                   
                </li>
                @if(Auth::guard('candidate')->check())
                <li>
                    <div class="dropdown">
                        <a href="javascript:;" style="padding-top: 10px;" class="btn btn-sm dropdown-toggle bg-transparent border-0 text-capitalize" id="dropdownMenuButton"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div id="initialsDiv" class="d-inline">
                                <img id="initialsCircle" class="rounded-circle w-30 h-30" src="{{!empty(Auth::guard('candidate')->user()->profile_image_url) ? Auth::guard('candidate')->user()->profile_image_url : null}}" >
                            </div>
                           
                        </a>
                      

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a style="margin-bottom: 10px;margin-top: 10px;" class="ft-w-bold dropdown-item text-dark" href="{{route('profile.candidateDashboard')}}">
                             {{Auth::guard('candidate')->user()->user->firstname.' '.Auth::guard('candidate')->user()->user->lastname}}
                              </a>
                            <hr class="drop-m">
                              <a class="ft-w-500 dropdown-item text-dark" href="{{route('profile.candidateProfile')}}">View Profile</a>
                            <a class="ft-w-500 dropdown-item text-dark" href="{{route('profile.candidateDashboard')}}">Dashboard</a>
                            <a class="ft-w-500 dropdown-item text-dark" href="#"> Help & Information</a>  
                               <hr class="drop-m">
                            <a  style="margin-bottom: 15px;margin-top: 10px;" class="ft-w-bold dropdown-item text-dark" href="{{route('candidate.logout')}}">Sign Out</a>
                        </div>
                    </div>
                </li>
                @else
                    <li><a href="{{route('candidate.candidatelogin')}}"><span class="v_cent"></span>Login</a></li>
                    <li><a href="{{route('candidate.candidatesignup')}}"><span class=""></span><button class="btn synup">Sign up</button></a></li>
                @endif
            </ul>
        </div>
    </div>
</nav>
<!-- END Header -->

<!-- Main container -->
<main class="main-content">
    @yield('content')
</main>
<!-- END Main container -->

<!-- Footer -->

  <div class="board_wrapper3" style="background-color: #3f36be;padding-top: 40px;">
            <div class="container footer">

                <div class="col-sm-3">
                 <a  href="{{route('jobs.jobOpenings')}}">
                  <div class="footerlogo">
                        <img src="{{ asset('auth_assets/landing/assets/logo.png')}}" style="max-width:100%;max-height:100%">
                    </div>
                    </a>



                </div>
                <div class="col-sm-3">
                    <ul>
                        <li>About Us</li>
                        <li>Contact</li>
                        <li>Help</li>
                        <li>Careers</li>
                    </ul>

                </div>
                <div class="col-sm-3">
                    <ul>
                        <li>Find a Job</li>
                        <li>Post a Job</li>
                        <li>Build a Profile</li>
                        <li>Outsourcing</li>
                    </ul>

                </div>
                <div class="col-sm-3">
                    <ul>
                        <li>Terms</li>
                        <li>Privacy Policy</li>
                        <li>Advertise with Us</li>

                    </ul>

                </div>
            </div>
        </div>
  <div class="board_wrapper3 last_foot" style="background-color: #f9f9f9; ">
            <div class="line">
                <div class="reserv">
                    &copy;{{ \Carbon\Carbon::today()->year }} @lang('app.by') AllDayHR All rights reserved.
                </div>
                <ul class="ul_term">
                    <li>About</li>
                    <li>Terms</li>
                    <li>Privacy Policy</li>
                </ul>
                <br class="clear" />
            </div>
    </div>
<script>
 let top_search;
 let main_search;
 top_search = document.querySelector(".top_search_btn");
 main_search = document.querySelector(".main_search_btn");
  
  if(top_search){
  top_search.onclick = function() { 
    $("#top_search").submit()
    }
  }

   if (main_search){
   main_search.onclick = function() { 
        $("#main_search").submit()
    }
   }

    $( ".nm-adhoc" ).change(function() {
      $("#main_search").submit()
    });

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('jobs.saveApplication')}}',
            container: '#createForm',
            type: "POST",
            file: true,
            redirect: true,
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
  var user = {!! json_encode($user) !!};
  var firstname = (user == null) ? 'firstname' :  user.firstname;
  var lastname =  (user == null) ? 'lastname' :  user.lastname;
  var initials = firstname.charAt(0)+""+lastname.charAt(0);
  ($('#initialsCircle').attr('src') == '') ?  replaceInitialsDiv() : null ;
  function replaceInitialsDiv(){
     let initialsDiv =  document.getElementById("initialsDiv")
      initialsDiv.innerHTML = initials
      initialsDiv.classList.add('initialsCircle');
  }
</script>
 @push('footer-script')
        </div>
    </body>
</html>
