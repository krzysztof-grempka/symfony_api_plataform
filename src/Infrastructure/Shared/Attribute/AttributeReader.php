<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Attribute;

use App\Infrastructure\Shared\ApiFilter\ApiFilter;

class AttributeReader
{
    public function __construct()
    {
    }

    public function getPropertiesWithAttribute(\ReflectionClass $class, string $annotationName, ?string $parent = null): array
    {
        $annotatedProperties = [];

        $properties = $class->getProperties();
        foreach ($properties as $property) {
            $annotation = $this->getPropertyAnnotation($property, $annotationName);
            if ($annotation instanceof $annotationName) {
                if ($annotation->subresource) {
                    $annotatedProperties = array_merge(
                        $annotatedProperties,
                        $this->getPropertiesWithAttribute(new \ReflectionClass($annotation->subresource), $annotationName, $property->getName())
                    );
                    continue;
                }

                if ($parent) {
                    $annotation->subresource = $class->getName();
                }

                $propertyName = ($parent ? $parent.'.' : '').$property->getName();
                $annotatedProperties[$propertyName] = $annotation;
            }
        }

        $parentClass = $class->getParentClass();
        if ($parentClass instanceof \ReflectionClass) {
            $annotatedProperties = array_merge(
                $annotatedProperties,
                $this->getPropertiesWithAttribute($parentClass, $annotationName)
            );
        }

        return $annotatedProperties;
    }

    private array $isRepeatableAttribute = [];

    public function getClassAnnotations(\ReflectionClass $class): array
    {
        return $this->convertToAttributeInstances($class->getAttributes());
    }

    public function getMethodAnnotations(\ReflectionMethod $method): array
    {
        return $this->convertToAttributeInstances($method->getAttributes());
    }

    public function getPropertyAnnotations(\ReflectionProperty $property): array
    {
        return $this->convertToAttributeInstances($property->getAttributes());
    }

    public function getPropertyAnnotation(\ReflectionProperty $property, $annotationName)
    {
        if ($this->isRepeatable($annotationName)) {
            throw new \LogicException(sprintf('The attribute "%s" is repeatable. Call getPropertyAnnotationCollection() instead.', $annotationName));
        }

        return $this->getPropertyAnnotations($property)[$annotationName]
            ?? ($this->isRepeatable($annotationName) ? new \ArrayObject() : null);
    }

    public function getPropertyAnnotationCollection(
        \ReflectionProperty $property,
        string $annotationName
    ): \ArrayObject {
        if (!$this->isRepeatable($annotationName)) {
            throw new \LogicException(sprintf('The attribute "%s" is not repeatable. Call getPropertyAnnotation() instead.', $annotationName));
        }

        return $this->getPropertyAnnotations($property)[$annotationName] ?? new \ArrayObject();
    }

    //    public function getPropertiesWithAttribute(ReflectionClass $class, string $attributeClass)
    //    {
    //
    //    }

    private function convertToAttributeInstances(array $attributes): array
    {
        $instances = [];

        foreach ($attributes as $attribute) {
            $attributeName = $attribute->getName();
            assert(is_string($attributeName));
            if (ApiFilter::class !== $attributeName) {
                continue;
            }

            $instance = $attribute->newInstance();
            assert($instance instanceof ApiFilter);
            if ($this->isRepeatable($attributeName)) {
                if (!isset($instances[$attributeName])) {
                    $instances[$attributeName] = new \ArrayObject();
                }

                $collection = $instances[$attributeName];
                assert($collection instanceof \ArrayObject);
                $collection[] = $instance;
            } else {
                $instances[$attributeName] = $instance;
            }
        }

        return $instances;
    }

    private function isRepeatable(string $attributeClassName): bool
    {
        if (!class_exists($attributeClassName)) {
            return false;
        }

        if (isset($this->isRepeatableAttribute[$attributeClassName])) {
            return $this->isRepeatableAttribute[$attributeClassName];
        }

        $reflectionClass = new \ReflectionClass($attributeClassName);
        $attribute = $reflectionClass->getAttributes()[0]->newInstance();

        return $this->isRepeatableAttribute[$attributeClassName] = ($attribute->flags & \Attribute::IS_REPEATABLE) > 0;
    }
}
