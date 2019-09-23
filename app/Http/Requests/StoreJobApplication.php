<?php

namespace App\Http\Requests;

use App\Question;
use Illuminate\Foundation\Http\FormRequest;

class StoreJobApplication extends CoreRequest
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
        $rules = [
            'full_name' => 'required',
            'email' => 'email|required',
            'phone' => 'required',
            'resume' => 'required',
        ];

        $answers = $this->get('answer');
        if(isset($answers) && !empty($this->get('answer')))
        {
            foreach($this->get('answer') as $key => $value){

                $answer = Question::where('id', $key)->first();
                if($answer->required == 'yes')
                    $rules["answer.{$key}"] = 'required';
            }
        }

        return $rules;

    }

    public function messages()
    {
        return [
            'answer.*' => 'This field is required.'
        ];
    }
}
