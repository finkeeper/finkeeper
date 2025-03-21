<?php

namespace frontend\modules\app\components;

use Yii;
use common\models\Exchange;
use common\components\BaseFunctions;
use frontend\modules\app\models\ApiChatbot;

class BybitApi {

	const BybitApiUrl     = 'https://api.bybit.com';
    const BybitTestApiUrl = 'https://api-testnet.bybit.com';
	const RecvWindow = 20000;
	
	public $api_key=''; 
	public $secret_key='';
	public $uid='';
	public $limitCoins = 10;

	/**
	 * getWalletBalanceUrl()
	 */
    public static function getWalletBalanceUrl() 
	{
        return self::BybitApiUrl . '/v5/asset/transfer/query-account-coins-balance';
		//asset/transfer/query-account-coins-balance
    }
	
	/**
	 * getWalletBalanceUrl()
	 */
    public static function getApiKeyInfoUrl() 
	{
        return self::BybitApiUrl . '/v5/user/query-api';
    }
	
	/**
	 * getWalletBalance()
	 */
    public function getWalletBalance() 
	{
		$currency = Exchange::getDefaultCurrency();
		$grafema = Exchange::getGrafemCurrency($currency);
		$data = [];
		$error = [];

		$response = $this->getBalance('FUND');
		if (empty($response['error'])) {
			
			if (
				!empty($response['data']['result']) && 
				!empty($response['data']['result']['balance'])
			) {
				$inc=1;
				$index=1;
				foreach ($response['data']['result']['balance'] as $key=>$val) {
		
					if ($inc>$this->limitCoins) {
						$inc=1;
						$index++;		
					}

					$list_coins[$index][] = strtoupper($val['coin']);
					$inc++;

					if (empty($val['walletBalance']) || empty($val['coin'])) {
						continue;
					}
					
					$value = 0;
					$price = [];
					$symbol = $val['coin'];
					$symbolid = strtolower($symbol);
					$coinid = strtolower($symbol).'0'.$key;
					
					$balance = number_format($val['walletBalance'], 12, '.', '');
				
					if (!empty($balance)) {
						if (is_float($balance)) {
							$balance = number_format($balance, 12, '.', '');
						} else if (is_int($balance)) {
							$balance = number_format($balance, 12, '.', '');
						} else {
							$balance = $balance*1;
							$balance = number_format($balance, 12, '.', '');
						}
					}
					
					if (empty($val['price'])) {
						$price = ApiChatbot::getPrice($symbolid, $currency, 1);
						if (!empty($price['error']) || empty($price['data'])) {
							$price['data'] = 0;
						}
					} else {
						$price['data'] = $val['price'];
					}
					
					if (empty($val['value'])) {
						$value = $price['data']*$balance;	
					} else {	
						$value = $val['value'];
					}
					
					if (!empty($value)) {
						if (is_float($value)) {
							$value = number_format($value, 12, '.', '');
						} else if (is_int($value)) {
							$value = number_format($value, 12, '.', '');
						} else {
							$value = $value*1;
							$value = number_format($value, 12, '.', '');
						}
					}
					
					$img = '/images/cryptologo/default_coin.webp';
					if (!empty($val['icon'])) {
						$img = $val['icon'];
					} else {
						$img_name = strtolower($symbol).'.webp';
						$path = getcwd().'/images/cryptologo/'.$img_name;
						if (file_exists($path)) {
							$img = '/images/cryptologo/'.$img_name;
						}
					}
			
					$currency_value = Exchange::formatValue($value);
					$class = 'middle_value';
					if ($currency_value<1) {
						$class = 'small_value';
					}
					
					$data['active'][] = [
						'balance' => $balance,
						'name' => $val['coin'],
						'currency' => $currency,
						'sort' => $value,
						'currency_value' => $currency_value,
						'img' => $img,
						'symbol' => $symbol,
						'symbolid' => $symbolid,
						'coinid' => $coinid,
						'grafema' => $grafema,
						'class' => $class,
						'price' => $price['data'],
						'network' => Yii::t('Frontend', 'Exchange').': Bybit',
						'network_icon' => '/images/logos/bybit2.png',
						'apr' => '',
						'asset' => $this->uid,
					];	
				}
			}
		} else {
			$error[] = addslashes($response['message']);
		}
		
		$str_coins = '';
		if (!empty($list_coins) && is_array($list_coins)) {
			
			foreach ($list_coins as $coins) {

				$str_coins = implode(',', $coins);

				$response = $this->getBalance('UNIFIED', $str_coins);
	
				if (empty($response['error'])) {
				
					if (
						!empty($response['data']['result']) && 
						!empty($response['data']['result']['balance'])
					) {
							
						foreach ($response['data']['result']['balance'] as $key=>$val) {
							
							if (empty($val['walletBalance']) || empty($val['coin'])) {
								continue;
							}
							
							$value = 0;
							$price = [];
							$symbol = $val['coin'];
							$symbolid = strtolower($symbol);
							$coinid = strtolower($symbol).'1'.$key;
							
							$balance = number_format($val['walletBalance'], 12, '.', '');
							
							if (!empty($balance)) {
								if (is_float($balance)) {
									$balance = number_format($balance, 12, '.', '');
								} else if (is_int($balance)) {
									$balance = number_format($balance, 12, '.', '');
								} else {
									$balance = $balance*1;
									$balance = number_format($balance, 12, '.', '');
								}
							}
					
							if (empty($val['price'])) {
								$price = ApiChatbot::getPrice($symbolid, $currency, 1);
								if (!empty($price['error']) || empty($price['data'])) {
									$price['data'] = 0;
								}
							} else {
								$price['data'] = $val['price'];
							}
					
							if (empty($val['value'])) {
								$value = $price['data']*$balance;	
							} else {	
								$value = $val['value'];
							}
							
							if (!empty($value)) {
								if (is_float($value)) {
									$value = number_format($value, 12, '.', '');
								} else if (is_int($value)) {
									$value = number_format($value, 12, '.', '');
								} else {
									$value = $value*1;
									$value = number_format($value, 12, '.', '');
								}
							}
					
							$img = '/images/cryptologo/default_coin.webp';
							if (!empty($val['icon'])) {
								$img = $val['icon'];
							} else {
								$img_name = strtolower($symbol).'.webp';
								$path = getcwd().'/images/cryptologo/'.$img_name;
								if (file_exists($path)) {
									$img = '/images/cryptologo/'.$img_name;
								}
							}
					
							$currency_value = Exchange::formatValue($value);
							$class = 'middle_value';
							if ($currency_value<1) {
								$class = 'small_value';
							}
							
							$data['trade'][] = [
								'balance' => $balance,
								'name' => $val['coin'],
								'currency' => $currency,
								'sort' => $value,
								'currency_value' => $currency_value,
								'img' => $img,
								'symbol' => $symbol,
								'symbolid' => $symbolid,
								'coinid' => $coinid,
								'grafema' => $grafema,
								'class' => $class,
								'price' => $price['data'],
								'network' => Yii::t('Frontend', 'Exchange').': Bybit',
								'network_icon' => '/images/logos/bybit2.png',
								'apr' => '',
								'asset' => $this->uid,
							];	
						}
					}
				} else {
					$error[] = addslashes($response['message']);
				}
			}
		}
		
		return [
			'error' => 0,
			'data' => $data,
			'errors' => $error,
		];
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
    public function getBalance($type='FUND', $couns='') 
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
	 * getApiKeyInfo($api_key='') 
	 */
    public function getApiKeyInfo() 
	{		
		$api_url = self::getApiKeyInfoUrl();
		
		$time = time() * 1000;

		$str_sign= $time . $this->api_key . self::RecvWindow;
		$sign = hash_hmac('sha256', $str_sign, $this->secret_key);

		
		$header = [
			'X-BAPI-API-KEY: '.$this->api_key,
			'X-BAPI-TIMESTAMP: '.$time,
			'X-BAPI-RECV-WINDOW: '.self::RecvWindow,
			'X-BAPI-SIGN: '.$sign,
			'Content-Type: application/json',
		];

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_ENCODING, '');
		curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
		curl_setopt($curl, CURLOPT_TIMEOUT, 0);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
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
		
		if (
			empty($data) || 
			!is_array($data) || 
			empty($data['result']) || 
			!is_array($data['result'])
		) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect response'),
			];
		}

		return [
			'error' => 0,
			'master' => !empty($data['result']['isMaster']) ? 1 : 0,
			'parent' => !empty($data['result']['parentUid']) ? $data['result']['parentUid'] : 0,
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