<?php

namespace App\Service;

use App\Entity\Sneaker;

class StockXService
{
    public function getStockXUrl(Sneaker $sneaker): string
    {
        return "https://www.sotckx.com/{$sneaker->getStockXLink()}";
    }
}
