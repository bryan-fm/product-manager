<?php

namespace App\DTOs;

class ProductData
{
    public function __construct(
        public string $name,
        public ?string $description,
        public float $price,
        public int $stock
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            description: $data['description'] ?? null,
            price: (float) $data['price'],
            stock: (int) $data['stock'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
        ];
    }
}