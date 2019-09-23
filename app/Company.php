<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $appends = [
        'logo_url'
    ];

    public function getLogoUrlAttribute()
    {
        if (is_null($this->logo)) {
            return asset('logo-not-found.png');
        }
        return asset('user-uploads/company-logo/' . $this->logo);
    }
}
