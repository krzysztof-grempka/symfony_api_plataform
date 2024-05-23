<?php

namespace App\Domain\User\Model;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'user_admin')]
class Admin extends User
{
}
