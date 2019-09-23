@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('app.createNew')</h4>

                    <form class="ajax-form" method="POST" id="createForm" onsubmit="return false;">
                        @csrf

                    <div id="education_fields"></div>
                    <div class="row">
                        <div class="col-sm-9 nopadding">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" name="name[]" class="form-control" placeholder="@lang('menu.jobCategories') @lang('app.name')">
                                    <div class="input-group-append">
                                        <button class="btn btn-success" type="button" id="add-more"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
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
    var room = 1;

    $('#add-more').click(function(){

        room++;
        var objTo = document.getElementById('education_fields')
        var divtest = document.createElement("div");
        divtest.setAttribute("class", "form-group removeclass" + room);
        var rdiv = 'removeclass' + room;
        divtest.innerHTML = '<div class="row"><div class="col-sm-9 nopadding"><div class="form-group"><div class="input-group"> <input type="text" name="name[]" class="form-control" placeholder="@lang('menu.jobCategories') @lang('app.name')"><div class="input-group-append"> <button class="btn btn-danger" type="button" onclick="remove_education_fields(' + room + ');"> <i class="fa fa-minus"></i> </button></div></div></div></div><div class="clear"></div></row>';

        objTo.appendChild(divtest)
    })

    function remove_education_fields(rid) {
        $('.removeclass' + rid).remove();
    }

    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.job-categories.store')}}',
            container: '#createForm',
            type: "POST",
            redirect: true,
            data: $('#createForm').serialize()
        })
    });
</script>
@endpush