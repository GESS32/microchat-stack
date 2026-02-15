<?php

declare(strict_types=1);

namespace App\Interface\Cli;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'user:register',
    description: 'Register a new user',
)]
class RegisterUserCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('User registered successfully!');

        return Command::SUCCESS;
    }
}
