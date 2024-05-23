<?php

namespace App\Infrastructure\Shared\Command;

use App\Application\Shared\Message\MessageBusInterface;
use App\Application\User\Message\EmployeeCreated;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:test-amqp',
    description: 'Test amqp',
)]
class TestAmqpCommand extends Command
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
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
        $this->messageBus->dispatch(new EmployeeCreated($input->getArgument('userId')));

        return Command::SUCCESS;
    }
}
