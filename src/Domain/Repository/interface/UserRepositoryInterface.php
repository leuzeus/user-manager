<?php

namespace App\Domain\Repository\interface;

use App\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function getById(int $id): ?User;
    public function add( string $username, string $role): User;
    public function all(): array;
}
