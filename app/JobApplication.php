<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class JobApplication extends Model
{
    use Notifiable;

    public function job(){
        return $this->belongsTo(Job::class, 'job_id');
    }

    public function candidate(){
        return $this->belongsTo(CandidateInfo::class, 'candidate_id','candidate_id');
    }
    
    //New
    public function work()
    {
        return $this->hasMany(CandidateWorkHistory::class, 'candidate_id', 'candidate_id');
    }

    public function education()
    {
        return $this->hasMany(CandidateEducation::class, 'candidate_id', 'candidate_id');;
    }
    public function test_groups()
    {
        return $this->hasMany(JobApplicationTestGroup::class, 'job_application_id', 'id');;
    }
    public function olevel()
    {
        return $this->hasMany(CandidateOlevel::class, 'candidate_id', 'candidate_id');
    }

    public function candidatescores()
    {
        return $this->hasMany(CandidateScores::class, 'candidate_id', 'candidate_id');
    }

    public function status(){
        return $this->belongsTo(ApplicationStatus::class, 'status_id');
    }

    public function schedule(){
        return $this->hasOne(InterviewSchedule::class)->latest();
    }
}
