<?php
namespace common\components;

/**
 * Bybit
 */
Class Cryptoprice
{
	const ApiUrl     = 'https://cryptoprices.cc';
	 
	public $api_key=''; 
	public $secret_key=''; 	 
	 
	/**
	 * getApiUrlPrice($symbol='') 
	 */
    public static function getApiUrlPrice($symbol='') 
	{
        if (empty($symbol) || !is_string($symbol)) {
			return false;
		}

		$symbol = strtoupper($symbol);

		return self::ApiUrl . '/'.$symbol;
    } 
	 
	/**
	 * getPrice($symbol='') 
	 */
    public static function getPrice($symbol='') 
	{
        if (empty($symbol) || !is_string($symbol)) {
			return false;
		}
		
		$api_url = self::getApiUrlPrice($symbol);
		
		$header = [
			'Content-Type: application/json',
		];
	
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	
		$response = curl_exec($curl);

		curl_close($curl);

		if (empty($response)) {
			return [
				'error' => 0,
				'data' => 0,
			];	
		}	

		if (
			!is_float($response) && 
			!is_numeric($response)
		) {
			return [
				'error' => 0,
				'data' => 0,
			];	
		}

		return [
			'error' => 0,
			'data' => $response,
		];	
    }  
	 
	 
	 
	 
	 
	 
	 
}