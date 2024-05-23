<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\Shared\Repository\RepositoryInterface;
use App\Domain\User\Model\User;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function save(User $user): void;

    public function remove(User $user): void;

    public function find(int $id): ?User;

    public function withFirstName(string $firstName): static;

    public function findOneByEmail(string $email): ?User;

    public function everyExist(int ...$ids): bool;

    public function everyExistAndEnabled(int ...$ids): bool;

    public function getEmailsForIds(int ...$ids): array;

    public function withFilters(array $filters): static;

    public function getReference(int $id): ?User;

    public function findOneBy(array $params): ?User;

    public function findOneByIdentifier(int $identifier): ?User;

    public function findNotVerifiedUsers(): array;

    public function getPhoneNumbersForIds(int ...$ids): array;
}
