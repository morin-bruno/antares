<?php

namespace App\EventListener;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
{
    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {

        
        $data = $event->getData();
        //dd($data);
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $data['data'] = array(
        'userID' => $user->getId(),
        'userRoles' => $user->getRoles()
    );

        $event->setData($data);
    }
}