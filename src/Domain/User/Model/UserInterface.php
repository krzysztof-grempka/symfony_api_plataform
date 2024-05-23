<?php

namespace App\Domain\User\Model;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

interface UserInterface extends PasswordAuthenticatedUserInterface
{
    public function getEmail(): string;

    public function getPassword(): string;

    public function updatePassword(string $hashedPassword): void;

    public function getRole(): string;

    public function isEnabled(): bool;

    public function getId(): ?int;

    public function setLastLogin(\DateTimeImmutable $lastLogin): self;
}
