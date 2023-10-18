<?php

namespace Modules\RefDrug\Http\Requests;

use App\Http\Requests\FormRequest;

class RefDrugFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['DrugCode'] = ['required','unique:RefDrug,DrugCode'];
        $rules['DrugGroupId'] = ['required'];
        $rules['DrugFormId'] = ['required'];
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
