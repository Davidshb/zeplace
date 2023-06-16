<?php

namespace App\Service;
use App\Dto\Output\UserProfileDTO;
use App\Entity\User;

readonly class UserService
{

    public function __construct(private UserSneakerService $sneakerService)
    {
    }

    /**
     * @param User $user
     * @return UserProfileDTO
     */
    public function computeUserProfile(User $user): UserProfileDTO
    {
        $res = new UserProfileDTO();

        $res->username = $user->getUsername();
        $res->sneakers = $this->sneakerService->computeUserSneakerDTOs($user->getUserSneakers()->toArray());

        return $res;
    }
}