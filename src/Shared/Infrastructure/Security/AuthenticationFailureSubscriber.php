<?php

namespace App\Shared\Infrastructure\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsEventListener(event: Events::AUTHENTICATION_FAILURE)]
#[AsEventListener(event: Events::JWT_INVALID)]
#[AsEventListener(event: Events::JWT_EXPIRED)]
#[AsEventListener(event: Events::JWT_NOT_FOUND)]
final class AuthenticationFailureSubscriber
{
    public function __construct(private TranslatorInterface $translator) {}

    public function __invoke(AuthenticationFailureEvent $event): void
    {
        $response = $event->getResponse();
        if (!$response instanceof JWTAuthenticationFailureResponse) {
            return;
        }

        $exception = $event->getException();
        $key = $exception->getMessageKey();
        $message = $this->translator->trans($key, $exception->getMessageData(), 'security');
        if ($message === $key) {
            $message = $this->translator->trans('An authentication exception occurred.', [], 'security');
        }

        $response->setMessage($message);
    }
}
