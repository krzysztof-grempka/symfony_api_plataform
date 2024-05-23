<?php

namespace App\Infrastructure\User\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Infrastructure\Shared\ApiPlatform\Factory\ResourceFactory;
use App\Infrastructure\Shared\ApiPlatform\Resource\ResourceInterface;
use App\Infrastructure\User\ApiPlatform\State\Processor\UserBlockadeCrudProcessor;
use App\Infrastructure\User\ApiPlatform\State\Provider\UserBlockadeCrudProvider;

#[ApiResource(
    shortName: 'UserBlockade',
    operations: [
        new Get(
            security: "(is_granted('ROLE_EMPLOYEE') and object.user.id == user.id)"
        ),
        new GetCollection(),
        new Post(
            security: "is_granted('IS_AUTHENTICATED_FULLY')"
        ),
        new Delete(),
    ],
    security: "is_granted('ROLE_ADMIN')",
    provider: UserBlockadeCrudProvider::class,
    processor: UserBlockadeCrudProcessor::class,
)]
class UserBlockadeResource implements ResourceInterface
{
    #[ApiProperty(writable: false, identifier: true)]
    public ?int $id = null;

    public UserResource $user;

    public string $reason;

    public static function fromModel(object $model, array $excludedVars = []): object
    {
        return ResourceFactory::fromModel(self::class, $model, $excludedVars);
    }
}
