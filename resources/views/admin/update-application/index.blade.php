@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if(isset($lastVersion))
                <div class="alert alert-danger">
                    <p> @lang('messages.updateAlert')</p>
                    <p>@lang('messages.updateBackupNotice')</p>
                </div>

                <div class="alert alert-info col-md-12">
                    <div class="col-md-10"><i class="ti-gift"></i> @lang('modules.update.newUpdate') <label class="label label-success">{{ $lastVersion }}</label><br><br>
                        <span class="font-12">@lang('modules.update.updateAlternate')</span>
                    </div>
                    <div class="col-md-2"><a id="update-app" href="javascript:;" class="btn btn-success btn-small">@lang('modules.update.updateNow') <i class="fa fa-download"></i></a></div>

                    <div class="col-md-12">
                        <p>{!! $updateInfo !!}</p>
                    </div>
                </div>

                <div id="update-area" class="m-t-20 m-b-20 col-md-12 white-box hide">
                    Loading...
                </div>
            @else
                <div class="alert alert-success col-md-12">
                    <div class="col-md-12">You have latest version of this app.</div>
                </div>
            @endif
        </div>


        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">

                                <table class="table table-bordered">
                                    <thead>
                                    <th>@lang('modules.update.systemDetails')</th>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>App Version <span
                                                    class="pull-right">{{ $appVersion }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Laravel Version <span
                                                    class="pull-right">{{ $laravelVersion }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>PHP Version
                                            @if (version_compare(PHP_VERSION, '7.1.0') > 0)
                                                <span class="pull-right">{{ phpversion() }} <i class="fa fa fa-check-circle text-success"></i></span>
                                            @else
                                                <span class="pull-right">{{ phpversion() }} <i  data-toggle="tooltip" data-original-title="@lang('messages.phpUpdateRequired')" class="fa fa fa-warning text-danger"></i></span>
                                            @endif
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <hr>
                    <!--row-->
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="box-title" id="structure">Update Log</h4>
                            <pre>
    <p>
        ├──
        │
        │   └── <strong class="font-bold">Version 1.4.4</strong>
        │       └── Bug fixes.
        │
        │   └── <strong class="font-bold">Version 1.4.3</strong>
        │       └── Added companies modules to manage jobs for multiple companies.
        │
        │   └── <strong class="font-bold">Version 1.3</strong>
        │       └── Added interview schedule feature.
        │
        │   └── <strong class="font-bold">Version 1.2</strong>
        │       └── Added email settings in settings section.
        │       └── Added custom job questions feature for job posts.
        │       └── Added export to excel button for job applications.
        │       └── Added filter for job applications table view.
        │
        │   └── <strong class="font-bold">Version 1.1</strong>
        │       └── Theme changed due to copyright issues. No major chnages in the UI.
        │
        │   └── <strong class="font-bold">Version 1.0</strong>
        │       └── Initial Release
        │
        └──
    </p>
                                        </pre>
                        </div>
                    </div>
                    <!--/row-->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer-script')
    <script type="text/javascript">
        var updateAreaDiv = $('#update-area');
        var refreshPercent = 0;
        var checkInstall = true;

        $('#update-app').click(function () {
            if($('#update-frame').length){
                return false;
            }

            swal({
                title: "Are you sure?",
                text: "Take backup of files and database before updating!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, update it!",
                cancelButtonText: "No, cancel please!",
               /* closeOnConfirm: true,
                closeOnCancel: true
                */
            }).then((result) => {
                 updateAreaDiv.removeClass('hide');
                if (result.value) {
                    $.easyAjax({
                        type: 'GET',
                        url: '{!! route("admin.update-application.updateApp") !!}',
                        success: function (response) {
                            updateAreaDiv.html("<strong>What's New:-</strong><br> "+response.description);
                            downloadApp();
                            downloadPercent();
                        }
                    });
                }
            });
        })

        function downloadApp(){
            $.easyAjax({
                type: 'GET',
                url: '{!! route("admin.update-application.download") !!}',
                success: function (response) {
                    clearInterval(refreshPercent);
                    $('#percent-complete').css('width', '100%');
                    $('#percent-complete').html('100%');
                    $('#download-progress').append("<i><span class='text-success'>Download complete.</span> Now Installing...Please wait (This may take few minutes.)</i>");

                    window.setInterval(function(){
                        /// call your function here
                        if(checkInstall == true){
                            checkIfFileExtracted();
                        }
                    }, 1500);

                    installApp();

                }
            });
        }

        function getDownloadPercent(){
            $.easyAjax({
                type: 'GET',
                url: '{!! route("admin.update-application.downloadPercent") !!}',
                success: function (response) {
                    response = response.toFixed(1);
                    $('#percent-complete').css('width', response+'%');
                    $('#percent-complete').html(response+'%');
                }
            });
        }

        function checkIfFileExtracted(){
            $.easyAjax({
                type: 'GET',
                url: '{!! route("admin.update-application.checkIfFileExtracted") !!}',
                success: function (response) {
                    checkInstall = false;
                    $('#download-progress').append("<br><i><span class='text-success'>Installed successfully. Reload page to see the changes.</span>.</i>");
                }
            });
        }

        function downloadPercent(){
            updateAreaDiv.append('<hr><div id="download-progress">' +
                'Download Progress<br><div class="progress progress-lg">'+
                '<div class="progress-bar progress-bar-success active progress-bar-striped" role="progressbar" id="percent-complete" ></div>'+
                '</div>' +
                '</div>'
            );
            //getting data
            refreshPercent = window.setInterval(function(){
                getDownloadPercent();
                /// call your function here
            }, 1500);
        }

        function installApp(){
            $.easyAjax({
                type: 'GET',
                url: '{!! route("admin.update-application.install") !!}',
                success: function (response) {
                    $('#download-progress').append("<br><i><span class='text-success'>Installed successfully. Reload page to see the changes.</span>.</i>");
                }
            });
        }
    </script>

@endpush