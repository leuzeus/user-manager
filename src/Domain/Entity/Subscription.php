<?php

namespace App\Domain\Entity;

class Subscription
{
    public function __construct(
        public readonly int           $id,
        public readonly Customer      $customer,
        public readonly PricingOption $pricingOption,
        public \DateTimeImmutable     $startDate,
        public \DateTimeImmutable     $endDate,
        public ?\DateTimeImmutable    $cancelledAt = null,
        public bool                   $isCancelled = false,
        public \DateTimeImmutable     $createdAt = new \DateTimeImmutable(),
    ) {}

    public function cancel(): void
    {
        $this->isCancelled = true;
        $this->cancelledAt = new \DateTimeImmutable();
    }

    public function isActive(\DateTimeInterface $date = new \DateTimeImmutable()): bool
    {
        return !($this->isCancelled || $date > $this->endDate);
    }
}
