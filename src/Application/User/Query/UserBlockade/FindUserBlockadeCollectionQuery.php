<?php

namespace App\Application\User\Query\UserBlockade;

use App\Application\Shared\Query\QueryInterface;

class FindUserBlockadeCollectionQuery implements QueryInterface
{
    public function __construct(
        public readonly ?int $page = null,
        public readonly ?int $itemsPerPage = null,
    ) {
    }
}
