<?php

namespace App\DataFixtures\Traits;

use App\Enum\FixturesGroupType;

trait FixtureGroupTrait
{
    public static function getGroups(): array
    {
        return array_map(
            fn(FixturesGroupType $groupType): string => $groupType->value,
            self::GROUP
        );
    }
}
