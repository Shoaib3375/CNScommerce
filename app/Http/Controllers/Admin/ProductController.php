<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $productService) {}

    public function index(Request $request): Response
    {
        $products = Product::with('creator')
            ->when($request->query('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
            'search'   => $request->query('search', ''),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Products/Create');
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $this->authorize('create', Product::class);

        $this->productService->create($request->validated(), $request->user());

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product): Response
    {
        return Inertia::render('Admin/Products/Edit', [
            'product' => $product,
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $this->authorize('update', $product);

        $this->productService->update($product, $request->validated());

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorize('delete', $product);

        $this->productService->delete($product);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
