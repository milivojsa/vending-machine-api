<?php

namespace App\Http\Requests\User;

use App\Enums\UserRoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class StoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'username' => ['required', 'string', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6'],
            'role' => [
                'required',
                'string',
                'in:' . implode(',', UserRoleEnum::values())
            ],
        ];
    }

    protected function passedValidation()
    {
        $this->merge([
            'password' => Hash::make($this->password),
        ]);
    }
}
