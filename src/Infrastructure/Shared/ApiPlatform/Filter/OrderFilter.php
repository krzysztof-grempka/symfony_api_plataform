<?php

namespace App\Infrastructure\Shared\ApiPlatform\Filter;

use Doctrine\ORM\QueryBuilder;

class OrderFilter extends CustomFilter implements CustomFilterInterface
{
    public function apply(QueryBuilder $queryBuilder, string $alias): void
    {
        if (!is_array($this->value)) {
            return;
        }

        $field = array_key_first($this->value);
        $order = $this->value[$field];

        if (str_contains($field, '.')) {
            $separatedData = explode('.', $field);
            $alias = reset($separatedData);
            $field = end($separatedData);
        }

        $queryBuilder->orderBy(sprintf('%s.%s', $alias, $field), strtolower($order));
    }
}
