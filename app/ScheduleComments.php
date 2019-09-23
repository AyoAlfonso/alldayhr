<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduleComments extends Model
{
    protected $dates = ['created_at'];
    protected $table = 'interview_schedule_comments';
    // Relation with job application
    public function jobApplication(){
        return $this->belongsTo(InterviewSchedule::class);
    }

    // Relation with user
    public function user(){
        return $this->belongsTo(User::class);
    }


}
