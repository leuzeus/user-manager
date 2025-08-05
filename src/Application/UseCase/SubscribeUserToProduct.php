<?php

namespace App\Application\UseCase;

use App\Domain\Entity\Subscription;
use App\Domain\Repository\CustomerRepositoryInterface;
use App\Domain\Repository\PricingOptionRepositoryInterface;
use App\Domain\Repository\SubscriptionRepositoryInterface;
use InvalidArgumentException;

class SubscribeUserToProduct
{
    public function __construct(
        private CustomerRepositoryInterface $customers,
        private PricingOptionRepositoryInterface $pricingOptions,
        private SubscriptionRepositoryInterface $subscriptions
    ) {}

    /**
     * Add a subscription for a user with a pricing option
     *
     * @param int $customerId
     * @param int $pricingOptionId
     * @return Subscription
     * @throws InvalidArgumentException if one IDs is invalid
     */
    public function handle(int $customerId, int $pricingOptionId): Subscription
    {
        $customer = $this->customers->getById($customerId);
        $pricing = $this->pricingOptions->getById($pricingOptionId);

        if (!$customer) {
            throw new InvalidArgumentException("Customer #$customerId not found.");
        }

        if (!$pricing) {
            throw new InvalidArgumentException("Pricing option #$pricingOptionId not found.");
        }

        $startDate = new \DateTimeImmutable();
        return $this->subscriptions->add($customer, $pricing, $startDate);
    }
}
