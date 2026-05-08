<?php

namespace App\Http\Controllers\Api;

use App\DTOs\ProductData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ProductResource;
use App\Traits\ApiResponse;
use App\Enums\LogSource;

class ProductController extends Controller
{
    use ApiResponse;

    public function __construct(
        private ProductService $service
    ) {}

    public function index(\App\Http\Requests\ProductFilterRequest $request)
    {
        $products = $this->service->paginate($request);

        return $this->successResponse(
            $products,
            'Produtos listados com sucesso'
        );
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->service->create(
            ProductData::fromArray($request->validated()),
            LogSource::API
        );

        return $this->successResponse(
            $product,
            'Produto criado com sucesso',
            201
        );
    }

    public function show(Product $product): JsonResponse
    {
        return $this->successResponse($product);
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $updatedProduct = $this->service->update(
            $product,
            ProductData::fromArray($request->validated()),
            LogSource::API
        );

        return $this->successResponse(
            $updatedProduct,
            'Produto atualizado com sucesso'
        );
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->service->delete($product, LogSource::API);

        return response()->json([
            'message' => 'Produto removido com sucesso'
        ]);
    }
}