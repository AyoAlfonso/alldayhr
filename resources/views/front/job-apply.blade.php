@extends('layouts.apply')

@section('header-text')
    <h1 class="hidden-sm-down">{{ ucwords($job->title) }}</h1>
    <h5 class="hidden-sm-down"><i class="icon-map-pin"></i> {{ ucwords($job->location->location) }}</h5>
@endsection

@section('content')

    <div class="row" style="padding-top: 1%;">

        <div id="vh-hr" class="box-hr">
            @if(!$candidate_user_info->HasAppliedJob($job->id))
                <div style="color: #999;font-size: 14px;"> Apply to</div>
            @else
                <div style="color: #999;font-size: 14px;"> Applied for</div>

            @endif
            <div style="font-family: Ubuntu-B; width: 100%;"><a class="job-title-apply" style="font-weight: 900;"
                                                                href="{{ route('jobs.jobDetail', $job->slug) }}">{{ ucwords($job->title) }}</a>
            </div>
            <div style="color: #999;font-size: 14px;width: 100%;"> {{ ucwords($company->company_name)}} </div>

        </div>

    </div>

    <div class="row" style="margin-right: 5%;margin-left: 5%;">
        <div id="vh-hr" class="box-hr-info px-0" style="margin-top: 2%;">
       <span class="px-3 cdd-name-apply">
           {{ucwords($candidate_user->fullname)}}
       </span>

            <div class="row px-3" style="margin-top: 3%">
                <div class="col-md-4  pb-2" style="border-right: 1px solid #dee2e6;">
                    <span style="font-size:12px;font-family: ubuntu-medium;color: {{empty($candidate_user_info->experience_level) ? '#e6e3e3' : '#9B9B9B'}}">EXPERIENCE</span>
                    <div class=""
                         style="color: #3B3B3B; font-family: ubuntu-bold;">
                        @if(!empty($candidate_user_info->experience_level))
                            {{ucwords($candidate_user_info->experience_level)}}
                            Year(s)
                        @endif
                    </div>
                </div>

                <div class="col-md-4  pb-2" style="border-right: 1px solid #dee2e6;">
                    <span style="font-size:12px;font-family: ubuntu-medium;color: {{empty($candidate_user_education->qualification) ? '#e6e3e3' : '#9B9B9B'}}">QUALIFICATION</span>

                    <div class=""
                         style="color: #3B3B3B; font-family: ubuntu-bold;"> {{!empty($candidate_user_education->qualification) ? ucwords($candidate_user_education->qualification) : '...'}}  </div>
                </div>

                <div class="col-md-4 pb-2">
                    <span style="font-size:12px;font-family: ubuntu-medium;color: {{empty($candidate_user_info->residence_state) ? '#e6e3e3' : '#9B9B9B'}}">LOCATION</span>


                    <div class=""
                         style="color: #3B3B3B; font-family: ubuntu-bold; "> {{!empty($candidate_user_info->residence_state) ? ucwords($candidate_user_info->residence_state) : '...'}} </div>
                </div>

            </div>
            <div class="cand-path-1">

            </div>
            @if($job_application_validation)
                <div style="color: #888;font-family: ubuntu-medium;font-size: 14px;"
                     class="p-40">

                    <p class="text-center" style="color: #35308E;">Please note that the following information or
                        documents must<br> be provided before you can submit
                        your application.</p>
                    <div class="row" style="background: #dbe6f1;    border: 1px solid #2B248F;">
                        <a href="{{route('profile.candidateProfile', "slug=$job->slug" )}}" class="row pl-20 pr-15">
                            @foreach($job_application_validation as $req)
                                <div class="col-md-9 col-sm-9 p-10 text-left"
                                     style="border-right: 1px solid #2B248F;">{{$req->label}}</div>
                                <div class="col-md-3 col-sm-3 border-bottom-1  p-10 text-left">
                                    @if(!$req->value)
                                        <span class="badge badge-default" style="color:#3F36BE;">pending</span>
                                    @else
                                        <span class="badge badge-success">submitted</span>
                                    @endif
                                </div>
                            @endforeach
                        </a>
                    </div>
                    <div class="row">
                        <div class="d-flex col-12 justify-content-center">
                            <a href="{{route('profile.candidateProfile', "slug=$job->slug" )}}"
                               class="candidate-save-button-1 m-2 cursor-pointer"
                               style="-webkit-box-sizing: border-box;box-sizing: border-box;padding: 10px;margin-left: 10px; color: #FFF; font-family: Ubuntu-L;font-size: 14px;font-weight: 500;line-height: 16px;text-align: center;border-radius: 4px;background-color: #3038BC;border: 1.5px solid #FFF;">
                                Begin Application
                            </a>
                        </div>

                    </div>
                </div>

            @else
                @if(!$candidate_user_info->HasAppliedJob($job->id))
                    <div style="color: #888;font-family: Ubuntu-l;font-size: 14px;font-weight: 500;line-height: 21px;display: inline-block;margin-left: 25%;margin-top: 3%;"
                         class="text-center">
                        <a href="{{route('profile.candidateProfile', "slug=$job->slug" )}}">
                            <span style="display: inline-block;">  <i class="fa fa-pencil"></i>    Edit your profile before applying   </span>
                        </a>
                    </div>
                @else

                    <div class="text-center" style="padding:20px;">
                        <h5 style="color: #35308e;">Thank you for applying!</h5>
                        <p style="font-size: 12px;"><b>Your application will be reviewed and <br> a response would be
                                communicated to you.</b></p>
                        <img src="{{asset('assets/images/application-review-icon.svg')}}"/>
                    </div>
                @endif
            @endif
        </div>

    </div>

    @if(!$candidate_user_info->HasAppliedJob($job->id) && !$job_application_validation)
        <form id="createForm" method="POST">
            <div class="row" style="padding-bottom: 25px; margin-top: 20px;margin-right: 5%;
margin-left: 5%;">
                <div id="vh-hr" class="box-hr-info">
                <span class="cdd-name-apply" style="font-size: 12px;">
             YOUR APPLICATION
           </span>


                    @if(is_array($job->job_roles) && count($job->job_roles) > 0)
                        <div class="row">
                            <div class="col-12 col-xs-12 pb-10">
                                <span>Job Role</span>
                            </div>
                            <div class="col-12 col-xs-12 pb-10">
                                <select class="form-control" name="job_role" id="job_role">
                                    <option value="" disabled selected>Choose job role</option>
                                    @foreach($job->job_roles as $job_role)
                                        <option>{{$job_role}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-12 col-xs-12 pb-10">
                            <span>Relevant Years of Experience</span>
                        </div>
                        <div class="col-12 col-xs-12 pb-10">
                            <input type="number" class="form-control" name="relevant_years_experience">

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-xs-12 pb-10">
                            <span>Cover Letter</span>
                        </div>
                        <div class="col-12 col-xs-12 pb-10">
                            <div>
                                <textarea id="cover_letter" class="form-control" name="cover_letter"
                                          rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                    @csrf
                    <input type="hidden" name="job_id" value="{{ $job->id }}">


                </div>
            </div>
        </form>
    @endif
    @if(!$job_application_validation)
        <div class="" style="padding-top: 5%;">
            <div class="candidate-footer"
                 style="position: fixed;right: 0;bottom: 0;left: 0;padding: 15px;text-align: right;background: #3038BC;color: white;margin: auto;">

                @if(!$candidate_user_info->HasAppliedJob($job->id))

                    <button class="candidate-save-button-1 cursor-pointer" onclick="saveform()"
                            style="-webkit-box-sizing: border-box;box-sizing: border-box;padding: 10px;margin-left: 10px; color: #FFF; font-family: Ubuntu-L;font-size: 14px;font-weight: 500;line-height: 16px;text-align: center;border-radius: 4px;background-color: #3038BC;border: 1.5px solid #FFF;">
                        Submit Application
                    </button>
                @else
                    <a href="{{url('/')}}" class="candidate-save-button-1 m-2 cursor-pointer"
                       style="-webkit-box-sizing: border-box;box-sizing: border-box;padding: 10px;margin-left: 10px; color: #FFF; font-family: Ubuntu-L;font-size: 14px;font-weight: 500;line-height: 16px;text-align: center;border-radius: 4px;background-color: #3038BC;border: 1.5px solid #FFF;">
                        Find Jobs
                    </a>

                @endif

            </div>
        </div>
    @endif

@endsection

@push('footer-script')
    <script>


        function saveform() {
                    @if(is_array($job->job_roles) && count($job->job_roles) > 0)
            let job_role = document.getElementById("job_role").value;
            if (!job_role) {
                $.toast({
                    heading: 'Error',
                    text: 'Please choose a job role.',
                    position: 'top-right',
                    showHideTransition: 'slide',
                    stack: false
                })
                return;
            }

                    @endif

            let cover_letter = document.getElementById("cover_letter").value;

            if (!cover_letter) {
                $.toast({
                    heading: 'Error',
                    text: 'Please put in your cover letter.',
                    position: 'top-right',
                    showHideTransition: 'slide',
                    stack: false
                })
                return;
            }

            $.easyAjax({
                url: '{{route('jobs.saveApplication')}}',
                container: '#createForm',
                type: "POST",
                file: true,
                redirect: true,
                // data: $('#createForm').serialize(),
                success: function (response) {

                    if (response.status = "201") {
                        $.toast({
                            heading: 'Hello',
                            text: response.msg + '🙂',
                            position: 'top-right',
                            showHideTransition: 'slide',
                            stack: false
                        })
                        window.location = '{{route('jobs.jobApply', $job->slug)}}';
                        return;
                    }
                },
                error: function (response) {
                    handleFails(response);

                }
            })
        };

        function handleFails(response) {
            if (typeof response.responseJSON.errors != "undefined") {
                var keys = Object.keys(response.responseJSON.errors);
                $('#createForm').find(".has-error").find(".help-block").remove();
                $('#createForm').find(".has-error").removeClass("has-error");

                for (var i = 0; i < keys.length; i++) {
                    // Escape dot that comes with error in array fields
                    var key = keys[i].replace(".", '\\.');
                    var formarray = keys[i];

                    // If the response has form array
                    if (formarray.indexOf('.') > 0) {
                        var array = formarray.split('.');
                        response.responseJSON.errors[keys[i]] = response.responseJSON.errors[keys[i]];
                        key = array[0] + '[' + array[1] + ']';
                    }

                    var ele = $('#createForm').find("[name='" + key + "']");

                    var grp = ele.closest(".form-group");
                    $(grp).find(".help-block").remove();

                    //check if wysihtml5 editor exist
                    var wys = $(grp).find(".wysihtml5-toolbar").length;

                    if (wys > 0) {
                        var helpBlockContainer = $(grp);
                    } else {
                        var helpBlockContainer = $(grp).find("div:first");
                    }
                    if ($(ele).is(':radio')) {
                        helpBlockContainer = $(grp).find("div:eq(2)");
                    }

                    if (helpBlockContainer.length == 0) {
                        helpBlockContainer = $(grp);
                    }

                    helpBlockContainer.append('<div class="help-block">' + response.responseJSON.errors[keys[i]] + '</div>');
                    $(grp).addClass("has-error");
                }

                if (keys.length > 0) {
                    var element = $("[name='" + keys[0] + "']");
                    if (element.length > 0) {
                        $("html, body").animate({scrollTop: element.offset().top - 150}, 200);
                    }
                }
            }
        }
    </script>
@endpush
