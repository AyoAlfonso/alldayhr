@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('app.createNew')</h4>

                    <form class="ajax-form" method="POST" id="createForm">
                        @csrf

                    <div id="education_fields"></div>
                    <div class="row">

                        <div id="addMoreBox1" class="col-md-12">
                            <div class="row">
                                <div class="col-md-11">
                                    <div class="col-md-12">
                                        <div id="dateBox" class="form-group ">
                                            <label for="name">@lang('app.question')</label>
                                            <input type="text" name="name[]" class="form-control" placeholder="@lang('menu.question')">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="address">@lang('app.required')</label>
                                            <select name="required[]" class="form-control">
                                                <option value="yes">@lang('app.yes')</option>
                                                <option value="no">@lang('app.no')</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-1" style="margin-top: 66px">
                                    <button type="button"  onclick="removeBox(1)"  class="btn btn-sm btn-danger"><i class="fa fa-times"></i></button>
                                </div>
                            </div>

                        </div>
                        <div id="insertBefore"></div>
                        <div class="clearfix">

                        </div>

                        <div class="col-md-12">
                            <button type="button" id="plusButton" class="btn btn-sm btn-info" style="margin-bottom: 20px">
                                @lang('app.addMore') <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <button type="button" id="save-form" class="btn btn-success"><i class="fa fa-check"></i> @lang('app.save')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer-script')
<script>

    var $insertBefore = $('#insertBefore');
    var $i = 0;

    // Add More Inputs
    $('#plusButton').click(function(){

        $i = $i+1;
        var indexs = $i+1;
        $(' <div id="addMoreBox'+indexs+'" class="col-md-12"> ' +
            '<div class="row">' +
            '<div class="col-md-11">' +
            '<div class="col-md-9">' +
        '<div id="dateBox" class="form-group ">' +
            '<label for="name">@lang('app.question')</label>' +
            '<input type="text" name="name['+$i+']" class="form-control" placeholder="@lang('menu.question')">' +
            '</div>' +
            '</div>' +
            '<div class="col-md-9">' +
        '<div class="form-group">' +
            '<label for="address">@lang('app.required')</label>' +
            '<select name="required['+$i+']" class="form-control">' +
            '<option value="yes">@lang('app.yes')</option>' +
            '<option value="no">@lang('app.no')</option>' +
            '</select>' +
            '</div>' +
            '</div>' +
            '</div>' +

            '<div class="col-md-1">' +
        '<button type="button"  onclick="removeBox('+indexs+')"  class="btn btn-sm btn-danger"><i class="fa fa-times"></i></button>' +
        '</div>' +
        '</div>').insertBefore($insertBefore);

    });

    // Remove fields
    function removeBox(index){
        $('#addMoreBox'+index).remove();
    }
    // // Remove fields
    // function removeBox(index){
    //     $('#addMoreBox'+index).remove();
    // }
    //
    // function remove_education_fields(rid) {
    //     $('.removeclass' + rid).remove();
    // }

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.questions.store')}}',
            container: '#createForm',
            type: "POST",
            redirect: true,
            data: $('#createForm').serialize()
        })
    });
</script>
@endpush