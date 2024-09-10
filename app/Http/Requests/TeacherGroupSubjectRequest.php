<?php

namespace App\Http\Requests;

use App\Models\Group;
use App\Models\TeacherGroupSubject;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class TeacherGroupSubjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
             'teacherSubject' => 'required',
             'group' => 'required'
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }


    public function after(): array
    {
        return [
            function (Validator $validator) {
                $input = request()->all();
                $group_id = $input['group'];
                $teacherSubject_id = $input['teacherSubject'];
                if (TeacherGroupSubject::where('group_id', $group_id)->where('teacher_subject_id', $teacherSubject_id)->exists()) {
                    $validator->errors()->add(
                        '',
                        'Преподаватель уже привязан к этой группе.'
                    );
                }
            }
        ];
    }}
