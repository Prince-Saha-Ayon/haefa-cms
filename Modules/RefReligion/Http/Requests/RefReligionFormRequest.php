<?php

namespace Modules\RefReligion\Http\Requests;

use App\Http\Requests\FormRequest;

class RefReligionFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['ReligionCode'] = ['required','unique:RefReligion,ReligionCode'];
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
