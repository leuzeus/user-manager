<?php

namespace App\Infrastructure\InMemory;

use App\Domain\Entity\PricingOption;
use App\Domain\Entity\Product;
use App\Domain\Repository\PricingOptionRepositoryInterface;

class PricingOptionRepository implements PricingOptionRepositoryInterface
{
    private array $options = [];
    private int $counter = 1;

    public function add(Product $product, string $name, int $duration, float $price, string $currency = 'USD'): PricingOption
    {
        $option = new PricingOption($this->counter++, $product, $name, $duration, $price, $currency);
        $this->options[$option->id] = $option;
        return $option;
    }

    public function getById(int $id): ?PricingOption
    {
        return $this->options[$id] ?? null;
    }

    public function findByProduct(Product $product): array
    {
        return array_filter($this->options, fn(PricingOption $opt) => $opt->product->id === $product->id);
    }

    public function all(): array
    {
        return array_values($this->options);
    }
}
