<?php

namespace App\Infrastructure\InMemory;

use App\Domain\Entity\Customer;
use App\Domain\Entity\User;
use App\Domain\Repository\interface\CustomerRepositoryInterface;

class CustomerRepository implements CustomerRepositoryInterface
{
    private array $customers = [];
    private int $counter = 0;

    public function add(User $user, string $name): Customer
    {
        $customer = new Customer($this->counter++, $user, $name);
        $this->customers[$customer->id] = $customer;
        return $customer;
    }

    public function getById(int $id): ?Customer
    {
        return $this->customers[$id] ?? null;
    }

    public function getByUserId(int $userId): ?Customer
    {
        foreach ($this->customers as $customer) {
            if ($customer->user->id === $userId) {
                return $customer;
            }
        }
        return null;
    }

    public function all(): array
    {
        return array_values($this->customers);
    }
}
