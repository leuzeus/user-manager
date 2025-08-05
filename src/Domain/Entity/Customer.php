<?php

namespace App\Domain\Entity;

class Customer
{
    public function __construct(
        public readonly int $id,
        public readonly User $user,
        public string $name,
        public \DateTimeImmutable $createdAt = new \DateTimeImmutable(),
    ) {}
}