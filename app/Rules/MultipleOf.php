<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MultipleOf implements Rule
{
    public function __construct(
        protected int $number
    ) {}

    public function passes($attribute, $value)
    {
        return (int) $value % $this->number === 0;
    }

    public function message()
    {
        return 'The :attribute should be in multiples of 5.';
    }
}
