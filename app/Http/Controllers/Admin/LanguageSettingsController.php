<?php

namespace App\Http\Controllers\Admin;

use App\CompanySetting;
use App\Helper\Reply;
use App\LanguageSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LanguageSettingsController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('app.language').' '.__('menu.settings');
        $this->pageIcon = 'icon-settings';
    }

    public function index(){
        $this->languages = LanguageSetting::all();
        return view('admin.language-settings.index', $this->data);
    }

    public function update(Request $request,$id){
        $setting = LanguageSetting::find($request->id);
        $setting->status = $request->status;
        $setting->save();

        return Reply::success(__('app.language').' '.__('menu.settings').' '.__('messages.updatedSuccessfully'));
    }

    public function changeLanguage(Request $request) {
        $setting = CompanySetting::first();
        $setting->locale = $request->input('lang');
        $setting->save();

        return Reply::success('Language changed successfully.');
    }
}
