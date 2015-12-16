<?php

namespace Converters;

use CurrencyConverter\Converter;
use CurrencyConverter\ConverterException;

class Free extends Converter
{

    // NOTICE: Put here your API key
    const API_KEY = null;

    private $current_api_key;

    public function __construct($api_key = self::API_KEY)
    {
        $this->current_api_key = $api_key;
    }

    public function rate($from, $to)
    {
        $response = $this->send(
            'covert',
            array(
                'from' => $from,
                'to' => $to,
                'amount' => 1,
                'format' => 1,
            )
        );
        if (!is_array($response) || !isset($response['result'])) {
            throw new ConverterException('Invalid API response');
        }

        return $response['result'];
    }

    private function send($operation, array $query)
    {
        $endpoint = sprintf(
            'https://apilayer.net/api/%s?%s',
            $operation,
            http_build_query(
                array_merge(
                    $query,
                    array('access_key' => $this->current_api_key)
                )
            )
        );
        $response = file_get_contents($endpoint);
        if (empty($response)) {
            throw new ConverterException('Exchange provider connection error');
        }

        return json_decode($response, true);
    }

}