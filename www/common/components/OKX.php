<?php
namespace common\components;

/**
 * Bybit
 */
Class OKX
{
	const ApiUrl     = 'https://www.okx.cab';
    const TestApiUrl = ''; 
	 
	public $api_key=''; 
	public $secret_key=''; 	 

	/**
	 * getWalletBalanceUrl()
	 * category: linear, inverse
	 * interval: 1, 3, 5, 15, 30, 60, 120, 240, 360, 720, D, M, W
	 * symbol: BTCUSDT
	 */
    public static function getApiUrlPrice($symbol='', $currency='usd', $interval='1', $category='linear') 
	{
        if (empty($symbol) || !is_string($symbol)) {
			return false;
		}

		$symbol = strtoupper($symbol);
		$currency = strtoupper($currency);

		return self::ApiUrl . '/api/v5/market/ticker?instId='.$symbol.'-'.$currency.'-SWAP';
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
		
		if (!empty($array['code'])) {
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
		
		$data = array_shift($array['data']);
		if (empty($data) || !is_array($data) || empty($data['last'])) {
			return [
				'error' => 0,
				'data' => 0,
			];
		}
		
		if (
			!is_float($data['last']) && 
			!is_numeric($data['last'])
		) {
			return [
				'error' => 0,
				'data' => 0,
			];	
		}

		return [
			'error' => 0,
			'data' => $data['last'],
		];
    }  
	 
	 
	 
	 
	 
	 
	 
}