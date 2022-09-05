<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return ProductResource::collection(Product::paginate());
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function store(StoreRequest $request)
    {
        $this->authorize('create', Product::class);

        Product::create($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Product created successfully!',
        ]);
    }

    public function update(UpdateRequest $request, Product $product)
    {
        $this->authorize('update', [$product]);

        $product->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully!',
        ]);
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', [$product]);

        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully!',
        ]);
    }
}
