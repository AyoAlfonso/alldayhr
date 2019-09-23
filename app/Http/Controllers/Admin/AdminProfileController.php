<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\UpdateProfile;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('menu.myProfile');
        $this->pageIcon = 'ti-user';
    }

    public function index(){
        return view('admin.profile.index', $this->data);
    }

    public function update(UpdateProfile $request){

        $user = User::find($this->user->id);
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

        return Reply::redirect(route('admin.profile.index'), __('menu.myProfile').' '.__('messages.updatedSuccessfully'));
    }
}
