<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\ApiPlatform\State;

use ApiPlatform\State\Pagination\PaginatorInterface;

final class Paginator implements PaginatorInterface, \IteratorAggregate
{
    public function __construct(
        private readonly iterable $items,
        private readonly float $currentPage,
        private readonly float $itemsPerPage,
        private readonly float $lastPage,
        private readonly float $totalItems,
    ) {
    }

    public function getCurrentPage(): float
    {
        return $this->currentPage;
    }

    public function getItemsPerPage(): float
    {
        return $this->itemsPerPage;
    }

    public function getLastPage(): float
    {
        return $this->lastPage;
    }

    public function getTotalItems(): float
    {
        return $this->totalItems;
    }

    public function count(): int
    {
        return iterator_count($this->getIterator());
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator((array) $this->items);
    }
}
