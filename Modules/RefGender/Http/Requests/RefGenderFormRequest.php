<?php

namespace Modules\RefGender\Http\Requests;

use App\Http\Requests\FormRequest;

class RefGenderFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['GenderCode'] = ['required','unique:RefGender,GenderCode'];
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
