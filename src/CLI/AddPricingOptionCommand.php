<?php

namespace App\CLI;

use App\Application\UseCase\AddPricingOption;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:add-pricing')]
class AddPricingOptionCommand extends Command
{
    public function __construct(private AddPricingOption $useCase)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Add a pricing option to a product')
            ->addArgument('productId', InputArgument::REQUIRED)
            ->addArgument('name', InputArgument::REQUIRED)
            ->addArgument('duration', InputArgument::REQUIRED)
            ->addArgument('price', InputArgument::REQUIRED)
            ->addArgument('currency', InputArgument::OPTIONAL, 'Default: USD', 'USD');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $pricing = $this->useCase->handle(
                (int) $input->getArgument('productId'),
                $input->getArgument('name'),
                (int) $input->getArgument('duration'),
                (float) $input->getArgument('price'),
                $input->getArgument('currency')
            );

            $output->writeln("Pricing #{$pricing->id} added: '{$pricing->name}' for {$pricing->price} {$pricing->currency} ({$pricing->durationInDays} days)");
            return Command::SUCCESS;

        } catch (\Throwable $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
            return Command::FAILURE;
        }
    }
}
