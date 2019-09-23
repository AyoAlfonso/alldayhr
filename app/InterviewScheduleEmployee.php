<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class InterviewScheduleEmployee extends Model
{
    use Notifiable;

    public function schedule(){
        return $this->belongsTo(InterviewSchedule::class, 'interview_schedule_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
