<?php

namespace App\Application\JWT_Token\EventListener;

use App\Domain\User\Repository\UserRepositoryInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class JWTCreatedListener
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $payload = $event->getData();

        $user = $this->userRepository->findOneByEmail($event->getUser()->getUserIdentifier());
        if (!$user->verified) {
            throw new AccessDeniedException('Access denied.');
        }

        $event->setData($payload);

        $header = $event->getHeader();
        $header['cty'] = 'JWT';

        $event->setHeader($header);
    }
}
