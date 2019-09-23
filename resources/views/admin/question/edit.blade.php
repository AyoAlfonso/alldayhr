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

                    <div id="education_fields"></div>
                    <div class="row">
                        <div class="col-sm-9 nopadding">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" name="question" value="{{ $question->question }}" class="form-control" placeholder="@lang('menu.jobCategories') @lang('app.name')">

                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <label for="address">@lang('app.required')</label>
                                <select name="required" class="form-control">
                                    <option @if($question->required == 'yes') selected @endif value="yes">@lang('app.yes')</option>
                                    <option @if($question->required == 'no') selected @endif  value="no">@lang('app.no')</option>
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
    // Update Question
    $('#save-form').click(function () {
        $.easyAjax({
            url: '{{route('admin.questions.update', $question->id)}}',
            container: '#createForm',
            type: "POST",
            redirect: true,
            data: $('#createForm').serialize()
        })
    });
</script>
@endpush