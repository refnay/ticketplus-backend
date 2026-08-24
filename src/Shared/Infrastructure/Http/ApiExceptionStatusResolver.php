<?php

namespace App\Shared\Infrastructure\Http;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class ApiExceptionStatusResolver
{
    public function resolve(Throwable $exception): ?int
    {
        $class = $exception::class;

        if (str_starts_with($class, 'App\\Shared\\Application\\Security\\Exception\\')) {
            return Response::HTTP_FORBIDDEN;
        }

        if (!str_contains($class, '\\Domain\\Exceptions\\')) {
            return null;
        }

        if (
            $class === 'App\\Account\\Member\\Domain\\Exceptions\\MemberNotAllowed'
            || $class === 'App\\Account\\User\\Domain\\Exceptions\\UserNotOwner'
        ) {
            return Response::HTTP_FORBIDDEN;
        }

        $name = substr($class, (int) strrpos($class, '\\') + 1);

        if (str_ends_with($name, 'NotFound')) {
            return Response::HTTP_NOT_FOUND;
        }

        if (str_ends_with($name, 'AlreadyExists') || str_ends_with($name, 'AlreadyProcessing')) {
            return Response::HTTP_CONFLICT;
        }

        if (str_starts_with($class, 'App\\Shared\\Domain\\Exceptions\\')) {
            return Response::HTTP_BAD_REQUEST;
        }

        return Response::HTTP_UNPROCESSABLE_ENTITY;
    }
}
