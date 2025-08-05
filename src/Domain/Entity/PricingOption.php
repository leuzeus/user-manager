<?php

namespace App\Domain\Entity;

class PricingOption
{
    public function __construct(
        public readonly int $id,
        public readonly Product $product,
        public string $name,            // "Monthly", "Yearly"
        public int $durationInDays,     // e.g. 30, 365
        public float $price,
        public string $currency = 'USD',
        public \DateTimeImmutable $createdAt = new \DateTimeImmutable(),
    ) {}
}