<?php

namespace App\Http\Requests\InterviewSchedule;

use App\Http\Requests\CoreRequest;

class StoreRequest extends CoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "candidate"    => "required",
            "employee.0"      => "required",
            "scheduleDate"    => "required",
            "scheduleTime"    => "required",
        ];
    }

    public function messages()
    {
        return [
            'employee.*' => 'Please select at-least one employee.',
        ];
    }
}
