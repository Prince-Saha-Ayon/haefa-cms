<?php

namespace Modules\RefVaccineAdult\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RefVaccineAdultRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['VaccineCode'] = ['required','unique:RefVaccineAdult,VaccineCode'];
        $rules['VaccineDoseNumber'] = ['required'];
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
