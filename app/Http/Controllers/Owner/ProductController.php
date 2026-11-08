<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $productService) {}

    public function index(): Response
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $products = Product::where('created_by', $user->id)
            ->paginate(15);

        return Inertia::render('Owner/MyProducts/Index', [
            'products' => $products,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Owner/MyProducts/Create');
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $this->productService->create($request->validated(), $request->user());

        return redirect()->route('owner.my-products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product): Response
    {
        $this->authorize('update', $product);

        return Inertia::render('Owner/MyProducts/Edit', [
            'product' => $product,
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $this->authorize('update', $product);

        $this->productService->update($product, $request->validated());

        return redirect()->route('owner.my-products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorize('delete', $product);

        $this->productService->delete($product);

        return redirect()->route('owner.my-products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
