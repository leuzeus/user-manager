<?php

namespace App\Tests\Application\UseCase\Application\UseCase;

use App\Application\UseCase\CancelSubscription;
use App\Domain\Entity\Customer;
use App\Domain\Entity\User;
use App\Infrastructure\InMemory\CustomerRepository;
use App\Infrastructure\InMemory\PricingOptionRepository;
use App\Infrastructure\InMemory\ProductRepository;
use App\Infrastructure\InMemory\SubscriptionRepository;
use App\Infrastructure\InMemory\UserRepository;
use PHPUnit\Framework\TestCase;

class CancelSubscriptionTest extends TestCase
{
    private UserRepository $userRepo;
    private CustomerRepository $customerRepo;
    private ProductRepository $productRepo;
    private PricingOptionRepository $pricingRepo;
    private SubscriptionRepository $subscriptionRepo;
    private CancelSubscription $useCase;

    protected function setUp(): void
    {
        $this->userRepo = new UserRepository();
        $this->customerRepo = new CustomerRepository();
        $this->productRepo = new ProductRepository();
        $this->pricingRepo = new PricingOptionRepository();
        $this->subscriptionRepo = new SubscriptionRepository();

        $this->useCase = new CancelSubscription($this->subscriptionRepo);
    }

    public function testItCancelsAnActiveSubscription(): void
    {
        $user = $this->userRepo->add('alice', 'customer');
        $customer = $this->customerRepo->add($user, 'Alice');
        $product = $this->productRepo->add('Newsletter');
        $pricing = $this->pricingRepo->add($product, 'Monthly', 30, 5.99);

        $subscription = $this->subscriptionRepo->add($customer, $pricing, new \DateTimeImmutable());

        $result = $this->useCase->handle($subscription->id);

        $this->assertTrue($result);
        $this->assertFalse($subscription->isActive());
        $this->assertNotNull($subscription->cancelledAt);
    }

    public function testItFailsWithInvalidSubscriptionId(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->useCase->handle(999);
    }
}
