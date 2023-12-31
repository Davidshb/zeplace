<?php

namespace App\Service;

use App\Dto\Output\EditUserSneakerDTO;
use App\Dto\Output\SaleShoeDTO;
use App\Dto\Output\SoldShoeDTO;
use App\Entity\UserSneaker;
use App\Enum\CurrencyLocaleType;
use App\Service\Helper\FormatterService;

readonly class UserSneakerService
{
    public function __construct(
        private FormatterService $formatterService
    ) {
    }

    /**
     * @param array<UserSneaker> $userSneakers
     * @return array<SoldShoeDTO>
     */
    public function computeSoldShoes(array $userSneakers): array
    {
        $dtos = [];

        foreach ($userSneakers as $userSneaker) {
            $sneaker = $userSneaker->getSneaker();

            $dto = new SoldShoeDTO();
            $dto->id = $userSneaker->getId();
            $dto->name = sprintf('%s %s', $sneaker->getTitle(), $sneaker->getColorisName());
            $dto->imgUrl = $sneaker->getThumbnailUrl();

            $dto->soldDate = $this->formatterService->formatDateByLocale($userSneaker->getSellingDate());

            $dto->soldPrice = $this->formatterService->formatCurrencyByLocale(
                $userSneaker->getSellingPrice(),
                CurrencyLocaleType::EUR
            );

            $profit = $userSneaker->getProfit();

            $dto->profitVal = $profit;

            $profitFormat = '';
            if ($profit > 0) {
                $profitFormat = '+';
            }

            $profitFormat .= $this->formatterService->formatCurrencyByLocale(
                $profit,
                CurrencyLocaleType::EUR
            );

            $dto->profit = $profitFormat;

            $dto->size = $userSneaker->getSize();

            $dtos[] = $dto;
        }

        return $dtos;
    }

    /**
     * @param array<UserSneaker> $userSneakers
     * @return array<SaleShoeDTO>
     */
    public function computeSaleShoes(array $userSneakers): array
    {
        $dtos = [];

        foreach ($userSneakers as $userSneaker) {
            $sneaker = $userSneaker->getSneaker();

            $dto = new SaleShoeDTO();
            $dto->id = $userSneaker->getId();
            $dto->name = sprintf('%s %s', $sneaker->getTitle(), $sneaker->getColorisName());
            $dto->imgUrl = $sneaker->getThumbnailUrl();
            $dto->purchaseDate = $this->formatterService->formatDateByLocale($userSneaker->getPurchaseDate());
            $dto->purchasePrice = $this->formatterService->formatCurrencyByLocale(
                $userSneaker->getPurchasePrice(),
                CurrencyLocaleType::EUR
            );
            $dto->sellingPrice = $this->formatterService->formatCurrencyByLocale(
                $userSneaker->getSellingPrice(),
                CurrencyLocaleType::EUR
            );

            $dto->size = $userSneaker->getSize();

            $dtos[] = $dto;
        }

        return $dtos;
    }

    public function computeEditUserSneaker(UserSneaker $userSneaker): EditUserSneakerDTO
    {
        $sneaker = $userSneaker->getSneaker();

        $dto = new EditUserSneakerDTO();

        $dto->id = $userSneaker->getId();
        $dto->title = "{$sneaker->getTitle()} {$sneaker->getColorisName()}";
        $dto->imgUrl = $sneaker->getImgUrl();
        $dto->purchasePrice = $userSneaker->getPurchasePrice();
        $dto->sellingPrice = $userSneaker->getSellingPrice();
        $dto->shippingCost = $userSneaker->getShippingCost();
        $dto->purchaseDate = $userSneaker->getPurchaseDate()->format('Y-m-d');
        $dto->sellingDate = $userSneaker->getSellingDate()?->format('Y-m-d');
        $dto->comment = $userSneaker->getComment();
        $dto->size = $userSneaker->getSize();

        return $dto;
    }
}
