<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class CandidateOlevel extends Model
{
    protected $fillable = [
        'uuid', 'type','candidate_id'
    ];
    public function results()
    {
        return $this->hasMany(CandidateOlevelResult::class, 'olevel', 'uuid');
    }
    public function checkOlevelID($value)
    {
        $olevel = Self::where('uuid', $value)->where('candidate_id', Auth::guard('candidate')->user()->candidate_id)->first();
        if (!$olevel)
            abort(404);
        return $olevel;
    }

    public function createOlevel($request)
    {
        $data['uuid'] = Uuid::uuid4()->toString();
        $data['type'] = $request->input('olevel_type');
        $data['candidate_id'] = Auth::guard('candidate')->user()->candidate_id;
        $olevel = self::create($data);
        if ($olevel) {
            $results = [];
            for ($i = 1; $i <= 7; $i++) {
                $results[] = [
                    'uuid' => Uuid::uuid4()->toString(),
                    'olevel' => $olevel->uuid,
                    'subject' => $request->input('subject_'.$i.'_label'),
                    'grade' => $request->input('subject_'.$i),
                ];
            }
            CandidateOlevelResult::insert($results);
        }
        return $olevel;
    }
    public function updateOlevel($request)
    {
        $data['type'] = $request->input('olevel_type');
        $olevel = $this->update($data);
        if ($olevel) {
            $this->results()->delete();
            $results = [];
            for ($i = 1; $i <= 7; $i++) {
                $results[] = [
                    'uuid' => Uuid::uuid4()->toString(),
                    'olevel' => $this->uuid,
                    'subject' => $request->input('subject_'.$i.'_label'),
                    'grade' => $request->input('subject_'.$i),
                ];
            }
            CandidateOlevelResult::insert($results);
        }
        return $olevel;
    }
}
