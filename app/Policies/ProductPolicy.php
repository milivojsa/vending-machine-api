<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->role === 'seller';
    }

    public function update(User $user, Product $product)
    {
        return $user->id === $product->sellerId;
    }

    public function delete(User $user, Product $product)
    {
        return $user->id === $product->sellerId;
    }
}
