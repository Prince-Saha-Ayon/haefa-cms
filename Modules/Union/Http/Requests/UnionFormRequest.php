<?php

namespace Modules\Union\Http\Requests;

use App\Http\Requests\FormRequest;

class UnionFormRequest extends FormRequest
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
            $rules['name'][2] = 'unique:unions,name,' . request()->id;
        }else{
            $rules['name'] = ['required','unique:unions,name'];
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
