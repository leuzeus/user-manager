<?php

namespace App\Application\UseCase;

use App\Domain\Entity\User;
use App\Domain\Entity\Customer;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Repository\CustomerRepositoryInterface;

class AddUser
{
    public function __construct(
        private UserRepositoryInterface $users,
        private CustomerRepositoryInterface $customers
    ) {}

    /**
     * Add a user. If role is 'customer', an entity customer is created.
     *
     * @param string $username
     * @param string $role
     * @return array{user: User, customer: Customer|null}
     */
    public function handle(string $username, string $role): array
    {
        $user = $this->users->add($username, $role);

        $customer = null;
        if ($role === 'customer') {
            $customer = $this->customers->add($user, $username); // username = nom du customer
        }

        return ['user' => $user, 'customer' => $customer];
    }
}
