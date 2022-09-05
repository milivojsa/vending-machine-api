<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BuyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_buy_product()
    {
        $buyer = User::create([
            'username' => 'test',
            'password' => Hash::make('password'),
            'role' => 'buyer',
        ]);

        Auth::login($buyer);

        $this->put('/api/deposit', [
            'coin' => 100,
        ]);

        $product = Product::create([
            'amountAvailable' => 10,
            'cost' => 20,
            'productName' => 'test-product',
            'sellerId' => 1,
        ]);

        $response = $this->put('/api/buy', [
            'productId' => $product->id,
            'amount' => 1,
        ]);

        $response->assertStatus(200);
    }

    public function test_seller_can_buy_product()
    {
        $user = User::create([
            'username' => 'test',
            'password' => Hash::make('password'),
            'role' => 'buyer',
        ]);

        Auth::login($user);

        $this->put('/api/deposit', [
            'coin' => 100,
        ]);

        $product = Product::create([
            'amountAvailable' => 10,
            'cost' => 20,
            'productName' => 'test-product',
            'sellerId' => 1,
        ]);

        $user->update(['role' => 'seller']);

        $response = $this->put('/api/buy', [
            'productId' => $product->id,
            'amount' => 1,
        ]);

        $response->assertStatus(403);
    }

    public function test_product_amount_availability()
    {
        $buyer = User::create([
            'username' => 'test',
            'password' => Hash::make('password'),
            'role' => 'buyer',
        ]);

        Auth::login($buyer);

        $this->put('/api/deposit', [
            'coin' => 100,
        ]);

        $product = Product::create([
            'amountAvailable' => 10,
            'cost' => 5,
            'productName' => 'test-product',
            'sellerId' => 1,
        ]);

        $response = $this->put('/api/buy', [
            'productId' => $product->id,
            'amount' => 11,
        ]);

        $response->assertStatus(422);

        $response = $this->put('/api/buy', [
            'productId' => $product->id,
            'amount' => 10,
        ]);

        $response->assertStatus(200);
    }

    public function test_user_deposit_lower()
    {
        $buyer = User::create([
            'username' => 'test',
            'password' => Hash::make('password'),
            'role' => 'buyer',
        ]);

        Auth::login($buyer);

        $this->put('/api/deposit', [
            'coin' => 5,
        ]);

        $product = Product::create([
            'amountAvailable' => 10,
            'cost' => 5,
            'productName' => 'test-product',
            'sellerId' => 1,
        ]);

        $response = $this->put('/api/buy', [
            'productId' => $product->id,
            'amount' => 10,
        ]);

        $response->assertStatus(422);
    }

    public function test_product_amount_updated()
    {
        $buyer = User::create([
            'username' => 'test',
            'password' => Hash::make('password'),
            'role' => 'buyer',
        ]);

        Auth::login($buyer);

        $this->put('/api/deposit', [
            'coin' => 100,
        ]);

        $product = Product::create([
            'amountAvailable' => 10,
            'cost' => 20,
            'productName' => 'test-product',
            'sellerId' => 1,
        ]);

        $this->put('/api/buy', [
            'productId' => $product->id,
            'amount' => 5,
        ]);

        $product->refresh();

        $this->assertEquals(5, $product->amountAvailable);
    }

    public function test_user_deposit_updated()
    {
        $buyer = User::create([
            'username' => 'test',
            'password' => Hash::make('password'),
            'role' => 'buyer',
        ]);

        Auth::login($buyer);

        $this->put('/api/deposit', [
            'coin' => 100,
        ]);

        $product = Product::create([
            'amountAvailable' => 10,
            'cost' => 20,
            'productName' => 'test-product',
            'sellerId' => 1,
        ]);

        $this->put('/api/buy', [
            'productId' => $product->id,
            'amount' => 1,
        ]);

        $buyer->refresh();

        $this->assertEquals(80, $buyer->deposit);
    }
}
