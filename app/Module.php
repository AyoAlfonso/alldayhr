<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $guarded = ['id'];

    public function permissions(){
        return $this->hasMany('App\Permission', 'module_id');
    }
}
