<?php

namespace App\Http\Requests;

use App\Rules\AvailableInStock;
use App\Rules\ValidUserDeposit;
use Illuminate\Foundation\Http\FormRequest;

class BuyRequest extends FormRequest
{
    public function rules()
    {
        return [
            'productId' => ['required', 'integer', 'exists:products,id'],
            'amount' => [
                'required',
                'integer',
                'min:1',
                new AvailableInStock($this->productId),
                new ValidUserDeposit($this->productId),
            ],
        ];
    }
}
