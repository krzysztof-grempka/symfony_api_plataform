<?php

namespace App\Domain\Shared\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait SoftDeleteableEntity
{
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    public ?\DateTimeImmutable $deleted = null;

    public function getDeleted(): ?\DateTimeImmutable
    {
        return $this->deleted;
    }
}
