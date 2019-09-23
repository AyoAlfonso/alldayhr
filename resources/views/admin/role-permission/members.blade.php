
<div class="modal-header">
    <h4 class="modal-title">@lang('modules.permission.addRoleMember')</h4>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
</div>
<div class="modal-body">
    <div class="portlet-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('app.name')</th>
                    <th>Role</th>
                    <th>@lang('app.action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($role->roleuser as $key=>$ruser)
                    <tr id="ruser-{{ $ruser->user->id }}">
                        <td>{{ $key+1 }}</td>
                        <td>{{ ucwords($ruser->user->name) }}</td>
                        <td>{{ ucwords($role->name) }}</td>
                        <td><a href="javascript:;" data-ruser-id="{{ $ruser->user->id }}" class="btn btn-sm btn-danger btn-rounded delete-category">@lang("app.remove")</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">@lang('messages.noRoleMemberFound')</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <hr>
        <form action="" id="createProjectCategory" class="ajax-form" method="POST">
            @csrf
            <input type="hidden" name="role_id" value="{{ $role->id }}">

            <div class="form-body">
                <div class="form-group">
                    <h5>@lang('modules.permission.addMembers')</h5>
                    <select class="select2 m-b-10 select2-multiple" style="width: 100% !important; " multiple="multiple"
                            data-placeholder="Choose Members" name="user_id[]">
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ ucwords($emp->name). ' ['.$emp->email.']' }} @if($emp->id == $user->id)
                                    (YOU) @endif</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" id="save-category" class="btn btn-success"> <i class="fa fa-check"></i> @lang('app.save')</button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('assets/node_modules/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/node_modules/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/node_modules/multiselect/js/jquery.multi-select.js') }}"></script>

<script>
    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    $('.delete-category').click(function () {
        var userId = $(this).data('ruser-id');
        var roleId = '{{ $role->id }}';
        var url = "{{ route('admin.role-permission.detachRole') }}";

        var token = "{{ csrf_token() }}";

        $.easyAjax({
            type: 'POST',
            url: url,
            data: {'_token': token, 'userId': userId, 'roleId': roleId},
            success: function (response) {
                if (response.status == "success") {
                    $.unblockUI();
//                                    swal("Deleted!", response.message, "success");
                    window.location.reload();
                }
            }
        });
    });

    $('#save-category').click(function () {
        $.easyAjax({
            url: '{{route('admin.role-permission.assignRole')}}',
            container: '#createProjectCategory',
            type: "POST",
            data: $('#createProjectCategory').serialize(),
            success: function (response) {
                if(response.status == 'success'){
                    window.location.reload();
                }
            }
        })
    });
</script>