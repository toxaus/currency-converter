<?php

use CurrencyConverter\Converter;
use CurrencyConverter\ConverterException;

class Free extends Converter
{

    public function rate($from, $to)
    {
        $key = $from."_".$to;
        $response = $this->send("convert", ["q" => $key, "compact" => "y"]);
        if (!is_array($response) && !array_key_exists($key, $response)) {
            throw new ConverterException("Wrong API response structure");
        }

        return $response[$key];
    }

    private function send($operation, $query)
    {
        $response = file_get_contents(sprintf(
            "http://www.freecurrencyconverterapi.com/api/v2/%s?%s",
            $operation, http_build_query($query)
        ));
        if (empty($response)) {
            throw new ConverterException("API connection failed");
        }

        return json_decode($response, true);
    }

}