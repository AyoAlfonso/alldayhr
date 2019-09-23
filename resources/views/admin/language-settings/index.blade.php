@extends('layouts.app')

@push('head-script')
    <link rel="stylesheet" href="{{ asset('assets/node_modules/switchery/dist/switchery.min.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <a href="{{ url('/translations') }}" target="_blank" class="btn btn-sm mb-3 btn-primary"><i class="ti-settings"></i> @lang('messages.manageLanguageTranslations')</a>
                    <form class="ajax-form" method="POST" id="createForm">
                        @method('PUT')
                        <div class="form-body">
                        <div class="row">

                            @foreach($languages as $language)
                                <div class="col-sm-6 col-md-4 m-t-10">
                                    <div class="form-group">
                                        <label class="control-label col-sm-8">{{ $language->language_name }}</label>

                                        <div class="col-sm-4">
                                            <div class="switchery-demo">
                                                <input type="checkbox"
                                                       @if($language->status == 'enabled') checked
                                                       @endif class="js-switch change-language-setting"
                                                       data-color="#99d683" data-size="small"
                                                       data-setting-id="{{ $language->id }}"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @endforeach

                        </div>
                        <!--/row-->
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer-script')
    <script src="{{ asset('assets/node_modules/switchery/dist/switchery.min.js') }}"></script>

    <script>

        // Switchery
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function () {
            new Switchery($(this)[0], $(this).data());

        });


        $('.change-language-setting').change(function () {
            var id = $(this).data('setting-id');

            if ($(this).is(':checked'))
                var status = 'enabled';
            else
                var status = 'disabled';

            var url = '{{route('admin.language-settings.update', ':id')}}';
            url = url.replace(':id', id);
            $.easyAjax({
                url: url,
                type: "POST",
                data: {'id': id, 'status': status, '_method': 'PUT', '_token': '{{ csrf_token() }}'}
            })
        });

    </script>
@endpush