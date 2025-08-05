<?php

namespace App\Domain\Repository\interface;

use App\Domain\Entity\Product;

interface ProductRepositoryInterface
{
    public function getById(int $id): ?Product;
    public function add(string $name, ?string $description = null): Product;
    public function all(): array;
}
