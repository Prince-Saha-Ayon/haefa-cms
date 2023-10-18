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
        $rules['PatientId']       = ['nullable'];
        $rules['WorkPlaceId']       = ['nullable'];
        $rules['WorkPlaceBranchId']       = ['nullable'];
        $rules['PatientCode']       = ['nullable'];
        $rules['RegistrationId']       = ['nullable'];
        $rules['GivenName']       = ['nullable'];
        $rules['FamilyName']       = ['nullable'];
        $rules['GenderId']       = ['nullable'];
        $rules['BirthDate']       = ['nullable'];
        $rules['Age']       = ['nullable'];
        $rules['AgeYear']       = ['nullable'];
        $rules['AgeMonth']       = ['nullable'];
        $rules['AgeDay']       = ['nullable'];
        $rules['JoiningDate']       = ['nullable'];
        $rules['ReligionId']       = ['nullable'];
        $rules['RefDepartmentId']       = ['nullable'];
        $rules['RefDesignationId']       = ['nullable'];
        $rules['MaritalStatusId']       = ['nullable'];
        $rules['EducationId']       = ['nullable'];
        $rules['FatherName']       = ['nullable'];
        $rules['MotherName']       = ['nullable'];
        $rules['SpouseName']       = ['nullable'];
        $rules['HeadOfFamilyId']       = ['nullable'];
        $rules['IdNumber']       = ['nullable'];
        $rules['CellNumber']       = ['nullable'];
        $rules['FamilyMembers']       = ['nullable'];
        $rules['ChildrenNumber']       = ['nullable'];
        $rules['ChildAge0To1']       = ['nullable'];
        $rules['ChildAge1To5']       = ['nullable'];
        $rules['ChildAgeOver5']       = ['nullable'];
        $rules['EmailAddress']       = ['nullable'];
        $rules['PatientImage']       = ['nullable'];
        $rules['Status']       = ['nullable'];
        $rules['CreateDate']       = ['nullable'];
        $rules['CreateUser']       = ['nullable'];
        $rules['UpdateDate']       = ['nullable'];
        $rules['UpdateUser']       = ['nullable'];
        $rules['OrgId']       = ['nullable'];
        $rules['IsCalculatedBirthday']       = ['nullable'];
        $rules['usersID']       = ['nullable'];
        $rules['IdType']       = ['nullable'];
        $rules['IdOwner']       = ['nullable'];
        $rules['StationStatus']       = ['nullable'];
        $rules['BarCode']       = ['nullable'];
        $rules['FingerPrint']       = ['nullable'];

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
