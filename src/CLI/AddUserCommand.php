<?php

namespace App\CLI;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

#[AsCommand(name: 'app:add-user')]
class AddUserCommand extends Command
{

}
