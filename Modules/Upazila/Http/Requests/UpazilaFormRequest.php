<?php

namespace Modules\Upazila\Http\Requests;

use App\Http\Requests\FormRequest;

class UpazilaFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        if(request()->id){
            $rules['name'][2] = 'unique:upazilas,name,' . request()->id;
        }else{
            $rules['name'] = ['required','unique:upazilas,name'];
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
