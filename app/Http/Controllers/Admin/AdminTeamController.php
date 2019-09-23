<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\StoreTeam;
use App\Http\Requests\UpdateTeam;
use App\Mail;
use App\Notifications\NewUser;
use App\Role;
use App\RoleUser;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Tests\HttpCache\StoreTest;
use Yajra\DataTables\Facades\DataTables;
use App\CompanySetting;

class AdminTeamController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('menu.team');
        $this->pageIcon = 'icon-people';
    }

    public function index(){
        abort_if(! $this->user->can('view_team'), 403);

        $this->users = User::all();
        return view('admin.team.index', $this->data);
    }

    public function create(){
        abort_if(! $this->user->can('add_team'), 403);

        $this->roles = Role::all();
        return view('admin.team.create', $this->data);
    }

    public function store(StoreTeam $request){
        abort_if(! $this->user->can('add_team'), 403);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        if ($request->hasFile('image')) {
            $user->image = $request->image->hashName();
            $request->image->store('user-uploads/profile');
        }
        $user->save();

        //attach role
        $user->roles()->attach($request->role_id);

        //send notification
//        $user->notify(new NewUser($request->password));
//        $data = ['user' => ' This is message !'];
//        Mail::to($user->email)->send(new testMessage($data));
        return Reply::redirect(route('admin.team.index'), __('menu.team').' '.__('messages.createdSuccessfully'));
    }

    public function edit($id){
        abort_if(! $this->user->can('edit_team'), 403);

        if($id == $this->user->id){
            abort(403);
        }

        $this->roles = Role::all();
        $this->team = User::find($id);
        return view('admin.team.edit', $this->data);
    }

    public function update(UpdateTeam $request, $id){
        abort_if(! $this->user->can('edit_team'), 403);

        if($id == $this->user->id){
            abort(403);
        }

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;

        if($request->password != ''){
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('image')) {
            $user->image = $request->image->hashName();
            $request->image->store('user-uploads/profile');
        }
        $user->save();

        //attach role
        RoleUser::where('user_id', $id)->delete();
        $user->roles()->attach($request->role_id);

        return Reply::redirect(route('admin.team.index'), __('menu.team').' '.__('messages.updatedSuccessfully'));
    }


    public function data() {
        abort_if(! $this->user->can('view_team'), 403);

        $users = User::allAdmins();
        $roles = Role::all();

        return DataTables::of($users)
            ->addColumn('action', function ($row) {
                $action = '';

                //do not allow user to change own details
                if($row->id == $this->user->id){
                    return $action;
                }

                if( $this->user->can('edit_team')){
                    $action.= '<a href="' . route('admin.team.edit', [$row->id]) . '" class="btn btn-primary btn-circle"
                      data-toggle="tooltip" data-original-title="'.__('app.edit').'"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }

                if( $this->user->can('delete_team')){
                    $action.= ' <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-row-id="' . $row->id . '" data-original-title="'.__('app.delete').'"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }
                return $action;
            })
            ->addColumn('role_name', function ($row) use ($roles) {

                //do not allow user to change own role
                if($row->id == $this->user->id){
                    return $row->role->role->display_name;
                }

                if(!$this->user->can('edit_team')){
                    return $row->role->role->display_name;
                }

                $roleOption = '<select name="role_id" class="form-control role_id" data-row-id="'.$row->id.'">';
                foreach ($roles as $role){
                    $roleOption.= '<option ';

                    if($row->role->role->id == $role->id){
                        $roleOption.= ' selected ';
                    }

                    $roleOption.= 'value="'.$role->id.'">'.ucwords($role->display_name).'</option>';
                }
                $roleOption.= '</select>';
                return $roleOption;
            })
            ->editColumn('name', function ($row) {
                return '<img src='.$row->profile_image_url.' class="img-circle" width="25" /> '.ucwords($row->name);
            })
            ->editColumn('email', function ($row) {
                return $row->email;
            })
            ->rawColumns(['name', 'action', 'role_name'])
            ->addIndexColumn()
            ->make(true);
    }

    public function destroy($id)
    {
        abort_if(! $this->user->can('delete_team'), 403);

        User::destroy($id);
        return Reply::success(__('messages.recordDeleted'));
    }

    public function changeRole(Request $request){
        //attach role
        $user = User::find($request->teamId);

        RoleUser::where('user_id', $request->teamId)->delete();
        $user->roles()->attach($request->roleId);

        return Reply::dataOnly(['status' => 'success']);
    }

    public function showCandidateLoginForm()
    {
        return view('candidate.auth.login');
    }

    public function showCandidateRegisterForm()
    {
        $setting = CompanySetting::first();
        return view('candidate.auth.registration')->with(
            ['setting' => $setting]
        );
    }
    
}
