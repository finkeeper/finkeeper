<?php

namespace api\modules\v2\components;

use Yii;
use common\models\Exchange;

class TonApi {
	
	const TonURL = 'https://tonapi.io/v2/blockchain/accounts';
	const JettonsURL = 'https://tonapi.io/v2/accounts';
	const TonApiPriceURL = 'https://tonapi.io/v2/rates';
	const AddressParseURL = 'https://tonapi.io/v2/address';
	
	/**
	 * getTonBalance($address='')
	 */
    public function getTonBalance($address='') 
	{
		$currency = Exchange::getDefaultCurrency();
		$grafema = Exchange::getGrafemCurrency($currency);
		
		$data = [
			'balance' => 0,
			'name' => 'Ton',
			'currency' => $currency,
			'sort' => 0,
			'currency_value' => 0,
			'img' => '/images/cryptologo/ton.webp',
			'symbol' => 'TON',
			'symbolid' => 'ton',
			'grafema' => $grafema,
			'class' => '',
		];
		
		if (empty($address)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not Address'),
				'data' => $data,
			];
		}

		$url = self::TonURL.'/'.$address;
		
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
				'data' => $data,
			];
		
		} else if (empty($array['balance'])) {
			return [
				'error' => 0,
				'empty' => true,
				'data' => $data,
			];
		}
		
		$tonbalance = $array['balance'] / 1000000000;
		$tonbalance = number_format($tonbalance, 10, '.', '');

		return [
			'error' => 0,
			'data' => [
				'balance' => Exchange::formatValue($tonbalance),
				'name' => 'Ton',
				'currency' => $currency,
				'sort' => 0,
				'currency_value' => 0,
				'img' => '/images/cryptologo/ton.webp',
				'symbol'=> 'TON',
				'symbolid' => 'ton',
				'grafema' => $grafema,
				'class' => '',
			],
		];
	}
	
	/**
	 * getJettonsBalance($address='')
	 */
    public function getJettonsBalance($address='', $currency='usd') 
	{
		if (empty($address)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not Address'),
			];
		}
		
		$url = self::JettonsURL.'/'.$address.'/jettons?currencies='.$currency;

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

		$data = [];
		$inc = 1;
		$key_currency = strtoupper($currency);
		$grafema = Exchange::getGrafemCurrency($currency);

		foreach ($array['balances'] as $val) {
			$decimal = 0;
			for ($i=1; $i<=$val['jetton']['decimals']; $i++) {
				if (empty($decimal)) {
					$decimal = 10;
				} else {
					$decimal = $decimal*10;
				}
			}

			$valbalance = $val['balance'] / $decimal;
			$value = $val['price']['prices'][$key_currency]*$valbalance;
			
			$img = '/images/cryptologo/default_coin.webp';
			$img_name = strtolower($val['jetton']['symbol']);
			$path = getcwd().'/images/cryptologo/'.$img_name;

			if (file_exists($path)) {
				$img = '/images/cryptologo/'.$img_name;
			} else if (!empty($val['jetton']['image'])) {
				$img = $val['jetton']['image'];
			}
			
			$currency_value = Exchange::formatValue($value);
			$class = 'middle_value';
			if ($currency_value<1) {
				$class = 'small_value';
			}
			
			$valbalance = number_format($valbalance, 10, '.', '');
			
			if ($val['jetton']['symbol']=='USD₮') {
				$val['jetton']['symbol'] = 'USDT';
			}
			
			$data[$inc] = [
				'balance' => Exchange::formatValue($valbalance),
				'name' => $val['jetton']['name'],
				'currency' => $currency,
				'sort' => $value,
				'currency_value' => $currency_value,
				'img' => $img,
				'symbol'=> $val['jetton']['symbol'],
				'symbolid'=> strtolower($val['jetton']['symbol']),
				'grafema' => $grafema,
				'class' => $class,
				'price' => $val['price']['prices'][$key_currency],
			];
				
			$inc++;	
		}
		
		return [
			'error' => 0,
			'data' => $data,
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
		
		$url = self::TonApiPriceURL.'?tokens='.$token.'&currencies='.$currency;
	
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
	 * getAddressParse($address='')
	 */ 
	public function getAddressParse($address='')
	{
		if (empty($address)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not Address'),
			];
		}
		
		$url = self::AddressParseURL.'/'.$address.'/parse';
	
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
	 * pstatic($className=__CLASS__)
	 */ 
	public static function pstatic($className=__CLASS__)
	{
		return new $className;
	}
}