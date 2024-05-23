<?php

declare(strict_types=1);

namespace App\Application\Message\Query;

use App\Application\Shared\Query\QueryInterface;

final class FindMessageContextQuery implements QueryInterface
{
    public function __construct(public readonly int $id)
    {
    }
}
