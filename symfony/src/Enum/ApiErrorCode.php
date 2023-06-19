<?php

namespace App\Enum;

enum ApiErrorCode: string
{
    case LOGIN_USERNAME_INVALID = 'LOGIN_USERNAME_INVALID';
    case LOGIN_PASSWORD_INVALID = 'LOGIN_PASSWORD_INVALID';
    case GENERIC_NOT_FOUND = 'GENERIC_NOT_FOUND';
    case LOGIN_USER_NOT_FOUND = 'LOGIN_USER_NOT_FOUND';
    case GENERIC_BAD_REQUEST = 'GENERIC_BAD_REQUEST';
    case GENERIC_SERVER_ERROR = 'GENERIC_SERVER_ERROR';
}
