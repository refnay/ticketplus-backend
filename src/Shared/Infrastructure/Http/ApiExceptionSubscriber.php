<?php

namespace App\Shared\Infrastructure\Http;

use App\Shared\Application\Security\Exception\CompanyOwnerRequired;
use App\Shared\Application\Security\Exception\AuthenticationRequired;
use App\Shared\Application\Security\Exception\CompanyRequired;
use App\Shared\Application\Security\Exception\MemberRequired;
use App\Shared\Application\Security\Exception\UserNotAllowed;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::EXCEPTION)]
final class ApiExceptionSubscriber
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $status = match (true) {
            $exception instanceof HttpExceptionInterface => $exception->getStatusCode(),
            $exception instanceof AuthenticationRequired => JsonResponse::HTTP_UNAUTHORIZED,
            $exception instanceof CompanyRequired,
            $exception instanceof MemberRequired,
            $exception instanceof CompanyOwnerRequired,
            $exception instanceof UserNotAllowed => JsonResponse::HTTP_FORBIDDEN,
            str_ends_with($exception::class, 'NotFound') => JsonResponse::HTTP_NOT_FOUND,
            default => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
        };

        $event->setResponse(new JsonResponse([
            'error' => [
                'code' => $exception->getMessage(),
                'status' => $status,
            ],
        ], $status));
    }
}
