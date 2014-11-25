<?php 

abstract class CurrencyConverter {

	const CONVERTER_FREE_API = "ConverterFreeApi";
	const CONVERTER_YAHOO_API = "ConverterYahooApi";

	protected static $_rates = array();
	protected static $_instances = array();

	private $_converter_type = null;

	public static function getInstance($converter)
	{
		if (isset(self::$_instances[$converter])) {
			return self::$_instances[$converter];
		} else {
			$file_path = dirname(__FILE__). DIRECTORY_SEPARATOR. "Converters". DIRECTORY_SEPARATOR;
			if (
				in_array($converter, array(self::CONVERTER_FREE_API, self::CONVERTER_YAHOO_API))
			) {
				$file_path .= $converter. ".php";
			} else {
				throw new Exception("Undefined converter type: ". $converter, 1);
			}
			if (!file_exists($file_path)) {
				throw new Exception("Converter file not found, path: ". $file_path, 2);
			}
			require_once($file_path);
			$instance = new $converter();
			$instance->_converter_type = $converter;
			self::$_instances[$converter] = $instance;
		}

		return self::$_instances[$converter];
	}

	public function __toString()
	{
		return print_r(self::$_rates[$this->_converter_type], true);
	}

	abstract public function convert($from_currency, $to_currency, $amount);

	abstract public function getRate($from_currency, $to_currency);

	protected function __construct()
	{

	}

	protected function _setRateInCache($from_currency, $to_currency, $rate)
	{
		self::$_rates[$this->_converter_type][$from_currency."_".$to_currency] = $rate;
	}

	protected function _getRateFromCache($from_currency, $to_currency)
	{
		return (isset(self::$_rates[$this->_converter_type][$from_currency."_".$to_currency]) 
			? self::$_rates[$this->_converter_type][$from_currency."_".$to_currency] : null
		);
	}

}