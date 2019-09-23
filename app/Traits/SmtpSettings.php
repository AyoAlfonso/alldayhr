<?php
/**
 * Created by PhpStorm.
 * User: DEXTER
 * Date: 24/05/17
 * Time: 11:29 PM
 */

namespace App\Traits;

use App\CompanySetting;
use App\EmailSetting;
use Illuminate\Mail\MailServiceProvider;
use Illuminate\Support\Facades\Config;


// Config::set('mail.username', "cc");
// Config::set('mail.password',"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");

trait SmtpSettings{

    public function setMailConfigs(){
        $smtpSetting = EmailSetting::first();
        $settings = CompanySetting::first();
        $company = explode(' ',trim($settings->company_name));

        Config::set('mail.driver', $smtpSetting->mail_driver);
        Config::set('mail.host', $smtpSetting->mail_host);
        Config::set('mail.port', $smtpSetting->mail_port);
        Config::set('mail.username', $smtpSetting->mail_username);
        Config::set('mail.password', $smtpSetting->mail_password);
        Config::set('mail.encryption', $smtpSetting->mail_encryption);
        Config::set('mail.from.name', $smtpSetting->mail_from_name);
        Config::set('mail.from.address', $smtpSetting->mail_from_email);

        Config::set('app.name', $company[0]);
        Config::set('mail.from.name', $company[0]);
        (new MailServiceProvider(app()))->register();
    }

}