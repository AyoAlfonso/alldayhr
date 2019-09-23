<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    public function category(){
        return $this->belongsTo(JobCategory::class, 'category_id');
    }

    protected $guarded = ['id'];
}
