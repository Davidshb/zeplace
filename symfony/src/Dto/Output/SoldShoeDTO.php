<?php

namespace App\Dto\Output;

use App\Enum\ShoeSize;

class SoldShoeDTO
{
    public ?int $id = null;

    public ?string $name = null;

    public ?string $imgUrl = null;

    public ?string $soldDate = null;

    public ?string $soldPrice = null;

    public ?string $profit = null;

    public ?float $profitVal = null;

    public ?ShoeSize $size = null;
}
