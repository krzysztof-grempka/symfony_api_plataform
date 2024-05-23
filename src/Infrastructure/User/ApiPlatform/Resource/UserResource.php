<?php

declare(strict_types=1);

namespace App\Infrastructure\User\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Application\User\Command\ChangePassword\ChangePasswordCommand;
use App\Application\User\Command\ResetPassword\SendEmailCommand;
use App\Application\User\Command\ResetPassword\SetNewPasswordCommand;
use App\Application\User\Command\VerifyEmail\VerifyEmailCommand;
use App\Infrastructure\Shared\ApiFilter\ApiFilter;
use App\Infrastructure\Shared\ApiPlatform\Factory\ResourceFactory;
use App\Infrastructure\Shared\ApiPlatform\OpenApi\DynamicFilterGenerator;
use App\Infrastructure\Shared\ApiPlatform\Resource\ResourceInterface;
use App\Infrastructure\Shared\Doctrine\DoctrineOrderTrait;
use App\Infrastructure\User\ApiPlatform\State\Processor\ResetPassword\SendEmailProcessor;
use App\Infrastructure\User\ApiPlatform\State\Processor\ResetPassword\SetNewPasswordProcessor;
use App\Infrastructure\User\ApiPlatform\State\Processor\UpdatePasswordProcessor;
use App\Infrastructure\User\ApiPlatform\State\Processor\UserCrudProcessor;
use App\Infrastructure\User\ApiPlatform\State\Processor\VerifyEmailProcessor;
use App\Infrastructure\User\ApiPlatform\State\Provider\MeProvider;
use App\Infrastructure\User\ApiPlatform\State\Provider\UserCrudProvider;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'User',
    operations: [
        new GetCollection(
            security: "is_granted('ROLE_ADMIN')",
            filters: [DynamicFilterGenerator::class],
        ),
        new Get(
            security: "is_granted('ROLE_ADMIN') or (is_granted('ROLE_EMPLOYEE') and object.id == user.id)",
        ),
        new Get(
            '/me',
            security: "is_granted('IS_AUTHENTICATED_FULLY') and object.id == user.id",
            provider: MeProvider::class,
        ),
        new Post(
            security: "is_granted('PUBLIC_ACCESS')",
        ),
        new Post(
            '/users/change-password',
            openapiContext: ['summary' => 'Change password.'],
            security: "is_granted('IS_AUTHENTICATED_FULLY')",
            input: ChangePasswordCommand::class,
            processor: UpdatePasswordProcessor::class,
        ),
        new Post(
            '/users/reset_password/email',
            security: "is_granted('PUBLIC_ACCESS')",
            input: SendEmailCommand::class,
            processor: SendEmailProcessor::class,
        ),
        new Post(
            '/users/reset_password/set_new',
            security: "is_granted('PUBLIC_ACCESS')",
            input: SetNewPasswordCommand::class,
            processor: SetNewPasswordProcessor::class,
        ),
        new Post(
            '/users/verify_email',
            security: "is_granted('PUBLIC_ACCESS')",
            input: VerifyEmailCommand::class,
            output: false,
            processor: VerifyEmailProcessor::class,
        ),
        new Put(
            security: "is_granted('ROLE_ADMIN') or (is_granted('ROLE_EMPLOYEE') and object.id == user.id)",
        ),
        new Patch(),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['user.read']],
    security: "is_granted('ROLE_ADMIN')",
    provider: UserCrudProvider::class,
    processor: UserCrudProcessor::class,
)]
final class UserResource implements ResourceInterface
{
    use DoctrineOrderTrait;

    #[ApiProperty(writable: false, identifier: true)]
    #[Groups(['user.read'])]
    #[ApiFilter]
    public ?int $id = null;

    #[Assert\NotNull]
    #[Assert\Email]
    #[Assert\Length(min: 5, max: 100)]
    #[Groups(['user.read'])]
    #[ApiFilter(allowTemplates: true)]
    public string $email;

    #[Assert\Length(min: 8)]
    public string $password;

    #[Assert\Length(min: 8)]
    public string $passwordRepeat;

    #[Assert\Length(min: 1)]
    #[Groups(['user.read'])]
    #[ApiFilter]
    public string $role;

    #[Assert\Length(min: 2, max: 50)]
    #[Groups(['user.read'])]
    #[ApiFilter(allowTemplates: true)]
    public ?string $firstName = null;

    #[Assert\Length(min: 2, max: 50)]
    #[Groups(['user.read'])]
    #[ApiFilter(allowTemplates: true)]
    public ?string $lastName = null;

    #[Groups(['user.read'])]
    #[ApiFilter]
    public bool $enabled = true;

    #[Groups(['user.read'])]
    #[ApiFilter(allowTemplates: true)]
    public ?\DateTimeImmutable $lastLogin = null;

    #[Groups(['user.read'])]
    public bool $verified = false;

    public static function fromModel(object $model, array $excludedVars = []): object
    {
        $excludedVars[] = 'user';

        return ResourceFactory::fromModel(self::class, $model, $excludedVars);
    }
}
