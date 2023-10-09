<?php

namespace Modules\RefBloodGroup\Http\Requests;

use App\Http\Requests\FormRequest;

class RefBloodGroupFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['BloodGroupCode'] = ['required','unique:RefBloodGroup,BloodGroupCode'];
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
