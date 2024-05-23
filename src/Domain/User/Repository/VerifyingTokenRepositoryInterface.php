<?php

namespace App\Domain\User\Repository;

use App\Domain\Shared\Repository\RepositoryInterface;
use App\Domain\User\Model\VerifyingToken;

interface VerifyingTokenRepositoryInterface extends RepositoryInterface
{
    public function generate(VerifyingToken $token): void;

    public function removeUsed(VerifyingToken $token): void;

    public function verify(string $recipient): ?VerifyingToken;

    public function findOneBy(array $params): ?VerifyingToken;
}
