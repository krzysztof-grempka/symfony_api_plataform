<?php

declare(strict_types=1);

namespace App\Application\User\Query;

use App\Application\Shared\Query\QueryInterface;

final class FindUserQuery implements QueryInterface
{
    public function __construct(
        public readonly int $id,
    ) {
    }
}
