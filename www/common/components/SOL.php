<?php
namespace common\components;

/**
 * SOL
 */
Class SOL
{
	const ApiUrl     = 'https://api.jup.ag/price/v2?ids';
    const TestApiUrl = '';  	 

	/**
	 * getWalletBalanceUrl()
	 * category: linear, inverse
	 * interval: 1, 3, 5, 15, 30, 60, 120, 240, 360, 720, D, M, W
	 * symbol: BTCUSDT
	 */
    public static function getApiUrlPrice($token='') 
	{
        if (empty($token) || !is_string($token)) {
			return false;
		}

		return self::ApiUrl.'='.$token;
    }

	/**
	 * getApiUrlPrice($symbol='', $currency='usd') 
	 */
    public static function getPrice($token='', $currency='usd') 
	{
        if (empty($token) || !is_string($token)) {
			return false;
		}
		
		$api_url = self::getApiUrlPrice($token);
		
		$header = [
			'Content-Type: application/json',
		];

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
	
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

		if (empty($array['data']) || !is_array($array['data'])) {
			return [
				'error' => 0,
				'data' => 0,
			];
		}
		
		foreach ($array['data'] as $token=>$value) {
			
			if (empty($value) || empty($value['price'])) {
				return [
					'error' => 0,
					'data' => 0,
				];
			}
			
			return [
				'error' => 0,
				'data' => $value['price'],
			];	
		}
    }  
}	