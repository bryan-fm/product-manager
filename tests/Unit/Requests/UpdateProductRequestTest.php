<?php

namespace Tests\Feature\Requests;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;

class UpdateProductRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create());
    }

    #[Test]
    public function it_fails_validation_when_data_is_invalid()
    {
        $product = Product::factory()->create();

        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => '',
            'description' => str_repeat('a', 300),
            'price' => -10,
            'stock' => -5,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
                'description',
                'price',
                'stock',
            ]);
    }

    #[Test]
    public function it_passes_validation_with_valid_data()
    {
        $product = Product::factory()->create();

        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => 'Produto Teste',
            'description' => 'Descrição válida',
            'price' => 19.99,
            'stock' => 100,
        ]);

        $response->assertStatus(200);
    }

    #[Test]
    public function it_requires_unique_name_excluding_current_product()
    {

        $productA = Product::factory()->create([
            'name' => 'Produto A'
        ]);

        $productB = Product::factory()->create([
            'name' => 'Produto B'
        ]);

        $response = $this->putJson("/api/products/{$productB->id}", [
            'name' => 'Produto A',
            'description' => 'Test',
            'price' => 10,
            'stock' => 5,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }
}