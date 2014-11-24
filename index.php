<?php 

require "CurrencyConverter/CurrencyConverter.php";

$conveter = CurrencyConverter::getInstance(CurrencyConverter::CONVERTER_FREE_API);

if ($conveter instanceof CurrencyConverter) {

	// Get rate
	echo $conveter->getRate("USD", "EUR"). "<br />";

	// Convert
	echo $conveter->convert("USD", "EUR", 12.5). "<br />";	

}


