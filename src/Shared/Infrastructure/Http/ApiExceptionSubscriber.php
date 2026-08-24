<?php

namespace App\Shared\Infrastructure\Http;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::EXCEPTION)]
final readonly class ApiExceptionSubscriber
{
    public function __construct(private ApiExceptionStatusResolver $statusResolver)
    {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $status = $this->statusResolver->resolve($exception);

        if ($status === null) {
            return;
        }

        $event->setResponse(new JsonResponse([
            'error' => [
                'code' => $exception->getMessage(),
                'status' => $status,
            ],
        ], $status));
    }
}
