<?php

namespace App\Http\Controllers;

use App\CompanySetting;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\App;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct() {
        $this->global = CompanySetting::first();
        App::setLocale($this->global->locale);
        Carbon::setLocale($this->global->locale);
        setlocale(LC_TIME,$this->global->locale.'_'.strtoupper($this->global->locale));

    }
}
