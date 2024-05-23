<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Service;

use App\Application\User\Service\UserHelperInterface;
use App\Domain\User\Model\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Infrastructure\User\ApiPlatform\Resource\UserResource;
use Symfony\Bundle\SecurityBundle\Security;

class UserHelper implements UserHelperInterface
{
    public function __construct(
        private readonly Security $security,
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function getUserModel(): ?User
    {
        $securityUserIdentifier = $this->security->getUser()?->getUserIdentifier();
        if (!$securityUserIdentifier) {
            return null;
        }

        return $this->userRepository->findOneByEmail($securityUserIdentifier);
    }

    public function getUserResource(): ?object
    {
        $userModel = $this->getUserModel();
        if (!$userModel) {
            return null;
        }

        return UserResource::fromModel($userModel);
    }

    public function isGranted(mixed $attributes, mixed $subject = null): bool
    {
        return $this->security->isGranted($attributes, $subject);
    }
}
