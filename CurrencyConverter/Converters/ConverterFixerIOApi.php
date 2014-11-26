<?php 

class ConverterFixerIOApi extends CurrencyConverter {

	const API_HOST = "http://api.fixer.io/latest";

	private static $_currencies = array();

	public function getRate($from_currency, $to_currency)
	{
		if (is_null($this->_getRateFromCache($from_currency, $to_currency))) {
			$response = $this->_makeRequest($from_currency);
			if (empty($response["rates"][$to_currency])) {
				throw new Exception("Currency `". $to_currency ."` is not supported", 1);
			}
			foreach ($response["rates"] as $currency => $rate) {
				$this->_setRateInCache($from_currency, $currency, $rate);
			}
		}
		
		return $this->_getRateFromCache($from_currency, $to_currency);
	}

	public function getCurrencies()
	{
		if (empty(self::$_currencies)) {
			$response = $this->_makeRequest();
			foreach ($response["rates"] as $currency => $rate) {
				array_push(self::$_currencies, $currency);
			}
		}

		return self::$_currencies;
	}

	private function _makeRequest($currency = null) 
	{
		$url = self::API_HOST. (!is_null($currency) 
			? "?" .http_build_query(array("base" => $currency))
			: ""
		);
		$response = file_get_contents($url);
		if (empty($response)) {
			throw new Exception("API request failed", 1);
		}

		$response = json_decode($response, true);
		if (isset($response["error"]) && isset($response["error"]["status"])) {
			switch ($response["error"]["status"]) {
				case 422:
					throw new Exception("Currency `". $currency ."` is not supported", 422);
					break;
				
				default:
					throw new Exception("Unexpected API error", -1);
					break;
			}
		} else if (!isset($response["base"]) || empty($response["rates"])) {
			throw new Exception("Unexpected API response strucutre", 2);
		}

		return $response;
	}

}