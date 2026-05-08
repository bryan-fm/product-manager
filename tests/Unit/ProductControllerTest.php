<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use App\Services\ProductService;
use App\Http\Requests\ProductFilterRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Api\ProductController;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\DTOs\ProductData;
use App\Enums\LogSource;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_returns_success_response()
    {
        $this->mock(ProductService::class, function ($mock) {
            $paginator = new LengthAwarePaginator([], 0, 15, 1);
            $mock->shouldReceive('paginate')->once()->with(Mockery::type(ProductFilterRequest::class))->andReturn($paginator);
        });

        $controller = $this->app->make(ProductController::class);
        $response = $controller->index(new ProductFilterRequest());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_store_calls_service_and_returns_created()
    {
        $data = [
            'name' => 'Test Product',
            'description' => 'Desc',
            'price' => 10.5,
            'stock' => 5,
        ];

        $product = new Product($data);

        $this->mock(ProductService::class, function ($mock) use ($data, $product) {
            $mock->shouldReceive('create')->once()->with(
                Mockery::on(function ($arg) use ($data) {
                    return $arg instanceof ProductData && $arg->name === $data['name'];
                }),
                LogSource::API
            )->andReturn($product);
        });

        $request = Mockery::mock(StoreProductRequest::class);
        $request->shouldReceive('validated')->andReturn($data);

        $controller = $this->app->make(ProductController::class);
        $response = $controller->store($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function test_show_returns_product()
    {
        $product = new Product([
            'name' => 'P',
            'price' => 1,
            'stock' => 1,
        ]);

        $controller = $this->app->make(ProductController::class);
        $response = $controller->show($product);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_update_calls_service_and_returns_success()
    {
        $product = new Product([
            'name' => 'Old',
            'price' => 5,
            'stock' => 2,
        ]);

        $data = [
            'name' => 'New Name',
            'description' => 'Updated',
            'price' => 20,
            'stock' => 10,
        ];

        $this->mock(ProductService::class, function ($mock) use ($product, $data) {
            $mock->shouldReceive('update')->once()->with(
                $product,
                Mockery::on(function ($arg) use ($data) {
                    return $arg instanceof ProductData && $arg->name === $data['name'];
                }),
                LogSource::API
            )->andReturn($product);
        });

        $request = Mockery::mock(UpdateProductRequest::class);
        $request->shouldReceive('validated')->andReturn($data);

        $controller = $this->app->make(ProductController::class);
        $response = $controller->update($request, $product);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_destroy_calls_service_and_returns_message()
    {
        $product = new Product([
            'name' => 'ToDelete',
            'price' => 1,
            'stock' => 1,
        ]);

        $this->mock(ProductService::class, function ($mock) use ($product) {
            $mock->shouldReceive('delete')->once()->with($product, LogSource::API)->andReturnNull();
        });

        $controller = $this->app->make(ProductController::class);
        $response = $controller->destroy($product);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $data = $response->getData(true);
        $this->assertArrayHasKey('message', $data);
    }
    
}
