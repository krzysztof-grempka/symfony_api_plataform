<?php

namespace App\Infrastructure\Shared\ApiPlatform\Resource;

interface ResourceInterface
{
    public static function fromModel(object $model, array $excludedVars = []): object;
}
