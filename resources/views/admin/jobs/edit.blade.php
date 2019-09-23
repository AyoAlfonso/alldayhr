@extends('layouts.app')

@push('head-script')
    <link rel="stylesheet" href="{{ asset('assets/node_modules/html5-editor/bootstrap-wysihtml5.css') }}">
    <link rel="stylesheet"
          href="{{ asset('assets/node_modules/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/node_modules/multiselect/css/multi-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ asset('auth_assets/css/job_.css') }}">
     <link rel="stylesheet" href="{{ asset('auth_assets/vendor/trumbowyg/dist/ui/trumbowyg.min.css') }}">
@endpush


@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">@lang('app.edit')</h4>

                    <form class="ajax-form" method="POST" id="createForm">
                        @csrf
                        @method('PUT')

                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label  class="jb-inpt-hd" for="address">@lang('app.company')</label>
                                    <select name="company"  style="height: 35px;" class="jb-inpt form-control">                               
                                        @foreach ($companies as $comp)
                                            <option
                                                    @if($comp->id == $job->company_id) selected @endif
                                            value="{{ $comp->id }}">{{ ucwords($comp->company_name) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="jb-inpt-hd" for="address">@lang('modules.jobs.jobTitle')</label>
                                    <input type="text" class="jb-inpt form-control" name="title" value="{{ $job->title }}">
                                </div>

                            </div>


                            <div class="">
                                <div class="" style="display: inline-flex;">
                                    <div class="col-md-3" style="margin-right: 15px;">
                                        <div class="form-group">
                                            <label class="jb-inpt-hd" for="address">Positions</label>
                                            <input type="number" class="jb-inpt-w form-control" style="height: 35px;" name="total_positions"
                                                   id="total_positions" value="{{ $job->total_positions }}">
                                        </div>
                                    </div>

                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label class="jb-inpt-hd" for="address">@lang('menu.jobCategories')</label>
                                            <select name="category_id" id="category_id" class="jb-inpt form-control" style="height: 35px;width: 100%;">
                                                @foreach($categories as $category)
                                                    <option
                                                            @if($category->id == $job->category_id) selected @endif
                                                    value="{{ $category->id }}">{{ ucfirst($category->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group" style="display:none">
                                            <label for="address">@lang('app.status')</label>
                                            <select name="status" id="hidden_status" class="form-control">

                                            </select>
                                        </div>

                                        <div class="toggle-button-cover" id="select-checkbox-adhoc" >
                                            <div class="button-cover">
                                                <div class="button-job-adhoc r" id="button-1">
                                                    <input style="display:none" type="radio" name="status" id="job-status">
                                                    <input type="checkbox" id="status"  value="{{ $job->status }}" class="checkbox">
                                                    <div class="knobs">  <span> </span> </div>
                                                    <div> </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" style="margin-left: -7.5px;">
                                <div>
                                    <div class="col-md-8" style="margin-right: 35px;">
                                    <div class="form-group">
                                        <label for="address">@lang('modules.jobs.jobDescription')</label>
                                        <textarea class="jb-inpt-w form-control" id="job_description" name="job_description" rows="15"
                                                  placeholder="Enter text ...">{!! $job->job_description !!}</textarea>
                                    </div>
                                    </div>

                                    <div class="col-md-8" style="margin-right: 35px;">
                                        <div class="form-group">
                                            <label class="jb-inpt-hd" for="address">@lang('modules.jobs.jobRequirement')</label>
                                            <textarea class="jb-inpt-w form-control" id="job_requirement" name="job_requirement" rows="15"
                                                      placeholder="List or explain responsibilites required of the job."> {!! $job->job_requirement !!} </textarea>
                                        </div>

                                    </div>
                               </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="jb-inpt-hd" for="address">@lang('menu.locations')</label>
                                    <select name="location_id" id="location_id"
                                            class="jb-inpt form-control"
                                            style="height: 35px;" >
                                        @foreach($locations as $location)
                                            <option
                                                    @if($location->id == $job->location_id) selected @endif
                                            value="{{ $location->id }}">{{ ucfirst($location->location). ' ('.$location->country->country_code.')' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12" style="margin-left: -7.5px;">
                                <div style="display: inline-flex;width: 60%;">
                                    <div class="col-md-9">
                                    <div class="form-group">
                                    <label class="jb-inpt-hd" >@lang('menu.skills')</label>
                                    <select class="select2 m-b-10 " id="job_skills"
                                            style="width: 100%; " multiple="multiple"
                                            data-placeholder="@lang('menu.addSkills')" name="skill_id[]">
                                        @foreach($skills as $skill)
                                            <option
                                                    @foreach($job->skills as $jskill)
                                                    @if($skill->id == $jskill->skill_id)
                                                    selected
                                                    @endif
                                                    @endforeach
                                                    value="{{ $skill->id }}">{{ ucwords($skill->name) }}</option>
                                        @endforeach
                                    </select>
                                   </div>
                                </div>

                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label class="jb-inpt-hd" for="address">Job Roles</label>
                                            <select class=" m-b-10 tagable-select"  id="job_roles"
                                                    style="width: 50%;height:50px !important" multiple="multiple"
                                                    data-placeholder="@lang('app.add') Job Roles"
                                                    name="job_roles[]">
                                                @foreach($job->job_roles as $job_role)
                                                    <option selected>{{$job_role}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12" style="margin-left: -7.5px;">
                                 <div style="display: inline-flex;width: 60%;">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label class="jb-inpt-hd" for="address">@lang('app.startDate')</label>
                                            <input type="text" class="form-control" id="date-start"
                                                   value="{{ $job->start_date->format('Y-m-d') }}" name="start_date">
                                        </div>
                                    </div>

                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label class="jb-inpt-hd" for="address">@lang('app.endDate')</label>
                                            <input type="text" class="form-control" id="date-end" name="end_date"
                                                   value="{{ $job->end_date->format('Y-m-d') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" style="margin-left: -7.5px;">
                                <div style="display: inline-flex;width: 60%;">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label class="jb-inpt-hd" for="address">Required Profile Information</label>
                                            <select class="select2 m-b-10 select2-multiple" id="required_info"
                                                    style="width: 100%; " multiple="multiple"
                                                    data-placeholder="@lang('app.add') Required profile information"
                                                    name="required_info[]">
                                                @foreach(\App\Helpers\General::getRequiredProfileInfo() as $index=>$info)
                                                    <option value="{{ $index }}" {{in_array($index,$job->required_info) ? 'selected': ''}}>{{ ucfirst($info) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label class="jb-inpt-hd" for="address">Required Profile Documents</label>
                                            <select class="select2 m-b-10 select2-multiple" id="required_docs"
                                                    style="width: 100%; " multiple="multiple"
                                                    data-placeholder="@lang('app.add') Select required profile information"
                                                    name="required_docs[]">
                                                @foreach($documents as $document)
                                                    <option value="{{ $document->uuid }}" {{in_array($document->uuid,$job->required_docs) ? 'selected': ''}}>{{ ucfirst($document->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="col-md-12">
                                @forelse($questions as $question)
                                    <div class="form-group col-md-6">
                                        <label class="">
                                            <div class="icheckbox_flat-green" aria-checked="false" aria-disabled="false"
                                                 style="position: relative;">
                                                <input @if(in_array($question->id, $jobQuestion)) checked
                                                       @endif type="checkbox" value="{{$question->id}}"
                                                       name="question[]" class="flat-red"
                                                       style="position: absolute; opacity: 0;">
                                                <ins class="iCheck-helper"
                                                     style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                            </div>
                                            {{ ucfirst($question->question)}} @if($question->required == 'yes')
                                                (@lang('app.required'))@endif
                                        </label>
                                    </div>
                                @empty
                                @endforelse
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-1 col-centered" style="margin-left: 40%;">
                                <button type="button" id="save-form" class="save-job-btn-adhoc btn btn-success">
                                    </i> @lang('app.save')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer-script')
    <script src="{{ asset('assets/node_modules/select2/dist/js/select2.full.min.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/node_modules/bootstrap-select/bootstrap-select.min.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/node_modules/html5-editor/wysihtml5-0.3.0.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/node_modules/html5-editor/bootstrap-wysihtml5.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/node_modules/moment/moment.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/node_modules/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"
            type="text/javascript"></script>
    <script src="{{ asset('assets/node_modules/multiselect/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('assets/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('auth_assets/vendor/trumbowyg/dist/trumbowyg.min.js')}}"></script>
    
    <script>
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-blue',
        })
        
       $('#job_description').trumbowyg({
            btns: [
                    ['formatting'],
                    ['strong', 'em', 'del'],
                    ['superscript', 'subscript'],
                    ['link'],
                    ['insertImage'],
                    ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                    ['unorderedList', 'orderedList'],
                    ['horizontalRule'],
                    ['removeformat'],
                    ['fullscreen']
                ]
       });
       
        $('#job_requirement').trumbowyg({
             btns: [
                    ['formatting'],
                    ['strong', 'em', 'del'],
                    ['superscript', 'subscript'],
                    ['link'],
                    ['insertImage'],
                    ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                    ['unorderedList', 'orderedList'],
                    ['horizontalRule'],
                    ['removeformat'],
                    ['fullscreen']
                ]
        });
      

        // For select 2
        $(".select2").select2();

        $('#date-end').bootstrapMaterialDatePicker({weekStart: 0, time: false});
        $('#date-start').bootstrapMaterialDatePicker({weekStart: 0, time: false}).on('change', function (e, date) {
            $('#date-end').bootstrapMaterialDatePicker('setMinDate', date);
        });



        $('#category_id').change(function () {

            var id = $(this).val();

            var url = "{{route('admin.job-categories.getSkills', ':id')}}";
            url = url.replace(':id', id);

            $.easyAjax({
                url: url,
                success: function (response) {
                    $('#job_skills').html(response.data);
                }
            })
        });
       
    let jobStatus = '{{ $job->status }}';
        if (jobStatus == 'active') {
             $('#job-status').prop('checked', true);
              $('#status').prop('checked', false);
             } else {
               $('#job-status').prop('checked', false);
               $('#status').prop('checked', true);
             }

        $("#select-checkbox-adhoc").on("click", function(e) {
            if ($("#job-status").is(":checked")) {
                $('#job-status').prop('checked', false);
            }  else {
                $('#job-status').prop('checked', true);
            }
        });

        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('admin.jobs.update', $job->id)}}',
                container: '#createForm',
                type: "POST",
                redirect: true,
                data: $('#createForm').serialize(),
                success: function(response){
                    let data = response.response;
                    let option = '';

                },
                error: function(error){
                    Toast.fire({
                        type: 'error',
                        title: (error.responseJSON.error) ? error.responseJSON.error[0] : 'Make sure to fill all fields',
                    })
                }
            })
        });
        $(".tagable-select").select2({
            tags: true,
        })

    </script>
@endpush
