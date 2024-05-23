<?php

namespace App\Infrastructure\Shared\ApiPlatform\Filter;

use Doctrine\ORM\QueryBuilder;

abstract class CustomFilter
{
    public function __construct(public string|array $value)
    {
    }

    protected function joinTables(QueryBuilder $queryBuilder, array $tablesToJoin): void
    {
        $allAliases = $queryBuilder->getAllAliases();
        foreach ($tablesToJoin as $table => $alias) {
            if (in_array($alias, $allAliases)) {
                continue;
            }

            $queryBuilder->leftJoin($table, $alias);
        }
    }
}
