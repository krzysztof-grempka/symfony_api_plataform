<?php

namespace App\Domain\User\Model;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'verifying_token')]
class VerifyingToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    public int $id;

    public function __construct(
        #[ORM\Column(type: Types::STRING)]
        public string $recipient,

        #[ORM\Column(type: Types::STRING)]
        public string $token,
    ) {
    }
}
