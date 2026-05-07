<?php
namespace App\Interfaces;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

// interface ProductRepositoryInterface 
// {
//     public function getAll();
//     public function findById($id);
//     public function store(array $data);
//     public function update($id, array $data);
//     public function delete($id);
// }


interface ProductRepositoryInterface
{
    public function paginate(?string $search = null): LengthAwarePaginator;

    public function create(array $data): Product;

    public function update(Product $product, array $data): Product;

    public function delete(Product $product): void;
}