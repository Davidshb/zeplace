<?php

namespace App\Service\Hydrator;

use App\Dto\Input\SneakerDTO;
use App\Entity\Sneaker;
use App\Repository\BrandRepository;
use DateTimeImmutable;
use Exception;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SneakerHydrator extends AbstractHydrator
{
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        private readonly BrandRepository $brandRepository
    ) {
        parent::__construct($serializer, $validator);
    }

    public function hydrateSneakerDTO(string $json): ?SneakerDTO
    {
        try {
            $sneakerDTO = $this->serializer->deserialize($json, SneakerDTO::class, 'json');
        } catch (Exception) {
            return null;
        }

        if ($this->validator->validate($sneakerDTO)->count() > 0) {
            return null;
        }

        return $sneakerDTO;
    }

    public function hydrateSneaker(Sneaker $sneaker, SneakerDTO $sneakerDto): void
    {
        $sneaker->setTitle($sneakerDto->title);
        $sneaker->setSku($sneakerDto->sku);
        $sneaker->setDescription($sneakerDto->description);
        $sneaker->setColorisName($sneakerDto->colorisName);
        $sneaker->setColorisCode($sneakerDto->colorisCode);
        $sneaker->setRetailPrice($sneakerDto->retailPrice);
        $sneaker->setStockXLink($sneakerDto->stockXLink);
        $sneaker->setDropDate(DateTimeImmutable::createFromFormat('d/m/Y|', $sneakerDto->dropDate));

        $brand = $this->brandRepository->findOrCreate($sneakerDto->brand);

        $sneaker->setBrand($brand);
    }
}
