<?php

namespace App\Service\Helper;

use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

class SerializerService
{
    private const JSON_FORMAT = 'json';

    public function __construct(
        private readonly SerializerInterface $serializer
    ) {
    }

    public function toJSON(mixed $data, array $groups = []): string
    {
        $contextBuilder = (new ObjectNormalizerContextBuilder())
            ->withGroups($groups)
            ->withSkipNullValues(false)
            ->withSkipUninitializedValues(false);

        return $this->serializer->serialize(
            $data,
            SerializerService::JSON_FORMAT,
            $contextBuilder->toArray()
        );
    }
}
