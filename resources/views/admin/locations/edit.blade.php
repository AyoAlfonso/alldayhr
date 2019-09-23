@extends('layouts.app')


@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">@lang('app.edit')</h4>

                    <form class="ajax-form" method="POST" id="createForm">
                        @csrf

                        <input name="_method" type="hidden" value="PUT">

                        <div class="row">
                            <div class="col-md-9">

                                <div class="form-group">
                                    <label for="address">@lang('app.country')</label>
                                    <select name="country_id" id="country_id"
                                            class="form-control select2 custom-select">
                                        @foreach($countries as $country)
                                            <option @if($country->id == $location->country_id) selected @endif value="{{ $country->id }}">{{ ucfirst($country->country_name) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>


                        <div id="education_fields"></div>
                        <div class="row">
                            <div class="col-sm-9 nopadding">
                                <div class="form-group">
                                        <input type="text" name="location" class="form-control" value="{{ $location->location }}"
                                               placeholder="@lang('menu.locations') @lang('app.name')">
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

        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route('admin.locations.update', $location->id)}}',
                container: '#createForm',
                type: "POST",
                redirect: true,
                data: $('#createForm').serialize()
            })
        });
    </script>
@endpush