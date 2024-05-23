<?php

declare(strict_types=1);

namespace App\Infrastructure\User\ApiPlatform\OpenApi;

use ApiPlatform\Api\FilterInterface;
use Symfony\Component\PropertyInfo\Type;

final class UserFilter implements FilterInterface
{
    public function getDescription(string $resourceClass): array
    {
        return [
            'firstName' => [
                'property' => 'firstName',
                'type' => Type::BUILTIN_TYPE_STRING,
                'required' => false,
            ],
        ];
    }
}
