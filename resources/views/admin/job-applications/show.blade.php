<link rel="stylesheet" href="{{ asset('assets/plugins/jquery-bar-rating-master/dist/themes/fontawesome-stars.css') }}">
<style>

    .right-panel-box{
        overflow-x: scroll;
        max-height: 34rem;
    }
    .resume-button{
        text-align: center; margin-top: 1rem
    }


</style>
<div class="rpanel-title"> @lang('menu.jobApplications') <span><i class="ti-close right-side-toggle"></i></span> </div>
<div class="r-panel-body p-3">

    <div class="row font-12">
        <div class="col-4">
            @if(is_null($candidate->profile_image_url))
                <img src="{{ asset('avatar.png')  }}" class="img-circle img-fluid">
            @else
            <div>
                <img  src="{{ asset($candidate->profile_image_url) }}" class="img-fluid" width="150">
             </div>
            @endif
                {{--<div class="col-sm-6">--}}
                    <p class="text-muted resume-button" >
                        <a target="_blank" href="{{ asset($application->resume) }}" class="btn btn-sm w-100 btn-primary">@lang('app.view') @lang('modules.jobApplication.resume')</a>
                    </p>
                   <p class="text-muted resume-button" >
                        <a target="_blank" href="{{ route('admin.getCandidateProfile',['id'=>$application->candidate->candidate_id]) }}" class="btn btn-sm  w-100 btn-info">@lang('app.view') Full Profile</a>
                    </p>
                {{--</div>--}}
                @if($user->can('edit_job_applications'))
                    <div class="stars stars-example-fontawesome">
                        <select id="example-fontawesome" name="rating" autocomplete="off">
                            <option value=""></option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                @endif
        </div>

        <div class="col-8 right-panel-box">
            <div class="col-sm-12">
                <strong>@lang('app.name')</strong><br>
                <p class="text-muted">{{ ucwords($application->full_name) }}</p>
            </div>

            <div class="col-sm-12">
                <strong>@lang('modules.jobApplication.appliedFor')</strong><br>
                <p class="text-muted">{{ ucwords($application->job->title).' ('.ucwords($application->job->location->location).')' }}</p>
            </div>

            <div class="col-sm-12">
                <strong>@lang('app.email')</strong><br>
                <p class="text-muted">{{ $application->email }}</p>
            </div>
            <div class="row col-md-12">
                <div class="col-sm-12 col-md-6">
                    <strong>@lang('app.phone')</strong><br>
                    <p class="text-muted">{{ $application->phone }}</p>
                </div>

                <div class="col-sm-12 col-md-6">
                    <strong>@lang('modules.jobApplication.appliedAt')</strong><br>
                    <p class="text-muted">{{ $application->created_at->format('d M, Y H:i') }}</p>
                </div>

            </div>
            <div class="row col-md-12">
                <div class="col-sm-12 col-md-6">

                <strong>Role Applied</strong><br>
                <p class="text-muted">{{ $application->job_role }}</p>
            </div>
            <div class="col-sm-12 col-md-6">
                <strong>Relevant years of experience</strong><br>
                <p class="text-muted">{{ $application->relevant_years_experience }}</p>
            </div>
            </div>
            <div class="col-sm-12">
                <strong>Cover Letter</strong><br>
                <p class="text-muted" style="height: 200px;overflow-x: scroll;border: 1px solid silver;padding: 10px;">{!!  nl2br($application->cover_letter)  !!}</p>
            </div>
            @forelse($answers as $answer)
            <div class="col-sm-12">
                <strong>{{$answer->question->question}} ? </strong><br>
                <p class="text-muted">{{ ucfirst($answer->answer)}}</p>
            </div>
            @empty
            @endforelse
            @if(!is_null($application->schedule))
                <hr>

                <h5>@lang('modules.interviewSchedule.scheduleDetail')</h5>
                <div class="col-sm-12">
                    <strong>@lang('modules.interviewSchedule.scheduleDate')</strong><br>
                    <p class="text-muted">{{ $application->schedule->schedule_date->format('d M, Y H:i') }}</p>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <strong>@lang('modules.interviewSchedule.assignedEmployee')</strong><br>
                    </div>
                    <div class="col-sm-6">
                        <strong>@lang('modules.interviewSchedule.employeeResponse')</strong><br>
                    </div>
                    @forelse($application->schedule->employee as $key => $emp )
                        <div class="col-sm-6">
                            <p class="text-muted">{{ ucwords($emp->user->name) }}</p>
                        </div>

                        <div class="col-sm-6">
                            @if($emp->user_accept_status == 'accept')
                                <label class="badge badge-success">{{ ucwords($emp->user_accept_status) }}</label>
                            @elseif($emp->user_accept_status == 'refuse')
                                <label class="badge badge-danger">{{ ucwords($emp->user_accept_status) }}</label>
                            @else
                                <label class="badge badge-warning">{{ ucwords($emp->user_accept_status) }}</label>
                            @endif
                        </div>
                    @empty
                    @endforelse
                </div>

            @endif

            @if(isset($application->schedule->comments) == 'interview' && count($application->schedule->comments) > 0)
                <hr>

                <h5>@lang('modules.interviewSchedule.comments')</h5>
                @forelse($application->schedule->comments as $key => $comment )

                    <div class="col-sm-12">
                        <p class="text-muted"><b>{{$comment->user->name }}:</b> {{ $comment->comment }}</p>
                    </div>
                @empty
                @endforelse

            @endif
            <div class="col-sm-12">
                <p class="text-muted">
                    @if(!is_null($application->skype_id))
                        <span class="skype-button rounded" data-contact-id="live:{{$application->skype_id}}" data-text="Call"></span>
                    @endif
                </p>
            </div>
            <div class="row">
                @if($user->can('add_schedule') && $application->status->status == 'interview' && is_null($application->schedule))
                    <div class="col-sm-6">
                        <p class="text-muted">
                            <a onclick="createSchedule('{{$application->id}}')" href="javascript:;" class="btn btn-sm btn-info">@lang('modules.interviewSchedule.scheduleInterview')</a>
                        </p>
                    </div>
                @endif
            </div>
        </div>

    </div>

</div>
@if($user->can('edit_job_applications'))
<script src="{{ asset('assets/plugins/jquery-bar-rating-master/dist/jquery.barrating.min.js') }}" type="text/javascript"></script>
<script>
    $('#example-fontawesome').barrating({
        theme: 'fontawesome-stars',
        showSelectedRating: false,
        onSelect:function(value, text, event){
            if(event !== undefined && value !== ''){
                var url = "{{ route('admin.job-applications.rating-save',':id') }}";
                url = url.replace(':id', {{$application->id}});
                var token = '{{ csrf_token() }}';
                var id = {{$application->id}};
                $.easyAjax({
                    type: 'Post',
                    url: url,
                    container: '#example-fontawesome',
                    data: {'rating':value, '_token':token},
                    success: function (response) {
                        $('#example-fontawesome_'+id).barrating('set', value);
                    }
                });
            }

        }
    });
    @if($application->rating !== null)
        $('#example-fontawesome').barrating('set', {{$application->rating}});
    @endif
</script>
@endif
@if(!is_null($application->skype_id))
    <script src="https://swc.cdn.skype.com/sdk/v1/sdk.min.js"></script>
@endif
