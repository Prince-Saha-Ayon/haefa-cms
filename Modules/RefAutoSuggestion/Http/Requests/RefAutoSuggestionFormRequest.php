<?php

namespace Modules\RefAutoSuggestion\Http\Requests;

use App\Http\Requests\FormRequest;

class RefAutoSuggestionFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['AutoSuggestion'] = ['required'];
        $rules['RefAutoSuggestionGroupId'] = ['required'];
      
        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
