<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    public const EDIT = 'EDIT';
    public const VIEW = 'VIEW';

    public function __construct(private readonly Security $security)
    {

    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof \App\Entity\User;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $currentUser = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$currentUser instanceof UserInterface) {
            return false;
        }

        // you know $subject is a Post object, thanks to `supports()`
        /** @var User $user */
        $user = $subject;
        return match ($attribute) {
            self::VIEW => $this->canView($user, $currentUser),
            self::EDIT => $this->canEdit($user, $currentUser),
            default => throw new \LogicException('This code should not be reached!')
        };
        return false;
    }

    private function canView(User $data, User $user): bool
    {
        // if they can edit, they can view
        if ($this->canEdit($data, $user)) {
            return true;
        }

        return false;
    }

    private function canEdit(User $data, User $currentUser): bool
    {
        // User can edit itself
        if ($data->getEmail() == $currentUser->getUserIdentifier() || $data->getId() == $currentUser->getId()) {
            return true;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }
}
