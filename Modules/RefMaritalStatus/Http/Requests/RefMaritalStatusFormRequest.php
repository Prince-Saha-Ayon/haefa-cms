<?php

namespace Modules\RefMaritalStatus\Http\Requests;

use App\Http\Requests\FormRequest;

class RefMaritalStatusFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['MaritalStatusCode'] = ['required','unique:RefMaritalStatus,MaritalStatusCode'];
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
