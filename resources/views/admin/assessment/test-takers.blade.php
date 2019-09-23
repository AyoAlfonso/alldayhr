@extends('layouts.app')
@push('head-script')
    <link rel="stylesheet" href="//cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/node_modules/html5-editor/bootstrap-wysihtml5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/node_modules/multiselect/css/multi-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ asset('auth_assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('auth_assets/css/tt.css') }}">
    <link rel="stylesheet" href="{{ asset('auth_assets/css/tt_a.css') }}">

    <link rel="stylesheet" href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.css') }}">
    
    <style>
        .mb-20 {
            margin-bottom: 20px
        }
        .datepicker {
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
                <div class="tt-hd"> {{$jobById->title}} &nbsp; ( {{$testGroupCount}} Candidates ) </div>
                <div class="tt-hd">  <i class=" icon fa fa-map-marker"></i> {{$jobById->location->location }}   </div>
                    <div style="display:none" class="row clearfix">
                        <div class="col-md-12 mb-20">
                        
                            <a class="" onclick="exportJobApplication('csv')">
                                <button class="btn btn-sm btn-primary" type="button">
                                    <i class="fa fa-upload"></i> Export CSV
                                </button>
                            </a>
                            <div style="text-align: right; cursor: pointer;" data-toggle="modal" data-dismiss="modal" data-target="#shortlistCandidatesModal"
                                 class="btn btn-outline btn-success btn-sm toggle-filter"><i
                                        class="fa fa-sliders"></i> Shortlist Test Takers 
                            </div>
                           
                         
                            <div style=" text-align: right; cursor: pointer;" data-toggle="modal" data-dismiss="modal" data-target="#sendEmailJobModal"
                                class="btn btn-outline btn-success btn-sm toggle-filter"><i
                              class="fa fa-envelope"></i>  @lang('modules.jobApplication.sendBulkEmail')
                            </div>
                            
                            <div style="margin-top: 10px;" class="form-group styled-select">
                                <select style="border: 1px solid #d2d6de;padding: 6px 12px;" id="selectStatus" name="selectStatus" data-style="form-control">
                                    <option value="all">@lang('modules.jobApplication.setStatus')</option>

                                    {{-- @forelse($boardColumns as $status)
                                        <option value="{{$status->id}}">{{ucfirst($status->status)}}</option>
                                    @empty
                                    @endforelse --}}
                                </select>
                            </div>


                        </div>
                    </div>

             <div class="row">
                    <div class="tt-dashboard col-md-3 clearfix"> 
                        <div class="row">  
                            <span class="col-md-6 float-left" style="
    margin: 15px 5% 5px 5%;
    font-size: 200%;
    font-weight: bold;
">  0 </span>
                            <span class="col-md-2 float-right" style="
    margin: 15px 5% 5px 5%;
    font-size: 200%;
    font-weight: bold;
"> <img 
                                style="height: 15px;"
                                src="{{asset('/auth_assets/images/successful-green-guys.svg')}}">  </span>
                         </div>

                        <div class="tt-hd " style="margin: 5px 5% 15px"> Successful Candidates </div>
                      
                    </div>    
                    <div class="tt-dashboard col-md-3 clearfix"> 
                        <div class="row">  
                            <span class="col-md-6 float-left" style="margin: 15px 5% 5px 5%; font-size: 200%; font-weight: bold;"> {{$testGroupCount}} </span>

                            <span class="col-md-2 float-right" style=" margin: 15px 5% 5px 5%;font-size: 200%; font-weight: bold;"> <img 
                                style="height: 15px;"
                                src=" {{asset('/auth_assets/images/undefined-red-guys.svg')}}">
                            </span>
                         </div>

                        <div class="tt-hd" style="margin: 5px 5% 15px"> Undefined Candidates </div>
                    </div>  
                </div>
            
               <div class="row">
                    <div class="col-md-8">
                                  <button style="display:none" id="createTestTakers" class="tt-submit">  <span class="tt-sbmt-txt"> Add To TAO <span>
                                </button> 
                                 <button class="tt-submit" data-toggle="modal" data-dismiss="modal" data-target="#deliverTestModal">
                                  <span class="tt-sbmt-txt">Create Test Delivery <span>
                                </button>
                                 <button id="delTestDelivery" class="tt-submit">
                                  <span class="tt-sbmt-txt">Delete Test Delivery <span>
                                </button>
                             {{-- <button style="display:none" id="deliverTestBtn" data-toggle="modal" data-dismiss="modal" data-target="#deliverTestModalExcludeUsers">  </button> --}}
                                </div>
                    <div class="col-md-4">
                        <span class="float-right">
                            <img style="height: 15px;margin-left: 15px" src="{{ asset('/auth_assets/images/options/change-category.svg')}}">
                             Change Category
                             </span>
                      
                        <span id="sendTTLogin" class="float-right"> <img style="height: 15px;margin: 1px 2px 5px;" src="{{ asset('auth_assets/images/options/mail.svg')}}">  Send Login   </span>
                        
                    </div>
                    
                </div>
                    <div class="table-responsive m-t-40">
                        <table id="myTable" class="table table-bordered table-striped" style="border-radius: 5px;">
                            <thead>
                            <tr>
                                <th> <input id="select-checkbox-adhoc" type="checkbox" class="cd-radio-input-adhoc" > </input>
                                </th>
                               
                                <th>@lang('modules.jobApplication.applicantName')</th>
                                <th>Test Platform</th>
                                <th>Test Status</th>
                                <th>Result Category</th>
                                <th>Total Score %</th>
                             
                            </tr>
                            </thead>
                        </table>
                    </div>
                
            <div>
                @include('modals/tao-delivery')
            </div>
             
            <div>
                @include('modals/tao-delivery-exclude-users')
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
                            
                            @include('modals/test-takers-shortlist')

                     </form>
                    </div>
                   </div>
                </div>
            </div>

            @include('modals/send-email')
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

    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.js') }}" type="text/javascript"></script>
    
    <script>

        $('#start-date').datepicker({
            format: 'yyyy-mm-dd'
        })
        $('#end-date').datepicker({
            format: 'yyyy-mm-dd'
        })
        
       
        /*Separating the time formats of the TAO dates from the genral dates to prevent compatibility issues*/
        $('#start_date').datetimepicker({
             format: 'Y-m-d H:i'
        })
        $('#end_date').datetimepicker({
                format: 'Y-m-d H:i'
        })

         $("#select-checkbox-adhoc").on("click", function(e) {
            if ($(this).is( ":checked" )) {
                $('.cd-radio-input').prop('checked', true);
            } else {
                $('.cd-radio-input').prop('checked', false);
            }
        });
         
  
    $(document).ready(function () {
         (function () {
            var url = "{{ route('admin.candidate-assessment.get-tests-templates') }}";
            $.easyAjax({
            type: 'GET',
            url: url,
                success: function (response) {
                    if (response.data) {
                         let tests = response.data.children;
                         let option = '';
                            tests.forEach((datum, i )=>{
                                if(datum['type'] == 'instance'){
                                    option += '<option ttnm="'+ datum['data'] +'"  value="'+ datum['attributes']['id'] + '">' + datum['data'] + '</option>';
                                }
                        })
                        $('#available_tao_tests').append(option);
                    }
                }
            });
          })()
        })
          
      $('#sendTTLogin').on('click', function(){
            // alert('sdfsd');
         })

        let candidatesInfo = {
            'excluded': [],
            'taoTestName': ''
         }

        $(document).ready(function(){
            //on load put the onlick on the pagination direction links, request the correct the
            var previousPageUrl;
            var nextPageUrl;
            
            function changePage(pageurl, link, direction) {

                  let page = pageurl.split('page=')
                  page =  (page.length > 0) ? page[1] : 1;
                  
                  let url = "{{ route('admin.candidate-assessment.get-test-takers-on-tao') }}";
                  url = url +'?&jobId=' + '{{$singleEntityId}}' + '&page=' +page;
                  $.ajax({
                        type: 'POST',
                        url: url,
                        data: {'search': $("#search-tt-excluded").val(), target: 'byName', type: 'ajax'}
                    }).done(function(data) {
            let ttpaginatiohtml = "<ul class='pagination' role='navigation'>"
                        
            //  {{-- Previous Page Link --}}
            let tt_previous_data = data.data.prev_page_url ? data.data.prev_page_url : data.data.first_page_url;
            let tt_next_data = data.data.next_page_url ? data.data.next_page_url : data.data.first_page_url;

            ttpaginatiohtml +=       "<li class='page-item'>";
            ttpaginatiohtml +=     "<a class='tt-delivery-pagination' id='tt-previous' data-datac='" + tt_previous_data +" ' rel='prev'> < </a>"
            ttpaginatiohtml +=     "</li>"
           
            //  {{-- Previous Page Link --}}
            ttpaginatiohtml +=    "<li class='page-item'>";
            ttpaginatiohtml +=       "<a class='tt-delivery-pagination' id='tt-next' data-datac='"+ tt_next_data  +" ' rel='next'> > </a>"
            ttpaginatiohtml +=    "</li>"
     
            let toPgInfo = data.data.total
            let ttpaginationpginfo = "Showing Page "+ data.data.current_page +" of "+ toPgInfo + " test takers";
                 
                 $('#tt-delivery-pagination').empty();
                 $('#tt-delivery-pagination').append(ttpaginatiohtml)
     
                 $('#tt-delivery-pginfo').empty();
                 $('#tt-delivery-pginfo').append(ttpaginationpginfo)
                  
                   onclickNextPrevious()
                   replaceTestTakersDynamically(data.data)
                   onclickExcludedCandidates()
                 });
             }
          
        $('#available_tao_tests').change(function() {
               candidatesInfo.taoTestName = this.options[this.selectedIndex].getAttribute('ttnm');
            });
            
          function updateExcludedCandidates(){
               let candidates = $('.tt-excluded-input');
                    for( var h=0;h<candidates.length;h++) {
                        if (candidates[h].checked) {
                            let candidateInfo = candidates[h].value
                                candidatesInfo.excluded.push(candidateInfo)
                        }
                    }
                var uniqueUris = [];
                $.each(candidatesInfo.excluded, function(i, el){
                    if($.inArray(el, uniqueUris) === -1) uniqueUris.push(el);
                });
                candidatesInfo.excluded = uniqueUris;
          }

       
       function onclickExcludedCandidates(){
        $('.tt-excluded-input').click(function () {
            
            if (this.checked == false) {
                if(candidatesInfo.excluded.length > 0){
                     var index = candidatesInfo.excluded.indexOf(this.value);
                        if (index > -1) {
                          candidatesInfo.excluded.splice(index, 1);

                        }
                  }
              }

              updateExcludedCandidates()
                let cd_count = candidatesInfo.excluded.length > 0 ? "("+candidatesInfo.excluded.length+") " : 'No ';
                let cd_count_statement = ""+ cd_count +"Test Takers Selected for Exclusion";
              $('#selectedCandidateCount').html(cd_count_statement)
            });
       }

       onclickExcludedCandidates();
       function onclickNextPrevious(){
                    $('#tt-previous').click(function() {
                    previousPageUrl  = $(this).attr("data-datac");
                    if(previousPageUrl) {
                        updateExcludedCandidates();
                        changePage(previousPageUrl, this, 'forward');
                    }
                
                });

                $('#tt-next').click(function() {
                    var nextPageUrl  = $(this).attr("data-datac");
                    if(nextPageUrl){
                            updateExcludedCandidates()
                            changePage(nextPageUrl, this, 'backwards');
                        
                    }
                });
             }
             onclickNextPrevious();
        
            function putCheckStatus(test_taker_uri){
                    let checked = ''
                            if (test_taker_uri) {
                               $.each(candidatesInfo.excluded, function(i, el) {
                                            if(test_taker_uri== el) {
                                             checked = 'checked';
                                           }
                                    });
                            }
                return checked
        }
        
            function replaceTestTakersDynamically(ttdata) {
                
                $('#excludeTestTakersDynamic').empty();
                                    
                function chunkTaoTesters(arr, chunkSize) {
                        var R = [];
                        for (var i = 0,len = arr.length; i<len; i+=chunkSize)
                            R.push(arr.slice(i,i+chunkSize));
                        return R;
                }

                let data = chunkTaoTesters(ttdata.data, 2)
                        
                var ttHtml = "";
                data.forEach(ttArray => {
                  
                            ttHtml = "<div class='row' style='margin-left: 10px;margin-top: 20px;margin-bottom: 2%;'>"
                                ttArray.forEach(tt=> {
                             let ttProfileImage = tt['profile_image_url'] ? tt['profile_image_url'] : '{{asset('/auth_assets/images/avatar.png') }}';
                             let test_taker_uri = tt['test_groups'].length > 0 ? tt['test_groups'][0]['test_taker_uri'] : null
                             let checked
                             
                             checked = putCheckStatus(test_taker_uri)
                            
                                ttHtml +=  "<div class='col-md-5' style='margin-left: 2.5%;margin-right: 2.5%;'>"
                                ttHtml +=    "<span>"
                                ttHtml +=  "<input class='tt-excluded-input' style='transform: scale(1.3)' value="+ test_taker_uri +"  "+ checked + " type='checkbox'> <img src="+ ttProfileImage +"  style='height: 25px;border-radius: 50%;margin-left: 5px;'>  </span> <span style='display: inline-block;margin: 0px 5px;'>"+ tt['full_name'] +"</span> </div>"
                                
                                    })
                            ttHtml +=  "</div>"
                            $('#excludeTestTakersDynamic').append(ttHtml);
            })
         }
     })


  

         function deleteDelivery(){
            let url = "{{ route('admin.candidate-assessment.del-delivery-on-tao') }}";
                $.easyAjax({
                            type: 'POST',
                            url: url,
                            data: { jobId: '{{$singleEntityId}}'},
                            crossDomain: true,
                            success: function (response) {
                                 swal("Delivery deleted !", response.message, response.status);
                                  tableLoad('load');
                                        table._fnDraw();
                            }
                        })
                        

         }

        function onDeliverTest(){
        // $("#deliverTestModal").modal('hide');

        let deliveryConfirmtext = `Are you sure you want to save 
        this Delivery? All test takers
        will receive a notification of the delivery details.`
        
    
       let accpt_submition = 1; 

       if (accpt_submition == 1) {
            if({{$testGroupCount}} < 1){
                    accpt_submition = 0;
                    swal("Did you forget?","Check if you have sent candidates to TAO" , "error");
               }
         }

        $('#deliveryTestForm input, #deliveryTestForm select').each(
            function(index){  
                var input = $(this);
                if(input.val()=='') {
                    accpt_submition = 0;
                     swal("Try again","You skipped a field" , "error");
                    //  return;
                }
            }
        );

    if(accpt_submition == 1 ){
         let end_date = new Date($("#end_date").val()).getTime();
         let start_date  = new Date($("#start_date").val()).getTime();
        if(start_date >= end_date ) {
            accpt_submition = 0;
            swal("Try again","Your test start date cannot be earlier or the samee as your test end date" , "error");
        }
    }

    

    if(accpt_submition == 1) {
          
            swal({
                title: "Confirm Test Delivery Info",
                text: "Exclude candidates on the next page. \n Existing deliveries and their properties will be replaced",
                type: "info",
                showCancelButton: true,
                confirmButtonColor: "#3038bc",
                confirmButtonText: "Yes, Save",
                cancelButtonText: "@lang('app.cancel')",
            }).then((result) => {

                $("#deliverTestModal").modal('hide');
                $("#deliverTestModalExcludeUsers").modal('show');
                
            })
          }
        }

        function excludeUsers(){
            let candidates = $('.cd-radio-input');
            let candidateChecked = [];
            for( var h=0;h<candidates.length;h++) {
                if (candidates[h].checked) {
                    let candidateInfo = candidates[h].value.split("|");
                    let candidateObj = { applicationId: candidateInfo[0], email: candidateInfo[1], uri: candidateInfo[2]  };
                    candidateChecked.push(candidateObj);
                }
            }
            swal({
                title: "Confirm Test Delivery",
                text: "Test takers will receive a notification of the delivery details.",
                type: "info",
                showCancelButton: true,
                confirmButtonColor: "#3038bc",
                confirmButtonText: "Yes, Save",
                cancelButtonText: "@lang('app.cancel')",
                allowOutsideClick: false
            }).then((result) => {
                    if( result.value == true) {
                        let url = "{{ route('admin.candidate-assessment.create-delivery-on-tao') }}";
                        url = url +'?&jobId=' + '{{$singleEntityId}}' 
                        $.easyAjax({
                            type: 'POST',
                            url: url,
                            data: {
                                'candidateChecked': candidateChecked,
                                'excludeUsers': candidatesInfo['excluded'],
                                'taoDeliveryData': $('#deliveryTestForm').serialize(),
                                'toaTestName' :  candidatesInfo.taoTestName,
                                'start_date':  $("#start_date").val().split(" ").join('+'),
                                'end_date':  $("#end_date").val().split(" ").join('+')
                             },
                            crossDomain: true,
                            success: function (response) {
                                candidatesInfo.excluded = [];
                                   if (response.status == "success") {
                                        $.unblockUI();
                                        $("#deliverTestModalExcludeUsers").modal('toggle');
                                        swal("Delivery created !", response.message, "success");
                                        tableLoad('load');
                                        table._fnDraw();
                                      }
                                    }
                                })
                             }
                        })
                }


    
        $(document).ready(function(){
            $("#search-tt-excluded").keyup(function(event){
                let url = "{{ route('admin.candidate-assessment.get-test-takers-on-tao') }}";
                url = url +'?&jobId=' + '{{$singleEntityId}}';
             
                $.easyAjax({

                            type: 'POST',
                            url: url,
                            data: {'search': $("#search-tt-excluded").val(), target: 'byName', type: 'ajax'},
                            crossDomain: true,
                            success: function (response) {
                                
                                $('#excludeTestTakersDynamic').empty();

                            
                            function chunkTaoTesters(arr, chunkSize) {
                            var R = [];
                            for (var i = 0,len = arr.length; i<len; i+=chunkSize)
                                R.push(arr.slice(i,i+chunkSize));
                              return R;
                            }
                      let data = chunkTaoTesters(response.data.data, 2)
                    
                     var ttHtml = ""
                     data.forEach(ttArray=>{
                        ttHtml = "<div class='row' style='margin-left: 10px;margin-top: 20px;margin-bottom: 2%;'>"
                            ttArray.forEach(tt=> {
                               let ttProfileImage = tt['profile_image_url'] ? tt['profile_image_url'] : '{{asset('/auth_assets/images/avatar.png') }}';
                               let test_taker_uri = tt['test_groups'].length > 0 ? tt['test_groups'][0]['test_taker_uri'] : null
                               let checked
                               checked = putCheckStatus(test_taker_uri)
                              
                                    ttHtml +=  "<div class='col-md-5' style='margin-left: 2.5%;margin-right: 2.5%;'>"
                                    ttHtml +=    "<span>"
                                    ttHtml +=  "<input class='tt-excluded-input' style='transform: scale(1.3)' value="+ test_taker_uri +" "+ checked + " type='checkbox'> <img src="+ ttProfileImage +"  style='height: 25px;border-radius: 50%;margin-left: 5px;'>  </span> <span style='display: inline-block;margin: 0px 5px;'>"+ tt['full_name'] +"</span> </div>"
                                 })
                          ttHtml +=  "</div>"
                          $('#excludeTestTakersDynamic').append(ttHtml);
                            })
                          }
                     })
               })
        });

       $('#delTestDelivery').on('click', function(){

            swal({
                title: "@lang('errors.areYouSure')",
                text: "Delete Delivery",
                type: "info",
                showCancelButton: true,
                confirmButtonColor: "#3038bc",
                confirmButtonText: "Yes, Delete",
                cancelButtonText: "@lang('app.cancel')",
            }) .then((result) => {
                   if (result.value == true) {
                          deleteDelivery();
                }
              })
        })

        $('#createTestTakers').on('click', function(){

                let candidates = $('.cd-radio-input');
                let candidateChecked = [];
                for( var h=0;h<candidates.length;h++) {
                    if (candidates[h].checked) {
                        let candidateInfo = candidates[h].value.split("|");
                        let candidateObj = { applicationId: candidateInfo[0], email: candidateInfo[1], uri: candidateInfo[2]  };
                        candidateChecked.push(candidateObj);
                    }
                }
                
                if(candidateChecked.length < 1 ){
                    swal("Try again","You didn't select any candidate" , "error");
                    return;
                }
                swal({
                    title: "@lang('errors.areYouSure')",
                    text: "@lang('errors.createTestTakers')",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#3038bc",
                    confirmButtonText: "@lang('app.update')",
                    cancelButtonText: "@lang('app.cancel')",
                })
                    .then((result) => {
                        if (result.value) {
                            var url = "{{ route('admin.candidate-assessment.createTestTakers') }}";
                            url = url +'?&jobId=' + '{{$singleEntityId}}';
                            var token = "{{ csrf_token() }}";
                            $.easyAjax({
                                type: 'POST',
                                url: url,
                                data: {'_token': token, 'ttArray': candidateChecked},
                                crossDomain: true,
                                success: function (response) {
                                    if (response.status == "success") {
                                        $.unblockUI();
                                        swal("Candidates Updated!", response.message, "success");
                                         tableLoad('load');
                                         $('.cd-radio-input-adhoc').prop('checked', false); 
                                        table._fnDraw();
                                    }
                                }
                            });
                        }
                    })
            // }
            return;

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
            
           url = "{{ route('admin.candidate-assessment.test-takers-data') }}";

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
                ajax: url+'?&singleEntityId=' +
                    singleEntityId + '&singleEntityIdType=' +
                    singleEntityIdType,
                    
                language: {
                    "url": "<?php echo __("app.datatable") ?>"
                },
                "fnDrawCallback": function (oSettings) {
                    $("body").tooltip({
                        selector: '[data-toggle="tooltip"]'
                    });
                },
                columns: [
                    {data: 'select_user', name: 'id'},
                    {data: 'full_name', name: 'full_name',},
                    {data: 'test_platform', name: 'email', width: '17%', searchable: false},
                    {data: 'test_status', name: 'email', searchable: false},
                    {data: 'results', name: 'email', searchable: false},
                    {data: 'total_score', name: 'email',},
                    
                ]
            });
            new $.fn.dataTable.FixedHeader(table);
        }


        table.on('click', '.show-detail', function () {
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
        });

        $('.toggle-filter').click(function () {
            $('#ticket-filters').toggle('slide');
        });
    </script>
@endpush
