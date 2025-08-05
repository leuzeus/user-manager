<?php

namespace App\Domain\Repository;

use App\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function getById(int $id): ?User;
    public function add(string $username, string $role): User;
    public function remove(int $id): void;
    public function all(): array;
}
