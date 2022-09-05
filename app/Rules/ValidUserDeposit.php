<?php

namespace App\Rules;

use App\Models\Product;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ValidUserDeposit implements Rule
{
    public function __construct(
        protected ?int $productId
    ){}

    public function passes($attribute, $value)
    {
        $product = Product::find($this->productId);

        $totalCost = $product->cost * $value;

        return  $totalCost <= Auth::user()->deposit;
    }

    public function message()
    {
        return 'The total cost is bigger than user deposit.';
    }
}
