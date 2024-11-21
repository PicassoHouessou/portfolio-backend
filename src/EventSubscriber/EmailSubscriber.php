<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class EmailSubscriber implements EventSubscriberInterface
{
    public function onKernelResponse(ResponseEvent $event)
    {
        // ...
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.response' => 'onKernelResponse',
        ];
    }
}
