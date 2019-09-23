<link rel="stylesheet" href="{{ asset('assets/node_modules/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}">
<link rel="stylesheet" href="{{ asset('assets/node_modules/html5-editor/bootstrap-wysihtml5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/node_modules/multiselect/css/multi-select.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/iCheck/all.css') }}">
<div class="modal-header">
<h4 class="modal-title"><i class="icon-plus"></i> @lang('modules.interviewSchedule.interviewSchedule')</h4>
<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
</div>
<div class="modal-body">
    <form id="createSchedule" class="ajax-form" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-body">
            <div class="row">
                <div class="col-md-6  col-xs-12">
                    <div class="form-group">
                        <label class="d-block">@lang('modules.interviewSchedule.candidate')</label>
                        <select class="select2 m-b-10 form-control "
                                data-placeholder="@lang('modules.interviewSchedule.chooseCandidate')" name="candidate">
                            @foreach($candidates as $candidate)
                                <option value="{{ $candidate->id }}">{{ ucwords($candidate->full_name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                        <label class="d-block">@lang('modules.interviewSchedule.employee')</label>
                        <select class="select2 m-b-10 form-control select2-multiple " multiple="multiple"
                                data-placeholder="@lang('modules.interviewSchedule.chooseEmployee')" name="employee[]">
                            @foreach($users as $emp)
                                <option value="{{ $emp->id }}">{{ ucwords($emp->name) }} @if($emp->id == $user->id)
                                        (@lang('app.you')) @endif</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-6 col-md-4 ">
                    <div class="form-group">
                        <label>@lang('modules.interviewSchedule.scheduleDate')</label>
                        <input type="text" name="scheduleDate" id="scheduleDate" value="{{$scheduleDate}}" class="form-control">
                    </div>
                </div>

                <div class="col-xs-5 col-md-4">
                    <div class="form-group chooseCandidate bootstrap-timepicker timepicker">
                        <label>@lang('modules.interviewSchedule.scheduleTime')</label>
                        <input type="text" name="scheduleTime" id="scheduleTime" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-12 ">
                    <div class="form-group">
                        <label>@lang('modules.interviewSchedule.comment')</label>
                        <textarea type="text" name="comment" id="comment" placeholder="@lang('modules.interviewSchedule.comment')" class="form-control"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>
<div class="modal-footer">
    <button type="button" class="btn dark btn-outline" data-dismiss="modal">@lang('app.close')</button>
    <button type="button" class="btn btn-success save-schedule">@lang('app.submit')</button>
</div>

<script src="{{ asset('assets/node_modules/moment/moment.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/node_modules/multiselect/js/jquery.multi-select.js') }}"></script>
<script src="{{ asset('assets/node_modules/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}" type="text/javascript"></script>

<script>
    // Select 2 init.
    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });
    // Datepicker set
    $('#scheduleDate').bootstrapMaterialDatePicker
    ({
        time: false,
        clearButton: true,
    });

    // Timepicker Set
    $('#scheduleTime').bootstrapMaterialDatePicker
    ({
        date: false,
        shortTime: true,   // look it
        format: 'HH:mm',
        switchOnClick: true
    });

    // Save Interview Schedule
    $('.save-schedule').click(function () {
        $.easyAjax({
            url: '{{route('admin.interview-schedule.store')}}',
            container: '#createSchedule',
            type: "POST",
            data: $('#createSchedule').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
    })
</script>
