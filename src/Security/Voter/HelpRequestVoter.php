<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class HelpRequestVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['HR_EDIT', 'HR_VIEW','HR_DELETE'])
            && $subject instanceof \App\Entity\HelpRequest;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        if ($user &&   in_array('ROLE_SUPERADMIN',$user->getRoles()))
        return true;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'HR_EDIT':
                // logic to determine if the user can EDIT
                // return true or false
                break;
            case 'HR_VIEW':
                if($subject->getRequestedBy()->getId()==$user->getId())
                return true;
                if ( in_array('ROLE_DIRECTOR',$user->getRoles()))
                return true;
                // logic to determine if the user can VIEW
                return false;
                break;
            case 'HR_DELETE':
                if($subject->getRequestedBy()->getId()==$user->getId() && $subject->getStatus()<1)
                return true;
                return false;
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }
}
