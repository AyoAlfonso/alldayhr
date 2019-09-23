<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    protected $guarded = ['id'];

    public function skills(){
        return $this->hasMany(Skill::class, 'category_id');
    }
}
