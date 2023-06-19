<?php

namespace App\Service\Hydrator;

use App\Dto\Input\LoginDTO;
use App\Exception\MultipleApiError;
use App\Security\APIError;
use Exception;

class LoginHydrator extends AbstractHydrator
{
    /**
     * @throws MultipleApiError
     */
    public function hydrateLoginDTO(string $json): ?LoginDTO
    {
        try {
            $loginDTO = $this->serializer->deserialize($json, LoginDTO::class, 'json');
        } catch (Exception) {
            return null;
        }

        $errors = $this->validator->validate($loginDTO);

        if ($errors->count() > 0) {
            $apiErrors = [];
            foreach ($errors as $error) {
                $apiError = new APIError();
                $apiError->setErrorMessage($error->getMessage());
                $apiError->setErrorCode($error->getCause()['constraint']['payload']);
                $apiErrors[] = $apiError;
            }
            throw new MultipleApiError($apiErrors);
        }

        return $loginDTO;
    }
}
