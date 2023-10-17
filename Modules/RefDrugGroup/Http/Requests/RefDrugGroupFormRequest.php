<?php

namespace Modules\RefDrugGroup\Http\Requests;

use App\Http\Requests\FormRequest;

class RefDrugGroupFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['DrugGroupCode'] = ['required','unique:RefDrugGroup,DrugGroupCode'];
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
