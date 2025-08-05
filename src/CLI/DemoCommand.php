<?php

namespace App\CLI;

use App\Application\UseCase\AddUser;
use App\Application\UseCase\AddProduct;
use App\Application\UseCase\AddPricingOption;
use App\Application\UseCase\SubscribeUserToProduct;
use App\Application\UseCase\CancelSubscription;
use App\Application\UseCase\ListActiveSubscriptions;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:demo')]
class DemoCommand extends Command
{
    public function __construct(
        private AddUser $addUser,
        private AddProduct $addProduct,
        private AddPricingOption $addPricing,
        private SubscribeUserToProduct $subscribe,
        private CancelSubscription $cancel,
        private ListActiveSubscriptions $list
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Runs a full demo flow: add user, product, pricing, subscription, then cancel it.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $output->writeln("Demo: creating user...");
            $res = $this->addUser->handle('demo_user', 'customer');
            $user = $res['user'];
            $customer = $res['customer'];
            $output->writeln("Created user #{$user->id} and customer #{$customer->id}");

            $output->writeln("\nAdding product...");
            $product = $this->addProduct->handle('DemoProduct', 'Demo description');
            $output->writeln("Created product #{$product->id}");

            $output->writeln("\nAdding pricing option...");
            $pricing = $this->addPricing->handle($product->id, 'Monthly', 30, 9.99);
            $output->writeln("Created pricing option #{$pricing->id}");

            $output->writeln("\nSubscribing customer...");
            $subscription = $this->subscribe->handle($customer->id, $pricing->id);
            $output->writeln("Subscription #{$subscription->id} active from {$subscription->startDate->format('Y-m-d')} to {$subscription->endDate->format('Y-m-d')}");

            $output->writeln("\nListing active subscriptions...");
            $subs = $this->list->handle($customer->id);
            foreach ($subs as $s) {
                $output->writeln("Subscription #{$s->id} — {$s->pricingOption->name}");
            }

            if(count($subs)){
                $output->writeln('Active subscriptions for customer');
            }

            $output->writeln("\nCancelling subscription...");
            $this->cancel->handle($subscription->id);
            $output->writeln("Subscription #{$subscription->id} cancelled");

            $output->writeln("\nRe-checking active subscriptions...");
            $subs = $this->list->handle($customer->id);
            if (empty($subs)) {
                $output->writeln("No active subscriptions (still valid until {$subscription->endDate->format('Y-m-d')})");
            } else {
                foreach ($subs as $s) {
                    $output->writeln("Subscription #{$s->id} is still active (end: {$s->endDate->format('Y-m-d')})");
                }
            }

            $output->writeln("\nDemo completed");

            return Command::SUCCESS;

        } catch (\Throwable $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
            return Command::FAILURE;
        }
    }
}
