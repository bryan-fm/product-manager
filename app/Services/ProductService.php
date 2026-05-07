<?php

namespace App\Services;

use App\DTOs\ProductData;
use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    public function paginate(?string $search = null): LengthAwarePaginator
    {
        return Product::query()
            ->when($search, fn ($query) =>
                $query->where('name', 'like', "%{$search}%")
            )
            // O 4º parâmetro força o Paginator a ler o 'page' da URL
            ->paginate(5, ['*'], 'page', request()->query('page'))
            ->withQueryString();
    }

    public function create(ProductData $data): Product
    {
        Cache::flush();

        return $this->repository->create($data->toArray());
    }

    public function update(Product $product, ProductData $data): Product
    {
        Cache::flush();

        return $this->repository->update($product, $data->toArray());
    }

    public function delete(Product $product): void
    {
        Cache::flush();

        $this->repository->delete($product);
    }
}