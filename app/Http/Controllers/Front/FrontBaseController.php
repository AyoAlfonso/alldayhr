<?php

namespace App\Http\Controllers\Front;

use App\CompanySetting;
use App\LanguageSetting;
use App\ThemeSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

class FrontBaseController extends Controller
{
    /**
     * @var array
     */
    public $data = [];

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->data[$name];
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data[ $name ]);
    }

    /**
     * UserBaseController constructor.
     */
    public function __construct()
    {
        // Inject currently logged in user object into every view of user dashboard

        $this->global = CompanySetting::first();
//        $this->emailSetting = EmailNotificationSetting::all();
        $this->companyName = $this->global->company_name;

        $this->frontTheme = ThemeSetting::first();
        $this->languageSettings = LanguageSetting::where('status', 'enabled')->get();

        App::setLocale($this->global->locale);
        Carbon::setLocale($this->global->locale);
        setlocale(LC_TIME,$this->global->locale.'_'.strtoupper($this->global->locale));

    }
}
