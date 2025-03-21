<?php

namespace frontend\modules\app\components;

use Yii;
use common\models\Exchange;
use common\components\BaseFunctions;
use frontend\modules\app\models\ApiChatbot;

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
			
			if (!empty($response['data'])) {
			
				foreach ($response['data'] as $key=>$val) {
				
					if (empty($val['availBal']) || empty($val['ccy'])) {
						continue;
					}
					
					$value = 0;
					$price = [];
					$symbol = $val['ccy'];
					$symbolid = strtolower($symbol);
					$coinid = strtolower($symbol).'0'.$key;
					
					$balance = number_format($val['availBal'], 12, '.', '');
				
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
						'name' => $val['ccy'],
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
						'network' => Yii::t('Frontend', 'Exchange').': OKX',
						'network_icon' => '/images/logos/okx2.png',
						'apr' => '',
						'asset' => $this->uid,
					];	
				}
			}
		} else {
			$error[] = addslashes($response['message']);
		}
		
		$response = $this->getBalance('UNIFIED');
		if (empty($response['error'])) {
			
			if (!empty($response['data'])) {
				
				foreach ($response['data'] as $val) {

					if (!empty($val['details']) && is_array($val['details'])) {
						
						foreach ($val['details'] as $key=>$asset) {	
						
							if (empty($asset['availBal']) || empty($asset['ccy'])) {
								continue;
							}
							
							$value = 0;
							$price = [];
							$symbol = $asset['ccy'];
							$symbolid = strtolower($symbol);
							$coinid = strtolower($symbol).'0'.$key;
							
							$balance = number_format($asset['availBal'], 12, '.', '');
						
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
							
							if (empty($asset['price'])) {
								$price = ApiChatbot::getPrice($symbolid, $currency, 1);
								if (!empty($price['error']) || empty($price['data'])) {
									$price['data'] = 0;
								}
							} else {
								$price['data'] = $asset['price'];
							}
							
							if (empty($asset['value'])) {
								$value = $price['data']*$balance;	
							} else {	
								$value = $asset['value'];
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
							if (!empty($asset['icon'])) {
								$img = $asset['icon'];
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
								'name' => $asset['ccy'],
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
								'network' => Yii::t('Frontend', 'Exchange').': OKX',
								'network_icon' => '/images/logos/okx2.png',
								'apr' => '',
								'asset' => $this->uid,
							];	
						}
					}
				}
			}
		} else {
			$error[] = addslashes($response['message']);
		}

		return [
			'error' => 0,
			'data' => $data,
			'errors' => $error,
		];
	}

	/**
	 * getWalletBalance($params='')
	 */
    public function getBalance($type='FUND') 
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