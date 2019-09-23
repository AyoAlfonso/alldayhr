@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('app.edit')</h4>

                    <form class="ajax-form" method="POST" id="createForm">
                        @csrf

                        <input name="_method" type="hidden" value="PUT">

                        <div class="row">
                            <div class="col-md-9">

                                <div class="form-group">
                                    <label for="address">@lang('menu.jobCategories')</label>
                                    <select name="category_id" id="category_id"
                                            class="form-control select2 custom-select">
                                        @foreach($categories as $category)
                                            <option @if($skill->category_id == $category->id) selected @endif value="{{ $category->id }}">{{ ucfirst($category->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>


                        <div id="education_fields"></div>
                        <div class="row">
                            <div class="col-sm-9 nopadding">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" name="name" value="{{ $skill->name }}" class="form-control"
                                               placeholder="@lang('menu.skills') @lang('app.name')">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="save-form" class="btn btn-success"><i
                                    class="fa fa-check"></i> @lang('app.save')</button>

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

    <script>
        // For select 2
        $(".select2").select2();

        var room = 1;

        $('#add-more').click(function () {

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
                url: '{{route('admin.skills.update', $skill->id)}}',
                container: '#createForm',
                type: "POST",
                redirect: true,
                data: $('#createForm').serialize()
            })
        });
    </script>
@endpush