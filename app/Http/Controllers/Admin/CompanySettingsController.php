<?php

namespace App\Http\Controllers\Admin;

use App\CompanySetting;
use App\Helper\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CompanySettingsController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = __('menu.companySettings');
        $this->pageIcon = 'icon-settings';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(! $this->user->can('manage_settings'), 403);

        $this->timezones = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);
        $setting = CompanySetting::first();

        if(!$setting){
            abort(404);
        }

        return view('admin.settings.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        abort_if(! $this->user->can('manage_settings'), 403);

        $setting = CompanySetting::find($id);
        $setting->company_name = $request->input('company_name');
        $setting->company_email = $request->input('company_email');
        $setting->company_phone = $request->input('company_phone');
        $setting->website = $request->input('website');
        $setting->address = $request->input('address');
        $setting->timezone = $request->input('timezone');
        $setting->latitude = $request->input('latitude');
        $setting->longitude = $request->input('longitude');
        $setting->locale = $request->input('locale');

        if ($request->hasFile('logo')) {
            $setting->logo = $request->logo->hashName();
            $request->logo->store('user-uploads/app-logo');
        }

        $setting->save();


        return Reply::redirect(route('admin.settings.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
