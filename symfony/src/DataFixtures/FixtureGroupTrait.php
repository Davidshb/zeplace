<?php

namespace App\DataFixtures;
use App\Enum\FixturesGroupType;

trait FixtureGroupTrait
{
    public static function getGroups(): array
    {
        return array_map(
            fn(FixturesGroupType $groupType): string => $groupType->value,
            self::group
        );
    }
}