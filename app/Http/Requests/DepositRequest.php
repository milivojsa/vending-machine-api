<?php

namespace App\Http\Requests;

use App\Rules\MultipleOf;
use Illuminate\Foundation\Http\FormRequest;

class DepositRequest extends FormRequest
{
    public function rules()
    {
        return [
            'coin' => [
                'required',
                'integer',
                new MultipleOf(5),
                'in:5,10,20,50,100',
            ],
        ];
    }
}
