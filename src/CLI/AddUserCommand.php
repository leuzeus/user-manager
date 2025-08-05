<?php

namespace App\CLI;

use App\Application\UseCase\AddUser;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:add-user')]
class AddUserCommand extends Command
{
    public function __construct(private AddUser $useCase)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Create a user (and customer if role=customer)')
            ->addArgument('username', InputArgument::REQUIRED)
            ->addArgument('role', InputArgument::OPTIONAL, 'Role of user (e.g. customer)', 'customer');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $result = $this->useCase->handle(
                $input->getArgument('username'),
                $input->getArgument('role')
            );

            $user = $result['user'];
            $customer = $result['customer'];

            $output->writeln("User #{$user->id} created with role '{$user->role}'");

            if ($customer) {
                $output->writeln("Linked customer #{$customer->id} created for user.");
            }

            return Command::SUCCESS;

        } catch (\Throwable $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
            return Command::FAILURE;
        }
    }
}
