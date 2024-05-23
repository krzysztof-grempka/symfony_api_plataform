<?php

namespace App\Application\User\EventSubscriber;

use App\Application\Shared\Notification\UserNotifierInterface;
use App\Domain\User\Model\UserBlockade;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Webmozart\Assert\Assert;

class UserBlockadeSubscriber implements EventSubscriber
{
    public function __construct(
        private readonly UserNotifierInterface $userNotifier,
    ) {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::preRemove,
        ];
    }

    public function postPersist(LifecycleEventArgs $event): void
    {
        $this->logActivity($event, Events::postPersist);
    }

    public function preRemove(LifecycleEventArgs $event): void
    {
        $this->logActivity($event, Events::preRemove);
    }

    public function logActivity(LifecycleEventArgs $event, string $eventType): void
    {
        Assert::inArray($eventType, [Events::postPersist, Events::preRemove]);

        $entity = $event->getObject();

        if (!$entity instanceof UserBlockade) {
            return;
        }

        switch ($eventType) {
            case Events::postPersist:
                // $this->userNotifier->blockUser($entity->user, ['email'], $entity->reason);
                break;

            case Events::preRemove:
                $this->userNotifier->unblockUser($entity->user, ['email']);
                break;
        }
    }
}
