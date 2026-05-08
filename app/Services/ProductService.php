<?php

namespace App\Services;

use App\DTOs\ProductData;
use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Enums\LogSource;

class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    public function paginate(?string $search = null): LengthAwarePaginator
    {
        $page = request()->query('page', 1);
        $cache = Cache::get('products_paginate_' . $search . '_page_' . $page);

        if ($cache) {
            Log::info('Produtos recuperados do cache', [
                'search' => $search,
                'user_id' => auth()->id(),
                'products' => count($cache->items()),
            ]);
            return $cache;
        }
        $response =  Product::query()
            ->when($search, fn ($query) =>
                $query->where('name', 'like', "%{$search}%")
            )
            ->paginate(10, ['*'], 'page', $page)
            ->withQueryString();
        Cache::put('products_paginate_' . $search . '_page_' . $page, $response, now()->addHour(2));
        return $response;
    }

    public function create(ProductData $data, LogSource $source): Product
    {
        Cache::flush();

        $product = $this->repository->create($data->toArray());

            Log::info('Produto criado', [
                'source' => $source->value,
                'product_id' => $product->id,
                'user_id' => auth()->id(),
            ]);

        return $product;
    }

    public function update(Product $product, ProductData $data, LogSource $source): Product
    {
        Cache::flush();

        $product = $this->repository->update($product, $data->toArray());

        Log::info('Produto atualizado', [
            'source' => $source->value,
            'product_id' => $product->id,
            'user_id' => auth()->id(),
        ]);

        return $product;
    }

    public function delete(Product $product, LogSource $source): void
    {
        Cache::flush();

        $this->repository->delete($product);

        Log::info('Produto deletado', [
            'source' => $source->value,
            'product_id' => $product->id,
            'user_id' => auth()->id(),
        ]);
    }

    public function changeStock(
        Product $product,
        int $quantity,
        string $type
        ): Product {

        $newStock = $product->stock;

        if ($type === 'increase') {
            $newStock += $quantity;
        }

        if ($type === 'decrease') {

            if ($product->stock < $quantity) {
                throw new BusinessException('Insufficient stock');
            }

            $newStock -= $quantity;
        }

        return $this->repository->updateStock(
            $product,
            $newStock
        );
    }
}