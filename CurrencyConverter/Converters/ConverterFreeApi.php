<?php 

class ConverterFreeApi extends CurrencyConverter {

	const API_HOST = "http://www.freecurrencyconverterapi.com/api/v2/";

	private static $_countries  = array();
	private static $_currencies = array();

	public function getRate($from_currency, $to_currency)
	{		
		if (is_null($this->_getRateFromCache($from_currency, $to_currency))) {
			$rate_key = $from_currency."_".$to_currency;
			$response = $this->_makeRequest(
				"convert", 
				array("q" => $rate_key, "compact" => "y")
			);
			if (!isset($response[$rate_key])) {
				throw new Exception("Wrong API response format", 1);
			}
			$this->_setRateInCache($from_currency, $to_currency, $response[$rate_key]["val"]);
		}

		return $this->_getRateFromCache($from_currency, $to_currency);
	}

	public function getCurrencies()
	{
		if (empty(self::$_currencies)) {
			$response = $this->_makeRequest("currencies", array());
			if (!isset($response["results"])) {
				throw new Exception("Wrong API response format", 1);
			}
			foreach ($response["results"] as $details) {
				self::$_currencies[$details["id"]] = array(
					"id" => $details["id"],
					"name" => $details["currencyName"],
					"country" => $details["country"],
				);
			} 
		}

		return self::$_currencies;
	}

	public function getCountries()
	{
		if (empty(self::$_currencies)) {
			$response = $this->_makeRequest("countries", array());
			if (!isset($response["results"])) {
				throw new Exception("Wrong API response format", 1);
			}
			foreach ($response["results"] as $details) {
				self::$_countries[$details["id"]] = array(
					"id" => $details["currencyId"],
					"name" => $details["name"],
					"iso_alpha3" => $details["alpha3"],
				);
			} 
		}

		return $self::$_countries;
	}

	private function _makeRequest($operation, $filters)
	{
		$url = self::API_HOST .$operation;
		if (!empty($filters)) {
			$url .= "?". http_build_query($filters);
		}
		$response = file_get_contents($url);
		if (empty($response)) {
			throw new Exception("API request failed", 0);
		}

		return json_decode($response, true);
	}

} 

