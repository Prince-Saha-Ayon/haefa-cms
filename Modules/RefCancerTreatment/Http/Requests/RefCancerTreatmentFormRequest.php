<?php

namespace Modules\RefCancerTreatment\Http\Requests;

use App\Http\Requests\FormRequest;

class RefCancerTreatmentFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['CancerTreatmentCode'] = ['required'];
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
