<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProductTest extends TestCase
{
    protected User $user;
    protected Product $product;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'username' => 'test',
            'password' => Hash::make('password'),
            'role' => 'seller',
        ]);

        Auth::login($this->user);

        $this->product = Product::create([
            'amountAvailable' => 10,
            'cost' => 20,
            'productName' => 'test-product',
            'sellerId' => $this->user->id,
        ]);
    }

    public function test_products_can_be_fetched()
    {
        $response = $this->get('/api/product');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'amountAvailable',
                    'cost',
                    'productName',
                    'sellerId',
                ],
            ],
        ]);
    }

    public function test_product_can_be_fetched()
    {
        $response = $this->get('/api/product/' . $this->product->id);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'amountAvailable',
                'cost',
                'productName',
                'sellerId',
                'created_at',
                'updated_at',
            ],
        ]);
        $response->assertJsonCount(1);
    }

    public function test_product_can_be_created()
    {
        $response = $this->post('/api/product', [
            'amountAvailable' => 1,
            'cost' => 50,
            'productName' => 'another-product',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'amountAvailable' => 1,
            'cost' => 50,
            'productName' => 'another-product',
        ]);
    }

    public function test_buyer_cannot_create_product()
    {
        $buyer = User::create([
            'username' => 'buyer-user',
            'password' => Hash::make('password'),
            'role' => 'buyer',
        ]);

        Auth::login($buyer);

        $response = $this->post('/api/product', [
            'amountAvailable' => 1,
            'cost' => 50,
            'productName' => 'another-product',
        ]);

        $response->assertStatus(403);
    }

    public function test_product_cannot_be_created_with_invalid_data()
    {
        $response = $this->post('/api/product', [
            'amountAvailable' => -400,
            'cost' => 50,
            'productName' => 'another-product',
        ]);

        $response->assertStatus(422);

        $response = $this->post('/api/product', [
            'amountAvailable' => 1,
            'cost' => 50,
        ]);

        $response->assertStatus(422);

        $response = $this->post('/api/product', [
            'amountAvailable' => 4,
            'cost' => -50,
            'productName' => 'another-product',
        ]);

        $response->assertStatus(422);

        $response = $this->post('/api/product', [
            'amountAvailable' => 4,
            'cost' => 33,
            'productName' => 'another-product',
        ]);

        $response->assertStatus(422);
    }

    public function test_product_is_updated()
    {
        $response = $this->put('/api/product/' . $this->product->id, [
            'amountAvailable' => 2,
            'cost' => 35,
            'productName' => 'updated-name-test-product',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'amountAvailable' => 2,
            'cost' => 35,
            'productName' => 'updated-name-test-product',
        ]);
    }

    public function test_product_cannot_be_updated_with_invalid_data()
    {
        $response = $this->put('/api/product/' . $this->product->id, [
            'amountAvailable' => -400,
        ]);

        $response->assertStatus(422);

        $response = $this->put('/api/product/' . $this->product->id, [
            'cost' => -50,
        ]);

        $response->assertStatus(422);

        $response = $this->put('/api/product/' . $this->product->id, [
            'productName' => '',
        ]);

        $response->assertStatus(422);

        $response = $this->put('/api/product/' . $this->product->id, [
            'amountAvailable' => 'test',
            'cost' => 'test',
        ]);

        $response->assertStatus(422);
    }

    public function test_product_can_be_deleted()
    {
        $product = Product::create([
            'amountAvailable' => 55,
            'cost' => 55,
            'productName' => 'to-be-deleted-product',
            'sellerId' => $this->user->id,
        ]);

        $response = $this->delete('/api/product/' . $product->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('products', [
            'amountAvailable' => 55,
            'cost' => 55,
            'productName' => 'to-be-deleted-product',
            'sellerId' => $this->user->id,
        ]);
    }
}
