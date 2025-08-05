<?php

namespace App\Domain\Repository;

use App\Domain\Entity\PricingOption;
use App\Domain\Entity\Product;

interface PricingOptionRepositoryInterface
{
    public function getById(int $id): ?PricingOption;
    public function findByProduct(Product $product): array;
    public function add(Product $product, string $name, int $duration, float $price, string $currency = 'USD'): PricingOption;
    public function all(): array;
}
