<?php

namespace App\Application\UseCase;

use App\Domain\Repository\SubscriptionRepositoryInterface;
use InvalidArgumentException;

class CancelSubscription
{
    public function __construct(
        private SubscriptionRepositoryInterface $subscriptions
    ) {}

    /**
     * Cancel a subscription.
     *
     * @param int $subscriptionId
     * @return bool true if success, false otherwise
     * @throws InvalidArgumentException if the subscription doesn't exist
     */
    public function handle(int $subscriptionId): bool
    {
        $subscription = $this->subscriptions->getById($subscriptionId);

        if (!$subscription) {
            throw new InvalidArgumentException("Subscription #$subscriptionId not found.");
        }

        return $this->subscriptions->cancel($subscriptionId);
    }
}
