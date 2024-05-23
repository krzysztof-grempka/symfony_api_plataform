<?php

namespace App\Application\User\Query\UserBlockade;

use App\Application\Shared\Query\QueryInterface;

class FindUserBlockadeQuery implements QueryInterface
{
    public function __construct(
        public readonly int $id,
    ) {
    }
}
