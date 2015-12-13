<?php

namespace CurrencyConverter\Converters;


use CurrencyConverter\Converter;
use CurrencyConverter\ConverterException;

class FixerIO extends Converter
{

    public function rate($from, $to)
    {
        if ($from == $to) {
            return 1;
        }
        $response = $this->send($from);
        if (!is_array($response) || !array_key_exists("rates", $response)) {
            throw new ConverterException("Wrong API response structure");
        } else if (!array_key_exists($to, $response["rates"])) {
            throw new ConverterException("{$to} is not supported");
        }

        return $response["rates"][$to];
    }

    private function send($currency)
    {
        $response = file_get_contents(sprintf(
            "http://api.fixer.io/latest?%s", http_build_query(["base" => $currency])
        ));
        if (empty($response)) {
            throw new ConverterException("API connection failed");
        }
        $response = json_decode($response, true);
        if (!is_array($response)) {
            throw new ConverterException("Wrong API response structure");
        } else if (
            array_key_exists("error", $response)
            && array_key_exists("status", $response["error"])
        ) {
            switch ($response["error"]["status"]) {

                case 422 :
                    throw new ConverterException("{$currency} is not supported");
                    break;

                default :
                    throw new ConverterException("Unexpected API error code: {$response["error"]["status"]}");
                    break;

            }
        }

        return $response;
    }

}