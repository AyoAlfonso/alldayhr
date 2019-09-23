<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CandidateOlevelResult extends Model
{
    protected $fillable = [
        'uuid', 'olevel', 'subject', 'grade'
    ];

}
