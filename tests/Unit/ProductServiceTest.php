<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use App\Models\Product;
use App\Services\ProductService;
use App\Interfaces\ProductRepositoryInterface;
use App\DTOs\ProductData;
use App\Enums\LogSource;

class ProductServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_create_calls_repository_and_returns_product()
    {
        $repo = Mockery::mock(ProductRepositoryInterface::class);
        $service = new ProductService($repo);

        $data = ['name' => 'Prod X', 'description' => 'desc', 'price' => 10.5, 'stock' => 5];

        $product = new Product();
        $product->forceFill($data);
        $product->id = 1;

        $repo->shouldReceive('create')->once()->with($data)->andReturn($product);

        $result = $service->create(ProductData::fromArray($data), LogSource::API);

        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals(1, $result->id);
    }

    public function test_update_calls_repository_and_returns_product()
    {
        $repo = Mockery::mock(ProductRepositoryInterface::class);
        $service = new ProductService($repo);

        $product = new Product();
        $product->forceFill(['name' => 'Old', 'price' => 5, 'stock' => 2]);
        $product->id = 2;

        $data = ['name' => 'Updated', 'description' => null, 'price' => 7.5, 'stock' => 3];

        $repo->shouldReceive('update')->once()->with($product, $data)->andReturn($product);

        $result = $service->update($product, ProductData::fromArray($data), LogSource::API);

        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals(2, $result->id);
    }

    public function test_delete_calls_repository()
    {
        $repo = Mockery::mock(ProductRepositoryInterface::class);
        $service = new ProductService($repo);

        $product = new Product();
        $product->id = 3;

        $repo->shouldReceive('delete')->once()->with($product)->andReturnNull();

        $service->delete($product, LogSource::API);

        $this->assertTrue(true);
    }
}
