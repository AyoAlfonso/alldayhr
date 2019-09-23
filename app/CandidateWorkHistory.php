<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class CandidateWorkHistory extends Model
{
    protected $fillable = [
        'uuid', 'candidate_id', 'title', 'company', 'location', 'from_year', 'from_month', 'to_year', 'to_month', 'current', 'job_function', 'achievements', 'industry',
    ];

    public function checkWorkID($value)
    {

        $work = Self::where('uuid', $value)->where('candidate_id', Auth::guard('candidate')->user()->candidate_id)->first();
        if (!$work)
            abort(404);
        return $work;
    }

    public function createWork($data)
    {
        $data['uuid'] = Uuid::uuid4()->toString();
        if (isset($data['current']) && $data['current'] == '1') {
            $this->resetAllCurrentWork();
        }
        return self::create($data);
    }

    private function resetAllCurrentWork()
    {
        self::where('candidate_id', Auth::guard('candidate')->user()->candidate_id)->update(['current' => 0]);
    }

    public function updateWork($id, $data)
    {
        if (isset($data['current']) && $data['current'] == '1') {
            $this->resetAllCurrentWork();
        }
        $work = $this->checkWorkID($id);
        return $work->update($data);
    }

}
