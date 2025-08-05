<?php

namespace App\Domain\Entity;

class Product
{
    public function __construct(
        public readonly int $id,
        public string $name,
        public ?string $description = null,
        public \DateTimeImmutable $createdAt = new \DateTimeImmutable(),
    ) {}
}
