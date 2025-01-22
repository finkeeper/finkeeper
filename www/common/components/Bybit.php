<?php
namespace common\components;

/**
 * Bybit
 */
Class Bybit
{
	const ApiUrl     = 'https://api.bybit.com';
    const TestApiUrl = 'https://api-testnet.bybit.com'; 
	 
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
		
		if ($currency=='usd') {
			$currency='usdt';
		}
		
		$symbol = strtoupper($symbol);
		$symbol .= strtoupper($currency);

		return self::ApiUrl . '/v5/market/index-price-kline?category='.$category.'&symbol='.$symbol.'&interval='.$interval;
    } 
	 
	/**
	 * getApiUrlPrice($symbol='', $currency='usd', $interval='1', $category='linear') 
	 * category: linear, inverse
	 * interval: 1, 3, 5, 15, 30, 60, 120, 240, 360, 720, D, M, W
	 * symbol: BTCUSDT
	 */
    public static function getPrice($symbol='', $currency='usd', $interval='1', $category='linear') 
	{
        if (empty($symbol) || !is_string($symbol)) {
			return false;
		}
		
		$api_url = self::getApiUrlPrice($symbol, $currency, $interval, $category);
		
		$header = [
			'Content-Type: application/json',
		];
	
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	
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
		
		if (!empty($array['retCode'])) {
			return [
				'error' => 0,
				'data' => 0,
			];
		}
		
		if (empty($array['result']) || empty($array['result']['list']) || !is_array($array['result']['list'])) {
			return [
				'error' => 0,
				'data' => 0,
			];
		}
		
		$data = array_shift($array['result']['list']);
		if (empty($data) || !is_array($data) || empty($data[1])) {
			return [
				'error' => 0,
				'data' => 0,
			];
		}

		if (
			!is_float($data[1]) && 
			!is_numeric($data[1])
		) {
			return [
				'error' => 0,
				'data' => 0,
			];	
		}

		return [
			'error' => 0,
			'data' => $data[1],
		];	
    }  
	 
	 
	 
	 
	 
	 
	 
}