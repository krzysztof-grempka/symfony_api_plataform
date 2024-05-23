<?php

declare(strict_types=1);

namespace App\Domain\Message\Model;

use App\Infrastructure\Message\ApiPlatform\Resource\MessageResource;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class SmsMessage extends Message
{
    public const RESOURCE = MessageResource::class;

    public function __construct(string $body)
    {
        parent::__construct(null, $body);
    }

    public function getResource(): string
    {
        return self::RESOURCE;
    }
}
