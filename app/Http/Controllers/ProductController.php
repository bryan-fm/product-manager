<?php

namespace App\Http\Controllers;

use App\DTOs\ProductData;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $service
    ) {}

    public function index(Request $request)
    {
        return Inertia::render('Products/Index', [
            'products' => $this->service->paginate(
                $request->search
            ),
            'filters' => $request->only('search')
        ]);
    }

    public function create()
    {
        return Inertia::render('Products/Create');
    }

    public function store(StoreProductRequest $request)
    {
        $this->service->create(
            ProductData::fromArray($request->validated())
        );

        return redirect()
            ->route('products.index')
            ->with('success', 'Produto criado com sucesso');
    }

    public function edit(Product $product)
    {
        return Inertia::render('Products/Edit', [
            'product' => $product
        ]);
    }

    public function update(
        UpdateProductRequest $request,
        Product $product
    ) {
        $this->service->update(
            $product,
            ProductData::fromArray($request->validated())
        );

        return redirect()
            ->route('products.index')
            ->with('success', 'Produto atualizado');
    }

    public function destroy(Product $product)
    {
        $this->service->delete($product);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produto removido');
    }

    public function show(Product $product)
    {
        return Inertia::render('Products/Show', [
            'product' => $product
        ]);
    }
}