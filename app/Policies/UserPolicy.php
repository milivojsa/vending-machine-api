<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function view(User $user, User $model)
    {
        return $user->id === $model->id;
    }

    public function update(User $user, User $model)
    {
        return $user->id === $model->id;
    }

    public function delete(User $user, User $model)
    {
        return $user->id === $model->id;
    }

    public function deposit(User $user)
    {
        return $user->role === 'buyer';
    }

    public function buy(User $user)
    {
        return $user->role === 'buyer';
    }

    public function reset(User $user)
    {
        return $user->role === 'buyer';
    }
}
