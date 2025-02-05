<?php

namespace frontend\modules\app\components;

use Yii;
use common\components\BaseFunctions;

class OKXApi {

	const OKXApiUrl     = 'https://www.okx.cab';
    const OKXTestApiUrl = '';
	
	public $api_key=''; 
	public $secret_key='';
	public $password='';

	/**
	 * getFUNDBalanceUrl()
	 */
    public static function getFUNDBalanceUrl() 
	{
        return '/api/v5/asset/balances';
    }
	
	/**
	 * getUNIFIEDBalanceUrl()
	 */
    public static function getUNIFIEDBalanceUrl() 
	{
        return '/api/v5/account/balance';
    }

	/**
	 * getWalletBalance($params='')
	 */
    public function getWalletBalance($type='FUND') 
	{
		$data = [
			'active' => [],
			'trade' => [],
		];
		
		/*
		$params=[
			'accountType' => $type,
			'memberId' => $this->uid,
		];
		
		$params = http_build_query($params);
		if (!empty($params)) {
			$api_url = self::getWalletBalanceUrl() . '?' . $params;
		} else {
			$api_url = self::getWalletBalanceUrl();
		}
		*/
		
		$api_url = '';
		if ($type=='FUND') {
			$api_url = self::getFUNDBalanceUrl();
		} else if ($type=='UNIFIED') {
			$api_url = self::getUNIFIEDBalanceUrl();
		}

		$curl = curl_init();
		$time = BaseFunctions::getDateFormatTZ('utc', 3);
		$sign = base64_encode(hash_hmac('sha256', $time.'GET'.$api_url, $this->secret_key, true));

		$header = [
			'OK-ACCESS-KEY: '.$this->api_key,
			'OK-ACCESS-TIMESTAMP: '.$time,
			'OK-ACCESS-PASSPHRASE: '.$this->password,
			'OK-ACCESS-SIGN: '.$sign,
			'Content-Type: application/json',
		];

		curl_setopt($curl, CURLOPT_URL, self::OKXApiUrl.$api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_ENCODING, '');
		curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
		curl_setopt($curl, CURLOPT_TIMEOUT, 0);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
		//curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
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
		if (!empty($data['msg']) && !empty($data['code'])) {
			return [
				'error' => 1,
				'message' => $data['msg'],
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
			'data' => $data['data'],	
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