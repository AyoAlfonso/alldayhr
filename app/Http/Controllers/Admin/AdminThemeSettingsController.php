<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\ThemeSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminThemeSettingsController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('menu.themeSettings');
        $this->pageIcon = 'ti-settings';
    }

    public function index(){
        return view('admin.theme-settings.index', $this->data);
    }

    public function store(Request $request){
        $theme = ThemeSetting::first();
        $theme->primary_color = $request->primary_color;
        $theme->front_custom_css =  $request->front_custom_css;
        $theme->admin_custom_css =  $request->admin_custom_css;
        $theme->save();

        return Reply::redirect(route('admin.theme-settings.index'), __('menu.themeSettings').' '.__('messages.updatedSuccessfully'));
    }
}
