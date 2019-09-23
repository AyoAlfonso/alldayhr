<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class CandidateRequestFormatter
{

    public static function updateProfileDetails($data)
    {
        $candidate = Auth::guard('candidate')->user();
        $data['candidate_id'] = $candidate->candidate_id;
        /*
        if(isset($data['certifications'])) {
            $data['certifications'] = json_encode(explode(",", $data['certifications']));
        }
        if(isset($data['skills'])) {
            $data['skills'] = json_encode(explode(",", $data['skills']));
        }
        if(isset($data['languages'])) {
            $data['languages'] = json_encode(explode(",", $data['languages']));
        }
        */
        if(isset($data['certifications'])){
            $data['certifications'] = isset($data['certifications']) && array_filter($data['certifications']) ?  json_encode($data['certifications']) : null;
        }
        if(isset($data['skills'])) {
            $data['skills'] = isset($data['skills']) && array_filter($data['skills']) ? json_encode($data['skills']) : null;
        }
        if(isset($data['languages'])) {
            $data['languages'] = isset($data['languages']) && array_filter($data['languages']) ? json_encode($data['languages']) : null;
        }

        return $data;
    }

    public static function addCandidateID($data)
    {
        $data['candidate_id'] = Auth::guard('candidate')->user()->candidate_id;
        return $data;
    }


}
