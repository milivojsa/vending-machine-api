<?php

namespace App\Http\Requests\Product;

use App\Rules\MultipleOf;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'amountAvailable' => ['sometimes', 'integer', 'min:0'],
            'cost' => [
                'sometimes',
                'integer',
                'min:5',
                new MultipleOf(5),
            ],
            'productName' => ['sometimes', 'string', 'min:3'],
        ];
    }
}
