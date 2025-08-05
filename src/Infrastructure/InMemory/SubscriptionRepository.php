<?php

namespace App\Infrastructure\InMemory;

use App\Domain\Entity\Customer;
use App\Domain\Entity\PricingOption;
use App\Domain\Entity\Subscription;
use App\Domain\Repository\SubscriptionRepositoryInterface;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    private array $subscriptions = [];
    private int $counter = 1;

    public function add(Customer $customer, PricingOption $pricingOption, \DateTimeImmutable $start): Subscription
    {
        $end = $start->modify("+{$pricingOption->durationInDays} days");
        $subscription = new Subscription($this->counter++, $customer, $pricingOption, $start, $end);
        $this->subscriptions[$subscription->id] = $subscription;
        return $subscription;
    }

    public function getById(int $id): ?Subscription
    {
        return $this->subscriptions[$id] ?? null;
    }

    public function getActiveByCustomer(Customer $customer, \DateTimeInterface $now = new \DateTimeImmutable()): array
    {
        return array_filter($this->subscriptions, function (Subscription $s) use ($customer, $now) {
            return $s->customer->id === $customer->id && $s->isActive($now);
        });
    }

    public function getAllByCustomer(Customer $customer): array
    {
        return array_values(array_filter($this->subscriptions, function (Subscription $s) use ($customer) {
            return $s->customer->id === $customer->id;
        }));
    }

    public function cancel(int $subscriptionId): bool
    {
        if (!isset($this->subscriptions[$subscriptionId])) return false;
        $this->subscriptions[$subscriptionId]->cancel();
        return true;
    }

    public function all(): array
    {
        return array_values($this->subscriptions);
    }
}
