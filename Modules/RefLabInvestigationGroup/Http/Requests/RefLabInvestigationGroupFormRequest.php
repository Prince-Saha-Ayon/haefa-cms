<?php

namespace Modules\RefLabInvestigationGroup\Http\Requests;

use App\Http\Requests\FormRequest;

class RefLabInvestigationGroupFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['RefLabInvestigationGroupCode'] = ['required','unique:RefLabInvestigationGroup,RefLabInvestigationGroupCode'];
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
