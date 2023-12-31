<?php

namespace App\Dto\Output;

use App\Enum\ShoeSize;

class EditUserSneakerDTO
{
    public ?int $id = null;

    public ?string $title = null;

    public ?string $imgUrl = null;

    public ?string $purchaseDate = null;

    public ?float $sellingPrice = null;

    public ?string $sellingDate = null;

    public ?float $purchasePrice = null;

    public ?float $shippingCost = null;

    public ?string $comment = null;

    public ?ShoeSize $size = null;
}
