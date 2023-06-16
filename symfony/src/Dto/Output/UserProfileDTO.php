<?php

namespace App\Dto\Output;

class UserProfileDTO
{
    public string $username;

    /** @var array<UserSneakerDTO> */
    public array $sneakers;
}