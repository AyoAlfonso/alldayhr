<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobSkill extends Model
{
    public function skill(){
        return $this->belongsTo(Skill::class, 'skill_id');
    }
}
