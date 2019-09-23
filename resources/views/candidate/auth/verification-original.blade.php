@extends('layouts.candidateauthpg')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{URL::asset('auth_assets/vendor/animate/animate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('auth_assets/css/util.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('auth_assets/css/main.css')}}">
@endsection

@section('contents')
    <div class="one-pg-wrap blue-bg">
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
                               ">              
                                {{$pgTitle}}
                                </div>
                                <div class="thirdHeader textRegular" style="color: #ffffff;flex-basis: 20px;"> </div>
                                </div>
                             <div class="flexheader ">  </div>
                       </div>
                    </div>
        <div class="row container-contact100" style="margin-left: 1%;">
        <div class="col-md-4"></div>

            <div class="col-md-4 wrap-contact200 wc-verification-card">
					<span class="flexheader">
					</span>

                @if($invalid == 0)
                    <span class="col-sm-12 text-center click-instruction" style="font-weight: bold;font-size: 24px;line-height: 26px; margin-bottom: 5%;">
                     You're almost ready
                    </span>

                    <span class="col-sm-9  text-center click-instruction" style="margin-top: -5%; margin-left: 10%;">
                            {{$message}}
                    </span>

                    <span class="col-sm-9  text-center click-instruction" style="margin-top: -5%; margin-left: 10%;">
                            {{$instruction}}
                    </span>
                    
                    <span class="col-sm-12 text-center customer-email">
                            {{$email}}
                    </span>
                    <div class="col-sm-12 text-center contact100-pic js-tilt" data-tilt>
                        <img src="{{URL::asset('auth_assets/images/mail.png')}}" alt="Envelope">
                    </div>
                @else
                    <span class="click-instruction" style="text-align:center; ">
                        {{$message}} , click <a 
                        href="{{ route('candidate.candidatesignup')}}"
                        >here to register</a> or <a href="{{ route('candidate.candidatelogin')}}">here to login</a>
                    </span>
                @endif

            </div>

               <div class="col-md-4"></div>
            </div>
            
            <div class="row" style="margin-top: 5%;">
                <div class="col-md-12 text-center">
                 <div class="gray-info">
                   <span class="mouse-point" style="width: 50%; "> <a href="{{ route('candidate.candidatelogin') }}" style="color: rgba(255, 255, 255, 0.5);">
                    Log in to a different account
                    </a></span>
                 </div>
               </div>
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
