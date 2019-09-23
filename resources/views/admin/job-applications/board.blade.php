@extends('layouts.app')

@permission('add_job_applications')
@section('create-button')
    <a href="{{ route('admin.job-applications.create') }}" class="btn btn-dark btn-sm m-l-15"><i
                class="fa fa-plus-circle"></i> @lang('app.createNew')</a>
@endsection
@endpermission

@push('head-script')
    <link rel="stylesheet" href="{{ asset('assets/lobipanel/dist/css/lobipanel.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <link rel="stylesheet" href="{{ asset('assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/node_modules/multiselect/css/multi-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/jquery-bar-rating-master/dist/themes/fontawesome-stars.css') }}">
    
    <style>
        .board-column{
            /* max-width: 21%; */
        }

        .board-column .card{
            box-shadow: none;
        }
        .notify-button{
            /*width: 9em;*/
            height: 1.5em;
            font-size: 0.730rem !important;
            line-height: 0.5 !important;
        }
        .panel-scroll{
            height: calc(100vh - 330px); overflow-y: scroll
        }
        .mb-20{
            margin-bottom: 20px
        }
        .datepicker{
            z-index: 9999 !important;
        }
        .select2-container--default .select2-selection--single, .select2-selection .select2-selection--single {
            width: 100%;
        }
        .select2-search {
            width: 100%;
        }
        .select2.select2-container {
            width: 100% !important;
        }
        .select2-search__field {
            width: 100% !important;
            display: block;
            padding: .375rem .75rem !important;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        .d-block{
            display: block;
        }
        .upcomingdata {
            height: 37.5rem;
            overflow-x: scroll;
        }
        .notify-button{
            height: 1.5em;
            font-size: 0.730rem !important;
            line-height: 0.5 !important;
        }
        .scheduleul
        {
            padding: 0 15px 0 11px;
        }

    </style>
@endpush

@section('content')

    <div class="row">
        <div class="col-sm-6">
            <a href="{{ route('admin.job-applications.table') }}" class="btn btn-sm btn-primary">@lang('app.tableView') <i class="fa fa-table"></i></a>
        </div>

    </div>

    <div class="container-scroll">
        <div class="row container-row">
            @foreach($boardColumns as $key=>$column)
                <div class="board-column p-0" data-column-id="{{ $column->id }}">
                    <div class="card" style="margin-bottom:0 !important;">
                        <div class="card-body">
                            <h4 class="card-title pt-1 pb-1">{{ ucwords($column->status) }} <span class="badge badge-pill badge-primary text-white ml-auto"> {{ count($column->applications) }}</span></h4>
                            <div class="card-text">
                                <div class="panel-body ">
                                    <div class="row">
                                        <div class="custom-column panel-scroll">
                                            @foreach($column->applications as $application)
                                                <div class="panel panel-default lobipanel show-detail "
                                                     data-widget="control-sidebar" data-slide="true"
                                                     data-row-id="{{ $application->id }}"
                                                     data-application-id="{{ $application->id }}" data-sortable="true" >
                                                    <div class="panel-body ">
                                                        <h5>
                                                            {!!  ($application->photo) ? '<img src="'.asset('user-uploads/candidate-photos/'.$application->photo).'"
                                                                        alt="user" class="img-circle" width="25">' : '<img src="'.asset('avatar.png').'"
                                                                        alt="user" class="img-circle" width="25">' !!}
                                                            {{ ucwords($application->full_name) }}</h5>
                                                        <div class="stars stars-example-fontawesome">
                                                            <select id="example-fontawesome_{{$application->id}}" data-value="{{ $application->rating }}"  data-id="{{ $application->id }}" class="example-fontawesome bar-rating" name="rating" autocomplete="off">
                                                                <option value=""></option>
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                                <option value="5">5</option>
                                                            </select>
                                                        </div>
                                                        <h6 class="text-muted">{{ ucwords($application->job->title) }}</h6>
                                                        <div class="pt-2 pb-2 mt-3">
                                                            <span class="text-dark font-14">
                                                                @if(!is_null($application->schedule)  && $column->id == 3)
                                                                    {{ $application->schedule->schedule_date->format('d M, Y') }}
                                                                @else
                                                                    {{ $application->created_at->format('d M, Y') }}
                                                                @endif
                                                            </span>
                                                                @permission('add_schedule')
                                                                <span id="buttonBox{{ $column->id }}{{$application->id}}" data-timestamp="@if(!is_null($application->schedule)){{$application->schedule->schedule_date->timestamp}}@endif">

                                                                    @if(!is_null($application->schedule) && $column->id == 3 && $currentDate < $application->schedule->schedule_date->timestamp)
                                                                        <button onclick="sendReminder({{$application->id}}, 'reminder')" type="button" class="btn btn-sm btn-info notify-button">@lang('app.reminder')</button>@endif
                                                                    @if($column->id == 4)
                                                                        <button onclick="sendReminder({{$application->id}}, 'notify')" type="button" class="btn btn-sm btn-info notify-button">@lang('app.notify')</button>
                                                                    @endif
                                                                </span>
                                                            @endpermission
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div class="panel panel-default lobipanel" data-sortable="true"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    {{--Ajax Modal Start for--}}
    <div class="modal fade bs-modal-md in" id="scheduleDetailModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn blue">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}
@endsection

@push('footer-script')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="{{ asset('assets/lobipanel/dist/js/lobipanel.min.js') }}"></script>
    <script src="{{ asset('assets/node_modules/moment/moment.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/node_modules/multiselect/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('assets/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/node_modules/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/node_modules/bootstrap-select/bootstrap-select.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/jquery-bar-rating-master/dist/jquery.barrating.min.js') }}" type="text/javascript"></script>

    <script>
        $('.example-fontawesome').barrating({
            theme: 'fontawesome-stars',
            showSelectedRating: false,
            readonly:true,

        });

        $(function() {
            $('.bar-rating').each(function(){
                const val = $(this).data('value');

                $(this).barrating('set', val ? val : '');
            });
        });

        {{--@if($application->rating !== null)--}}
            $('.example-fontawesome').barrating('set', '');
        {{--@endif--}}
        // Schedule create modal view
        function createSchedule (id) {
            var url = "{{ route('admin.job-applications.create-schedule',':id') }}";
            url = url.replace(':id', id);
            $('#modelHeading').html('Schedule');
            $.ajaxModal('#scheduleDetailModal', url);
        }

        // Send Reminder and notification to Candidate
        function sendReminder(id, type){
            var msg;

            if(type == 'notify'){
                msg = "@lang('errors.sendHiredNotification')";
            }
            else{
                msg = "@lang('errors.sendInterviewReminder')";
            }
            swal({
                title: "@lang('errors.areYouSure')",
                text: msg,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('app.yes')",
                cancelButtonText: "@lang('app.cancel')",
               /* closeOnConfirm: true,
                closeOnCancel: true
                */
            }).then((result) => {
                if (result.value) {
                    var url = "{{ route('admin.interview-schedule.notify',[':id',':type']) }}";
                    url = url.replace(':id', id);
                    url = url.replace(':type', type);

                    // update values for all tasks
                    $.easyAjax({
                        url: url,
                        type: 'GET',
                        success: function (response) {
                        }
                    });
                }
            });
        }

        $(function () {
            // Getting Data of all colomn and applications
            boardStracture =  JSON.parse('{!! $boardStracture !!}');

            var oldParentId, oldElementIds = [], i = 1;
            $('.lobipanel').on('dragged.lobiPanel', function (e, a) {
                var $parent = $(this).parent(),
                    $children = $parent.children();
                var pr = $(this).closest('.board-column'),
                    c = pr.find('.custom-column');

                if (i++ % 2) {
                    oldParentId = pr.data('column-id');
                    $children.each(function (ind, el) {
                        oldElementIds.push($(el).data('application-id'));
                    });
                    return true;
                }
                var currentParentId = pr.data('column-id');
                var currentElementIds = [];
                $children.each(function (ind, el) {
                    currentElementIds.push($(el).data('application-id'));
                });

                var oldOriginalIds = boardStracture[oldParentId];

                var range = oldOriginalIds.length;
                var missingElementId;
                for (var j = 0; j < range; j++) {
                    if (oldOriginalIds[j] !== oldElementIds[j]) {
                        missingElementId = oldOriginalIds[j];
                        break;
                    }
                }

                boardStracture[oldParentId] = oldElementIds.slice(0, -1);
                boardStracture[currentParentId] = currentElementIds.slice(0, -1);
                var boardColumnIds = [];
                var applicationIds = [];
                var prioritys = [];

                $children.each(function (ind, el) {
                    boardColumnIds.push($(el).closest('.board-column').data('column-id'));
                    applicationIds.push($(el).data('application-id'));
                    prioritys.push($(el).index());
                });

                if(oldParentId == 3 && currentParentId == 4){
                    $('#buttonBox' + oldParentId + missingElementId).show();
                    var button  = '<button onclick="sendReminder('+ missingElementId +', \'notify\')" type="button" class="btn btn-sm btn-info notify-button">@lang('app.notify')</button>';
                    $('#buttonBox' + oldParentId + missingElementId).html(button);
                    $('#buttonBox' + oldParentId + missingElementId).attr('id', 'buttonBox' + currentParentId + missingElementId);

                }else if(oldParentId == 4  && currentParentId == 3){
                    var timeStamp = $('#buttonBox' + oldParentId + missingElementId).data('timestamp');
                    var currentDate = {{$currentDate}};
                    if(currentDate < timeStamp){
                    $('#buttonBox' + oldParentId + missingElementId).show();
                    var button  = '<button onclick="sendReminder('+ missingElementId +', \'reminder\')" type="button" class="btn btn-sm btn-info notify-button">@lang('app.reminder')</button>';
                    $('#buttonBox' + oldParentId + missingElementId).html(button);
                        $('#buttonBox' + oldParentId + missingElementId).attr('id', 'buttonBox' + currentParentId + missingElementId);
                    }
                }else{
                    $('#buttonBox' + oldParentId + missingElementId).attr('id', 'buttonBox' + currentParentId + missingElementId);
                    $('#buttonBox' + currentParentId + missingElementId).hide();
                }

                // update values for all tasks
                $.easyAjax({
                    url: '{{ route("admin.job-applications.updateIndex") }}',
                    type: 'POST',
                    data: {
                        boardColumnIds: boardColumnIds,
                        applicationIds: applicationIds,
                        prioritys: prioritys,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function (response) {
                    }
                });
                oldParentId = null; oldElementIds = []; currentParentId = null; currentElementIds = [];


            }).lobiPanel({
                sortable: true,
                reload: false,
                editTitle: false,
                close: false,
                minimize: false,
                unpin: false,
                expand: false

            });

        });
    </script>
    <script>
        $('body').on('click', '.show-detail', function (event) {
            if($(event.target).hasClass('notify-button')){
               return false;
            }
            $(".right-sidebar").slideDown(50).addClass("shw-rside");

            var id = $(this).data('row-id');
            var url = "{{ route('admin.job-applications.show',':id') }}";
            url = url.replace(':id', id);

            $.easyAjax({
                type: 'GET',
                url: url,
                success: function (response) {
                    if (response.status == "success") {
                        $('#right-sidebar-content').html(response.view);
                    }
                }
            });
        })
        // job-applications.create-schedule
    </script>
@endpush