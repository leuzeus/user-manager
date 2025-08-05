<?php

namespace App\Domain\Repository\interface;

use App\Domain\Entity\Customer;
use App\Domain\Entity\User;

interface CustomerRepositoryInterface
{
    public function getById(int $id): ?Customer;
    public function getByUserId(int $userId): ?Customer;
    public function add(User $user, string $name): Customer;
    public function all(): array;
}
