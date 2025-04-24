<?php

namespace api\modules\v2\components;

use Yii;
use common\models\Exchange;
use common\components\BaseFunctions;
use api\modules\v2\models\ApiChatbot;

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
		
		foreach ($result['data'] as $coin) {
			
			if (empty($coin['totalBalance'])) {
				continue;
			}
			
			$name = '';
			$symbol = '';
			$logo = '';
			$decimals = 0;

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
			$balance = number_format($balance, 10, '.', '');
			$balance = Exchange::formatValue($balance);

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

			$data[0][] = [
				'balance' => $balance,
				'name' =>$name,
				'currency' => $currency,
				'sort' => 0,
				'currency_value' => 0,
				'image' => $logo,
				'symbol' => $symbol,
				'symbolid' => strtolower($symbol),
				'grafema' => $grafema,
				'class' => '',
				'price' => '',
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