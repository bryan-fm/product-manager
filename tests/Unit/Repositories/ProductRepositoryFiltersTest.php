<?php

namespace Tests\Unit\Repositories;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Repositories\ProductRepository;

class ProductRepositoryFiltersTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_filter_returns_matching_products()
    {
        $repo = new ProductRepository();

        Product::factory()->create(['name' => 'Alpha Product 1', 'price' => 10, 'stock' => 5]);
        Product::factory()->create(['name' => 'Beta Item 1', 'price' => 20, 'stock' => 2]);

        $request = Request::create('/', 'GET', ['search' => 'Alpha']);
        $result = $repo->paginate($request);

        $this->assertEquals(1, $result->total());
        $this->assertEquals('Alpha Product 1', $result->items()[0]->name);
    }

    public function test_stock_filter_returns_matching_products()
    {
        $repo = new ProductRepository();

        Product::factory()->create(['name' => 'P1-1', 'price' => 5, 'stock' => 5]);
        Product::factory()->create(['name' => 'P2-1', 'price' => 6, 'stock' => 2]);

        $request = Request::create('/', 'GET', ['stock' => 5]);
        $result = $repo->paginate($request);

        $this->assertEquals(1, $result->total());
        $this->assertEquals(5, $result->items()[0]->stock);
    }

    public function test_price_filter_returns_matching_products()
    {
        $repo = new ProductRepository();

        Product::factory()->create(['name' => 'Cheap-1', 'price' => 5, 'stock' => 1]);
        Product::factory()->create(['name' => 'Expensive-1', 'price' => 100, 'stock' => 3]);

        $request = Request::create('/', 'GET', ['price' => 100]);
        $result = $repo->paginate($request);

        $this->assertEquals(1, $result->total());
        $this->assertEquals('Expensive-1', $result->items()[0]->name);
    }

    public function test_combined_filters_return_expected_product()
    {
        $repo = new ProductRepository();

        Product::factory()->create(['name' => 'Alpha 1', 'price' => 50, 'stock' => 5]);
        Product::factory()->create(['name' => 'Alpha 2', 'price' => 10, 'stock' => 1]);
        Product::factory()->create(['name' => 'Beta 3', 'price' => 50, 'stock' => 5]);

        $request = Request::create('/', 'GET', ['search' => 'Alpha', 'price' => 50, 'stock' => 5]);
        $result = $repo->paginate($request);

        $this->assertEquals(1, $result->total());
        $this->assertEquals(50, $result->items()[0]->price);
        $this->assertEquals(5, $result->items()[0]->stock);
        $this->assertStringContainsString('Alpha', $result->items()[0]->name);
    }
}
