<?php

namespace CurrencyConverter\Converters;

use CurrencyConverter\Converter;
use CurrencyConverter\ConverterException;

class Yahoo extends Converter
{

    public function rate($from, $to)
    {
        $response = $this->send("select * from yahoo.finance.xchange where pair in (\"{$from}{$to}\")");
        if (!is_array($response) || !isset($response["query"]["results"]["rate"]["Rate"])
           || (!isset($response["query"]["results"]["rate"]["Rate"])
            || $response["query"]["results"]["rate"]["Rate"] == 'N/A'
           )
        ) {
            throw new ConverterException("Wrong API response structure");
        }

        return $response["query"]["results"]["rate"]["Rate"];
    }

    private function send($query, $format = "json")
    {

        $url      = sprintf(
           "https://query.yahooapis.com/v1/public/yql?%s",
           http_build_query([
              "q"      => $query,
              "format" => $format,
              "env"    => "store://datatables.org/alltableswithkeys",
           ])
        );
        $response = file_get_contents($url);
        if (empty($response)) {
            throw new ConverterException("API connection failed");
        }

        return json_decode($response, true);
    }

}