<?php

namespace Modules\RefReferral\Http\Requests;

use App\Http\Requests\FormRequest;

class RefReferralFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['RCode'] = ['required','unique:RefReferral,RCode'];
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
