<?php

namespace App\Domain\Shared\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait TimestampableEntity
{
    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(name: 'created', type: Types::DATETIME_IMMUTABLE)]
    public \DateTimeImmutable $created;

    #[ORM\Column(name: 'updated', type: Types::DATETIME_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    public \DateTimeImmutable $updated;

    public function getCreated(): \DateTimeImmutable
    {
        return $this->created;
    }

    public function getUpdated(): \DateTimeImmutable
    {
        return $this->updated;
    }
}
