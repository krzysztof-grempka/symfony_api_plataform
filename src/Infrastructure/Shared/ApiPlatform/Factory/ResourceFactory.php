<?php

namespace App\Infrastructure\Shared\ApiPlatform\Factory;

use App\Domain\Shared\Model\ModelInterface;
use Doctrine\ORM\PersistentCollection;

class ResourceFactory
{
    public static function fromModel(string $resourceClass, object $model, array $excludedVars = []): object
    {
        $resource = new $resourceClass();
        foreach (get_object_vars($model) as $key => $value) {
            if (!property_exists($resource, $key) || in_array($key, $excludedVars)) {
                continue;
            }

            if ($value instanceof ModelInterface && $value->getResource()) {
                $value = $value->getResource()::fromModel($value, $excludedVars);
            }

            if ($value instanceof PersistentCollection) {
                foreach ($value as $entity) {
                    if ($entity->getResource()) {
                        $resource->$key[] = $entity->getResource()::fromModel($entity, $excludedVars);
                    }
                }
                continue;
            }

            $resource->$key = $value;
        }

        return $resource;
    }
}
