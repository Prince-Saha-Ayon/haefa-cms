<?php

namespace Modules\Gender\Http\Requests;

use App\Http\Requests\FormRequest;

class GenderFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];   
        $rules['GenderCode'] = ['required','string','unique:genders,GenderCode'];
        $rules['Description']              = ['required','string'];
        if(request()->update_id){
            $rules['GenderCode'][2] = 'unique:genders,GenderCode,'.request()->update_id;
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
