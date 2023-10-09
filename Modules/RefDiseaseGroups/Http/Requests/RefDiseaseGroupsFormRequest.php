<?php

namespace Modules\RefDiseaseGroups\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RefDiseaseGroupsFormRequest extends FormRequest
{
     /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['DiseaseGroupName'] = ['required','unique:RefDiseaseGroups,DiseaseGroupName'];
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
