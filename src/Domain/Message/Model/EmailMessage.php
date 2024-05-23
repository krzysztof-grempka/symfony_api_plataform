<?php

declare(strict_types=1);

namespace App\Domain\Message\Model;

use App\Infrastructure\Message\ApiPlatform\Resource\MessageResource;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Entity]
class EmailMessage extends Message
{
    public const RESOURCE = MessageResource::class;

    public function __construct(string $subject, string $body)
    {
        Assert::minLength($subject, 8);
        parent::__construct($subject, $body);
    }

    public function getResource(): string
    {
        return self::RESOURCE;
    }
}
