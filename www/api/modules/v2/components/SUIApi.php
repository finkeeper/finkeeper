<?php

namespace api\modules\v2\components;

use Yii;
use common\models\Exchange;
use common\components\BaseFunctions;

class SUIApi {
	
	public $address=''; 
	public $api_key = '';
	public $api_url = '';
	
	/**
	 * construct
	 */
	function __construct() {
		
		$conf = Exchange::getConfig(3);
		if (empty($conf) || !is_array($conf) || empty($conf['solana']) || !is_array($conf['solana'])) {
			return false;
		}
		
		if (
			empty($conf['sui']['apikey']) ||
			empty($conf['sui']['apiurl'])
		) {
			return false;
		}

		$this->api_key = $conf['sui']['apikey'];
		$this->api_url = $conf['sui']['apiurl'];
	}

	/**
	 * getWalletBalance($params='')
	 */
    public function getWalletBalance() 
	{
		$currency = Exchange::getDefaultCurrency();
		$grafema = Exchange::getGrafemCurrency($currency);
		$data = [];

		if (empty($this->address)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not Address'),
				'data' => $data,
			];
		}
		
		$result = $this->getSuiBalance();
		if (!empty($result['error'])) {
			return [
				'error' => 1,
				'messsage' => $result['message'],
				'data' => $data,
			];
		}
	
		if (empty($result) || empty($result['data'])) {
			return [
				'error' => 0,
				'messsage' => Yii::t('Api', 'Not Sui Active'),
				'data' => $data,
			];
		}
		
		foreach ($result['data']['coins'] as $coin) {
			
			if (empty($coin['balance'])) {
				continue;
			}

			$amount = (int) $coin['balance'];
			$decimals = BaseFunctions::getDecimalsNumber($coin['decimals']);
			$balance = $amount / $decimals;
			$balance = number_format($balance, 10, '.', '');
			$balance = Exchange::formatValue($balance);
			
			$data[0][] = [
				'balance' => $balance,
				'name' => $coin['name'],
				'currency' => $currency,
				'sort' => 0,
				'currency_value' => 0,
				'img' => !empty($coin['logo']) ? $coin['logo'] : '/images/cryptologo/sui.webp',
				'symbol' => $coin['symbol'],
				'symbolid' => strtolower($coin['symbol']),
				'grafema' => $grafema,
				'class' => '',
				'price' => !empty($coin['price']) ? $coin['price'] : 0,
			];			
		}

		return [
			'error' => 0,
			'data' => $data,
		];
    }
	
	/**
	 * getSolBalance()
	 */
    public function getSuiBalance() 
	{	
		$api_url = $this->api_url.'='.$this->address;

		$curl = curl_init();

		$header = [
			'Content-Type: application/json',
			'x-api-key: '.$this->api_key,
		];

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		$response = curl_exec($curl);
		curl_close($curl);

		if (empty($response)) {
			return [
				'error' => 1,
				'messsage' => Yii::t('Error', 'Not response'),
			];
		}
	
		if (!is_string($response)) {
			return [
				'error' => 1,
				'messsage' => Yii::t('Error', 'Incorrect response'),
			];
		}

		$data = json_decode($response, true);
		if (
			empty($data) || 
			!is_array($data) || 
			empty($data['code']) || 
			$data['code']!=200 ||
			empty($data['result']) || 
			!is_array($data['result']) ||
			!is_array($data['result']['coins'])
		) {			
			$message = Yii::t('Error', 'Response error');
			if (!empty($data['message'])) {
				$message = $data['message'];
			}
				
			return [
				'error' => 1,
				'messsage' => $message,
			];
		}

		if (empty($data['result']['coins'])) {
			return [
				'error' => 0,
				'data' => [],
			];
		}

		return [
			'error' => 0,
			'data' => $data['result'],	
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