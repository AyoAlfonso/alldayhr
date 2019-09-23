<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\SmtpSetting\UpdateSmtpSetting;
use App\Http\Requests\UpdateProfile;
use App\EmailSetting;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AdminSmtpSettingController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('menu.mailSetting');
        $this->pageIcon = 'ti-user';
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        $this->smtpSetting = EmailSetting::first();
        return view('admin.mail-setting.index', $this->data);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function update(UpdateSmtpSetting $request){
        $smtp = EmailSetting::first();

        $smtp->mail_driver     = $request->mail_driver;
        $smtp->mail_host       = $request->mail_host;
        $smtp->mail_port       = $request->mail_port;
        $smtp->mail_username   = $request->mail_username;
        $smtp->mail_password   = $request->mail_password;
        $smtp->mail_from_name  = $request->mail_from_name;
        $smtp->mail_from_email = $request->mail_from_email;
        $smtp->mail_encryption = ($request->mail_encryption == 'none') ? null : $request->mail_encryption;
        $smtp->save();

        return Reply::success(__('messages.mailSettingsUpdated'));
    }

}
