<?php 

class ConverterYahooApi extends CurrencyConverter {

	const API_HOST = "https://query.yahooapis.com/v1/public/yql";

	public function convert($from_currency, $to_currency, $amount)
	{
		$rate = $this->getRate($from_currency, $to_currency);

		return $amount * $rate;
	}

	public function getRate($from_currency, $to_currency)
	{
		if (is_null($this->_getRateFromCache($from_currency, $to_currency))) {
			$parameters = array(
				"q" => sprintf(
					'select * from yahoo.finance.xchange where pair in ("%s%s")',
					$from_currency, $to_currency
				),
				"format" => "json"
			);
			$response = $this->_makeRequest($parameters);
			if (!isset($response["query"]) || !isset($response["query"]["results"]["rate"])) {
				throw new Exception("Wrong API response format", 1);
			}
			$this->_setRateInCache($from_currency, $to_currency, $response["query"]["results"]["rate"]["Rate"]);
		}
		

		return $this->_getRateFromCache($from_currency, $to_currency);
	}

	private function _makeRequest($parameters)
	{
		if (empty($parameters)) {
			throw new Exception("The request query is empty", 1);
		}
		$url = self::API_HOST. "?". http_build_query($parameters). "&env=store://datatables.org/alltableswithkeys";
		$response = file_get_contents($url);
		if (empty($response)) {
			throw new Exception("API request failed", 0);
		}

		return json_decode($response, true);
	}

}