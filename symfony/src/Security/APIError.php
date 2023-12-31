<?php

namespace App\Security;

use App\Enum\ApiErrorCode;
use OpenApi\Attributes as OA;

class APIError
{
    #[OA\Property(
        type: 'string',
        example: 'ERROR_CODE'
    )]
    private ApiErrorCode $errorCode;

    public function __construct(
        ApiErrorCode $errorCode,
        private ?string $errorMessage = null
    ) {
        $this->errorCode = $errorCode;
    }


    /**
     * @return ApiErrorCode
     */
    public function getErrorCode(): ApiErrorCode
    {
        return $this->errorCode;
    }

    /**
     * @param ApiErrorCode $errorCode
     * @return APIError
     */
    public function setErrorCode(ApiErrorCode $errorCode): APIError
    {
        $this->errorCode = $errorCode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    /**
     * @param string|null $errorMessage
     * @return APIError
     */
    public function setErrorMessage(?string $errorMessage): APIError
    {
        $this->errorMessage = $errorMessage;
        return $this;
    }
}
