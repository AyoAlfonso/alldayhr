<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class CandidateEducation extends Model
{
    protected $fillable = [
        'uuid','candidate_id', 'institution', 'grade', 'qualification', 'field_of_study', 'from_year', 'to_year',
    ];

    public function checkEducationID($value)
    {
        $edu = Self::where('uuid', $value)->where('candidate_id', Auth::guard('candidate')->user()->candidate_id)->first();
        if (!$edu)
            abort(404);
        return $edu;
    }

    public function createEducation($data)
    {
        $data['uuid'] = Uuid::uuid4()->toString();
        return self::create($data);
    }

    public function updateEducation($id, $data)
    {
        $edu = $this->checkEducationID($id);
        return $edu->update($data);
    }


}
