<?php

namespace App\Infrastructure\InMemory;

use App\Domain\Entity\Product;
use App\Domain\Repository\interface\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    private array $products = [];
    private int $counter = 0;

    public function add(string $name, ?string $description = null): Product
    {
        $product = new Product($this->counter++, $name, $description);
        $this->products[$product->id] = $product;
        return $product;
    }

    public function getById(int $id): ?Product
    {
        return $this->products[$id] ?? null;
    }

    public function all(): array
    {
        return array_values($this->products);
    }
}
