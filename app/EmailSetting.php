<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class EmailSetting extends Authenticatable
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $table = 'smtp_settings';

    protected $hidden = [
        'mail_password'
    ];

}
