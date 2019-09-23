@extends('layouts.candidateauthpg')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{URL::asset('auth_assets/vendor/animate/animate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('auth_assets/css/util.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('auth_assets/css/main.css')}}">
@endsection

@section('contents')
    <div class="one-pg-wrap lt-blue-bg">
   <div class="container-fluid">
    <div class="" style="">
            <div class="row">
                    <div class=" col-md-12 text-center" style="padding-top: 2%;">

                            <div class="flexheader ">
                          </div>
                    
                    <div class="flexheader">
                       <div class="logo-postion" style="padding-bottom: 1%; width: 40%;">
                                     <span >
                                    <a href="{{route('candidate.candidatelogin')}}">
                                    <img src="{{ asset('/auth_assets/images/allday-hr-logo-light.png')}}"
                                    style="width: 20%;"
                                    alt="AlldayHR">
                                </a>
                                     </span>
                                </div>
                    <div style="
                                font-size:25px;
                                font-weight:500;
                                font-family:Ubuntu-M;
                                color: #ffffff;
                                flex-basis: 20px;
                                margin-top: 5%;
                               ">
                               Password changed successfully !
                    </div>
                     <div class="thirdHeader textRegular" style="color: #ffffff;flex-basis: 20px;"> </div>
                  </div>
                 <div class="flexheader ">  </div>
                </div>
                <div style="padding-top: 2%;margin-left: 45%;">
                        <a href="{{ route('candidate.candidatelogin')}}"
                        > 
                        <div class="flexheader">
                             
                                <button type="submit" style="border: solid 0.5px #004ecf;
                                height: 50px;" class="save-button text-center">
                                    <span>
                                     Login Now
                                    </span>
                                </button>
                        </div>
                    </a>
                       </div>
             </div>
        <div class="row container-contact100" style="margin-left: 1%;">
        <div class="col-md-4">  </div>
         <div class="col-md-4"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script >
        $('.js-tilt').tilt({
            scale: 1.1
        })
    </script>
    <!--===============================================================================================-->
    <script src="{{URL::asset('auth_assets/js/main.js')}}"></script>
@endsection
