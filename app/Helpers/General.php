<?php

namespace App\Helpers;

use App\DocumentType;

class General
{
    public static function getNationalities()
    {
        return collect(json_decode(file_get_contents(storage_path('json/nationalities.json'))))->toArray();
    }

    public static function getIndustries()
    {
        return collect(json_decode(file_get_contents(storage_path('json/industry.json'))))->pluck('name')->toArray();
    }

    public static function getUniversities()
    {
        $all_universities = collect(json_decode(file_get_contents(storage_path('json/world_universities_and_domains.json'))))->pluck('name')->toArray();
//        $data = array_merge(json_decode(file_get_contents(storage_path('json/nigerian_universities.json'))),json_decode(file_get_contents(storage_path('json/nigerian_polytechnics.json'))));
        $data = json_decode(file_get_contents(storage_path('json/nigerian_polytechnics.json')));
        $local_uni = collect($data)->pluck('university')->toArray();
        return collect(array_unique(array_merge($all_universities,$local_uni)))->values()->toArray();
        /*
        $data = array_merge(json_decode(file_get_contents(storage_path('json/nigerian_universities.json'))),json_decode(file_get_contents(storage_path('json/nigerian_polytechnics.json'))));
        return collect($data)->pluck('university')->toArray();
        */
    }
    public static function getLanguages()
    {
        return collect(json_decode(file_get_contents(storage_path('json/languages.json'))))->values()->pluck('name')->toArray();
    }

    public static function getstates()
    {
        return collect(json_decode(file_get_contents(storage_path('json/states.json'))))->pluck('state')->pluck('name')->toArray();
    }

    public static function getLgaState($state_sel)
    {
        return collect(json_decode(file_get_contents(storage_path('json/states.json'))))->pluck('state')->filter(function($state) use($state_sel){
            if($state->name === urldecode($state_sel))
                return $state;
        })->pluck('locals')->map(function ($e){
            return collect($e)->map(function($local){
                return $local->name;
            })->toArray();
        });
    }

    public static function getQualifications()
    {
        return collect(json_decode(file_get_contents(storage_path('json/general.json')))->qualifications)->pluck('name')->toArray();
    }

    public static function getGrades()
    {
        return collect(json_decode(file_get_contents(storage_path('json/general.json')))->grades)->pluck('name')->toArray();
    }

    public static function getURLS()
    {
        $routeCollection = \Illuminate\Support\Facades\Route::getRoutes();
        $data = [];
        foreach ($routeCollection as $i => $value) {
            if (in_array('auth', $value->action['middleware'])) {
                if ($value->methods[0] == 'GET')
                    $data[] = $value->uri;
            }
        }
        return $data;
    }

    public static function getNotificationList()
    {
        return config('notifications') ? config('notifications') : [];
    }


    public static function generateBootstrapData(){
        return [
            'document_types'=>DocumentType::where('status','enabled')->get()
        ];
    }

    public static function getRequiredProfileInfo(){
        return [
            'olevel'=>'Olevel',
            'work'=>'Work History',
            'nysc'=>'Nysc',
            'skillsother'=>'Skill & Others',
        ];
    }
}

?>
