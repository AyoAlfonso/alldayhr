@extends('layouts.app') @push('head-script')
<link rel="stylesheet" href="{{ asset('assets/node_modules/dropify/dist/css/dropify.min.css') }}"> 
@endpush 
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">@lang('app.edit')</h4>

                <form id="editSettings" class="ajax-form">
                    @csrf @method('PUT')

                    <div class="form-group">
                        <label for="company_name">@lang('modules.accountSettings.companyName')</label>
                        <input type="text" class="form-control" id="company_name" name="company_name" value="{{ $company->company_name }}">
                    </div>
                    <div class="form-group">
                        <label for="company_email">@lang('modules.accountSettings.companyEmail')</label>
                        <input type="email" class="form-control" id="company_email" name="company_email" value="{{ $company->company_email }}">
                    </div>
                    <div class="form-group">
                        <label for="company_phone">@lang('modules.accountSettings.companyPhone')</label>
                        <input type="tel" class="form-control" id="company_phone" name="company_phone" value="{{ $company->company_phone }}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">@lang('modules.accountSettings.companyWebsite')</label>
                        <input type="text" class="form-control" id="website" name="website" value="{{ $company->website }}">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">@lang('modules.accountSettings.companyLogo')</label>
                        <div class="card">
                            <div class="card-body">
                                <input type="file" id="input-file-now" name="logo" class="dropify" @if(is_null($company->logo))
                                data-default-file="{{ asset('logo-not-found.png') }}" @else data-default-file="{{ asset('user-uploads/company-logo/'.$company->logo)
                                }}" @endif />
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="address">@lang('modules.accountSettings.companyAddress')</label>
                        <textarea class="form-control" id="address" rows="5" name="address">{{ $company->address }}</textarea>
                    </div>


                    <div class="form-group">
                        <label for="address">@lang('app.status')</label>
                        <select name="status" id="status" class="form-control">
                                <option @if($company->status == 'active') selected @endif>active</option>
                                <option @if($company->status == 'inactive') selected @endif>inactive</option>
                            </select>
                    </div>

                    <div class="form-group">
                        <label for="address">@lang('modules.company.showFrontend')</label>
                        <select name="show_in_frontend" id="show_in_frontend" class="form-control">
                                            <option @if($company->show_in_frontend == 'true') selected @endif value="true">@lang('app.yes')</option>
                                            <option @if($company->show_in_frontend == 'false') selected @endif value="false">@lang('app.no')</option>
                                        </select>
                    </div>

                    <button type="button" id="save-form" class="btn btn-success waves-effect waves-light m-r-10">
                            @lang('app.save')
                        </button>
                    <button type="reset" class="btn btn-inverse waves-effect waves-light">@lang('app.reset')</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
 @push('footer-script')
<script src="{{ asset('assets/node_modules/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/node_modules/bootstrap-select/bootstrap-select.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/node_modules/dropify/dist/js/dropify.min.js') }}" type="text/javascript"></script>

<script>
    // For select 2
        $(".select2").select2();

        $('.dropify').dropify({
            messages: {
                default: '@lang("app.dragDrop")',
                replace: '@lang("app.dragDropReplace")',
                remove: '@lang("app.remove")',
                error: '@lang('app.largeFile')'
            }
        });

       

        $('#save-form').click(function () {
            $.easyAjax({
                url: '{{route("admin.company.update", $company->id)}}',
                container: '#editSettings',
                type: "POST",
                redirect: true,
                file: true
            })
        });

</script>




@endpush