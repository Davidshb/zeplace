<?php

namespace App\Dto\Input;

use App\Enum\ApiErrorCode;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

class LoginDTO
{
    #[OA\Property(example: 'davidshbo', nullable: false)]
    #[Assert\NotBlank(message: "le nom d'utilisateur est requis", payload: ApiErrorCode::LOGIN_USERNAME_INVALID)]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: "le nom d'utilisateur doit faire minimum 5 caractères",
        maxMessage: "le nom d'utilisateur doit faire maximum 255 caractères",
        payload: ApiErrorCode::LOGIN_USERNAME_INVALID
    )]
    public ?string $username = null;

    #[OA\Property(example: '********', nullable: false)]
    #[Assert\NotBlank(message: "le mot de passe est requis", payload: ApiErrorCode::LOGIN_USERNAME_INVALID)]
    #[Assert\Length(
        min: 8,
        minMessage: "le mot de passe doit faire minimum 8 caractères",
        payload: ApiErrorCode::LOGIN_USERNAME_INVALID
    )]
    public ?string $password = null;
}
