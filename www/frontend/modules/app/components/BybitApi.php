<?php

namespace frontend\modules\app\components;

use Yii;

class BybitApi {

	const BybitApiUrl     = 'https://api.bybit.com';
    const BybitTestApiUrl = 'https://api-testnet.bybit.com';
	const RecvWindow = 20000;
	
	public $api_key=''; 
	public $secret_key='';
	public $uid='';

	/**
	 * getWalletBalanceUrl()
	 */
    public static function getWalletBalanceUrl() 
	{
        return self::BybitApiUrl . '/v5/asset/transfer/query-account-coins-balance';
		//asset/transfer/query-account-coins-balance
    }

	/**
	 * getWalletBalance($params='')
	 * Type account:
	 * SPOT
	 * CONTRACT
	 * UNIFIED
	 * OPTION
	 * INVESTMENT
	 * FUND - Active account
	 */
    public function getWalletBalance($type='FUND', $couns='') 
	{
		$data = [
			'active' => [],
			'trade' => [],
		];
		
		$params=[
			'accountType' => $type,
			'memberId' => $this->uid,
		];
		
		if ($type=='UNIFIED' && !empty($couns)) {
			
			$params['coin'] = $couns;
		}
		
		$params = http_build_query($params);
		
		if (!empty($params)) {
			$api_url = self::getWalletBalanceUrl() . '?' . $params;
		} else {
			$api_url = self::getWalletBalanceUrl();
		}

		$curl = curl_init();
		$time = time() * 1000;

		$str_sign= $time . $this->api_key . self::RecvWindow . $params;
		$sign = hash_hmac('sha256', $str_sign, $this->secret_key);

		$header = [
			'X-BAPI-API-KEY: '.$this->api_key,
			'X-BAPI-TIMESTAMP: '.$time,
			'X-BAPI-RECV-WINDOW: '.self::RecvWindow,
			'X-BAPI-SIGN: '.$sign,
			'Content-Type: application/json',
		];

		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_ENCODING, '');
		curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
		curl_setopt($curl, CURLOPT_TIMEOUT, 0);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_HTTPGET, true);
	
		$response = curl_exec($curl);

		curl_close($curl);
		
		if (empty($response)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not response'),
			];
		}
		
		if (!is_string($response)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect response'),
			];
		}
		
		$data = json_decode($response, true);
		if (!empty($data['retCode'])) {
			return [
				'error' => 1,
				'message' => $data['retMsg'],
			];
		}
		
		if (empty($data) || !is_array($data)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect response'),
			];
		}

		return [
			'error' => 0,
			'data' => $data,	
		];
    }

	/**
	 * pstatic($className=__CLASS__)
	 */ 
	public static function pstatic($className=__CLASS__)
	{
		return new $className;
	}
}