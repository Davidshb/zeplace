<?php

namespace App\Dto\Output;

use App\Enum\ShoeSize;

class SaleShoeDTO
{
    public ?int $id = null;

    public ?string $name = null;

    public ?string $imgUrl = null;

    public ?string $purchaseDate = null;

    public ?string $sellingPrice = null;

    public ?ShoeSize $size = null;
    public ?string $purchasePrice = null;
}
