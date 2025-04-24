<?php

namespace frontend\modules\app\components;

use Yii;
use common\models\Exchange;
use common\components\BaseFunctions;
use frontend\modules\app\models\ApiChatbot;

class BTCApi {
	
	public $address=''; 
	public $api_key = '';
	public $api_url = '';
	public $decimals = 8;
	public $symbol = 'btc';
	
	/**
	 * construct
	 */
	function __construct() {
		
		$conf = Exchange::getConfig(3);
		if (empty($conf) || !is_array($conf) || empty($conf['btc']) || !is_array($conf['btc'])) {
			return false;
		}
		
		if (
			empty($conf['btc']['apikey']) ||
			empty($conf['btc']['apiurl'])
		) {
			return false;
		}

		$this->api_key = $conf['btc']['apikey'];
		$this->api_url = $conf['btc']['apiurl'];
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
		
		$result = $this->getBtcBalance();
		if (!empty($result['error'])) {
			return [
				'error' => 1,
				'message' => $result['message'],
				'data' => $data,
			];
		}
	
		if (empty($result) || empty($result['data'])) {
			return [
				'error' => 0,
				'message' => Yii::t('Api', 'Not BTC Active'),
				'data' => $data,
			];
		}
		
		$summ = 0;
		$amount = (int) $result['data'];
		$decimals = BaseFunctions::getDecimalsNumber($this->decimals);
		$balance = $amount / $decimals;
		$balance = number_format($balance, 12, '.', '');
			
		$value = 0;
		$symbol = strtoupper($this->symbol);
		$symbolid = strtolower($symbol);
		$coinid = strtolower($symbol).'01';
		$name = strtoupper($symbol);
			
		$price = 0;
		if (empty($result['data']['price'])) {
			$price = ApiChatbot::getPrice($symbolid, $currency, 1);
			if (empty($price['error']) && !empty($price['data'])) {
				$value = $price['data']*$balance;	
			}
		} else {
			$price['data'] = $result['data']['price'];
			$value = $result['data']['price']*$balance;
		}
			
			
		$logo = '';
		if (!empty($result['data']['logo'])) {
			$logo = $coin['logo'];
		}
			
		if (empty($logo)) {
			$logo = '/images/cryptologo/default_coin.webp';
			$img_name = strtolower($symbol);
			$path = getcwd().'/images/cryptologo/'.$img_name.'.webp';
			if (file_exists($path)) {
				$logo = '/images/cryptologo/'.$img_name.'.webp';
			}
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
					
		$currency_value = Exchange::formatValue($value);
		$class = 'middle_value';
		if ($currency_value<1) {
			$class = 'small_value';
		}
						
		$address = $this->getAddressParse($this->address);
			
		$data[] = [
			'balance' => $balance,
			'negative' => 0,
			'name' =>$name,
			'currency' => $currency,
			'sort' => $value,
			'currency_value' => $currency_value,
			'img' => $logo,
			'symbol' => $symbol,
			'symbolid' => $symbolid,
			'coinid' => $coinid,
			'grafema' => $grafema,
			'class' => $class,
			'price' => $price['data'],
			'network' => Yii::t('Frontend', 'Wallet').' BTC',
			'network_icon' => '/images/logos/btc2.png',
			'apr' => '',
			'asset' => $address,
		];			

		return [
			'error' => 0,
			'data' => $data,
		];
    }
	
	/**
	 * getSolBalance()
	 */
    public function getBtcBalance() 
	{	
		$api_url = $this->api_url.'/'.$this->address;

		$header = [
			'Content-Type: application/json',
		];

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		//curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36");
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		//curl_setopt($curl, CURLOPT_COOKIEFILE, 'user_cookie_file.txt');
		//curl_setopt($curl, CURLOPT_COOKIEJAR, 'user_cookie_file.txt');
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_REFERER, $api_url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
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
		if (empty($data['chain_stats']) || !isset($data['chain_stats']['funded_txo_sum'])) {			
			$message = Yii::t('Error', 'Response error');
			if (!empty($data['message'])) {
				$message = $data['message'];
			}
				
			return [
				'error' => 1,
				'message' => $message,
			];
		}

		return [
			'error' => 0,
			'data' => $data['chain_stats']['funded_txo_sum'],	
		];
	}
	
	/**
	 * getAddressParse($address='')
	 */ 
	public function getAddressParse($address='')
	{
		return $address;
	}

	/**
	 * pstatic($className=__CLASS__)
	 */ 
	public static function pstatic($className=__CLASS__)
	{
		return new $className;
	}
}