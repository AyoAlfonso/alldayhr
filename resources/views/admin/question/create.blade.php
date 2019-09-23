@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">@lang('app.createNew')</h4>

                    <form class="ajax-form" method="POST" id="createForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="address">@lang('menu.question')</label>
                                    <input type="text" name="question" class="form-control" placeholder="@lang('menu.question')">
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="address">@lang('app.required')</label>
                                    <select name="required" class="form-control">
                                        <option value="yes">@lang('app.yes')</option>
                                        <option value="no">@lang('app.no')</option>
                                    </select>
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