<?php

namespace App\Infrastructure\Shared\ApiPlatform\Filter;

use Doctrine\ORM\QueryBuilder;

interface CustomFilterInterface
{
    public function apply(QueryBuilder $queryBuilder, string $alias): void;
}
