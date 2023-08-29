<?php

namespace Modules\RefChiefComplain\Http\Requests;

use App\Http\Requests\FormRequest;

class RefChiefComplainFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['CCCode'] = ['required','unique:RefChiefComplain,CCCode'];
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
