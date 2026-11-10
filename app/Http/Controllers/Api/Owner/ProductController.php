<?php

namespace App\Http\Controllers\Api\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Http\Requests\Owner\StoreProductRequest;
use App\Models\Product;
use App\Models\User;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $productService) {}

    public function index(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $products = Product::where('created_by', $user->id)->paginate(15);

        return response()->json($products);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->create($request->validated(), $request->user());

        return response()->json($product, 201);
    }

    public function edit(Product $product): JsonResponse
    {
        $this->authorize('update', $product);

        return response()->json(['product' => $product]);
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $this->authorize('update', $product);

        $this->productService->update($product, $request->validated());

        return response()->json($product);
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->authorize('delete', $product);

        $this->productService->delete($product);

        return response()->json(null, 204);
    }
}
