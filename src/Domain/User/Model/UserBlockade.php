<?php

namespace App\Domain\User\Model;

use App\Domain\Shared\Model\ModelInterface;
use App\Domain\Shared\Traits\SoftDeleteableEntity;
use App\Domain\Shared\Traits\TimestampableEntity;
use App\Infrastructure\User\ApiPlatform\Resource\UserBlockadeResource;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity]
#[ORM\Table(name: 'user_blockade')]
#[Gedmo\SoftDeleteable(fieldName: 'deleted', timeAware: false, hardDelete: true)]
class UserBlockade implements ModelInterface
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    public const RESOURCE = UserBlockadeResource::class;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    public ?int $id = null;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: User::class)]
        public User $user,

        #[ORM\Column(type: Types::STRING)]
        public string $reason,
    ) {
    }

    public function getResource(): string
    {
        return self::RESOURCE;
    }
}
