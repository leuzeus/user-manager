<?php

namespace App\Tests\Application\UseCase;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use App\CLI\AddUserCommand;
use App\CLI\AddProductCommand;
use App\CLI\AddPricingOptionCommand;
use App\CLI\SubscribeCommand;
use App\CLI\CancelSubscriptionCommand;
use App\CLI\ListActiveSubscriptionsCommand;
use App\CLI\DemoCommand;
use App\Infrastructure\InMemory\UserRepository;
use App\Infrastructure\InMemory\CustomerRepository;
use App\Infrastructure\InMemory\ProductRepository;
use App\Infrastructure\InMemory\PricingOptionRepository;
use App\Infrastructure\InMemory\SubscriptionRepository;
use App\Application\UseCase\AddUser;
use App\Application\UseCase\AddProduct;
use App\Application\UseCase\AddPricingOption;
use App\Application\UseCase\SubscribeUserToProduct;
use App\Application\UseCase\CancelSubscription;
use App\Application\UseCase\ListActiveSubscriptions;

class EndToEndCliTest extends TestCase
{
    private Application $application;

    protected function setUp(): void
    {
        $userRepo = new UserRepository();
        $customerRepo = new CustomerRepository();
        $productRepo = new ProductRepository();
        $pricingRepo = new PricingOptionRepository();
        $subscriptionRepo = new SubscriptionRepository();

        $this->application = new Application();

        $this->application->add(new AddUserCommand(new AddUser($userRepo, $customerRepo)));
        $this->application->add(new AddProductCommand(new AddProduct($productRepo)));
        $this->application->add(new AddPricingOptionCommand(new AddPricingOption($productRepo, $pricingRepo)));
        $this->application->add(new SubscribeCommand(new SubscribeUserToProduct($customerRepo, $pricingRepo, $subscriptionRepo)));
        $this->application->add(new CancelSubscriptionCommand(new CancelSubscription($subscriptionRepo)));
        $this->application->add(new ListActiveSubscriptionsCommand(new ListActiveSubscriptions($customerRepo, $subscriptionRepo)));
        $this->application->add(new DemoCommand(
            new AddUser($userRepo, $customerRepo),
            new AddProduct($productRepo),
            new AddPricingOption($productRepo, $pricingRepo),
            new SubscribeUserToProduct($customerRepo, $pricingRepo, $subscriptionRepo),
            new CancelSubscription($subscriptionRepo),
            new ListActiveSubscriptions($customerRepo, $subscriptionRepo)
        ));
    }

    public function testDemoCommand(): void
    {
        $command = $this->application->find('app:demo');
        $tester = new CommandTester($command);

        $exitCode = $tester->execute([]);
        $output = $tester->getDisplay();

        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Demo completed', $output);
        $this->assertStringContainsString('Active subscriptions for customer', $output, 'Subscription added');
        $this->assertStringContainsString('No active subscriptions', $output, 'Subscription deleted');
    }
}
