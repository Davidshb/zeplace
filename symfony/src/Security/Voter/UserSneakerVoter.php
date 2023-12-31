<?php

namespace App\Security\Voter;

use App\Entity\UserSneaker;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserSneakerVoter extends Voter
{
    public const USER_SNEAKER_EDIT = 'user_sneaker_edit';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::USER_SNEAKER_EDIT && $subject instanceof UserSneaker;
    }

    public function supportsAttribute(string $attribute): bool
    {
        return self::USER_SNEAKER_EDIT === $attribute;
    }

    public function supportsType(string $subjectType): bool
    {
        return UserSneaker::class === $subjectType;
    }

    /**
     * @inheritDoc
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var UserSneaker $userSneaker */
        $userSneaker = $subject;

        return $userSneaker->getUser() === $token->getUser();
    }
}
