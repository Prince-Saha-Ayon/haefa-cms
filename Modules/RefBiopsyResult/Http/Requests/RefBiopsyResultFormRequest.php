<?php

namespace Modules\RefBiopsyResult\Http\Requests;

use App\Http\Requests\FormRequest;

class RefBiopsyResultFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['BiopsyResultCode'] = ['required','unique:RefBiopsyResult,BiopsyResultCode'];
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
