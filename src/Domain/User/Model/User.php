<?php

declare(strict_types=1);

namespace App\Domain\User\Model;

use App\Domain\Shared\Model\ModelInterface;
use App\Domain\Shared\Traits\SoftDeleteableEntity;
use App\Domain\Shared\Traits\TimestampableEntity;
use App\Infrastructure\User\ApiPlatform\Resource\UserResource;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;
use Webmozart\Assert\Assert;

#[ORM\Entity]
#[ORM\Table(name: 'user_base')]
#[ORM\Index(columns: ['email'], name: 'email_search_idx')]
#[ORM\Index(columns: ['first_name'], name: 'first_name_search_idx')]
#[ORM\Index(columns: ['last_name'], name: 'last_name_search_idx')]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'employee' => 'Employee',
    'admin' => 'Admin',
])]
#[Gedmo\SoftDeleteable(fieldName: 'deleted', timeAware: false, hardDelete: true)]
#[Gedmo\Loggable]
class User implements UserInterface, SymfonyUserInterface, ModelInterface
{
    use SoftDeleteableEntity;
    use TimestampableEntity;

    public const RESOURCE = UserResource::class;

    public const ROLE_EMPLOYEE = 'ROLE_EMPLOYEE';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public const ALLOWED_ROLES = [
        self::ROLE_EMPLOYEE,
        self::ROLE_ADMIN,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    public int $id;

    #[ORM\Column(type: Types::STRING)]
    public string $password;

    #[ORM\Column(type: Types::BOOLEAN)]
    public bool $enabled = true;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    public ?\DateTimeImmutable $lastLogin = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    public bool $verified = false;

    public function __construct(
        #[ORM\Column(length: 100, unique: true)]
        public string $email,

        #[ORM\Column(type: Types::STRING)]
        public string $role,

        #[ORM\Column(length: 50, nullable: true)]
        #[Gedmo\Versioned]
        public ?string $firstName = null,

        #[ORM\Column(length: 50, nullable: true)]
        #[Gedmo\Versioned]
        public ?string $lastName = null,
    ) {
        Assert::email($email);
        Assert::inArray($role, self::ALLOWED_ROLES);
        Assert::nullOrLengthBetween($firstName, 2, 50);
        Assert::nullOrLengthBetween($lastName, 2, 50);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function updatePassword(string $hashedPassword): void
    {
        $this->password = $hashedPassword;
    }

    public function getRoles(): array
    {
        return [$this->role];
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setLastLogin(\DateTimeImmutable $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    public function getFullName(): string
    {
        return $this->firstName.' '.$this->lastName;
    }

    public function getResource(): string
    {
        return self::RESOURCE;
    }
}
