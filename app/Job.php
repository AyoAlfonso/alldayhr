<?php

namespace App;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use Sluggable;

    protected $dates = ['end_date', 'start_date'];

    public function category(){
        return $this->belongsTo(JobCategory::class, 'category_id');
    }

    public function location(){
        return $this->belongsTo(JobLocation::class, 'location_id');
    }
    // public function locations(){
    //     return $this->hasMany(JobLocation::class, 'location_id');
    // }
    public function skills(){
        return $this->hasMany(JobSkill::class, 'job_id');
    }

    public function company(){
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => ['title', 'location.location']
            ]
        ];
    }

    public static function activeJobs($limit){
      $jobs = Job::where('status', 'active')
            ->where('start_date', '<=', Carbon::now()->format('Y-m-d'))
            ->where('end_date', '>=', Carbon::now()->format('Y-m-d'));
            if($limit){
              $jobs->limit($limit);
            }
            return $jobs;
    }

    public static function activeJobsLimited($limit){
        $jobs = Job::where('status', 'active');
        if($limit){
            $jobs->limit($limit);
          }
          return $jobs;
            // ->get();
    }
    

    public static function activeJobsCount(){
        return Job::where('status', 'active')
            ->where('start_date', '<=', Carbon::now()->format('Y-m-d'))
            ->where('end_date', '>=', Carbon::now()->format('Y-m-d'))
            ->count();
    }

    public function question(){
        return $this->hasMany(JobQuestion::class);
    }

    public function getJobRolesAttribute(){
        return $this->job_roles_json && is_array(json_decode($this->job_roles_json)) ? json_decode($this->job_roles_json) : [];
    }

    public function getRequiredInfoAttribute(){
        return $this->required_info_json && is_array(json_decode($this->required_info_json)) ? json_decode($this->required_info_json) : [];
    }
    public function getRequiredDocsAttribute(){
        return $this->required_docs_json && is_array(json_decode($this->required_docs_json)) ? json_decode($this->required_docs_json) : [];
    }

}
