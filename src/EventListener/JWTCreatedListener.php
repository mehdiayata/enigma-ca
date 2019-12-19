<?php
namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener
{
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        // Ajoute une date d'expiration de 4h
        $expiration = new \DateTime('+4 hour');

        $payload = $event->getData();
        $payload['exp'] = $expiration->getTimestamp();

        $event->setData($payload);
    }
}