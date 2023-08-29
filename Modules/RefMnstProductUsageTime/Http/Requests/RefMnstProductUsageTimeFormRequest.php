<?php

namespace Modules\RefMnstProductUsageTime\Http\Requests;

use App\Http\Requests\FormRequest;

class RefMnstProductUsageTimeFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['MenstruationProductUsageTimeCode'] = ['required','unique:RefMnstProductUsageTime,MenstruationProductUsageTimeCode'];
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
