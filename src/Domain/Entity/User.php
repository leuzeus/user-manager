<?php

namespace App\Domain\Entity;

class User
{
    public function __construct(
        public readonly int $id,
        public string $username,
        public string $role,
        public \DateTimeImmutable $createdAt = new \DateTimeImmutable(),
    ) {}
}
