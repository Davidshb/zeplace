<?php

namespace App\Service;

use App\Dto\Output\UserSneakerDTO;
use App\Entity\UserSneaker;
use App\Enum\CurrencyLocaleType;
use App\Service\Helper\FormatterService;

readonly class UserSneakerService
{
    public function __construct(private FormatterService $formatterService)
    {
    }

    /**
     * @param array<UserSneaker> $userSneakers
     * @return array<UserSneakerDTO>
     */
    public function computeUserSneakerDTOs(array $userSneakers): array
    {
        $res = [];
        foreach ($userSneakers as $userSneaker) {
            $sneaker = $userSneaker->getSneaker();

            $dto = new UserSneakerDTO();
            $dto->title = sprintf("%s %s",$sneaker->getTitle(), $sneaker->getColorisName());
            $dto->sku = $sneaker->getSku();
            $dto->dropDate = $this->formatterService->formatDateByLocale($sneaker->getDropDate());
            $dto->sellingPrice = $this->formatterService->formatCurrencyByLocale(
                $userSneaker->getSellingPrice() / 100,
                CurrencyLocaleType::USD
            );
            $res[] = $dto;
        }

        return $res;
    }
}