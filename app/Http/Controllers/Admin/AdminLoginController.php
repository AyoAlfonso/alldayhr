<?php

namespace App\Http\Controllers\Admin;

use App\CompanySetting;
use App\ThemeSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends AdminBaseController
{
    public function getLogin()
    {
        $setting = CompanySetting::first();
        $frontTheme = ThemeSetting::first();
        return view('auth.login', compact('setting', 'frontTheme'));
    }

    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            if(Auth::user()->hasRole('candidate')){
                Auth::logout();
                return redirect()->back()->withInput()->withErrors(['email'=>'Could not login with credentials']);
            }
            return redirect()->route('admin.dashboard');
        }
        return redirect()->back()->withInput()->withErrors(['email'=>'Could not login with credentials']);

    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }

}
