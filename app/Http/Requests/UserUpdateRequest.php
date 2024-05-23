<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UserUpdateRequest extends FormRequest
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
        $item_id = request()->route('id');

        return [
            'name' => 'sometimes|max:255',
            'surname' => 'sometimes|max:255',
            'patronymic' => 'sometimes|max:255',
            'phone' => 'sometimes|string|unique:users,phone,' . $item_id,
            'email' => 'sometimes|string|email|unique:users,email,' . $item_id,
            'sex' => 'sometimes',
            'birthdate' => 'sometimes|date',
            'password' => 'sometimes',
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


    public function validated($key = null, $default = null)
    {
        $data = $this->validator->validated();
        $this->dd($data);
        if ($this->input('password') && isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($this->input('password'));
        } else {
            unset($data['password']);
        }
        return $data;
    }
}
