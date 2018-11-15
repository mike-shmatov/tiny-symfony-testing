<?php
namespace Tiny\SymfonyTesting\TinyTestingBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExceptionEventListener
{
    public function onKernelException(GetResponseForExceptionEvent $event) {
        $ex = $event->getException();
        if ( ! $ex instanceof HttpException ) {
            throw $ex;
        }
        return;
    }
}
