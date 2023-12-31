<?php

namespace App\Service;

class CacheKeyService
{
    public const SOLD_SHOES_BASE_KEY = 'sold-shoes';
    public const SALE_SHOES_BASE_KEY = 'sale-shoes';
    public const USER_BASE_KEY = 'user';

    public function getSoldShoesCacheKey(int $userId): string
    {
        return sprintf("%s-%d", self::SOLD_SHOES_BASE_KEY, $userId);
    }

    public function getSaleShoesCacheKey(int $userId): string
    {
        return sprintf("%s-%d", self::SALE_SHOES_BASE_KEY, $userId);
    }

    public function getUserTag(int $userId): string
    {
        return sprintf('%s-%d', self::USER_BASE_KEY, $userId);
    }
}
