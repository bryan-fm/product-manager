<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ProductRepository implements ProductRepositoryInterface
{
    public function paginate($request = null): LengthAwarePaginator
    {
        if (is_null($request)) {
            $request = new Request();
        }

        $search = $request->query('search');
        $stock = $request->query('stock');
        $price = $request->query('price');
        return Product::query()
            ->when($search, fn ($query) =>
                $query->where('name', 'like', "%{$search}%")
            )
            ->when(!is_null($stock), fn ($query) =>
                $query->where('stock', $stock)
            )
            ->when(!is_null($price), fn ($query) =>
                $query->where('price', $price)
            )
            ->paginate(10)
            ->withQueryString();
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product;
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }

    public function updateStock(Product $product, int $stock): Product
{
    $product->update([
        'stock' => $stock
    ]);

    return $product->fresh();
}
}