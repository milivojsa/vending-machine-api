<?php

namespace App\Rules;

use App\Models\Product;
use Illuminate\Contracts\Validation\Rule;

class AvailableInStock implements Rule
{
    public function __construct(
        protected ?int $productId
    ){}

    public function passes($attribute, $value)
    {
        return Product::find($this->productId)->amountAvailable >= $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The amount is not available in stock.';
    }
}
