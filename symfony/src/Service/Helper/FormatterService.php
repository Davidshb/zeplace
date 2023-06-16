<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Service\Helper;

use App\Enum\CurrencyLocaleType;
use DateTimeInterface;
use IntlDateFormatter;
use NumberFormatter;

class FormatterService
{
    public function formatCurrencyByLocale(float|int $amount, CurrencyLocaleType $currencyLocaleType): string
    {
        $formatter = new NumberFormatter($currencyLocaleType->value, NumberFormatter::CURRENCY, $currencyLocaleType->name);
        return $formatter->format($amount);
    }

    public function formatDateByLocale(DateTimeInterface $dateTime): string
    {
        $formatter = new IntlDateFormatter('fr', IntlDateFormatter::LONG, IntlDateFormatter::NONE);
        return $formatter->format($dateTime);
    }
}