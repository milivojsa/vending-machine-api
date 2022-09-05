<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            'username' => ['required', 'exists:users,username'],
            'password' => ['required'],
        ];
    }
}
