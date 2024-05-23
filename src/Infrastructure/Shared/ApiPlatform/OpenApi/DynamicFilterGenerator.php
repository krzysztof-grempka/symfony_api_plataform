<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\ApiPlatform\OpenApi;

use ApiPlatform\Api\FilterInterface;
use App\Infrastructure\Shared\ApiFilter\ApiFilter;
use App\Infrastructure\Shared\Attribute\AttributeReader;
use Symfony\Component\PropertyInfo\Type;

class DynamicFilterGenerator implements FilterInterface
{
    public function __construct(private readonly AttributeReader $attributeReader)
    {
    }

    /**
     * @throws \ReflectionException
     */
    public function getDescription(string $resourceClass): array
    {
        if (!class_exists($resourceClass)) {
            return [];
        }

        $description = [];
        $class = new \ReflectionClass($resourceClass);
        $result = $this->attributeReader->getPropertiesWithAttribute($class, ApiFilter::class);
        /** @var ApiFilter $item */
        foreach ($result as $key => $item) {
            if ($item->subresource) {
                $keys = explode('.', $key);
                $property = (new \ReflectionClass($item->subresource))->getProperty(end($keys));
            } else {
                $property = $class->getProperty($key);
            }
            $propertyName = $property->getName();
            $description[$propertyName] = [
                'property' => $propertyName,
                'type' => Type::$builtinTypes[array_search($property->getType()?->getName(), Type::$builtinTypes)],
                'required' => $item->required,
            ];
        }

        return $description;
    }
}
