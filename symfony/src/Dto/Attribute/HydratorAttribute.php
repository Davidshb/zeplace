<?php

namespace App\Dto\Attribute;

use App\Enum\HydratorAttributeType;
use Attribute;

#[Attribute]
class HydratorAttribute
{
    public function __construct(public readonly HydratorAttributeType $attributeType)
    {
    }
}
