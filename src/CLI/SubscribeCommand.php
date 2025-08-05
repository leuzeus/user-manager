<?php

namespace App\CLI;

use App\Application\UseCase\SubscribeUserToProduct;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:subscribe')]
class SubscribeCommand extends Command
{
    public function __construct(private SubscribeUserToProduct $useCase)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Subscribe a customer to a pricing option')
            ->addArgument('customerId', InputArgument::REQUIRED)
            ->addArgument('pricingOptionId', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $subscription = $this->useCase->handle(
                (int) $input->getArgument('customerId'),
                (int) $input->getArgument('pricingOptionId')
            );

            $output->writeln("Subscription #{$subscription->id} created: {$subscription->startDate->format('Y-m-d')} to {$subscription->endDate->format('Y-m-d')}");
            return Command::SUCCESS;

        } catch (\Throwable $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
            return Command::FAILURE;
        }
    }
}
