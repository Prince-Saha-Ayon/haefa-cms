<?php

namespace Modules\RefDesignation\Http\Requests;

use App\Http\Requests\FormRequest;

class RefDesignationFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['DesignationTitle'] = ['required','unique:RefDesignation,DesignationTitle'];
        $rules['WorkPlaceId'] = ['required'];
        $rules['RefDepartmentId'] = ['required'];
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
