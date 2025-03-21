<?php

namespace frontend\modules\app\components;

use Yii;
use common\models\Exchange;
use common\components\BaseFunctions;
use frontend\modules\app\models\ApiChatbot;

class APTApi {
	
	public $address=''; 
	public $api_key = '';
	public $api_url = '';
	
	/**
	 * construct
	 */
	function __construct() {
		
		$conf = Exchange::getConfig(3);
		if (empty($conf) || !is_array($conf) || empty($conf['apt']) || !is_array($conf['apt'])) {
			return false;
		}
		
		if (
			empty($conf['apt']['apikey']) || 
			empty($conf['apt']['apiurl'])
		) {
			return false;
		}

		$this->api_key = $conf['apt']['apikey'];
		$this->api_url = $conf['apt']['apiurl'];
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
		
		$result = $this->getAptBalance();
		if (!empty($result['error'])) {
			return [
				'error' => 1,
				'message' => $result['message'],
				'data' => $data,
			];
		}
		
		if (!empty($result['data']['balances'])) {

			foreach ($result['data']['balances'] as $key=>$val) {
				
				if ($this->parseCoin($val['token'])) {
					$val['asset'] = $this->parseCoin($val['token']);
				}
				
				$value = 0;
				$price = [];
				$symbol = $val['asset'];
				$symbolid = strtolower($symbol);
				$coinid = strtolower($symbolid).$key;
				$balance = number_format($val['balance'], 12, '.', '');
				
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
					$img_name = strtolower($val['asset']).'.webp';
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
					'name' => $val['provider'],
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
					'network' => Yii::t('Frontend', 'Wallet').' Aptos',
					'network_icon' => '/images/logos/apt2.png',
					'network_link' => '',
					'apr' => '',
					'asset' => $address,
					'protocol' => '',
				];	
			}
		}
		
		if (!empty($result['data']['positions'])) {
			
			foreach ($result['data']['positions'] as $key=>$val) {
			
				if ($this->parseCoin($val['token'])) {
					$val['asset'] = $this->parseCoin($val['token']);
				}
			
				$value = 0;
				$price = [];
				$symbol = $val['asset'];
				$symbolid = strtolower($symbol);
				$coinid = strtolower($symbolid).$key;
				$balance = number_format($val['amount'], 12, '.', '');
			
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
					$img_name = strtolower($val['asset']).'.webp';
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

				$protocol_icon = '/images/logos/apt2.png';
				$protocol_link = '';
				if ($val['protocol']=='Echelon') {
					
					$protocol_icon = '/images/logos/echelon2.png';
					$protocol_link = 'https://app.echelon.market/dashboard?network=aptos_mainnet';
		
				} else if ($val['protocol']=='Joule') {
					
					$protocol_icon = '/images/logos/joule2.png';
					$protocol_link = 'https://app.joule.finance/rewards?tabId=referral&referralAddress=0x56ff2fc971deecd286314fe99b8ffd6a5e72e62eacdc46ae9b234c5282985f97';
	
				} 

				$apr = '';
				if (!empty($val['supplyApr'])) {
					$apr = Exchange::formatValue($val['supplyApr'], 1);
				}

				$data[] = [
					'balance' => $balance,
					'name' => $val['provider'],
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
					'network' => Yii::t('Frontend', 'Wallet').' Aptos',
					'network_icon' => $protocol_icon,
					'network_link' => $protocol_link,
					'apr' => Yii::t('Api', 'APR').' '.$apr.'%',
					'asset' => $address,
					'protocol' => Yii::t('Api', 'Protocol').': '.$val['protocol'],
				];	
			}
		}

		return [
			'error' => 0,
			'data' => $data,
		];
    }
	
	/**
	 * getAptBalance()
	 */
    public function getAptBalance() 
	{	

		$api_url = $this->api_url;
		$api_url = $api_url.'?address='.$this->address;

		$header = [
			'Content-Type: application/json',
			'Authorization: Bearer '.$this->api_key,
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
		if (empty($data) || !is_array($data) || !isset($data['balances']) || !is_array($data['balances'])) {
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
	 * getAddressParse($address='')
	 */ 
	public function getAddressParse($address='')
	{
		return $address;
	}
	
	/**
	 * parseCoin(token)
	 */
	public function parseCoin($token='')
	{
		if (empty($token)) {
			return false;
		}
		
		$tokens = [
			'0xf22bede237a07e121b56d91a491eb7bcdfd1f5907926a9e58338f964a01b17fa::asset::USDT' => 'lzUSDT',
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