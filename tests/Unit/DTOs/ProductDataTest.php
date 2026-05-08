<?php

namespace Tests\Unit\DTOs;

use Tests\TestCase;
use App\DTOs\ProductData;

class ProductDataTest extends TestCase
{
    public function test_from_array_and_to_array()
    {
        $data = [
            'name' => 'DTO Prod',
            'description' => 'dto',
            'price' => '15.75',
            'stock' => '4',
        ];

        $dto = ProductData::fromArray($data);

        $this->assertEquals('DTO Prod', $dto->name);
        $this->assertEquals(15.75, $dto->price);
        $this->assertEquals(4, $dto->stock);

        $arr = $dto->toArray();

        $this->assertIsArray($arr);
        $this->assertArrayHasKey('name', $arr);
    }
}
