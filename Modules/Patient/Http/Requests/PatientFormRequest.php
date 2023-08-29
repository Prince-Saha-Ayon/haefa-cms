<?php

namespace Modules\Patient\Http\Requests;

use App\Http\Requests\FormRequest;

class PatientFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['name'] = ['required','string','unique:patients,name'];
        if(request()->update_id){
            $rules['name'][2] = 'unique:patients,name,'.request()->update_id;
        }
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
