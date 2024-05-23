<?php

namespace App\Infrastructure\Shared\Doctrine;

use App\Infrastructure\Shared\ApiFilter\ApiFilter;
use App\Infrastructure\Shared\ApiPlatform\Filter\OrderFilter;

trait DoctrineOrderTrait
{
    #[ApiFilter(allowTemplates: true, customFilter: OrderFilter::class)]
    public ?string $orderFilter = null;
}
