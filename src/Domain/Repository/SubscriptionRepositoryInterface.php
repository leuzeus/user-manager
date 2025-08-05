<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Customer;
use App\Domain\Entity\PricingOption;
use App\Domain\Entity\Subscription;

interface SubscriptionRepositoryInterface
{
    public function getById(int $id): ?Subscription;
    public function add(Customer $customer, PricingOption $pricingOption, \DateTimeImmutable $start): Subscription;
    public function getActiveByCustomer(Customer $customer, \DateTimeInterface $now = new \DateTimeImmutable()): array;
    public function getAllByCustomer(Customer $customer): array;
    public function cancel(int $subscriptionId): bool;
    public function all(): array;
}
