<?php

namespace App\Infrastructure\Shared\Command;

use App\Application\Message\Command\CreateSmsMessagesCommand;
use App\Application\Shared\Command\CommandBusInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:test-sms',
    description: 'Test SMS',
)]
class TestSmsCommand extends Command
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('userId', InputArgument::REQUIRED, 'UserId to test amqp')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->commandBus->dispatch(new CreateSmsMessagesCommand('test sms', [(int) $input->getArgument('userId')]));

        return Command::SUCCESS;
    }
}
