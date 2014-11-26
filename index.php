<?php 

require "CurrencyConverter/CurrencyConverter.php";

$conveter = CurrencyConverter::getInstance(CurrencyConverter::CONVERTER_FREE_API);

$tpl = '<p><span>%s<span>: <span>%s</span></p>';

if ($conveter instanceof CurrencyConverter) {

	echo '<h2>Convert using: Free API</h2>';

	// Get rate
	printf($tpl, "Get rate", $conveter->getRate("USD", "EUR"));

	// Convert
	printf($tpl, "Convert amount", $conveter->convert("USD", "EUR", 12.5));	

	// Get currencies
	//printf("<pre>%s</pre>", print_r($conveter->getCurrencies(), true));

}

$conveter = CurrencyConverter::getInstance(CurrencyConverter::CONVERTER_YAHOO_API);

if ($conveter instanceof CurrencyConverter) {

	echo '<h2>Convert using: Yahoo Finance API</h2>';

	// Get rate
	printf($tpl, "Get rate", $conveter->getRate("USD", "EUR"));

	// Convert
	printf($tpl, "Convert amount", $conveter->convert("USD", "EUR", 12.5));	

}

$conveter = CurrencyConverter::getInstance(CurrencyConverter::CONVERTER_FIXER_IO_API);

if ($conveter instanceof CurrencyConverter) {

	echo '<h2>Convert using: Fixer.io</h2>';

	// Get rate
	printf($tpl, "Get rate", $conveter->getRate("USD", "EUR"));

	// Convert
	printf($tpl, "Convert amount", $conveter->convert("USD", "EUR", 12.5));	

	// Get available currencies
	//printf("<pre>%s</pre>", print_r($conveter->getCurrencies(), true));

}



