<?php

namespace App\Infrastructure\Shared\Symfony\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ContentTypeSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['setContentType', EventPriorities::PRE_RESPOND],
        ];
    }

    public function setContentType(ViewEvent $event): void
    {
        $format = $event->getRequest()->getRequestFormat();

        if (in_array($format, ['jsonld', 'json'])) {
            return;
        }

        $data = $event->getControllerResult();
        $filename = 'raport.xlsx';

        $response = new Response(
            $data,
            200,
            [
                'content-type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]
        );

        $event->setResponse($response);
    }
}
