<?php

namespace App\CLI;

use App\Application\UseCase\CancelSubscription;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:cancel-subscription')]
class CancelSubscriptionCommand extends Command
{
    public function __construct(private CancelSubscription $useCase)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Cancel a subscription by ID')
            ->addArgument('subscriptionId', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $id = (int) $input->getArgument('subscriptionId');
            $success = $this->useCase->handle($id);

            if ($success) {
                $output->writeln("Subscription #$id has been cancelled.");
                return Command::SUCCESS;
            }

            $output->writeln("<error>Failed to cancel subscription #$id</error>");
            return Command::FAILURE;

        } catch (\Throwable $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
            return Command::FAILURE;
        }
    }
}
