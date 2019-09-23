<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CandidateValidator
{
   public static function getMonths(){
       return ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

   }
    public static function UpdateProfileImage()
    {
        return [
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public static function UpdateCV()
    {
        return [
            'cv_file' => 'required|file|mimes:pdf,docx|max:2048',
        ];
    }
    public static function addDocument()
    {
        return [
            'doc_file' => 'required|file|mimes:pdf,docx|max:2048',
            'doc_type' => 'required|exists:document_types,uuid',
        ];
    }

    public static function UpdateProfileDetails()
    {
        return [
            'firstname'=>'required',
            'lastname'=>'required',
            'othername'=>'required',
            'marital_status'=>'required',
            'languages'=>'required|array|min:1',
            'languages.*'=>'required',
            'date_of_birth' => 'required|date|date_format:Y-m-d',
            'gender'=>'required|in:M,F',
            'phone_number'=>'required|numeric',
            'state'=>'required|in:'.implode(",", General::getstates()),
            'residence_state'=>'required|in:'.implode(",", General::getstates()),
            'lga'=>'required',
            'residence_lga'=>'required',
            'nationality'=>'required',
            'landmark' =>'sometimes|nullable',
            'experience_level' =>'required|numeric',
        ];
    }
    public static function UpdateProfileSkills()
    {
        return [
            'nysc_status'=>'required',
        ];
    }

    public static function UpdateCoverLetter()
    {
        return [
            'cover_letter' => 'required'
        ];
    }
    public static function UpdateEducation($data)
    {
        $niceNames = [
            'from_year'=>'Admission Year',
            'to_year'=>'Graduation Year',
            'field_of_study'=>'Course of study',
        ];

        $v =  Validator::make($data,  [
            'institution' => 'required',
            'qualification' => 'required|in:'.implode(",",General::getQualifications()),
            'field_of_study' => 'required',
            'grade' => 'required|in:'.implode(",",General::getGrades()),
            'from_year' => 'required|numeric|digits:4',
            'to_year' => 'required|numeric|digits:4|min:'.(int) (isset($data['from_year']) ? $data['from_year'] : 0),
        ]);
        $v->setAttributeNames($niceNames);

        return $v;
    }

    public static function UpdateWork($data)
    {
        $months = self::getMonths();
        $messages = [
            'to_month.min'    => 'The To Year must be at least '.$months[(int) (isset($data['from_month']) ? $data['from_month'] - 1 : 0)],
        ];
        $v =  Validator::make($data, [
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'job_function' => 'nullable|string',
            'achievements' => 'nullable|string',
            'industry' => 'nullable|in:'.implode(",",General::getIndustries()),
            'from_month' => 'required|numeric|max:12',
            'from_year' => 'required|numeric|digits:4',
            'to_year'=> 'required|numeric|digits:4|min:'.(int) (isset($data['from_year']) ? $data['from_year'] : 0),
            'to_month'=> 'required|numeric|max:12|min:'.(int) (isset($data['from_month']) && $data['from_year'] == $data['to_year'] ? $data['from_month'] : 0),
            //'to_month'=> 'required|numeric|max:12|min:'.(int) (isset($data['from_month']) ? $data['from_month'] : 0),
            'current' => 'required|numeric'
        ],$messages);
        $v->sometimes(['to_month' , 'to_year'], 'required|numeric', function ($input) {
            return (isset($input->current) && empty($input->current) || !isset($input->current));
        });

        return $v;
    }

    public static function addOlevel($request)
    {
        $messages = [
            'subject_1.required'    => 'Your grade in Mathematics is required.',
            'subject_2.required'    => 'Your grade in English is required.',
            'subject_3.required'    => 'Your grade in Subject 3 is required.',
            'subject_4.required'    => 'Your grade in Subject 4 is required.',
            'subject_5.required'    => 'Your grade in Subject 5 is required.',
            'subject_6.required'    => 'Your grade in Subject 6 is required.',
            'subject_7.required'    => 'Your grade in Subject 7 is required.',
            'subject_3_label.required'    => 'Subject 3 is required.',
            'subject_4_label.required'    => 'Subject 4 is required.',
            'subject_5_label.required'    => 'Subject 5 is required.',
            'subject_6_label.required'    => 'Subject 6 is required.',
            'subject_7_label.required'    => 'Subject 7 is required.',
        ];
        return  Validator::make($request->all(), [
            'olevel_type' => 'required',
            'subject_1_label' => 'required',
            'subject_1' => 'required',
            'subject_2_label' => 'required',
            'subject_2' => 'required',
            'subject_3_label' => 'required',
            'subject_3' => 'required',
            'subject_4_label' => 'required',
            'subject_4' => 'required',
            'subject_5_label' => 'required',
            'subject_5' => 'required',
            'subject_6_label' => 'required',
            'subject_6' => 'required',
            'subject_7_label' => 'required',
            'subject_7' => 'required',
            /*
            'subject_3' => Rule::requiredIf(function () use ($request) {
                return !empty($request->input('subject_3_label'));
            }),
            'subject_3_label' => Rule::requiredIf(function () use ($request) {
                return !empty($request->input('subject_3'));
            }),
            'subject_4' => Rule::requiredIf(function () use ($request) {
                return !empty($request->input('subject_4_label'));
            }),
            'subject_4_label' => Rule::requiredIf(function () use ($request) {
                return !empty($request->input('subject_4'));
            }),
            'subject_5' => Rule::requiredIf(function () use ($request) {
                return !empty($request->input('subject_5_label'));
            }),
            'subject_5_label' => Rule::requiredIf(function () use ($request) {
                return !empty($request->input('subject_5'));
            }),
            'subject_6' => Rule::requiredIf(function () use ($request) {
                return !empty($request->input('subject_6_label'));
            }),
            'subject_6_label' => Rule::requiredIf(function () use ($request) {
                return !empty($request->input('subject_6'));
            }),
            'subject_7' => Rule::requiredIf(function () use ($request) {
                return !empty($request->input('subject_7_label'));
            }),
            'subject_7_label' => Rule::requiredIf(function () use ($request) {
                return !empty($request->input('subject_7'));
            }),
            */
        ],$messages);
    }
}
