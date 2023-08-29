<?php

namespace Modules\RefMenstruationProduct\Http\Requests;

use App\Http\Requests\FormRequest;

class RefMenstruationProductFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['MenstruationProductCode'] = ['required','unique:RefMenstruationProduct,MenstruationProductCode'];
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
