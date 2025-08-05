<?php

namespace App\Infrastructure\InMemory;

use App\Domain\Entity\User;
use App\Domain\Repository\interface\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    private array $users = [];
    private int $counter = 0;

    public function add(string $username, string $role): User
    {
        $user = new User($this->counter++, $username, $role);
        $this->users[$user->id] = $user;
        return $user;
    }

    public function getById(int $id): ?User
    {
        return $this->users[$id] ?? null;
    }

    public function all(): array
    {
        return array_values($this->users);
    }
}
