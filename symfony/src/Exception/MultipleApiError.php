<?php

namespace App\Exception;

use App\Security\APIError;
use Exception;
use Throwable;

class MultipleApiError extends Exception
{
    /** @var array<APIError> */
    private array $apiErrors;

    /**
     * @param array<APIError> $apiErrors
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        array $apiErrors,
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->apiErrors = $apiErrors;
    }

    /**
     * @return array<APIError>
     */
    public function getApiErrors(): array
    {
        return $this->apiErrors;
    }
}
