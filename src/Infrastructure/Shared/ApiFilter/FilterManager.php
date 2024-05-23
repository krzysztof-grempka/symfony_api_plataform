<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\ApiFilter;

use App\Infrastructure\Shared\Attribute\AttributeReader;

class FilterManager
{
    public function __construct(private readonly AttributeReader $attributeReader)
    {
    }

    public function getValidFilters(string $resourceClass, array $filters): array
    {
        if (!class_exists($resourceClass)) {
            return [];
        }

        $reflectionClass = new \ReflectionClass($resourceClass);
        $propertiesAllowedForFiltering = $this->attributeReader->getPropertiesWithAttribute($reflectionClass, ApiFilter::class);

        return $this->getFilters($filters, $propertiesAllowedForFiltering);
    }

    private function getFilters(array $filters, array $propertiesAllowedForFiltering): array
    {
        $validFilters = [];
        $this->getFiltersWithValidNames($validFilters, $filters, $propertiesAllowedForFiltering);
        $this->removeFiltersWithInvalidOperators($validFilters, $propertiesAllowedForFiltering);

        return $validFilters;
    }

    private function getFiltersWithValidNames(array &$validFilters, array $filters, array $propertiesAllowedForFiltering): void
    {
        foreach ($filters as $filterName => $filterValue) {
            if (in_array($filterName, ['groups', 'order', 'page', 'itemsPerPage', 'XDEBUG_SESSION'], true)) {
                continue;
            }

            if (array_key_exists($filterName, $propertiesAllowedForFiltering)) {
                if ($propertiesAllowedForFiltering[$filterName]->customFilter) {
                    $validFilters[$filterName] = new ($propertiesAllowedForFiltering[$filterName]->customFilter)($filterValue);
                } else {
                    $validFilters[$filterName] = $filterValue;
                }
            } else {
                throw new \InvalidArgumentException(sprintf('Filter "%s" is not allowed for this resource.', $filterName));
            }
        }
    }

    private function removeFiltersWithInvalidOperators(array &$validFilters, array $propertiesAllowedForFiltering): void
    {
        foreach ($validFilters as $filterName => $filterValue) {
            if (is_array($filterValue)) {
                foreach ($filterValue as $operator => $value) {
                    if (!in_array($operator, ApiFilter::ENABLED_ARRAY_OPERATORS, true)) {
                        unset($validFilters[$filterName][$operator]);
                    }

                    if (!$propertiesAllowedForFiltering[$filterName]->allowTemplates && !in_array($operator, ApiFilter::SQL_OPERATOR_TEMPLATE_MAP, true)) {
                        unset($validFilters[$filterName][$operator]);
                    }
                }

                if (empty($validFilters[$filterName])) {
                    unset($validFilters[$filterName]);
                }
            }
        }
    }
}
