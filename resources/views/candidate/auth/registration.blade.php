@extends('layouts.candidateauthpg')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{URL::asset('auth_assets/vendor/animate/animate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('auth_assets/css/util.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::asset('auth_assets/css/main.css')}}">
@endsection

@section('contents')
            <div class="one-pg-wrap lt-blue-bg">
        <div class="container-fluid">
            <div class="container" >
             <div class="row">
                        <div class=" col-md-12 text-center" style="padding-top: 2%;">

                                <div class="flexheader ">
                              </div>
                        
                              <div class="flexheader">
                                    <div class="logo-postion" style="padding-bottom: 1%; width: 40%;">
                                            <span >
                                           <a href="{{route('candidate.candidatelogin')}}">
                                           <img src="{{ asset('/auth_assets/images/allday-hr-logo-light.png')}}"
                                           style="width: 100px;"
                                           alt="allDayHr">
                                       </a>
                                    </span>
                                </div>
                                    <div style="
                                    font-size:24px;
                                    font-weight:500;
                                    font-family:Ubuntu-M;
                                    color: #ffffff;
                                   ">
                                    Create Your Account
                                    </div>
                                    <div class="thirdHeader textRegular" style="color: #ffffff; flex-basis: 5px;"> </div>
                                    </div>
                                 <div class="flexheader ">  </div>
                           </div>
                        </div>
      
        <div>

        <div class="row">
            
        

        <div class="col-md-4">

        </div>

        <div class="col-md-4 wrap-contact100">
					<span class="flexheader">
					</span>

                    <form class="form-gray-fields"
                    action="{{ route('candidate.candidatesignup')}}"
                    method="POST">
                        {{ csrf_field() }}
                        @if ($errors->any())
                            <div class="col-md-12">
                                <div class="col-md-12">
                                    <div class="alert alert-danger alert-dismissible alert-adhoc">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <ul style="display:inline; font-size:11px; ">
                                            @foreach($errors->all() as $error)
                                                <li>{{$error}}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="col-md-12">
                            <div class="form-group"   style="color: rgba(46, 56, 77, 0.5)">
                                <label class="no-trans text-uppercase" for="">First Name</label>
                                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                <input type="text" class="form-control auth-input-field" name="firstname" id="candidateFirstName" placeholder="Enter first name" required="true">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group" style="color: rgba(46, 56, 77, 0.5)">
                                <label class="no-trans text-uppercase" for="">Last Name</label>
                                <input type="text" class="form-control auth-input-field" name="lastname" id="candidateLastName" placeholder="Enter last name">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group" style="color: rgba(46, 56, 77, 0.5)">
                                <label class="no-trans text-uppercase" for="">Email</label>
                                <input type="text" class="form-control auth-input-field" name="email" id="email" placeholder="Enter email address">
                            </div>
                        </div>

                        <div class="col-md-12">
                                <div class="form-group" style="color: rgba(46, 56, 77, 0.5)">
                                    <label class="no-trans text-uppercase" for="">Password</label>
                                    <input type="password" class="form-control auth-input-field" name="password" id="candidatePassword" placeholder="Enter password">
                                </div>
                         </div>

                  

                        <div class="col-md-12">
                            <div class="form-group text-center"  style="color: rgba(46, 56, 77, 0.5)">
                                <button class="btn pp-btn-hover btn-primary btn-block" style="padding: 3%;
                                background-color: #3d34cb;" type="submit">
                                Create your account</button>
                            </div>
                        </div>
                    </form>
            </div>
          
           <div class="col-md-4">

           </div>
        </div>   

            <div class="col-md-12 text-center" style="margin-top: 2%;margin-bottom: 15%;">
                <div class="gray-info">
                 <span class="mouse-point" style="width: 50%;"> <a 
                     href="{{ route('candidate.candidatelogin')}}"
                     style="color: #fff;"> Already have an account? Log in </a></span>
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
