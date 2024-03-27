<?php

namespace App\Security\Voter;

use App\Entity\PostContent;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PostContentVoter extends Voter
{
    public const EDIT = 'EDIT';
    public const VIEW = 'VIEW';
    public const DELETE = 'DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof PostContent;
    }

    /**
     * @param PostContent $postContent
     */
    protected function voteOnAttribute(string $attribute, $postContent, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (self::VIEW == $attribute) {
            return true;
        }

        // the user must be logged in; if not, deny permission
        if (!$user instanceof User) {
            return false;
        }

        // the logic of this voter is pretty simple: if the logged user is the
        // author of the given blog post, grant permission; otherwise, deny it.
        // (the supports() method guarantees that $postContent is a Post object)
        return $user === $postContent->getPost()->getAuthor();
    }
}
