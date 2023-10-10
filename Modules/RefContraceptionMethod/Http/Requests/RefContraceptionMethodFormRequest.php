<?php

namespace Modules\RefContraceptionMethod\Http\Requests;

use App\Http\Requests\FormRequest;

class RefContraceptionMethodFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['ContraceptionMethodCode'] = ['required','unique:RefContraceptionMethod,ContraceptionMethodCode'];
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
