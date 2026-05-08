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

    public function paginate($request): LengthAwarePaginator
    {
        $page = $request->query('page', 1);
        $key = 'products_paginate_' . $request->query('search') . '_stock_' . $request->query('stock') . '_price_' . $request->query('price') . '_page_' . $page;
        $hash = hash('sha256', $key);
        $cache = Cache::get($hash);

        if ($cache) {
            Log::info('Produtos recuperados do cache', [
                'search' => $key,
                'user_id' => auth()->id(),
                'products' => count($cache->items()),
            ]);
            return $cache;
        }
        $response =  $this->repository->paginate($request);
        Cache::put($hash, $response, now()->addHour(2));
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
}
