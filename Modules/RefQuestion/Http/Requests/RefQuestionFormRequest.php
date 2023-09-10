<?php

namespace Modules\RefQuestion\Http\Requests;

use App\Http\Requests\FormRequest;

class RefQuestionFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['QuestionModuleName'] = ['required','unique:RefQuestion,QuestionModuleName'];
        $rules['QuestionTypeId'] = ['required'];
        $rules['QuestionGroupId'] = ['required'];
        $rules['AnswerTitle'] = ['required'];

        $numAns = count($this->AnswerTitle);
        for($n=0;$n<$numAns;$n++) {
            $rules['AnswerTitle.' . $n] = 'required';
        }

        $numAnsGroup = count($this->AnswerGroupId);
        for($n=0;$n<$numAnsGroup;$n++) {
            $rules['AnswerGroupId.' . $n] = 'required';
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
