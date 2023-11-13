<?php

namespace Modules\RefProvisionalDiagnosisGroup\Http\Requests;

use App\Http\Requests\FormRequest;

class RefProvisionalDiagnosisGroupFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['RefProvisionalDiagnosisGroupCode'] = ['required'];
        $rules['CommonTerm'] = ['required'];
        $rules['Category'] = ['required'];
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
