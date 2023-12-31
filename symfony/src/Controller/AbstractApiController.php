<?php

namespace App\Controller;

use App\Enum\ApiErrorCode;
use App\Security\APIError;
use App\Service\Helper\SerializerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AbstractApiController extends AbstractController
{
    public function __construct(
        protected readonly SerializerService $serializerService
    ) {
    }

    /**
     * @param array<APIError> $apiErrors
     * @param int $httpCode
     * @return JsonResponse
     */
    public function getError(array $apiErrors, int $httpCode): JsonResponse
    {
        return new JsonResponse(
            $this->serializerService->toJSON($apiErrors),
            status: $httpCode,
            json: true
        );
    }

    public function getNotFoundError(
        ApiErrorCode $code = ApiErrorCode::GENERIC_NOT_FOUND,
        string $message = "la resource n'a pas été trouvée"
    ): JsonResponse {
        $apiError = new APIError($code, $message);

        return $this->getError([$apiError], Response::HTTP_NOT_FOUND);
    }

    public function getBadRequest(
        ApiErrorCode $code = ApiErrorCode::GENERIC_BAD_REQUEST,
        string $message = 'Bad request'
    ): JsonResponse {
        $apiError = new APIError($code, $message);
        return $this->getError([$apiError], Response::HTTP_BAD_REQUEST);
    }

    public function getServerError(
        ApiErrorCode $code = ApiErrorCode::GENERIC_SERVER_ERROR,
        string $message = 'Erreur serveur'
    ): JsonResponse {
        $apiError = new APIError($code, $message);

        return $this->getError([$apiError], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function getOk(bool $res = true): JsonResponse
    {
        return new JsonResponse(['response' => $res]);
    }
}
