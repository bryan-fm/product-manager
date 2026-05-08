<?php

namespace Tests\Feature\Integration;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;

class ProductIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $this->actingAs(User::factory()->create());
    }

    #[Test]
    public function user_can_create_product_and_persist_in_database()
    {
        $this->authenticate();

        $payload = [
            'name' => 'Produto Integração',
            'description' => 'Produto criado via teste',
            'price' => 99.90,
            'stock' => 10,
        ];

        $response = $this->postJson('/api/products', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'price',
                    'stock',
                ],
                'message'
            ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Produto Integração',
            'price' => 99.90,
        ]);
    }

    #[Test]
    public function product_creation_fails_and_does_not_persist_invalid_data()
    {
        $this->authenticate();

        $response = $this->postJson('/api/products', [
            'name' => '',
            'price' => -10,
            'stock' => -5,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'price', 'stock']);

        $this->assertDatabaseCount('products', 0);
    }

    #[Test]
    public function user_can_update_product_successfully()
    {
        $this->authenticate();

        $product = \App\Models\Product::factory()->create();

        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => 'Produto Atualizado',
            'description' => 'Nova descrição',
            'price' => 120,
            'stock' => 50,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Produto Atualizado',
            ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Produto Atualizado',
        ]);
    }

    #[Test]
    public function user_can_delete_product_successfully()
    {
        $this->authenticate();

        $product = \App\Models\Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }

    #[Test]
    public function user_can_list_products_after_creation()
    {

        $this->authenticate();

        \App\Models\Product::factory()->count(3)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ]);

        $this->assertCount(3, $response->json('data')['data']);
    }

    #[Test]
    public function product_name_must_be_unique()
    {
        $this->authenticate();

        \App\Models\Product::factory()->create([
            'name' => 'Produto Único'
        ]);

        $response = $this->postJson('/api/products', [
            'name' => 'Produto Único',
            'price' => 10,
            'stock' => 5,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }
}