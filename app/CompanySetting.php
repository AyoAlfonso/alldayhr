<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    protected $appends = [
        'logo_url'
    ];

    public function getLogoUrlAttribute()
    {
        if (is_null($this->logo)) {
            return asset('app-logo.png');
        }
        return asset('user-uploads/app-logo/' . $this->logo);
    }
}
