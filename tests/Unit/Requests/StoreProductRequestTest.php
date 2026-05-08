<?php

namespace Tests\Feature\Requests;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use App\Models\User;

class StoreProductRequestTest extends TestCase
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
        $response = $this->postJson('/api/products', [
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
        $response = $this->postJson('/api/products', [
            'name' => 'Produto Teste 2',
            'description' => 'Descrição válida',
            'price' => 19.99,
            'stock' => 100,
        ]);

        $response->assertStatus(201); // ou 200 dependendo do seu controller
    }

    #[Test]
    public function it_requires_name_price_and_stock()
    {
        $response = $this->postJson('/api/products', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
                'price',
                'stock',
            ]);
    }
}