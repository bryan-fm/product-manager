<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Repositories\ProductRepository;

class ProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_and_find_product()
    {
        $repo = new ProductRepository();

        $data = [
            'name' => 'Repo Prod',
            'description' => 'From repo',
            'price' => 9.99,
            'stock' => 7,
        ];

        $product = $repo->create($data);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Repo Prod']);
    }

    public function test_update_product()
    {
        $product = Product::factory()->create(['stock' => 5]);

        $repo = new ProductRepository();

        $updated = $repo->update($product, ['name' => 'Updated', 'price' => 12.5, 'stock' => 3]);

        $this->assertEquals('Updated', $updated->name);
        $this->assertEquals(3, $updated->stock);
    }

    public function test_delete_product()
    {
        $product = Product::factory()->create();

        $repo = new ProductRepository();

        $repo->delete($product);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_updateStock_changes_stock()
    {
        $product = Product::factory()->create(['stock' => 2]);

        $repo = new ProductRepository();

        $updated = $repo->updateStock($product, 10);

        $this->assertEquals(10, $updated->stock);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 10]);
    }

    public function test_paginate_returns_length_aware_paginator()
    {
        Product::factory()->count(12)->create();

        $repo = new ProductRepository();

        $paginator = $repo->paginate(null);

        $this->assertInstanceOf(\Illuminate\Contracts\Pagination\LengthAwarePaginator::class, $paginator);
        $this->assertEquals(10, $paginator->perPage());
    }
}
