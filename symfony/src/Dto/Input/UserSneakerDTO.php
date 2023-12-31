<?php

namespace App\Dto\Input;

use App\Dto\Attribute\HydratorAttribute;
use App\Enum\ApiErrorCode;
use App\Enum\HydratorAttributeType;
use App\Enum\ShoeSize;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

class UserSneakerDTO
{
    #[OA\Property(example: '2023-10-10', nullable: false)]
    #[Assert\NotBlank(message: "la date d'achat est requise", payload: ApiErrorCode::DATE_INVALID)]
    #[Assert\Date(message: "la date d'achat n'est pas valide", payload: ApiErrorCode::DATE_INVALID)]
    #[HydratorAttribute(attributeType: HydratorAttributeType::DATETIME)]
    public ?string $purchaseDate = null;

    #[OA\Property(example: 20.4, nullable: false)]
    #[Assert\Positive(message: "le prix d'achat doit être supérieur à 0", payload: ApiErrorCode::PRICE_POSITIVE)]
    #[Assert\NotBlank(message: "le prix d'achat est requis", payload: ApiErrorCode::PRICE_POSITIVE)]
    public ?float $purchasePrice = null;

    #[OA\Property(example: 2, nullable: false)]
    #[Assert\PositiveOrZero(
        message: "le cout d'expédition n'est pas valide",
        payload: ApiErrorCode::COST_POSITIVE_OR_ZERO
    )]
    #[Assert\NotBlank(message: "le cout d'expédition est requis", payload: ApiErrorCode::COST_POSITIVE_OR_ZERO)]
    public ?float $shippingCost = null;

    #[OA\Property(example: 'commentaire privé', nullable: false)]
    public ?string $comment = null;

    #[OA\Property(type: 'enum', enum: [ShoeSize::US10, ShoeSize::US11, '...'], example: 'US 9', nullable: false)]
    #[Assert\NotNull(message: "la taille de la paire n'est pas valide", payload: ApiErrorCode::SHOE_SIZE_INVALID)]
    public ?ShoeSize $size = null;

    #[OA\Property(example: 154.3, nullable: true)]
    #[Assert\PositiveOrZero(
        message: "le prix de vente n'est pas valide",
        payload: ApiErrorCode::SELLING_PRICE_INVALID
    )]
    public ?float $sellingPrice = null;

    #[OA\Property(example: '2023-10-10', nullable: true)]
    #[Assert\Date(message: "la date de vente n'est pas valide", payload: ApiErrorCode::DATE_INVALID)]
    #[HydratorAttribute(attributeType: HydratorAttributeType::DATETIME)]
    public ?string $sellingDate = null;
}
