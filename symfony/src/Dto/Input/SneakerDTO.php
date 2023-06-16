<?php

namespace App\Dto\Input;

use Symfony\Component\Validator\Constraints as Assert;

class SneakerDTO
{
    #[Assert\NotBlank]
    public ?string $title = null;

    #[Assert\NotNull]
    public ?string $description = null;

    #[Assert\NotBlank]
    public ?string $sku = null;

    #[Assert\NotBlank]
    public ?string $colorisCode = null;

    #[Assert\NotBlank]
    public ?string $colorisName = null;

    #[Assert\NotBlank]
    public ?int $retailPrice = null;

    #[Assert\NotBlank]
    public ?string $stockXLink = null;

    #[Assert\NotBlank]
    public ?string $brand = null;

    #[Assert\NotBlank]
    public ?string $dropDate = null;
}