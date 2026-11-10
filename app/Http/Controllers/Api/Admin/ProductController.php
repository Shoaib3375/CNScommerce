<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $productService) {}

    public function index(Request $request): JsonResponse
    {
        $products = Product::with('creator')
            ->when($request->query('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(15)
            ->withQueryString();

        return response()->json($products);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $this->authorize('create', Product::class);

        $product = $this->productService->create($request->validated(), $request->user());

        return response()->json($product, 201);
    }

    public function edit(Product $product): JsonResponse
    {
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
