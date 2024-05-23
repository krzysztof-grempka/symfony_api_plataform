<?php

namespace App\Infrastructure\Shared\Command;

use App\Application\Shared\Message\MessageBusInterface;
use App\Application\User\Message\EmployeeCreated;
use App\Domain\User\Repository\UserRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:user:send-verifying-token',
    description: 'Send verifying token to not verified users',
)]
class SendVerifyingTokenCommand extends Command
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly UserRepositoryInterface $userRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $users = $this->userRepository->findNotVerifiedUsers();
        if (empty($users)) {
            return Command::SUCCESS;
        }

        foreach ($users as $user) {
            $this->messageBus->dispatch(new EmployeeCreated($user->getId()));
        }

        return Command::SUCCESS;
    }
}
