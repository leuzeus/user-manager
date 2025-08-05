<?php

namespace App\CLI;

use App\Application\UseCase\ListActiveSubscriptions;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:list-subscriptions')]
class ListActiveSubscriptionsCommand extends Command
{
    public function __construct(private ListActiveSubscriptions $useCase)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('List all active subscriptions for a customer')
            ->addArgument('customerId', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $subscriptions = $this->useCase->handle((int) $input->getArgument('customerId'));

            if (empty($subscriptions)) {
                $output->writeln("ℹ️ No active subscriptions.");
                return Command::SUCCESS;
            }

            foreach ($subscriptions as $sub) {
                $output->writeln("#{$sub->id} | {$sub->pricingOption->name} | {$sub->startDate->format('Y-m-d')} → {$sub->endDate->format('Y-m-d')}");
            }

            return Command::SUCCESS;

        } catch (\Throwable $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
            return Command::FAILURE;
        }
    }
}
