<?php

namespace App\CLI;

use App\Application\UseCase\AddProduct;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:add-product')]
class AddProductCommand extends Command
{
    public function __construct(private AddProduct $useCase)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Add a new product')
            ->addArgument('name', InputArgument::REQUIRED)
            ->addArgument('description', InputArgument::OPTIONAL, 'Optional description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $product = $this->useCase->handle(
                $input->getArgument('name'),
                $input->getArgument('description')
            );

            $output->writeln("Product #{$product->id} created: '{$product->name}'");
            return Command::SUCCESS;

        } catch (\Throwable $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
            return Command::FAILURE;
        }
    }
}
