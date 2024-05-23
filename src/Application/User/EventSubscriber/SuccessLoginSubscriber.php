<?php

declare(strict_types=1);

namespace App\Application\User\EventSubscriber;

use App\Domain\User\Repository\UserRepositoryInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class SuccessLoginSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function onLexikJwtAuthenticationOnAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $user = $event->getUser();
        $user->setLastLogin(new \DateTimeImmutable());

        $this->userRepository->save($user);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'lexik_jwt_authentication.on_authentication_success' => 'onLexikJwtAuthenticationOnAuthenticationSuccess',
        ];
    }
}
