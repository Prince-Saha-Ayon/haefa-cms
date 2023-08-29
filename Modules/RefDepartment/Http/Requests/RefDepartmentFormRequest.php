<?php

namespace Modules\RefDepartment\Http\Requests;

use App\Http\Requests\FormRequest;

class RefDepartmentFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['WorkPlaceId'] = ['required'];
        $rules['DepartmentCode'] = ['required','unique:RefDepartment,DepartmentCode'];
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
