<?php

namespace App\Service\Helper;

use App\Enum\CurrencyLocaleType;
use DateTimeInterface;
use NumberFormatter;
use IntlDateFormatter;

final class FormatterService
{
    public function formatCurrencyByLocale(float|int|null $amount, CurrencyLocaleType $currencyLocaleType): string
    {
        $formatter = new NumberFormatter(
            $currencyLocaleType->value,
            NumberFormatter::CURRENCY,
            $currencyLocaleType->name
        );

        return $formatter->format($amount);
    }

    public function formatDateByLocale(DateTimeInterface $dateTime): string
    {
        $formatter = new IntlDateFormatter(
            'fr',
            IntlDateFormatter::LONG,
            IntlDateFormatter::NONE
        );

        return $formatter->format($dateTime);
    }
}
