<?php

namespace App\Service;

use App\Dto\Input\LoginDTO;
use App\Entity\User;
use App\Repository\UserRepository;

readonly class UserService
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function getUserFromLogin(LoginDTO $loginDTO): ?User
    {
        return $this->userRepository->findOneBy(['username' => $loginDTO->username]);
    }
}
