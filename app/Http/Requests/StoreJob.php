<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;

class StoreJob extends CoreRequest
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
            'title' => 'required',
            'job_description' => 'required',
            'job_requirement' => 'required',
            'location_id' => 'required',
            'category_id' => 'required',
            'total_positions' => 'required|numeric',
            'start_date' => 'required|date',
            'company' => 'required',
            'end_date' => 'required|date'
        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => __('menu.jobCategories').' '.__('errors.fieldRequired'),
            'location_id.required' => __('menu.locations').' '.__('errors.fieldRequired')
        ];
    }
}
