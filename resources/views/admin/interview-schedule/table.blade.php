@extends('layouts.app')

@push('head-script')
    <link rel="stylesheet" href="//cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/node_modules/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">

    <style>
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
            /*height: 1.5em;*/
            font-size: 0.730rem !important;
            line-height: 0.5 !important;
        }
        .scheduleul
        {
            padding: 0 15px 0 11px;
        }
    </style>
@endpush

@permission('add_skills')
@section('create-button')
{{--    <a href="{{ route('admin.skills.create') }}" class="btn btn-dark btn-sm m-l-15"><i class="fa fa-plus-circle"></i> @lang('app.createNew')</a>--}}
@endsection
@endpermission

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <a href="javascript:;" id="toggle-filter" class="btn btn-outline btn-danger btn-sm toggle-filter"><i
                                            class="fa fa-sliders"></i> @lang('app.filterResults')</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <form class="form-inline justify-content-lg-end " style="align-items: end !important; ">
                                <div class="form-group mr-3" style="width:50%">
                                    <select name="statusMultiple" id="statusMultiple" class="form-control" style="width:100%;">
                                        <option value="rejected">@lang('app.rejected')</option>
                                        <option value="hired">@lang('app.hired')</option>
                                        <option value="pending">@lang('app.pending')</option>
                                        <option value="canceled">@lang('app.canceled')</option>
                                    </select>
                                </div>

                                <button type="button" id="changeMultipleStatus" class="btn btn-success"><i class="fa fa-check"></i> @lang('app.apply')</button>
                            </form>

                        </div>
                    </div>

                    <div class="row" style="display: none; background: #fbfbfb;" id="ticket-filters">
                        <div class="col-md-12">
                            <h4>@lang('app.filterBy') <a href="javascript:;" class="pull-right toggle-filter"><i class="fa fa-times-circle-o"></i></a></h4>
                        </div>
                         <div class="col-md-12">

                             <form action="" class="row" id="filter-form" style="width: 100%;">
                                 <div class="col-md-4">
                                     <h5 >@lang('app.selectDateRange')</h5>
                                     <div class="example">
                                         <div class="input-daterange input-group" id="date-range">
                                             <input type="text" class="form-control" id="start-date" placeholder="Show Results From" value="" />
                                             <span class="input-group-addon bg-info b-0 text-white p-1">@lang('app.to')</span>
                                             <input type="text" class="form-control" id="end-date" placeholder="Show Results To" value="" />
                                         </div>
                                     </div>
                                 </div>
                                 <div class="col-md-3">
                                     <h5 >@lang('app.candidate')</h5>
                                     <div class="form-group">
                                         {{--<label class="control-label">@lang('modules.client.status')</label>--}}
                                         <select class="form-control select2" name="applicationID" id="applicationID" data-style="form-control">
                                             <option value="all">@lang('app.all')</option>
                                             @forelse($candidates as $candidate)
                                                 <option value="{{$candidate->id}}">{{ ucfirst($candidate->full_name) }}</option>
                                             @empty
                                             @endforelse
                                         </select>
                                     </div>
                                 </div>
                                 <div class="col-md-2">
                                     <h5 >@lang('app.status')</h5>
                                     <div class="form-group">
                                         {{--<label class="control-label">@lang('modules.client.status')</label>--}}
                                         <select class="form-control select2" name="status" id="status" data-style="form-control">
                                             <option value="all">@lang('app.all')</option>
                                             <option value="pending">@lang('app.pending')</option>
                                             <option value="rejected">@lang('app.rejected')</option>
                                             <option value="hired">@lang('app.hired')</option>
                                             <option value="canceled">@lang('app.canceled')</option>
                                         </select>
                                     </div>
                                 </div>
                                 <div class="col-md-3">
                                     <h5 ></h5>
                                     <div class="form-group" style="margin-top: 20px">
                                         <label class="control-label col-xs-12">&nbsp;</label>
                                         <button type="button" id="apply-filters" class="btn btn-success col-md-6"><i class="fa fa-check"></i> @lang('app.apply')</button>
                                         <button type="button" id="reset-filters" class="btn btn-inverse col-md-5 col-md-offset-1"><i class="fa fa-refresh"></i> @lang('app.reset')</button>
                                     </div>
                                 </div>
                             </form>
                         </div>
                    </div>
                    <div class="table-responsive m-t-40">
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th><input name="select_all" value="1" id="example-select-all" type="checkbox" /></th>
                                {{--<th>#</th>--}}
                                <th>@lang('app.candidate')</th>
                                <th>@lang('menu.interviewDate')</th>
                                <th>@lang('menu.status')</th>
                                <th>@lang('app.action')</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
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
@endsection

@push('footer-script')
    <script src="{{ asset('assets/node_modules/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/node_modules/bootstrap-select/bootstrap-select.min.js') }}" type="text/javascript"></script>
    <script src="//cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
    <script src="{{ asset('assets/node_modules/moment/moment.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>


    <script>
        loadTable();
        $(".select2").select2({
            formatNoMatches: function () {
                return "{{ __('messages.noRecordFound') }}";
            }
        });
        $('#start-date').datepicker({
            format: 'yyyy-mm-dd'
        })
        $('#end-date').datepicker({
            format: 'yyyy-mm-dd'
        })

        var table;
        function loadTable(){
            var startDate = $('#start-date').val();

            if (startDate == '') {
                startDate = null;
            }

            var endDate = $('#end-date').val();

            if (endDate == '') {
                endDate = null;
            }

            var status = $('#status').val();
            var applicationID = $('#applicationID').val();

            table = $('#myTable').dataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: '{!! route('admin.interview-schedule.data') !!}?startDate=' + startDate + '&endDate=' + endDate + '&status=' + status + '&applicationID=' + applicationID,
                language: {
                    "url": "<?php echo __("app.datatable") ?>",
                    buttons: {
                        selectAll: "Select all items",
                        selectNone: "Select none"
                    }
                },
                "fnDrawCallback": function( oSettings ) {
                    $("body").tooltip({
                        selector: '[data-toggle="tooltip"]'
                    });
                },
                columns: [
                    { data: 'id', name: 'id'},
                    { data: 'full_name', name: 'full_name' },
                    { data: 'schedule_date', name: 'schedule_date' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action' }
                ],
                buttons: [
                    'selectAll',
                    'selectNone'
                ],
                columnDefs: [ {
                    targets: 0,
                    data: null,
                    defaultContent: '',
                    orderable: false,
                    className: 'select-checkbox',
                    'render': function (data, type, full, meta){
                        return '<input type="checkbox" class="checkboxes" name="id[]" value="'
                            + $('<div/>').text(data).html() + '">';
                    }
                } ],
                select: {
                    style:    'os',
                    selector: 'td:first-child'
                },
                order: [[ 1, 'asc' ]]
            });
            new $.fn.dataTable.FixedHeader( table );

        }

        // Handle click on "Select all" control
        $('#example-select-all').on('click', function(){
            // Check/uncheck all checkboxes in the table
            $('input[type="checkbox"]').prop('checked', this.checked);
        });

        // Employee Response on schedule
        $('#changeMultipleStatus').on('click', function(){
            var msg;
            var status = $('#statusMultiple').val();
            var selectedArray = [];
            $('.checkboxes:checked').each(function(){
                selectedArray.push($(this).val());
            });
            if(selectedArray.length > 0){
            swal({
                title: "@lang('errors.askForCandidateEmail')",
                text: msg,
                type: "info",
                showCancelButton: true,
                confirmButtonColor: "#0c19dd",
                confirmButtonText: "@lang('app.yes')",
                cancelButtonText: "@lang('app.no')",
              /*  closeOnConfirm: true,
                closeOnCancel: true
                */
            }).then((result) => {
                if (result.value) {
                    changeMultipleStatus(status , 'yes')
                }
                else{
                    changeMultipleStatus(status , 'no')
                }
            });
            }else{
                $.showToastr('Please check atleast one checkbox', "error" );
                // alert('Please check atleast one checkbox');
            }
        });

        function changeMultipleStatus(status, mailToCandidate){
            var selectedArray = [];
            $('.checkboxes:checked').each(function(){
                selectedArray.push($(this).val());
            });
            var token = "{{ csrf_token() }}";
            var url = "{{ route('admin.interview-schedule.change-status-multiple') }}";
            $.easyAjax({
                url: url,
                type: "POST",
                data: {'_token': token, "id": selectedArray, "status": status,'mailToCandidate': mailToCandidate},
                container: '#myTable',
                success: function (response) {
                    $.unblockUI();
                    table._fnDraw();
                }
            });
        }

        // Edit Data
        $('body').on('click', '.edit-data', function(){
            var id = $(this).data('row-id');
            var url = "{{ route('admin.interview-schedule.edit',':id') }}";
            url = url.replace(':id', id);
            $('#modelHeading').html('Schedule');
            $('#scheduleDetailModal').modal('hide');
            $.ajaxModal('#scheduleDetailModal', url);
        });
        // View Data
        $('body').on('click', '.view-data', function(){
            var id = $(this).data('row-id');
            var url = "{{ route('admin.interview-schedule.show',':id') }}?table=yes";
            url = url.replace(':id', id);
            $('#modelHeading').html('Schedule');
            $('#scheduleDetailModal').modal('hide');
            $.ajaxModal('#scheduleDetailModal', url);
        });

        // Delete Data
        $('body').on('click', '.sa-params', function(){
            var id = $(this).data('row-id');
            swal({
                title: "@lang('errors.areYouSure')",
                text: "@lang('errors.deleteWarning')",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "@lang('app.delete')",
                cancelButtonText: "@lang('app.cancel')",
              /*  closeOnConfirm: true,
                closeOnCancel: true
                */
            }).then((result) => {
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
                                table._fnDraw();
                            }
                        }
                    });
                }
            });
        });

        // Filte Toggle
        $('.toggle-filter').click(function () {
            $('#ticket-filters').toggle('slide');
        })

        // Apply Filter
        $('#apply-filters').click(function () {
            loadTable();
        });

        // Reset Filters
        $('#reset-filters').click(function () {
            $('#filter-form')[0].reset();
            $('#status').val('all');
            $('#status').select2();
            $('#applicationID').val('all');
            $('#applicationID').select2();
            loadTable();
        })

    </script>
@endpush