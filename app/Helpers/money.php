<?php

use Money\Money;
use Money\Parser;
use Money\Currency;
use Money\Currencies\ISOCurrencies;

if (! function_exists('currencies')) {
    /**
     * Get all available currencies
     *
     */
    function currencies()
    {
        return cache()->remember("currencies", 60*60*24, function() {
            $moneyCurrencies = new ISOCurrencies();

            foreach($moneyCurrencies as $currency) {
                $currencies[$currency->getCode()] = $moneyCurrencies->subunitFor($currency);
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
