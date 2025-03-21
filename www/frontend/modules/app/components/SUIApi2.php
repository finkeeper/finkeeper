<?php

namespace frontend\modules\app\components;

use Yii;
use common\models\Exchange;
use common\components\BaseFunctions;
use frontend\modules\app\models\ApiChatbot;

class SUIApi2 {
	
	public $address=''; 
	public $api_key = '';
	public $api_url = '';
	public $decimals = 9;
	
	/**
	 * construct
	 */
	function __construct() {
		
		$conf = Exchange::getConfig(3);
		if (empty($conf) || !is_array($conf) || empty($conf['sui']) || !is_array($conf['sui'])) {
			return false;
		}
		
		if (
			empty($conf['sui']['apikey2']) ||
			empty($conf['sui']['apiurl2'])
		) {
			return false;
		}

		$this->api_key = $conf['sui']['apikey2'];
		$this->api_url = $conf['sui']['apiurl2'];
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
				'message' => $result['message'],
				'data' => $data,
			];
		}
	
		if (empty($result) || empty($result['data'])) {
			return [
				'error' => 0,
				'message' => Yii::t('Api', 'Not Sui Active'),
				'data' => $data,
			];
		}
		
		$summ = 0;
		foreach ($result['data'] as $key=>$coin) {
			
			if (empty($coin['totalBalance'])) {
				continue;
			}
			
			$name = '';
			$symbol = '';
			$logo = '';
			$decimals = 0;
			$price = [];

			$coin_metadata = $this->getCoinMetadata($coin['coinType']);
			
			if (empty($coin_metadata['error']) && !empty($coin_metadata['data'])) {
				
				if (!empty($coin_metadata['data']['decimals'])) {
					$decimals = $coin_metadata['data']['decimals'];
				}
				
				if (!empty($coin_metadata['data']['name'])) {
					$name = $coin_metadata['data']['name'];
				}
				
				if (!empty($coin_metadata['data']['symbol'])) {
					$symbol = $coin_metadata['data']['symbol'];
				}
				
				
				if (!empty($coin_metadata['data']['iconUrl'])) {
					$logo = $coin_metadata['data']['iconUrl'];
				}
			}
			
			if (empty($decimals)) {
				$decimals = $this->decimals;
			}
	
			$amount = (int) $coin['totalBalance'];
			$decimals = BaseFunctions::getDecimalsNumber($decimals);
			$balance = $amount / $decimals;
			$balance = number_format($balance, 12, '.', '');

			if (empty($name)) {
				preg_match_all('/(.*?::)([A-Z \_ 0-9]{1,})$/', $coin['coinType'], $matches);
				if (!empty($matches) && !empty($matches[2]) && !empty($matches[2][0])) {
					$name = $matches[2][0];
				}

				if ($name=='SCALLOP_SCA') {
					
					$name='sSCA';
					
				} else if ($name=='MSEND_SERIES_1') {
					
					$name='mSEND';
					
				} else if ($name=='ASUIIO') {
					
					$name='ASUI.IO';
					
				} else if ($name=='SCALLOP_SUI') {
					
					$name='sSUI';
					
				} else if ($name=='SCALLOP_USDC') {
					
					$name='sUSDC';
				}
			}
			
			if (empty($symbol)) {
				$symbol = strtoupper($name);
			}
			
			$value = 0;
			$symbolid = strtolower($symbol);
			$coinid = strtolower($symbol).$key;
			
			if (empty($coin['price'])) {
				$price = ApiChatbot::getPrice($symbolid, $currency, 1);
				if (empty($price['error']) && !empty($price['data'])) {
					$value = $price['data']*$balance;	
				}
			} else {
				$price['data'] = $coin['price'];
				$value = $coin['price']*$balance;
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
				'network' => Yii::t('Frontend', 'Wallet').' Sui',
				'network_icon' => '/images/logos/sui2.png',
				'apr' => '',
				'asset' => $address,
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
		$api_url = $this->api_url;

		$header = [
			'Content-Type: application/json',
		];
		
		$data = [
			'jsonrpc' => '2.0',
			'id' => time(),
			'method' => 'suix_getAllBalances',
			'params' => [
				$this->address,
			],
		];

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
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

		if (
			empty($data) || 
			!is_array($data) || 
			empty($data['result']) || 
			!is_array($data['result'])
		) {			
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'No response'),
			];
		}
		
		if (!empty($data['error'])) {			
			
			$message = Yii::t('Error', 'Response error').': ';
			if (!empty($data['error']['message'])) {
				$message .= $data['message'];
			}
			
			return [
				'error' => 1,
				'message' => $message,
			];
		}

		return [
			'error' => 0,
			'data' => $data['result'],	
		];
	}
	
	/**
	 * getCoinMetadata($coin_type='') 
	 */ 
	public function getCoinMetadata($coin_type='') 
	{
		if (empty($coin_type)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not coin type'),
			];
		}
		
		$api_url = $this->api_url;

		$header = [
			'Content-Type: application/json',
		];
		
		$data = [
			'jsonrpc' => '2.0',
			'id' => time(),
			'method' => 'suix_getCoinMetadata',
			'params' => [
				$coin_type,
			],
		];
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);  
		//curl_setopt($curl, CURLOPT_PORT, 8443);
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

		if (
			empty($data) || 
			!is_array($data) || 
			empty($data['result']) || 
			!is_array($data['result'])
		) {			
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'No response'),
			];
		}
		
		if (!empty($data['error'])) {			
			
			$message = Yii::t('Error', 'Response error').': ';
			if (!empty($data['error']['message'])) {
				$message .= $data['message'];
			}
			
			return [
				'error' => 1,
				'message' => $message,
			];
		}

		return [
			'error' => 0,
			'data' => $data['result'],	
		];
	}
	/**
	 * transactionStatusSui($digest='')
	 */
	public function transactionStatusSui($digest='')
	{
		if (empty($digest)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not digest'),
			];
		}
		
		$api_url = $this->api_url;

		$header = [
			'Content-Type: application/json',
		];
		
		$data = [
			'jsonrpc' => '2.0',
			'id' => time(),
			'method' => 'sui_getTransactionBlock',
			'params' => [
				$digest,
				[
					'showInput' => false,
					'showRawInput' => false,
					'showEffects' => true,
					'showEvents' => false,
					'showObjectChanges' => false,
					'showBalanceChanges' => false,
				],
			],
		];
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
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

		if (
			empty($data) || 
			!is_array($data) || 
			empty($data['result']) || 
			!is_array($data['result']) ||
			empty($data['result']['effects']) || 
			!is_array($data['result']['effects']) ||
			empty($data['result']['effects']['status']) || 
			!is_array($data['result']['effects']['status']) ||
			empty($data['result']['effects']['status']['status'])
		) {			
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'No response'),
			];
		}
		
		if (!empty($data['error'])) {			
			
			$message = Yii::t('Error', 'Response error').': ';
			if (!empty($data['error']['message'])) {
				$message .= $data['message'];
			}
			
			return [
				'error' => 1,
				'message' => $message,
			];
		}

		return [
			'error' => 0,
			'data' => $data['result']['effects']['status']['status'],	
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