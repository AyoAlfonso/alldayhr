@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">@lang('app.createNew')</h4>

                    <form class="ajax-form" method="POST" id="createForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-4 pl-4 pr-4  pb-4 b-b">
                                <h5>@lang('modules.front.personalInformation')</h5>
                            </div>


                            <div class="col-md-8 pb-4 b-b">

                                <div class="form-group">
                                    <label class="control-label">@lang('menu.jobs')</label>
                                    <select name="job_id" id="job_id" onchange="getQuestions(this.value)" class="form-control">
                                        @foreach($jobs as $job)
                                            <option value="{{ $job->id }}">{{ ucwords($job->title).' ('.ucwords($job->location->location).')' }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <input class="form-control" type="text" name="full_name" placeholder="@lang('app.name')">
                                </div>

                                <div class="form-group">
                                    <input class="form-control" type="email" name="email"
                                           placeholder="@lang('app.email')">
                                </div>

                                <div class="form-group">
                                    <input class="form-control" type="tel" name="phone"
                                           placeholder="@lang('app.phone')">
                                </div>

                                <div class="form-group">
                                    <h6>@lang('modules.front.photo')</h6>
                                    <input class="select-file" accept=".png,.jpg,.jpeg" type="file" name="photo"><br>
                                    <span class="help-block">@lang('modules.front.photoFileType')</span>
                                </div>

                            </div>

                            <div class="col-md-4 pl-4 pr-4 pb-4 pt-4 b-b">
                                <h5>@lang('modules.front.resume')</h5>
                            </div>


                            <div class="col-md-8 pb-4 pt-4 b-b">

                                <div class="form-group">
                                    <input class="select-file" type="file" name="resume"><br>
                                </div>


                            </div>

                            <div class="col-md-4 pl-4 pr-4 pt-4 b-b">
                                <h5>@lang('modules.front.coverLetter')</h5>
                            </div>


                            <div class="col-md-8 pt-4 b-b">

                                <div class="form-group">
                                    <textarea class="form-control" name="cover_letter" rows="4"></textarea>
                                </div>
                            </div>

                            <div class="col-md-4 pl-4 pr-4 pt-4 b-b" id="questionBoxTitle">
                                <h5>@lang('modules.front.additionalDetails')</h5>
                            </div>


                            <div class="col-md-8 pt-4 b-b" id="questionBox">

                            </div>

                        </div>

                        <br>
                        <button type="button" id="save-form" class="btn btn-success"><i
                                    class="fa fa-check"></i> @lang('app.save')</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer-script')
    <script>

        $('#save-form').click(function () {

            $.easyAjax({
                url: '{{route('admin.job-applications.store')}}',
                container: '#createForm',
                type: "POST",
                redirect: true,
                file:true,
                data: $('#createForm').serialize(),
                error: function (response) {
                    handleFails(response);
                }
            })
        });

        var val = $('#job_id').val(); // get Current Selected Job
        getQuestions (val); // get Questions by question on page load

        // get Questions on change Job
         function getQuestions (id) {
            var url = "{{ route('admin.job-applications.question',':id') }}";
            url = url.replace(':id', id);

            $.easyAjax({
                type: 'GET',
                url: url,
                success: function (response) {
                    if (response.status == "success") {
                        if(response.count > 0){ // Question Found for selected job
                            $('#questionBox').show();
                            $('#questionBoxTitle').show();
                            $('#questionBox').html(response.view);
                        }else{ // Question Not Found for selected job
                            $('#questionBox').hide();
                            $('#questionBoxTitle').hide();
                        }
                    }

                }
            });
        }

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
                    if(formarray.indexOf('.') >0){
                        var array = formarray.split('.');
                        response.responseJSON.errors[keys[i]] = response.responseJSON.errors[keys[i]];
                        key = array[0]+'['+array[1]+']';
                    }

                    var ele = $('#createForm').find("[name='" + key + "']");

                    var grp = ele.closest(".form-group");
                    $(grp).find(".help-block").remove();

                    //check if wysihtml5 editor exist
                    var wys = $(grp).find(".wysihtml5-toolbar").length;

                    if(wys > 0){
                        var helpBlockContainer = $(grp);
                    }
                    else{
                        var helpBlockContainer = $(grp).find("div:first");
                    }
                    if($(ele).is(':radio')){
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