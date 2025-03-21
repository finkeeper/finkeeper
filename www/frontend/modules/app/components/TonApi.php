<?php

namespace frontend\modules\app\components;

use Yii;
use common\models\Exchange;
use common\components\BaseFunctions;
use frontend\modules\app\models\ApiChatbot;

class TonApi {
	
	public $address='';
	public $decimals = 9;
	
	/**
	 * construct
	 */
	function __construct() {
		
		$conf = Exchange::getConfig(3);
		if (empty($conf) || !is_array($conf) || empty($conf['ton']) || !is_array($conf['ton'])) {
			return false;
		}
		
		if (empty($conf['ton']['apiurl'])) {
			return false;
		}

		$this->api_url = $conf['ton']['apiurl'];
	}
	
	/**
	 * getWalletBalance()
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
		
		$result = $this->getTonBalance();
		if (!empty($result['error'])) {
			return [
				'error' => 1,
				'message' => !empty($result['message']) ? $result['message'] : Yii::t('Error', 'No balance'),
				'data' => $data,
			];
		}
		
		if (empty($result) || empty($result['data'])) {
			return [
				'error' => 0,
				'message' => Yii::t('Api', 'Not Ton Active'),
				'data' => $data,
			];
		}
		
		$value = 0;
		$price = [];
		$amount = (int) $result['data'];
		$decimals = BaseFunctions::getDecimalsNumber($this->decimals);
		$balance = $amount / $decimals;
		$balance = number_format($balance, 12, '.', '');
		
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
		
		if (empty($result['price'])) {
			$price = ApiChatbot::getPrice('ton', $currency, 2);
			if (!empty($price['error']) || empty($price['data'])) {
				$price['data'] = 0;
			}
		} else {
			$price['data'] = $result['price'];
		}
		
		if (empty($result['value'])) {
			$value = $price['data']*$balance;	
		} else {	
			$value = $result['value'];
		}

		$currency_value = Exchange::formatValue($value);
		$class = 'middle_value';
		if ($currency_value<1) {
			$class = 'small_value';
		}
		
		$address = $this->getAddressParse($this->address);
		
		$data[] = [
			'balance' => $balance,
			'name' => 'Ton',
			'currency' => $currency,
			'sort' => $value,
			'currency_value' => $currency_value,
			'img' => '/images/cryptologo/ton.webp',
			'symbol' => 'TON',
			'symbolid' => 'ton',
			'coinid' => 'ton00',
			'grafema' => $grafema,
			'class' => $class,
			'price' => $price['data'],
			'network' => Yii::t('Frontend', 'Wallet').' Ton',
			'network_icon' => '/images/logos/tonkeeper2.png',
			'apr' => '',
			'asset' => $this->address,
		];
		
		$result =  $this->getJettonsBalance($currency);
		if (!empty($result['error'])) {
			return [
				'error' => 1,
				'message' => !empty($result['message']) ? $result['message'] : Yii::t('Error', 'No balance'),
				'data' => $data,
			];
		}
		
		if (empty($result) || empty($result['data']) || !empty($result['empty'])) {
			return [
				'error' => 0,
				'message' => Yii::t('Api', 'Not Ton Active'),
				'data' => $data,
			];
		}
		
		foreach ($result['data'] as $key=>$val) {
					
			if ($this->parseCoin($val['jetton']['symbol'])) {
				$val['jetton']['symbol'] = $this->parseCoin($val['jetton']['symbol']);
			}
			
			
			$value = 0;
			$price = [];
			$symbol = $val['jetton']['symbol'];
			$symbolid = strtolower($symbol);
			$coinid = strtolower($symbolid).'1'.$key;
			
			$amount = (int) $val['balance'];
			$decimals = BaseFunctions::getDecimalsNumber($val['jetton']['decimals']);
			$balance = $amount / $decimals;
			$balance = number_format($balance, 12, '.', '');

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
			
			$cyrid = strtoupper($currency);
			
			if (empty($val['price']['prices'][$cyrid])) {
				$price = ApiChatbot::getPrice($symbolid, $currency, 2);
				if (!empty($price['error']) || empty($price['data'])) {
					$price['data'] = 0;
				}
			} else {
				$price['data'] = $val['price']['prices'][$cyrid];
			}
			
			if (empty($val['value'])) {
				$value = $price['data']*$balance;	
			} else {	
				$value = $val['attributes']['value'];
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
			if (!empty($val['jetton']['image'])) {
				$img = $val['jetton']['image'];
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
			
			$address = $this->getAddressParse($this->address);
			
			$data[] = [
				'balance' => $balance,
				'name' => $val['jetton']['name'],
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
				'network' => Yii::t('Frontend', 'Wallet').' Ton',
				'network_icon' => '/images/logos/tonkeeper2.png',
				'apr' => '',
				'asset' => $this->address,
			];
		}

		return [
			'error' => 0,
			'data' => $data,
		];
	}
	
	
	/**
	 * getTonBalance()
	 */
    public function getTonBalance() 
	{
		$url = $this->api_url.'blockchain/accounts/'.$this->address;

		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_URL => $url,
		]);

		$response = curl_exec($ch);
		curl_close($ch);

		$data = [];
		if (empty($response)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not response'),
				'data' => $data,
			];
		}
		
		if (!is_string($response)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect response'),
				'data' => $data,
			];
		}

		$array = json_decode($response, true);
		if (empty($array) || !is_array($array)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect response'),
				'data' => $data,
			];
		}

		if (!empty($array['error']) && $array['error']=='entity not found') {
			
			return [
				'error' => 0,
				'empty' => true,
				'data' => [],
			];
		
		} else if (empty($array['balance'])) {
			return [
				'error' => 0,
				'empty' => true,
				'data' => [],
			];
		}
		
		return [
			'error' => 0,
			'data' => $array['balance'],	
		];
	}
	
	/**
	 * getJettonsBalance($address='')
	 */
    public function getJettonsBalance($currency='usd') 
	{
		$url = $this->api_url.'accounts/'.$this->address.'/jettons?currencies='.$currency;

		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_URL => $url,
		]);
		
		$response = curl_exec($ch);
		curl_close($ch);

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
		
		$array = json_decode($response, true);		
		if (empty($array) || !is_array($array)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect1 response'),
			];
		}
		
		if (empty($array['balances']) || !is_array($array['balances'])) {
			return [
				'error' => 0,
				'empty' => true,
			];
		}
		
		return [
			'error' => 0,
			'data' => $array['balances'],
		];
	}

	/**
	 * getTonApiPrice($token='', $currency='usd')
	 */ 
	public function getTonApiPrice($token='', $currency='usd')
	{
		if (empty($token)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not Token'),
				'data' => 0,
			];
		}
		
		$url = $this->api_url.'rates/?tokens='.$token.'&currencies='.$currency;
	
		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_URL => $url,
		]);
		
		$response = curl_exec($ch);
		curl_close($ch);

		if (empty($response)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not response'),
				'data' => 0,
			];
		}
		
		if (!is_string($response)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect response'),
				'data' => 0,
			];
		}
		
		$array = json_decode($response, true);
		if (empty($array) || !is_array($array)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect response'),
				'data' => 0,
			];
		}
		
		if (
			empty($array['rates']) || 
			empty($array['rates'][strtoupper($token)]) ||
			empty($array['rates'][strtoupper($token)]['prices'])
		) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing Balance'),
				'data' => 0,
			];
		}
		
		if (empty($array['rates'][strtoupper($token)]['prices'][strtoupper($currency)])) {
			return [
				'error' => 0,
				'data' => 0,
			];	
		}	

		if (
			!is_float($array['rates'][strtoupper($token)]['prices'][strtoupper($currency)]) && 
			!is_numeric($array['rates'][strtoupper($token)]['prices'][strtoupper($currency)])
		) {
			return [
				'error' => 0,
				'data' => 0,
			];	
		}
		
		return [
			'error' => 0,
			'data' => $array['rates'][strtoupper($token)]['prices'][strtoupper($currency)],
		];	
	}
	
	/**
	 * getAddressParse()
	 */ 
	public function getAddressParse($address='')
	{
		if (empty($address)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not Address'),
			];
		}
		
		$url = $this->api_url.'address/'.$address.'/parse';
	
		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_URL => $url,
		]);
		
		$response = curl_exec($ch);
		curl_close($ch);

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
		
		$array = json_decode($response, true);
		if (empty($array) || !is_array($array)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect response'),
			];
		}
		
		if (
			empty($array['non_bounceable']) || 
			empty($array['non_bounceable']['b64url'])
		) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Error Parse'),
			];
		}
		
		return $array['non_bounceable']['b64url'];
	}	
	
	/**
	 * parseCoin(token)
	 */
	public function parseCoin($token='')
	{
		if (empty($token)) {
			return false;
		}
		
		$token = strtolower($token);

		$tokens = [
			'usd₮' => 'USDT',
		];
		
		if (empty($tokens[$token])) {
			return false;
		}
		
		return $tokens[$token];	
	}

	/**
	 * pstatic($className=__CLASS__)
	 */ 
	public static function pstatic($className=__CLASS__)
	{
		return new $className;
	}
}