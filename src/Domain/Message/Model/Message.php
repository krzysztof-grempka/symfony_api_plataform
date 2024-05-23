<?php

declare(strict_types=1);

namespace App\Domain\Message\Model;

use App\Domain\Shared\Model\ModelInterface;
use App\Domain\Shared\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Entity]
#[ORM\Table(name: 'message')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'sms' => SmsMessage::class,
    'email' => EmailMessage::class,
])]
abstract class Message implements ModelInterface
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    public readonly ?int $id;

    public function __construct(
        #[ORM\Column(type: 'text', length: 255, nullable: true)]
        public readonly ?string $subject,
        #[ORM\Column(type: 'text')]
        public readonly string $body
    ) {
        Assert::minLength($body, 8);
    }
}
