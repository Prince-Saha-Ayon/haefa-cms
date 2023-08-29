<?php

namespace Modules\BarcodeFormat\Http\Requests;

use App\Http\Requests\FormRequest;

class BarcodeFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['barcode_prefix'] = ['required','string','unique:barcode_formats,barcode_prefix'];
        if(request()->update_id){
            $rules['barcode_prefix'][2] = 'unique:barcode_formats,barcode_prefix,'.request()->update_id;
        }
        $rules['barcode_district'] = ['string'];
        $rules['barcode_upazila'] = ['string'];
        $rules['barcode_union'] = ['string'];
        $rules['barcode_community_clinic'] = ['string'];
        $rules['barcode_number'] = ['required'];
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
