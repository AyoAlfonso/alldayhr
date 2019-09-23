@extends('layouts.app')

@push('head-script')
    <link rel="stylesheet" href="{{ asset('assets/plugins/calendar/dist/fullcalendar.css') }}">
    <link rel="stylesheet" href="//cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet"
          href="{{ asset('assets/node_modules/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}">

    <style>
        .mb-20 {
            margin-bottom: 20px
        }

        .datepicker {
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
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .d-block {
            display: block;
        }

        .upcomingdata {
            height: 37.5rem;
            overflow-x: scroll;
        }

        .notify-button {
            /*height: 1.5em;*/
            font-size: 0.730rem !important;
            line-height: 0.5 !important;
        }

        .scheduleul {
            padding: 0 15px 0 11px;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-md-12 mb-2">
                @permission('view_schedule')
                <a href="{{ route('admin.interview-schedule.table-view') }}"
                   class="btn btn-sm btn-primary">@lang('app.tableView') <i class="fa fa-table"></i></a>
                @endpermission
                @permission('add_schedule')
                <a href="#" data-toggle="modal" onclick="createSchedule()"
                   class="btn btn-sm btn-success waves-effect waves-light">
                    <i class="ti-plus"></i> @lang('modules.interviewSchedule.addInterviewSchedule')
                </a>
                @endpermission
        </div>
    </div>
    <div class="row">

        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div id="calendar"></div>
                </div><!-- /.card-body -->
            </div>

        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex p-0 ui-sortable-handle">
                    <h3 class="card-title p-3">
                        <i class="fa fa-file"></i> @lang('modules.interviewSchedule.interviewSchedule')
                    </h3>
                </div><!-- /.card-header -->
                <div class="card-body">
                        @forelse($upComingSchedules as $key => $upComingSchedule)
                            <div>
                                @php
                                    $date = \Carbon\Carbon::createFromFormat('Y-m-d', $key);
                                @endphp
                                <h4>{{ $date->format('M d, Y') }}</h4>


                                <ul class="scheduleul">
                                    @forelse($upComingSchedule as $key => $dtData)

                                        <li class="deco" id="schedule-{{$dtData->id}}" onclick="getScheduleDetail(event, {{$dtData->id}}) "
                                            style="list-style: none;">
                                            <h5 class="text-muted"
                                                style="float: left">{{ ucfirst($dtData->title) }} </h5>
                                            <div class="pull-right">
                                                @if($user->can('edit_schedule'))
                                                    <span style="margin-right: 15px;">
                                                        <button onclick="editUpcomingSchedule(event, '{{ $dtData->id }}')"
                                                                class="btn btn-sm btn-info notify-button editSchedule"
                                                                title="Edit"> <i class="fa fa-pencil"></i></button>
                                                    </span>
                                                @endif
                                                @if($user->can('delete_schedule'))
                                                    <span style="margin-right: 15px;">
                                                        <button data-schedule-id="{{ $dtData->id }}"
                                                                class="btn btn-sm btn-danger notify-button deleteSchedule"
                                                                title="Delete"> <i class="fa fa-trash"></i></button>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="clearfix"></div>
                                            <div class="direct-chat-name"
                                                 style="font-size: 13px">{{ ucfirst($dtData->full_name) }}</div>
                                            <span class="direct-chat-timestamp"
                                                  style="font-size: 13px">{{ $dtData->schedule_date->format('h:i a') }}</span>

                                            @if(in_array($user->id, $dtData->employee->pluck('user_id')->toArray()))
                                                @php
                                                    $empData = $dtData->employeeData($user->id);
                                                @endphp

                                                @if($empData->user_accept_status == 'accept')
                                                    <label class="badge badge-success float-right">@lang('app.accepted')</label>
                                                @elseif($empData->user_accept_status == 'refuse')
                                                    <label class="badge badge-danger float-right">@lang('app.refused')</label>
                                                @else
                                                    <span class="float-right">
                                                        <button onclick="employeeResponse({{$empData->id}}, 'accept')"
                                                                class="btn btn-sm btn-success notify-button responseButton">@lang('app.accept')</button>
                                                        <button onclick="employeeResponse({{$empData->id}}, 'refuse')"
                                                                class="btn btn-sm btn-danger notify-button responseButton">@lang('app.refuse')</button>
                                                    </span>
                                                @endif
                                            @endif
                                        </li>
                                        @if($key != (count($upComingSchedule)-1))
                                            <hr>@endif
                                    @empty

                                    @endforelse
                                </ul>

                            </div>
                            <hr>
                        @empty
                            <div>
                                <p>@lang('messages.noUpcomingScheduleFund')</p>
                            </div>
                        @endforelse
                </div><!-- /.card-body -->
            </div>
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

    {{--Ajax Modal Start for--}}
    <div class="modal fade bs-modal-md in" id="scheduleEditModal" role="dialog" aria-labelledby="myModalLabel"
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
    <script src="{{ asset('assets/node_modules/select2/dist/js/select2.full.min.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/node_modules/bootstrap-select/bootstrap-select.min.js') }}"
            type="text/javascript"></script>
    <script src="//cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
    <script src="{{ asset('assets/node_modules/moment/moment.js') }}" type="text/javascript"></script>

    <script src="{{ asset('assets/plugins/calendar/dist/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/calendar/dist/jquery.fullcalendar.js') }}"></script>
    <script src="{{ asset('assets/plugins/calendar/dist/locale-all.js') }}"></script>

    <script>
        userCanAdd = false;
        userCanEdit = false;
        @if($user->can('add_schedule'))
            userCanAdd = true;
        @endif
        @if($user->can('edit_schedule'))
            userCanEdit = true;
        @endif
        taskEvents = [
                @foreach($schedules as $schedule)
            {
                id: '{{ ucfirst($schedule->id) }}',
                title: '{{ $schedule->title }} on {{ $schedule->full_name }}',
                start: '{{ $schedule->schedule_date }}',
                end: '{{ $schedule->schedule_date }}',
            },
            @endforeach
        ];
    </script>
    <script src="{{ asset('js/schedule-calendar.js') }}"></script>

    <script>
        // Schedule create modal view

        @if($user->can('edit_schedule'))
        // Schedule create modal view
        function editUpcomingSchedule(event, id) {
            if (!$(event.target).closest('.editSchedule').length) {
                return false;
            }
            var url = "{{ route('admin.interview-schedule.edit',':id') }}";
            url = url.replace(':id', id);
            $('#modelHeading').html('Schedule');
            $('#scheduleEditModal').modal('hide');
            $.ajaxModal('#scheduleEditModal', url);
        }
        @endif

        // Update Schedule
        function reloadSchedule() {
            $.easyAjax({
                url: '{{route('admin.interview-schedule.index')}}',
                container: '#updateSchedule',
                type: "GET",
                success: function (response) {
                    $('.upcomingdata').html(response.data);

                    taskEvents = [];
                    response.scheduleData.forEach(function(schedule){
                        const taskEvent = {
                            id: schedule.id,
                            title: schedule.title +' on '+  schedule.full_name ,
                            start: schedule.schedule_date ,
                            end: schedule.schedule_date,
                        };
                        taskEvents.push(taskEvent);
                    });

                    $.CalendarApp.reInit();
                }
            })
        }
        @if($user->can('delete_schedule'))
        $('body').on('click', '.deleteSchedule', function (event) {
            var id = $(this).data('schedule-id');
            if (!$(event.target).closest('.deleteSchedule').length) {
                return false;
            }
            swal({
                title: "@lang('errors.areYouSure')",
                text: "@lang('errors.deleteWarning')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('app.delete')",
                cancelButtonText: "@lang('app.cancel')",
               /* closeOnConfirm: true,
                closeOnCancel: true
                */
            })
         .then((result) => {
                if (result.value) {

                    var url = "{{ route('admin.interview-schedule.destroy',':id') }}";
                    url = url.replace(':id', id);

                    var token = "{{ csrf_token() }}";

                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        data: {'_token': token, '_method': 'DELETE'},
                        success: function (response) {
                            if (response.status == "success") {
                                $.unblockUI();
                                $('#schedule-'+id).remove();
                                // Schedule create modal view
                                reloadSchedule();
                            }
                        }
                    });
                }
            });
        });
        @endif
        // Employee Response on schedule
        function employeeResponse(id, type) {
            var msg;

            if (type == 'accept') {
                msg = "@lang('errors.acceptSchedule')";
            } else {
                msg = "@lang('errors.refuseSchedule')";
            }
            swal({
                title: "@lang('errors.areYouSure')",
                text: msg,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('app.yes')",
                cancelButtonText: "@lang('app.cancel')",
              /*  closeOnConfirm: true,
                closeOnCancel: true
                */
            }).then((result) => {
                if (result.value) {
                    var url = "{{ route('admin.interview-schedule.response',[':id',':type']) }}";
                    url = url.replace(':id', id);
                    url = url.replace(':type', type);

                    // update values for all tasks
                    $.easyAjax({
                        url: url,
                        type: 'GET',
                        success: function (response) {
                            if (response.status == 'success') {
                                window.location.reload();
                            }
                        }
                    });
                }
            });
        }

        // schedule detail
        var getScheduleDetail = function (event, id) {

            if ($(event.target).closest('.editSchedule, .deleteSchedule, .responseButton').length) {
                return false;
            }

            var url = '{{ route('admin.interview-schedule.show', ':id')}}';
            url = url.replace(':id', id);

            $('#modelHeading').html('Schedule');
            $.ajaxModal('#scheduleDetailModal', url);
        }
        @if($user->can('add_schedule'))

        // Schedule create modal view
        function createSchedule(scheduleDate) {
            if (typeof scheduleDate === "undefined") {
                scheduleDate = '';
            }
            var url = '{{ route('admin.interview-schedule.create')}}?date=' + scheduleDate;
            $('#modelHeading').html('Schedule');
            $.ajaxModal('#scheduleDetailModal', url);
        }
        @endif

        @if($user->can('add_schedule'))
            function addScheduleModal(start, end, allDay) {
            var scheduleDate;
            if (start) {
                var sd = new Date(start);
                var curr_date = sd.getDate();
                if (curr_date < 10) {
                    curr_date = '0' + curr_date;
                }
                var curr_month = sd.getMonth();
                curr_month = curr_month + 1;
                if (curr_month < 10) {
                    curr_month = '0' + curr_month;
                }
                var curr_year = sd.getFullYear();
                scheduleDate = curr_year + '-' + curr_month + '-' + curr_date;
            }

            createSchedule(scheduleDate);
        }
        @endif
    </script>
@endpush