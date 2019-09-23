<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobApplicationAnswer extends Model
{
    protected $guarded = ['id'];

    public function job(){
        return $this->belongsTo(Job::class);
    }

    public function jobApplication(){
        return $this->belongsTo(JobApplication::class);
    }

    public function question(){
        return $this->belongsTo(Question::class);
    }
}
