<?php declare(strict_types=1);

namespace Freshleafmedia\MoneyCast;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Exception\UnknownCurrencyException;
use Money\Money;
use Money\Parser\DecimalMoneyParser;

final class MoneyCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): ?Money
    {
        if ($value === null) {
            return null;
        }

        $currencyCode = substr($value, 0, 3);
        $amount = substr($value, 3);
        $currency = new Currency($currencyCode);

        if ((new ISOCurrencies())->contains($currency) === false) {
            throw new UnknownCurrencyException("Unknown currency code [$currencyCode] in value [$value].");
        }

        return new Money($amount, $currency);
    }

    public function set($model, string $key, $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof Money) {
            return $value->getCurrency()->getCode() . $value->getAmount();
        }

        $currencyCode = substr($value, 0, 3);
        $amount = substr($value, 3);

        $money = (new DecimalMoneyParser(new ISOCurrencies()))
            ->parse($amount, new Currency($currencyCode));

        return $money->getCurrency()->getCode() . $money->getAmount();
    }
}