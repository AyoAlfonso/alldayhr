@extends('layouts.app')

@push('head-script')
    <link rel="stylesheet" href="{{ asset('assets/node_modules/switchery/dist/switchery.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/node_modules/icheck/skins/all.css') }}">
    <link href="{{ asset('assets/node_modules/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/node_modules/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/node_modules/multiselect/css/multi-select.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-sm-12">
                        <a href="javascript:;" id="addRole" class="btn btn-success btn-sm btn-outline  waves-effect waves-light "><i class="fa fa-gear"></i> @lang("modules.roles.addRole")</a>
                    </div>

                    @foreach($roles as $role)
                        <div class="col-md-12 b-all mt-2">
                            <div class="row">
                                <div class="col-md-4 text-center p-2 bg-dark ">
                                    <h5 class="text-white mt-2 mb-2"><strong>{{ ucwords($role->display_name) }}</strong></h5>
                                </div>
                                <div class="col-md-4 text-center bg-dark role-members">
                                    {{--<button class="btn btn-xs btn-danger btn-rounded show-members" data-role-id="{{ $role->id }}"><i class="fa fa-users"></i> {{ count($role->roleuser)  }} Member(s)</button>--}}
                                </div>
                                <div class="col-md-4 p-2 bg-dark" style="padding-bottom: 11px !important;">
                                    <button class="btn btn-default btn-sm btn-rounded pull-right toggle-permission" data-role-id="{{ $role->id }}"><i class="fa fa-key"></i> Permissions</button>
                                </div>


                                <div class="col-md-12 b-t permission-section" style="display: none;" id="role-permission-{{ $role->id }}" >
                                    <table class="table ">
                                        <thead>
                                        <tr class="bg-white">
                                            <th>
                                                <div class="form-group">
                                                    <input type="checkbox" value="{{ $role->id }}" class=" select_all_permission" id="select_all_permission_{{ $role->id }}"
                                                           @if(count($role->permissions) == $totalPermissions) checked @endif
                                                    >
                                                    <label for="select_all_permission_{{ $role->id }}">@lang('modules.permission.selectAll')</label>
                                                </div>
                                            </th>
                                            <th>@lang('app.add')</th>
                                            <th>@lang('app.view')</th>
                                            <th>@lang('app.update')</th>
                                            <th>@lang('app.delete')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($modules as $module)
                                            <tr>
                                                <td>{{ ucwords($module->module_name) }}

                                                    @if($module->description != '')
                                                        <a class="mytooltip" href="javascript:void(0)"> <i class="fa fa-info-circle"></i><span class="tooltip-content5"><span class="tooltip-text3"><span class="tooltip-inner2">{{ $module->description  }}</span></span></span></a>
                                                    @endif
                                                </td>

                                                @foreach($module->permissions as $permission)
                                                    <td>
                                                        <div class="switchery-demo">
                                                            <input type="checkbox"
                                                                   @if($role->hasPermission([$permission->name]))
                                                                   checked
                                                                   @endif
                                                                   class="js-switch assign-role-permission permission_{{ $role->id }}" data-size="small" data-color="#00c292" data-permission-id="{{ $permission->id }}" data-role-id="{{ $role->id }}" />
                                                        </div>
                                                    </td>
                                                @endforeach

                                                @if(count($module->permissions) < 4)
                                                    @for($i=1; $i<=(4-count($module->permissions)); $i++)
                                                        <td>&nbsp;</td>
                                                    @endfor
                                                @endif

                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    {{--Ajax Modal Start for--}}
    <div class="modal fade bs-modal-md in" id="managePermissionModal" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" id="modal-data-application">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <span class="caption-subject font-red-sunglo bold uppercase" id="modelHeading"></span>
                </div>
                <div class="modal-body">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn blue">Save changes</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{--Ajax Modal Ends--}}
@endsection

@push('footer-script')
    <script src="{{ asset('assets/node_modules/switchery/dist/switchery.min.js') }}"></script>
    <script>
        $(function () {
            $('.assign-role-permission').on('change', assignRollPermission);
        });

        $('.toggle-permission').click(function () {
            var roleId = $(this).data('role-id');
            $('#role-permission-'+roleId).toggle();
        })

        // Switchery
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());
        });

        // Initialize multiple switches
        var animating = false;
        var masteranimate = false;

        var assignRollPermission = function () {

            var roleId = $(this).data('role-id');
            var permissionId = $(this).data('permission-id');

            if($(this).is(':checked'))
                var assignPermission = 'yes';
            else
                var assignPermission = 'no';

            var url = '{{route('admin.role-permission.store')}}';

            $.easyAjax({
                url: url,
                type: "POST",
                data: { 'roleId': roleId, 'permissionId': permissionId, 'assignPermission': assignPermission, '_token': '{{ csrf_token() }}' }
            })
        };

        $('.assign-role-permission').change(assignRollPermission());

        $('.select_all_permission').change(function () {
            if($(this).is(':checked')){
                var roleId = $(this).val();
                var url = '{{ route('admin.role-permission.assignAllPermission') }}';

                $.easyAjax({
                    url: url,
                    type: "POST",
                    data: { 'roleId': roleId, '_token': '{{ csrf_token() }}' },
                    success: function () {
                        masteranimate = true;
                        if (!animating){
                            var masterStatus = true;
                            $('.assign-role-permission').off('change');
                            $('input.permission_'+roleId).each(function(index){
                                var switchStatus = $('input.permission_'+roleId)[index].checked;
                                if(switchStatus != masterStatus){
                                    $(this).trigger('click');
                                }
                            });
                            $('.assign-role-permission').on('change', assignRollPermission);
                        }
                        masteranimate = false;
                    }
                })
            }
            else{
                var roleId = $(this).val();
                var url = '{{ route('admin.role-permission.removeAllPermission') }}';

                $.easyAjax({
                    url: url,
                    type: "POST",
                    data: { 'roleId': roleId, '_token': '{{ csrf_token() }}' },
                    success: function () {
                        masteranimate = true;
                        if (!animating){
                            var masterStatus = false;
                            $('.assign-role-permission').off('change');
                            $('input.permission_'+roleId).each(function(index){
                                var switchStatus = $('input.permission_'+roleId)[index].checked;
                                if(switchStatus != masterStatus){
                                    $(this).trigger('click');
                                }
                            });
                            $('.assign-role-permission').on('change', assignRollPermission);
                        }
                        masteranimate = false;
                    }
                })
            }
        })

        $('.show-members').click(function () {
            var id = $(this).data('role-id');
            var url = '{{ route('admin.role-permission.showMembers', ':id')}}';
            url = url.replace(':id', id);

            $('#modelHeading').html('Role Members');
            $.ajaxModal('#managePermissionModal', url);
        })

        $('#addRole').click(function () {
            var url = '{{ route('admin.role-permission.create')}}';

            $('#modelHeading').html('Role Members');
            $.ajaxModal('#managePermissionModal', url);
        })
    </script>

@endpush