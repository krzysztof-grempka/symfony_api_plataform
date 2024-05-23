<?php

declare(strict_types=1);

namespace App\Application\User\Service;

use App\Domain\User\Model\User;

interface UserHelperInterface
{
    public function getUserModel(): ?User;

    public function getUserResource(): ?object;

    public function isGranted(mixed $attributes, mixed $subject = null): bool;
}
