<?php

use Money\Money;
use Money\Parser;
use Money\Currency;
use Money\Exchange;
use Money\Converter;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;

if (! function_exists('currencies')) {
    /**
     * Get all available currencies
     *
     * @return array
     */
    function currencies()
    {
        return cache()->remember("currencies", 60*60*24, function() {
            $moneyCurrencies = new ISOCurrencies();

            foreach($moneyCurrencies as $currency) {
                $currencies[$currency->getCode()] = [
                    //'currency' =>
                    'subunit' => $moneyCurrencies->subunitFor($currency),
                    'numericcode' => $moneyCurrencies->numericCodeFor($currency)
                ];
            }

            return $currencies;
        });
    }
}

if (! function_exists('makeMoney')) {
    /**
     * Create/Make a new immutable Money object
     *
     * @param mixed $amount
     * @param string $currency
     * @return \Money\Money
     */
    function makeMoney(mixed $amount, string $currency)
    {
        $currency = strtoupper($currency);
        $numberFormatter = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
        $currency = new Currency($currency);
        $currencies = new ISOCurrencies();

        $parser = new Parser\AggregateMoneyParser([
            new Parser\IntlMoneyParser($numberFormatter, $currencies),
            new Parser\DecimalMoneyParser($currencies),
        ]);

        return $parser->parse(number_format($amount, $currencies->subunitFor($currency), thousands_separator: ''), $currency);
    }
}

if (! function_exists('format')) {
    /**
     * Get a string representation of the Money Object.
     *
     * @param \Money\Money $money
     * @return string
     */
    function formatMoney(Money $money)
    {
        $currencies = new ISOCurrencies();
        $formatter = new DecimalMoneyFormatter($currencies);

        return $formatter->format($money);
    }
}


if (! function_exists('convertMoney')) {
    /**
     * Convert an amount in one currency to another currency using a specific rate,
     *
     * @param mixed $money
     * @param string $fromCurrency
     * @param string $toCurrency
     * @param mixed $rate
     * @return \Money\Money
     */
    function convertMoney(mixed $money, string $fromCurrency, string $toCurrency, mixed $rate)
    {
        if (! ($money instanceof Money)) {
            $money = makeMoney($money, $fromCurrency);
        }

        $currencies = new ISOCurrencies();
        $exchange = new Exchange\ReversedCurrenciesExchange(new Exchange\FixedExchange([
            $fromCurrency => [
                $toCurrency => (string) $rate
            ],
        ]));

        $converter = new Converter($currencies, $exchange);
        $toCurrency = $money->getCurrency()->getCode() === strtoupper($toCurrency) ? $fromCurrency: $toCurrency;

        return $converter->convert($money, new Currency($toCurrency));
    }
}
