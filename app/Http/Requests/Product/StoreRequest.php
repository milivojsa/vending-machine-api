<?php

namespace App\Http\Requests\Product;

use App\Rules\MultipleOf;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'amountAvailable' => ['required', 'integer', 'min:1'],
            'cost' => [
                'required',
                'integer',
                'min:5',
                new MultipleOf(5),
            ],
            'productName' => ['required', 'string'],
            'sellerId' => ['required', 'exists:users,id'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'sellerId' => Auth::id(),
        ]);
    }
}
