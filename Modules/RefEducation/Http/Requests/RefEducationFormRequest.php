<?php

namespace Modules\RefEducation\Http\Requests;

use App\Http\Requests\FormRequest;

class RefEducationFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['EducationCode'] = ['required','unique:RefEducation,EducationCode'];
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
