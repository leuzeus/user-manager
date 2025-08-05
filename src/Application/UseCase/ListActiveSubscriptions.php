<?php

namespace App\Application\UseCase;

use App\Domain\Entity\Subscription;
use App\Domain\Repository\CustomerRepositoryInterface;
use App\Domain\Repository\SubscriptionRepositoryInterface;
use InvalidArgumentException;

class ListActiveSubscriptions
{
    public function __construct(
        private CustomerRepositoryInterface $customers,
        private SubscriptionRepositoryInterface $subscriptions
    ) {}

    /**
     * Return a list of active Subscriptions from one user.
     *
     * @param int $customerId
     * @return Subscription[]
     * @throws InvalidArgumentException If the customer is not found
     */
    public function handle(int $customerId): array
    {
        $customer = $this->customers->getById($customerId);

        if (!$customer) {
            throw new InvalidArgumentException("Customer #$customerId not found.");
        }

        return $this->subscriptions->getActiveByCustomer($customer);
    }
}
