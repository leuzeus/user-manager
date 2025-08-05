<?php

namespace App\Tests\Application\UseCase;

use App\Application\UseCase\SubscribeUserToProduct;
use App\Infrastructure\InMemory\CustomerRepository;
use App\Infrastructure\InMemory\PricingOptionRepository;
use App\Infrastructure\InMemory\ProductRepository;
use App\Infrastructure\InMemory\SubscriptionRepository;
use App\Infrastructure\InMemory\UserRepository;
use PHPUnit\Framework\TestCase;

class SubscribeUserToProductTest extends TestCase
{
    private UserRepository $userRepo;
    private CustomerRepository $customerRepo;
    private ProductRepository $productRepo;
    private PricingOptionRepository $pricingRepo;
    private SubscriptionRepository $subscriptionRepo;
    private SubscribeUserToProduct $useCase;

    protected function setUp(): void
    {
        $this->userRepo = new UserRepository();
        $this->customerRepo = new CustomerRepository();
        $this->productRepo = new ProductRepository();
        $this->pricingRepo = new PricingOptionRepository();
        $this->subscriptionRepo = new SubscriptionRepository();

        $this->useCase = new SubscribeUserToProduct(
            $this->customerRepo,
            $this->pricingRepo,
            $this->subscriptionRepo
        );
    }

    public function testItSubscribesUserToProductSuccessfully(): void
    {
        $user = $this->userRepo->add('john_doe', 'customer');
        $customer = $this->customerRepo->add($user, 'John Doe');
        $product = $this->productRepo->add('Test Product');
        $pricing = $this->pricingRepo->add($product, 'Monthly', 30, 9.99);

        $subscription = $this->useCase->handle($customer->id, $pricing->id);

        $this->assertNotNull($subscription);
        $this->assertSame($customer->id, $subscription->customer->id);
        $this->assertSame($pricing->id, $subscription->pricingOption->id);
        $this->assertTrue($subscription->isActive());
    }

    public function testItFailsWithUnknownCustomer(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->useCase->handle(999, 1);
    }

    public function testItFailsWithUnknownPricing(): void
    {
        $user = $this->userRepo->add('jane_doe', 'customer');
        $customer = $this->customerRepo->add($user, 'Jane Doe');

        $this->expectException(\InvalidArgumentException::class);
        $this->useCase->handle($customer->id, 999);
    }
}
