@extends('layouts.app')
@push('head-script')

   <link rel="stylesheet" href="{{ asset('auth_assets/css/main.css') }}">
    <link rel="stylesheet" href="//cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugins/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ asset('auth_assets/css/flexdatalist.css') }}">
 <script>
 
document.onreadystatechange = function(){
    let footerElement; let footer;
    footer = '<div class="" style="padding-top: 5%;">'
    footer += '<div class="candidate-footer" style="position: fixed;right: 0;bottom: 0;left: 0;padding: 10px;text-align: right;background: #3038BC;color: white;margin: auto;">'
    footer +=  '<button type="button" style="float: right; margin-right: 5px;" class="btn white-underb-btn white-underb-txt cd-pool-btns-pd" data-dismiss="modal" data-toggle="modal" data-target="#assignToJobModal"> Assign To Job </button>'
    footer += '<button type="button" style="float: right;margin-right: 5px;" class="btn btn bl-underb-btn bl-underb-txt cd-pool-btns-pd" data-toggle="modal" data-dismiss="modal" data-target="#sendEmailJobModal"> Send Email </button>'
    footer +=    '</div>'
    footer +='</div>'

    if(document.readyState === 'complete') {
    footerElement = document.getElementsByClassName('main-footers')[0];
        footerElement.innerHTML = footer;
        footerElement.className = "";
    }
}
        function exportJobApplication(exportType) {
            var startDate;
            var endDate;
            var type = exportType;
            var status = $('#status').val();
            var jobs = $('#jobs').val();
            var location = $('#location').val();

            startDate = $('#start-date').val();
            endDate = $('#end-date').val();

            if (startDate == '' || startDate == null) {
                startDate = 0;
            }

            if (endDate == '' || endDate == null) {
                endDate = 0;
            }
            let shortlistingParams = getShortlistingParams();
            var url = '{{ route('admin.downloadCandidateData')}}';
            url += '?&skills=' +
                shortlistingParams.skills + '&companies=' +
                shortlistingParams.companies + '&jobTitles=' +
                shortlistingParams.jobTitles + '&industry=' +
                shortlistingParams.industry + '&keyword=' +
                shortlistingParams.keyword + '&university=' +
                shortlistingParams.university + '&candidateQualifications=' +
                shortlistingParams.candidateQualifications + '&candidateDegrees=' +
                shortlistingParams.candidateDegrees + '&candidateResidentialState=' +
                shortlistingParams.candidateResidentialState + '&candidateCourse=' +
                shortlistingParams.candidateCourse + '&candidate_age_higher_bound=' +
                shortlistingParams.candidate_age_higher_bound + '&candidate_age_lower_bound=' +
                shortlistingParams.candidate_age_lower_bound +'&candidate_experience_lower_bound='+
                shortlistingParams.candidate_experience_lower_bound + '&candidate_experience_higher_bound='+
                shortlistingParams.candidate_experience_higher_bound +'&relevant_experience_lower_bound='+
                shortlistingParams.relevant_experience_lower_bound + '&relevant_experience_higher_bound='+
                shortlistingParams.relevant_experience_higher_bound + '&nysc_strict_result='+
                shortlistingParams.nysc_strict_result;
            // return;
            url = type == 'csv' ? url.replace(':type', type) : url.replace(':type', 'xlsx');
            window.location.href = url;
        }
            function applyshortlistsettings(){

            let shortlistData = document.getElementsByClassName("shortlistInput");
            $.ajax({
                        dataType: 'json',
                        url: this.config.pasteParserRulesets,
                        method: 'POST',
                        data: shortlistData,
                        crossDomain: true,
                        async: false,
                        error: function (jqXHR, textStatus, errorThrown) {
                        },
                        success: function (pasteParserRulesets) {
                        var table = document.getElementById("droneTable");
                        var row = table.insertRow(1);
                        var cell1 = row.insertCell(0); var cell2 = row.insertCell(1);
                        var cell3 = row.insertCell(2); var cell4 = row.insertCell(3);
                        var cell5 = row.insertCell(4); var cell6 = row.insertCell(5);
                        var cell7 = row.insertCell(6);

                        }
                    });
            }
 </script>

    <style>
        .mb-20{
            margin-bottom: 20px
        }
        .datepicker{
            z-index: 9999 !important;
        }
        .select2-container--default .select2-selection--single, .select2-selection .select2-selection--single {
            width: 250px;
        }
    </style>
@endpush


@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div style="font-weight: bold;color: #3038bc;margin: 10px 0px;font-size: 18px;"> Candidate Pool </div>
                    <div class="row clearfix">
                        <div class="col-md-12 mb-20">
                            <a href="javascript:;" id="toggle-filter"
                               class="btn btn-outline btn-success btn-sm toggle-filter"><i
                                        class="fa fa-sliders"></i> @lang('app.filterResults')</a>
                            <a class="" onclick="exportJobApplication('csv')">
                                <button class="btn btn-sm btn-primary" type="button">
                                    <i class="fa fa-upload"></i> Export CSV
                                </button>
                            </a>
                            <div style="text-align: right; cursor: pointer;" data-toggle="modal" data-dismiss="modal" data-target="#shortlistCandidatesModal"
                                 class="btn btn-outline btn-success btn-sm toggle-filter"><i
                                        class="fa fa-sliders"></i> Shortlist Candidates 
                            </div>
                             
                         

                        </div>
                    </div>
                    <div class="row b-b b-t" style="display: none; background: #fbfbfb;" id="ticket-filters">
                        <div class="col-md-12">
                            <h4>@lang('app.filterBy') <a href="javascript:;" class="pull-right toggle-filter"><i
                                            class="fa fa-times-circle-o"></i></a></h4>
                        </div>
                        <div class="col-md-12">
                            <form action="" id="filter-form" class="row">
                                <div class="col-md-5">
                                    <div class="example">
                                        <div class="input-daterange input-group" id="date-range">
                                            <input type="text" class="form-control" id="start-date"
                                                   placeholder="Show Results From" value=""/>
                                            <span class="input-group-addon bg-info b-0 text-white p-1">@lang('app.to')</span>
                                            <input type="text" class="form-control" id="end-date"
                                                   placeholder="Show Results To" value=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <select class="select2" name="status" id="status" data-style="form-control">
                                            <option value="all">@lang('modules.jobApplication.allStatus')</option>
                                            @forelse($boardColumns as $status)
                                                <option value="{{$status->id}}">{{ucfirst($status->status)}}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <select class="select2" name="jobs" id="jobs" data-style="form-control">
                                            <option value="all">@lang('modules.jobApplication.allJobs')</option>
                                            @forelse($jobs as $job)
                                                <option title="{{ucfirst($job->title)}}"
                                                        value="{{$job->id}}">{{ucfirst($job->title)}}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <select class="select2" name="location" id="location" data-style="form-control">
                                            <option value="all">@lang('modules.jobApplication.allLocation')</option>
                                            @forelse($locations as $location)
                                                <option value="{{$location->id}}">{{ucfirst($location->location)}}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group float-right">
                                        <label class="control-label col-xs-12">&nbsp;</label>
                                        <button type="button" id="apply-filters"
                                                class="btn btn-sm btn-success col-md-6"><i
                                                    class="fa fa-check"></i> @lang('app.apply')</button>
                                        <button type="button" id="reset-filters"
                                                class="btn btn-sm btn-dark col-md-5 col-md-offset-1"><i
                                                    class="fa fa-refresh"></i> @lang('app.reset')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive m-t-40">
                        <table id="myTable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th> <input id="select-checkbox-adhoc" type="checkbox" class="cd-radio-input-adhoc" > </input>
                                </th>
                                <th>#</th>
                                <th>@lang('modules.jobApplication.applicantFirstName')</th>
                                <th>@lang('modules.jobApplication.applicantLastName')</th>
                                <th>@lang('modules.jobApplication.resume')</th>
                                <th>@lang('modules.jobApplication.applicantEmail')</th>
                                <th>@lang('modules.jobApplication.applicantPhone')</th>
                               
                            </tr>
                            </thead>
                        </table>
                    </div>

            <div class="modal fade" id="shortlistCandidatesModal" tabindex="-1" role="dialog" aria-labelledby="shortlistCandidatesModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content" style="border-radius: 5px; width: 150%; height:auto;">
                        <div class="modal-header">
                            <span class="modal-title content-body" style="width: 100%;font-size: 16px;margin-bottom: 1%;" id="shortlistCandidatesModalLabel">Shortlist Candidates</span>
                           <span> <a class="pull-right" onclick="clearFilterModal()">
                                <button class="btn btn-sm clear-button" type="button">
                                    <i class="fa fa-filter"></i>  Clear Filter
                                </button>
                            </a>
                           </span>
                        </div>
                        <div class="modal-body">
                            <form>
                            
                            @include('modals/post-shortlist')

                     </form>
                    </div>
                   </div>
                </div>
            </div>

            @include('modals/send-email')
            @include('modals/assign-job')
            
        </div>


    </div>
@endsection


@push('footer-script')
    <script src="{{ asset('assets/node_modules/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/node_modules/bootstrap-select/bootstrap-select.min.js') }}" type="text/javascript"></script>
    <script src="//cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
    <script src="{{ asset('assets/node_modules/moment/moment.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/node_modules/multiselect/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('assets/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
    <script>
        $('#start-date').datepicker({
            format: 'yyyy-mm-dd'
        })

        $('#end-date').datepicker({
            format: 'yyyy-mm-dd'
        })
       
        var table;
        tableLoad('load');
        // For select 2
        $(".select2").select2();
        $('#reset-filters').click(function () {
            $('#filter-form')[0].reset();
            $('#filter-form').find('select').select2('render');
            tableLoad('load');
        })
        $('#apply-filters').click(function () {
            tableLoad('filter');
        });

        function tableLoad(type) {

            var status = $('#status').val();
            var jobs = $('#jobs').val();
            var location = $('#location').val();
            var startDate = $('#start-date').val();
            var endDate = $('#end-date').val();

            var singleEntityId = '{{$singleEntityId}}';
            var singleEntityIdType = '{{$singleEntityIdType}}';
            var shortlisting = type == 'shortlisting' ? 'shortlisting' : '';

            let shortlistingParams = getShortlistingParams();

            table = $('#myTable').dataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                destroy: true,
                stateSave: true,
                lengthMenu: [10, 25, 50, 150, 250, 500],
                columnDefs: [{
                    targets: 0,
                    checkboxes:{
                        selectRow: true,
                    },
                    orderable: false,
                }],
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                },
                order:[[1, 'asc']],
                ajax: '{!! route('admin.showCandidatePoolData') !!}?status=' +
                    status + '&location=' +
                    location + '&startDate=' +
                    startDate + '&endDate=' +
                    endDate + '&jobs=' +
                    jobs + '&singleEntityId=' +
                    singleEntityId + '&singleEntityIdType=' +
                    singleEntityIdType  + '&shortlisting=' +
                    shortlisting + '&skills=' +
                    shortlistingParams.skills + '&companies=' +
                    shortlistingParams.companies + '&jobTitles=' +
                    shortlistingParams.jobTitles + '&industry=' +
                    shortlistingParams.industry + '&keyword=' +
                    shortlistingParams.keyword + '&university=' +
                    shortlistingParams.university + '&candidateQualifications=' +
                    shortlistingParams.candidateQualifications + '&candidateDegrees=' +
                    shortlistingParams.candidateDegrees + '&candidateResidentialState=' +
                    shortlistingParams.candidateResidentialState + '&candidateCourse=' +
                    shortlistingParams.candidateCourse + '&candidate_age_higher_bound=' +
                    shortlistingParams.candidate_age_higher_bound + '&candidate_age_lower_bound=' +
                    shortlistingParams.candidate_age_lower_bound +'&candidate_experience_lower_bound='+
                    shortlistingParams.candidate_experience_lower_bound + '&candidate_experience_higher_bound='+
                    shortlistingParams.candidate_experience_higher_bound +'&relevant_experience_lower_bound='+
                    shortlistingParams.relevant_experience_lower_bound + '&relevant_experience_higher_bound='+
                    shortlistingParams.relevant_experience_higher_bound + '&candidate_certifications='+
                    shortlistingParams.candidate_certifications + '&candidate_state_of_origin='+
                    shortlistingParams.candidate_state_of_origin + '&olevel_higher_bound='+
                    shortlistingParams.olevel_higher_bound + '&olevel_lower_bound='+
                    shortlistingParams.olevel_lower_bound + '&nysc_strict_result='+
                    shortlistingParams.nysc_strict_result,

                language: {
                    "url": "<?php echo __("app.datatable") ?>"
                },
                "fnDrawCallback": function (oSettings) {
                    $("body").tooltip({
                        selector: '[data-toggle="tooltip"]'
                    });
                },
                columns: [
                    {data: 'select_user', name: 'user_id'},
                    {data: 'id', name: 'user_id'},
                    {data: 'first_name', name: 'users.firstname', }, 
                    {data: 'last_name', name: 'users.lastname'}, 
                    {data: 'resume', name: 'cv_url'},
                    {data: 'email', name: 'users.email', width: '17%'},
                    {data: 'phone', name: 'phone_number'},
                   
                ]
            });
            new $.fn.dataTable.FixedHeader(table);
        }

        $("#select-checkbox-adhoc").on("click", function(e) {
            if ($(this).is( ":checked" )) {
                $('.cd-radio-input').prop('checked', true);
            } else {
                $('.cd-radio-input').prop('checked', false);
            }
        });

      function applyShortlistingFilterNoLoad() {
             runFlex0();
             $("#saveShortListingFilterId").attr('data-active','');
             $("#saveShortListingFilterId").css({'opacity':'0.5','cursor':'not-allowed'});
        }

       function applyShortlistingFilter(noreload) {
          runFlex0();
          if(!noreload) {
            tableLoad('shortlisting');
            $('#shortlistCandidatesModal').modal('hide');
         }
        }

        $('.toggle-filter').click(function () {
            $('#ticket-filters').toggle('slide');
        });

        function exportJobApplication(exportType) {
            var startDate;
            var endDate;
            var type = exportType;
            var status = $('#status').val();
            var jobs = $('#jobs').val();
            var location = $('#location').val();

            startDate = $('#start-date').val();
            endDate = $('#end-date').val();

            if (startDate == '' || startDate == null) {
                startDate = 0;
            }

            if (endDate == '' || endDate == null) {
                endDate = 0;
            }

            let shortlistingParams = getShortlistingParams();
            var singleEntityId = '{{$singleEntityId}}';
            var singleEntityIdType = '{{$singleEntityIdType}}';

            var url = '{{ route('admin.downloadCandidateData', [':status',':location',':startDate', ':endDate', ':jobs', ':type',':skills' ]) }}';

            url = url.replace(':status', status);
            url = url.replace(':location', location);
            url = url.replace(':startDate', startDate);
            url = url.replace(':endDate', endDate);
            url = url.replace(':jobs', jobs);
            url += '&skills=' +
                shortlistingParams.skills + '&companies=' +
                shortlistingParams.companies + '&jobTitles=' +
                shortlistingParams.jobTitles + '&industry=' +
                shortlistingParams.industry + '&keyword=' +
                shortlistingParams.keyword + '&university=' +
                shortlistingParams.university + '&candidateQualifications=' +
                shortlistingParams.candidateQualifications + '&candidateDegrees=' +
                shortlistingParams.candidateDegrees + '&candidateResidentialState=' +
                shortlistingParams.candidateResidentialState + '&candidateCourse=' +
                shortlistingParams.candidateCourse + '&candidate_age_higher_bound=' +
                shortlistingParams.candidate_age_higher_bound + '&candidate_age_lower_bound=' +
                shortlistingParams.candidate_age_lower_bound +'&candidate_experience_lower_bound='+
                shortlistingParams.candidate_experience_lower_bound + '&candidate_experience_higher_bound='+
                shortlistingParams.candidate_experience_higher_bound +'&relevant_experience_lower_bound='+
                shortlistingParams.relevant_experience_lower_bound + '&relevant_experience_higher_bound='+
                shortlistingParams.relevant_experience_higher_bound + '&nysc_strict_result='+
                shortlistingParams.nysc_strict_result + '&singleEntityId='+
                singleEntityId + '&singleEntityIdType=' +singleEntityIdType;
            // return;
            url = type == 'csv' ? url.replace(':type', type) : url.replace(':type', 'xlsx');
            window.location.href = url;
        }


function onSendCandidateEmail() {
    let candidateChecked = [];
    let candidates = $('.cd-radio-input');
    const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
    
  for( var h=0;h<candidates.length;h++) {
    if (candidates[h].checked) {
         candidateChecked.push(candidates[h].value);
        }
    }

let message = document.getElementById("candidate-message").value;
let subject = document.getElementById("candidate-subject").value;

function toastMessageFunc(data) {
        Toast.fire({
        type: 'error',
        title: `You didn't put in a ${data}`
        })
}
if (candidateChecked.length < 1) {
    toastMessageFunc('candidate');
  return;
}

if (!message) {
    toastMessageFunc('message');
  return;
}

if (!subject) {
     toastMessageFunc('subject');
  return;
}

let url = '{{route("admin.sendemailtocandidate") }}';
  $.easyAjax({
        url,
        type: "POST",
        data: {
            subject,
            message,
            candidateChecked
            },
        crossDomain: true,
        success: function(response){
        let data = response.response;
        let option = '';

        $.unblockUI();
        $("#sendEmailJobModal").modal('hide');
            Toast.fire({
            type: 'success',
            title: 'Candidate emails sent!'
         })
    },
    error: function(error){
        Toast.fire({
        type: 'error',
        title: `Couldn't send email to candidates!`
        })
    }
 });
}
    </script>
@endpush


   </body>
</html>