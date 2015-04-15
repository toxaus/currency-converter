<?php

namespace CurrencyConverter;

abstract class Converter
{

    abstract public function rate($from, $to);

    public function convert($from, $to, $amount)
    {
        return $amount * $this->rate($from, $to);
    }

}