<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicationStatus extends Model
{
    protected $table = 'application_status';

    public function applications(){
        return $this->hasMany(JobApplication::class, 'status_id')->orderBy('column_priority');
    }
}
