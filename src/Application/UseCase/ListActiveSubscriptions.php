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
     * Retourne la liste des abonnements actifs d’un client donné.
     *
     * @param int $customerId
     * @return Subscription[]
     * @throws InvalidArgumentException si le customer est introuvable
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
