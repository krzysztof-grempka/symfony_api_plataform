<?php

declare(strict_types=1);

namespace App\Application\Shared\Command;

use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\LockInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait SingletonCommandTrait
{
    private LockFactory $lockFactory;

    #[Required]
    public function setLockFactory(LockFactory $lockFactory): void
    {
        $this->lockFactory = $lockFactory;
    }

    protected function getLock(float $ttl, ?string $name = null): LockInterface
    {
        if (null === $name) {
            $name = static::getDefaultName();
        }

        return $this->lockFactory->createLock($name, $ttl, false);
    }
}
