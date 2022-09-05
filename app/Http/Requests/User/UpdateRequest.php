<?php

namespace App\Http\Requests\User;

use App\Enums\UserRoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'username' => ['nullable', 'string', 'unique:users,username,' . $this->user->id],
            'password_old' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'required_with:password_old', 'string', 'min:6'],
            'role' => [
                'string',
                'in:' . implode(',', UserRoleEnum::values())
            ],
        ];
    }

    protected function passedValidation()
    {
        if ($this->password) {
            $this->replace([
                'password' => Hash::make($this->password),
            ]);
        }
    }
}
