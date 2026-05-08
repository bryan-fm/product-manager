<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;

class ProductModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_fillable_and_create()
    {
        $product = Product::factory()->create([
            'name' => 'ModelProd',
            'price' => 2.5,
            'stock' => 1,
        ]);

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'ModelProd']);
    }
}
