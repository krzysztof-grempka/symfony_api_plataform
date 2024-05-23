<?php

declare(strict_types=1);

namespace App\Application\Message\Query;

use App\Application\Shared\Query\QueryInterface;

final class FindMessageContextsQuery implements QueryInterface
{
    public function __construct(public readonly ?int $page = null, public readonly ?int $itemsPerPage = null)
    {
    }
}
