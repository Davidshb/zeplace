<?php

namespace App\Service\Hydrator;

use App\Dto\Attribute\HydratorAttribute;
use App\Enum\ApiErrorCode;
use App\Enum\HydratorAttributeType;
use App\Exception\MultipleApiError;
use App\Security\APIError;
use DateTime;
use Exception;
use ReflectionClass;

class DTOHydrator extends AbstractHydrator
{
    /**
     * @template T of object
     *
     * @psalm-param string $json
     * @psalm-param class-string<T> $class
     * @psalm-return  T
     * @throws MultipleApiError
     * @throws Exception
     */
    public function hydrateFromJson(string $json, string $class)
    {
        try {
            /** @psalm-var T $dto */
            $dto = $this->serializer->deserialize($json, $class, 'json');
        } catch (Exception) {
            throw new Exception();
        }

        $errors = $this->validator->validate($dto);

        if ($errors->count() > 0) {
            $apiErrors = [];
            foreach ($errors as $error) {
                $apiError = new APIError(
                    $error->getConstraint()->payload ?? ApiErrorCode::GENERIC_MISSING_PAYLOAD,
                    $error->getMessage()
                );
                $apiErrors[] = $apiError;
            }
            throw new MultipleApiError($apiErrors);
        }

        return $dto;
    }

    /**
     * @throws Exception
     */
    public function hydrateFromDto(object $dto, object $entity): void
    {
        $rf = new ReflectionClass($dto);
        $properties = $rf->getProperties();

        foreach ($properties as $property) {
            $setter = "set" . ucfirst($property->getName());

            $value = $property->getValue($dto);

            $hydrytorType = $property->getAttributes(HydratorAttribute::class)[0] ?? null;

            if ($value !== null && $hydrytorType !== null) {
                dump($hydrytorType->getArguments());
                $value = match ($hydrytorType->getArguments()['attributeType']) {
                     HydratorAttributeType::DATETIME => new DateTime($value)
                };
            }

            $entity->$setter($value);
        }
    }
}
