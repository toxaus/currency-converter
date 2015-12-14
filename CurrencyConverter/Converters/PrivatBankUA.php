<?php

namespace CurrencyConverter\Converters;

use \CurrencyConverter\Converter;
use \CurrencyConverter\ConverterException;

class PrivatBankUA extends Converter
{
    
    // Different request formats
    const PB_FORMAT_JSON = 'json';
    const PB_FORMAT_XML = 'xml';

    // Different currency rates
    const PB_RATE_CACHE = '5';
    const PB_RATE_CARDS = '11';

    private static $format = self::PB_FORMAT_JSON;
    private static $rate = self::PB_RATE_CARDS;

    private $url = 'https://api.privatbank.ua/p24api/pubinfo?exchange&%s&coursid=%s';

    public function __construct()
    {
        $this->url = sprintf($this->url, self::$format, self::$rate);
    }

    public function rate($from, $to)
    {
        $rate = null;

        $responseArray = $this->send();

        if (empty($responseArray) || !is_array($responseArray)) {
            throw new ConverterException('Wrong API response structure');
        }
        $currencyRates = $this->makeCurrencyRates($responseArray);
        if (isset($currencyRates[$from]) && isset($currencyRates[$from][$to])) {
            $rate = $currencyRates[$from][$to];
        } else {
            throw new ConverterException("Can not find rate for {$from} to {$to}");
        }


        return $rate;
    }

    private function send()
    {

        $response = file_get_contents($this->url);
        if (empty($response)) {
            throw new ConverterException('API connection failed');
        }

        return json_decode($response, true);
    }

    private function makeCurrencyRates(array $responseArray)
    {
        $currencyRates = array();

        foreach ($responseArray as $oneRate) {
            if (!isset($currencyRates[$oneRate['base_ccy']])) {
                $currencyRates[$oneRate['base_ccy']] = array(
                   $oneRate['base_ccy'] => 1,
                );
            }
            if (!isset($currencyRates[$oneRate['ccy']])) {
                $currencyRates[$oneRate['ccy']] = array(
                   $oneRate['ccy'] => 1,
                );
            }
            $currencyRates[$oneRate['base_ccy']][$oneRate['ccy']] = $oneRate['buy'];
            $currencyRates[$oneRate['ccy']][$oneRate['base_ccy']] = $oneRate['sale'];
        }

        return $currencyRates;
    }

}
