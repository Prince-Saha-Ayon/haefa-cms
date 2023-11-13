<?php

namespace Modules\RefLabInvestigation\Http\Requests;

use App\Http\Requests\FormRequest;

class RefLabInvestigationFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['RefLabInvestigationCode'] = ['required'];
        $rules['RefLabInvestigationGroupId'] = ['required'];
        $rules['Investigation'] = ['required'];
      
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
