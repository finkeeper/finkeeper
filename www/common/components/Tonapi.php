<?php
namespace common\components;

/**
 * Bybit
 */
Class Tonapi
{
	const ApiUrl     = 'https://tonapi.io/v2/rates';
    const TestApiUrl = ''; 	 

	/**
	 * getApiUrlPrice()
	 */
    public static function getApiUrlPrice($symbol='', $currency='usd') 
	{
        if (empty($symbol) || !is_string($symbol)) {
			return false;
		}

		$symbol = strtoupper($symbol);
		$currency = strtoupper($currency);
		
		return self::ApiUrl.'?tokens='.$symbol.'&currencies='.$currency;
    } 
	 
	/**
	 * getApiUrlPrice($symbol='', $currency='usd') 
	 */
    public static function getPrice($symbol='', $currency='usd') 
	{
		if (empty($symbol) || !is_string($symbol)) {
			return false;
		}
		
		$api_url = self::getApiUrlPrice($symbol, $currency);
		
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_HEADER, false);
	
		$response = curl_exec($curl);

		curl_close($curl);

		if (empty($response) || !is_string($response)) {
			return [
				'error' => 0,
				'data' => 0,
			];	
		}	
		
		$array = json_decode($response, true);
		if (empty($array) || !is_array($array)) {
			return [
				'error' => 0,
				'data' => 0,
			];	
		}	
		
		if (
			empty($array['rates']) || 
			empty($array['rates'][strtoupper($symbol)]) ||
			empty($array['rates'][strtoupper($symbol)]['prices'])
		) {
			return [
				'error' => 0,
				'data' => 0,
			];
		}
		
		if (empty($array['rates'][strtoupper($symbol)]['prices'][strtoupper($currency)])) {
			return [
				'error' => 0,
				'data' => 0,
			];	
		}

		if (
			!is_float($array['rates'][strtoupper($symbol)]['prices'][strtoupper($currency)]) && 
			!is_numeric($array['rates'][strtoupper($symbol)]['prices'][strtoupper($currency)])
		) {
			return [
				'error' => 0,
				'data' => 0,
			];	
		}
		
		return [
			'error' => 0,
			'data' => $array['rates'][strtoupper($symbol)]['prices'][strtoupper($currency)],
		];	
    }  	 
}