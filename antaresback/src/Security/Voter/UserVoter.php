<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserVoter extends Voter
{
    public const EDIT = 'USER_EDIT';
    public const VIEW = 'USER_VIEW';
    public const DELETE = 'USER_DELETE';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof \App\Entity\User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                /// On verifie si on peut editer EDIT
                //On verifie si l'utilisateur est MODERATEUR
                if ($this->security->isGranted('ROLE_ADMIN')) {
                    return true;
                }

                return $user === $subject();

                break;
            case self::VIEW:
                
                if ($this->security->isGranted('ROLE_ADMIN')) {
                    return true;
                }

                if ($subject == $user) {
                    return true;
                }
                break;

            case self::DELETE:

                if ($this->security->isGranted('ROLE_ADMIN')) {
                    return true;
                }
                if ($subject == $user) {
                    return true;
                }
                break;
            }
        

        return false;
    }
}
