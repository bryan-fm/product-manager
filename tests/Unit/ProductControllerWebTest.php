<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Inertia\Response as InertiaResponse;
use App\Http\Controllers\ProductController;
use App\Services\ProductService;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\ProductFilterRequest;

class ProductControllerWebTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_returns_inertia_response()
    {
        $service = Mockery::mock(ProductService::class);
        $paginator = new LengthAwarePaginator([], 0, 15, 1);
        $service->shouldReceive('paginate')->once()->with(Mockery::type(ProductFilterRequest::class))->andReturn($paginator);

        $this->app->instance(ProductService::class, $service);

        $controller = $this->app->make(ProductController::class);

        $response = $controller->index(new ProductFilterRequest());

        $this->assertInstanceOf(InertiaResponse::class, $response);
        // Avoid calling Inertia response helper methods in unit tests without Inertia testing package
    }

    public function test_create_returns_inertia_response()
    {
        $service = Mockery::mock(ProductService::class);
        $this->app->instance(ProductService::class, $service);

        $controller = $this->app->make(ProductController::class);
        $response = $controller->create();

        $this->assertInstanceOf(InertiaResponse::class, $response);
    }

    public function test_show_returns_inertia_response()
    {
        $service = Mockery::mock(ProductService::class);
        $this->app->instance(ProductService::class, $service);

        $product = new Product(['name' => 'P', 'price' => 1, 'stock' => 1]);

        $controller = $this->app->make(ProductController::class);
        $response = $controller->show($product);

        $this->assertInstanceOf(InertiaResponse::class, $response);
        // Avoid inspecting inertia props in unit tests
    }

    public function test_edit_returns_inertia_response()
    {
        $service = Mockery::mock(ProductService::class);
        $this->app->instance(ProductService::class, $service);

        $product = new Product(['name' => 'P', 'price' => 1, 'stock' => 1]);

        $controller = $this->app->make(ProductController::class);
        $response = $controller->edit($product);

        $this->assertInstanceOf(InertiaResponse::class, $response);
        // Avoid inspecting inertia props in unit tests
    }

    public function test_store_calls_service_and_redirects()
    {
        $service = Mockery::mock(ProductService::class);

        $payload = [
            'name' => 'New Prod',
            'description' => 'desc',
            'price' => 10,
            'stock' => 5,
        ];

        $product = new Product($payload);

        $service->shouldReceive('create')->once()->andReturn($product);

        $this->app->instance(ProductService::class, $service);

        $request = Mockery::mock(StoreProductRequest::class);
        $request->shouldReceive('validated')->andReturn($payload);

        $controller = $this->app->make(ProductController::class);
        $response = $controller->store($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('Produto criado com sucesso', session()->get('success'));
    }

    public function test_update_calls_service_and_redirects()
    {
        $service = Mockery::mock(ProductService::class);

        $product = new Product(['name' => 'Old', 'price' => 5, 'stock' => 2]);

        $service->shouldReceive('update')->once()->andReturn($product);

        $this->app->instance(ProductService::class, $service);

        $request = Mockery::mock(UpdateProductRequest::class);
        $request->shouldReceive('validated')->andReturn([
            'name' => 'Updated',
            'description' => null,
            'price' => 6,
            'stock' => 3,
        ]);

        $controller = $this->app->make(ProductController::class);
        $response = $controller->update($request, $product);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('Produto atualizado', session()->get('success'));
    }

    public function test_destroy_calls_service_and_redirects()
    {
        $service = Mockery::mock(ProductService::class);
        $service->shouldReceive('delete')->once()->andReturnNull();

        $this->app->instance(ProductService::class, $service);

        $product = new Product(['name' => 'D', 'price' => 1, 'stock' => 1]);

        $controller = $this->app->make(ProductController::class);
        $response = $controller->destroy($product);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('Produto removido', session()->get('success'));
    }

}
