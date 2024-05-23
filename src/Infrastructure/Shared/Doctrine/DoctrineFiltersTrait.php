<?php

namespace App\Infrastructure\Shared\Doctrine;

use App\Infrastructure\Shared\ApiFilter\ApiFilter;
use App\Infrastructure\Shared\ApiPlatform\Filter\CustomFilterInterface;
use Doctrine\ORM\QueryBuilder;

trait DoctrineFiltersTrait
{
    public function withFilters(array $filters): static
    {
        return $this->filter(static function (QueryBuilder $qb) use ($filters): void {
            foreach ($filters as $filterName => $filterValue) {
                if ($filterValue instanceof CustomFilterInterface) {
                    $filterValue->apply($qb, self::ALIAS);
                    continue;
                }

                if (str_contains($filterName, '.')) {
                    $filterNames = explode('.', $filterName);
                    $alias = reset($filterNames);
                    $filterName = end($filterNames);
                } else {
                    $alias = self::ALIAS;
                }

                if (is_array($filterValue)) {
                    foreach ($filterValue as $operator => $value) {
                        if (array_key_exists($operator, ApiFilter::SQL_OPERATOR_SIGN_MAP)) {
                            $parameterName = $filterName.$operator;
                            $qb->andWhere(sprintf('%s.%s %s :%s', $alias, $filterName, ApiFilter::SQL_OPERATOR_SIGN_MAP[$operator], $parameterName))
                                ->setParameter(sprintf('%s', $parameterName), $value);
                        } elseif (array_key_exists($operator, ApiFilter::SQL_OPERATOR_TEMPLATE_MAP)) {
                            $parameterName = $filterName.$operator;
                            $qb->andWhere(sprintf('lower(%s.%s) like lower(:%s)', $alias, $filterName, $parameterName))
                                ->setParameter(sprintf('%s', $parameterName), str_replace(ApiFilter::TEMPLATE_STRING, $value, ApiFilter::SQL_OPERATOR_TEMPLATE_MAP[$operator]));
                        } elseif (ApiFilter::IGNORE_ACCENT == $operator) {
                            $parameterName = $filterName.$operator;
                            $qb->andWhere(sprintf('lower(COLLATE(%s.%s, ignore_accent)) = lower(:%s)', $alias, $filterName, $parameterName))
                                ->setParameter(sprintf('%s', $parameterName), $value);
                        }
                    }
                } else {
                    if (is_numeric($filterValue) or in_array($filterValue, [true, false])) { // wbudowana funkcja is_bool($filterValue) nie działa
                        $qb->andWhere(sprintf('%s.%s = :%s', $alias, $filterName, $filterName))->setParameter(sprintf('%s', $filterName), $filterValue);
                    } else {
                        $qb->andWhere(sprintf('lower(%s.%s) = lower(:%s)', $alias, $filterName, $filterName))->setParameter(sprintf('%s', $filterName), $filterValue);
                    }
                }
            }
        });
    }
}
