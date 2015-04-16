<?php

use CurrencyConverter\Converter;

class ConverterTest extends PHPUnit_Framework_TestCase
{

    public function testRate(Converter $converter)
    {
        $this->assertEquals(1, $converter->rate("USD", "USD"));
    }

    public function testConvert(Converter $converter)
    {
        $this->assertEquals(10, $converter->convert("USD", "USD", 10));
    }

}