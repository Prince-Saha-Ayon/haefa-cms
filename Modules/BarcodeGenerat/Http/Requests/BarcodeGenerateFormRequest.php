<?php

namespace Modules\BarcodeGenerat\Http\Requests;

use App\Http\Requests\FormRequest;

class BarcodeGenerateFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['mdata_barcode_prefix'] = ['string'];
        $rules['mdata_barcode_number'] = ['string'];
        $rules['mdata_barcode_prefix_number'] = ['string'];
        $rules['mdata_barcode_generate'] = ['string'];
        $rules['mdata_barcode_status'] = ['string'];
        $rules['address'] = ['required'];
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
