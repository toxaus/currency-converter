<?php 

require "CurrencyConverter.php";

$conveter = new CurrencyConverter();

//echo "Get countries: <br />";
//printf('<pre>%s</pre>', print_r($conveter->getCountries(), true));

//echo "Get curencies: <br />";
//printf('<pre>%s</pre>', print_r($conveter->getCurrencies(), true));

echo "Get rate: <br />";
printf('<pre></pre>', print_r($conveter->getRate("EUR", "USD")));

echo "Convert value: <br />";
printf('<pre></pre>', print_r($conveter->convert("EUR", "USD", 10)));

