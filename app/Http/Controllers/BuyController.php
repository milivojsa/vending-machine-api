<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuyRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BuyController extends Controller
{
    public function __invoke(BuyRequest $request)
    {
        $this->authorize('buy', User::class);

        $user = Auth::user();
        $product = Product::find($request->productId);

        $totalSpent = $product->cost * $request->amount;

        $user->update([
            'deposit' => $user->deposit - $totalSpent,
        ]);

        $product->update([
            'amountAvailable' => $product->amountAvailable - $request->amount,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Product bought successfully!',
            'data' => [
                'totalSpent' => $totalSpent,
                'product' => new ProductResource($product),
                'change' => $this->calculateChange($user->deposit)
            ],
        ]);
    }

    public function calculateChange($deposit)
    {
        $coins = [100, 50, 20, 10, 5];
        $change = [];

        foreach ($coins as $coin) {
            if ($deposit >= $coin) {
                $coinCount = floor($deposit / $coin);

                for ($i = 0; $i < $coinCount; $i++) {
                    $change[] = $coin;
                    $deposit -= $coin;
                }
            }
        }

        sort($change);

        return $change;
    }
}
