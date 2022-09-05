<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DepositTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_deposit()
    {
        $user = User::create([
            'username' => 'test',
            'password' => Hash::make('password'),
            'role' => 'buyer',
        ]);

        Auth::login($user);

        $response = $this->put('/api/deposit', [
            'coin' => 5,
        ]);

        $response->assertStatus(200);
    }

    public function test_seller_can_add_deposit()
    {
        $user = User::create([
            'username' => 'test',
            'password' => Hash::make('password'),
            'role' => 'seller',
        ]);

        Auth::login($user);

        $response = $this->put('/api/deposit', [
            'coin' => 5,
        ]);

        $response->assertStatus(403);
    }

    public function test_any_coin_can_be_added()
    {
        $user = User::create([
            'username' => 'test',
            'password' => Hash::make('password'),
            'role' => 'buyer',
        ]);

        Auth::login($user);

        $response = $this->put('/api/deposit', [
            'coin' => 33,
        ]);

        $response->assertStatus(422);
    }
}
