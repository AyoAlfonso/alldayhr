<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobQuestion extends Model
{
    protected $guarded = ['id'];

    public function job(){
        return $this->belongsTo(Job::class);
    }

    public function question(){
        return $this->belongsTo(Question::class);
    }

    public function getAnswerByQuestion($applicationID){
        $answer =  JobApplicationAnswer::where('job_application_id', $applicationID)
                                ->where('question_id', $this->question_id)->first();
        if($answer){
            return $answer->answer;
        }
        return '';
    }
}
