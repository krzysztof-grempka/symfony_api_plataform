<?php

declare(strict_types=1);

namespace App\Infrastructure\Message\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Application\Message\Command\CreateEmailMessagesCommand;
use App\Application\Message\Command\CreateSmsMessagesCommand;
use App\Infrastructure\Message\ApiPlatform\State\Processor\EmailMessageContextCreateProcessor;
use App\Infrastructure\Message\ApiPlatform\State\Processor\SmsMessageContextCreateProcessor;
use App\Infrastructure\Message\ApiPlatform\State\Provider\MessageContextReadProvider;
use App\Infrastructure\Shared\ApiPlatform\Factory\ResourceFactory;
use App\Infrastructure\Shared\ApiPlatform\Resource\ResourceInterface;
use App\Infrastructure\User\ApiPlatform\Resource\UserResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Message',
    operations: [
        new GetCollection(),
        new Get(),
        new Post(
            '/send-email',
            openapiContext: ['summary' => 'Send email messages'],
            input: CreateEmailMessagesCommand::class,
            provider: MessageContextReadProvider::class,
            processor: EmailMessageContextCreateProcessor::class,
        ),
        new Post(
            '/send-sms',
            openapiContext: ['summary' => 'Send sms messages'],
            input: CreateSmsMessagesCommand::class,
            provider: MessageContextReadProvider::class,
            processor: SmsMessageContextCreateProcessor::class,
        ),
    ],
    normalizationContext: ['groups' => ['message.read']],
    security: "is_granted('ROLE_ADMIN')",
    provider: MessageContextReadProvider::class
)]
final class MessageContextResource implements ResourceInterface
{
    #[ApiProperty(writable: false, identifier: true)]
    #[Groups(['message.read'])]
    public int $id;
    #[Assert\NotNull]
    #[Groups(['message.read'])]
    public string $status;
    #[Groups(['message.read'])]
    public ?\DateTimeImmutable $sentAt;
    #[Assert\NotNull]
    #[Groups(['message.read'])]
    public UserResource $sender;
    #[Assert\NotNull]
    #[Groups(['message.read'])]
    public UserResource $recipient;
    #[Assert\NotNull]
    #[Groups(['message.read'])]
    public MessageResource $content;

    public static function fromModel(object $model, array $excludedVars = []): object
    {
        return ResourceFactory::fromModel(self::class, $model, $excludedVars);
    }
}
