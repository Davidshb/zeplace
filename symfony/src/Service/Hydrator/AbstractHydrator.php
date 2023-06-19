<?php

namespace App\Service\Hydrator;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractHydrator
{
    public function __construct(
        protected SerializerInterface $serializer,
        protected ValidatorInterface $validator
    ) {
    }
}
