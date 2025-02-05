<?php

namespace api\modules\v2\components;

use Yii;
use common\models\Exchange;
use common\components\SOL;
use common\components\BaseFunctions;

class SOLApi {
	
	public $address=''; 
	public $api_key = '';
	public $program_id = '';
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
			empty($conf['solana']['apikey']) ||
			empty($conf['solana']['programid']) ||
			empty($conf['solana']['decimals']) || 
			empty($conf['solana']['apiurl'])
		) {
			return false;
		}

		$this->api_key = $conf['solana']['apikey'];
		$this->program_id = $conf['solana']['programid'];
		$this->api_url = $conf['solana']['apiurl'];
		$this->decimals = $conf['solana']['decimals'];
	}

	/**
	 * getWalletBalance($params='')
	 */
    public function getWalletBalance() 
	{
		$currency = Exchange::getDefaultCurrency();
		$grafema = Exchange::getGrafemCurrency($currency);
		$data = [];
		
		$data[0] = [
			'balance' => 0,
			'name' => 'Sol',
			'currency' => $currency,
			'sort' => 0,
			'currency_value' => 0,
			'img' => '/images/cryptologo/sol.webp',
			'symbol' => 'SOL',
			'symbolid' => 'sol',
			'grafema' => $grafema,
			'class' => '',
		];
		
		if (empty($this->address)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not Address'),
				'data' => $data,
			];
		}
		
		$result = $this->getSolBalance();
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
				'message' => Yii::t('Api', 'Not SOL Active'),
				'data' => $data,
			];
		}
		
		$amount = (int) $result['data'];
		$decimals = BaseFunctions::getDecimalsNumber($this->decimals);
		$solbalance = $amount / $decimals;

		$solbalance = number_format($solbalance, 10, '.', '');
		
		$data[0]['balance'] = Exchange::formatValue($solbalance);
		
		return [
			'error' => 0,
			'data' => $data,
		];
    }
	
	/**
	 * getSolBalance()
	 */
    public function getSolBalance() 
	{	
		$api_url = $this->api_url.'?api-key='.$this->api_key;
	
		$curl = curl_init();

		$header = [
			'Content-Type: application/json',
		];
		
		$id = time();
		
		$params = '{"jsonrpc": "2.0", "id": '.$id.', "method": "getBalance", "params": ["'.$this->address.'"]}';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
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
		if (!empty($data['error'])) {
			
			$message = Yii::t('Error', 'Response error');
			if (!empty($data['error']['message'])) {
				$message = $data['error']['message'];
			}
				
			return [
				'error' => 1,
				'message' => $message,
			];
		}
		
		if (
			empty($data) || 
			!is_array($data) || 
			empty($data['result']) || 
			!is_array($data['result']) ||
			empty($data['result']['value'])
		) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect response'),
			];
		}

		return [
			'error' => 0,
			'data' => $data['result']['value'],	
		];
	}
	
	/**
	 * getJettonsBalance()
	 */
    public function getTokenBalance() 
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
		
		$result =  $this->getTokenAccountsByOwner($this->address);
		if (!empty($result['error'])) {
			return [
				'error' => 1,
				'message' => !empty($result['message']) ? $result['message'] : Yii::t('Error', 'No token'),
				'data' => $data,
			];
		}

		foreach ($result['data'] as $value) {
			
			if (empty($value['price'])) {
				$price = SOL::pstatic()->getPrice($value['mint']);
				if (empty($price['error']) && !empty($price['data'])) {
					$value['price'] = $price['data'];
				}
			}

			$data[] = [
				'balance' => $value['coin_balance'],
				'price' => $value['price'],
				'name' => $value['name'],
				'currency' => $currency,
				'sort' => $value['coin_balance'],
				'currency_value' => 0,
				'img' => $value['image'],
				'symbol' => strtoupper($value['symbol']),
				'symbolid' => strtolower($value['symbol']),
				'grafema' => $grafema,
				'class' => '',
			];
		}

		return [
			'error' => 0,
			'data' => $data,
		];	
	}
	
	/**
	 * getTokenAccountsByOwner($address='')
	 */
    public function getTokenAccountsByOwner($address='') 
	{
		if (empty($address)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing Address'),
			];
		}
		
		$api_url = $this->api_url.'?api-key='.$this->api_key;
	
		$curl = curl_init();

		$header = [
			'Content-Type: application/json',
		];
		
		$id = time();
		
		$params = '{"jsonrpc": "2.0", "id": '.$id.', "method": "getTokenAccountsByOwner", "params": ["'.$this->address.'", {"programId": "'.$this->program_id.'"}, {"encoding": "jsonParsed"}]}';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
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
		if (!empty($data['error'])) {
			
			$message = Yii::t('Error', 'Response error');
			if (!empty($data['error']['message'])) {
				$message = $data['error']['message'];
			}
				
			return [
				'error' => 1,
				'message' => $message,
			];
		}

		if (
			empty($data) || 
			!is_array($data) || 
			empty($data['result']) || 
			!is_array($data['result']) ||
			empty($data['result']['value']) ||
			!is_array($data['result']['value'])
		) {
			return [
				'error' => 0,
				'data' => [],
			];
		}
		
		$formating_data = [];
		foreach ($data['result']['value'] as $value) {
			if (
				empty($value) || 
				!is_array($value) || 
				empty($value['pubkey']) || 
				empty($value['account']) ||
				!is_array($value['account']) ||
				empty($value['account']['data']) ||
				!is_array($value['account']['data']) ||
				empty($value['account']['data']['parsed']) ||
				!is_array($value['account']['data']['parsed']) ||
				empty($value['account']['data']['parsed']['info']) ||
				!is_array($value['account']['data']['parsed']['info']) ||
				empty($value['account']['data']['parsed']['info']['mint'])
			) {
				continue;
			}
			
			
			$balance = $this->getTokenAccountBalance($value['pubkey']);
			if (!empty($balance['error'])) {
				continue;
			}
			
			$coininfo = $this->getAsset($value['account']['data']['parsed']['info']['mint']);
			if (!empty($coininfo['error'])) {
				continue;
			}
			
			$formating_data[] = [
				'mint' => $value['account']['data']['parsed']['info']['mint'],
				'publickey' => $value['pubkey'],
				'balance' => $balance['data']['balance'],
				'coin_balance' => $balance['data']['coin_balance'],
				'name' => $coininfo['data']['name'],
				'symbol' => $coininfo['data']['symbol'],
				'image' => $coininfo['data']['image'],
				'decimals' => $coininfo['data']['decimals'],
				'price' => $coininfo['data']['price'],
				'currency' => $coininfo['data']['currency'],
			];
		}

		return [
			'error' => 0,
			'data' => $formating_data,
		];
	}
	
	/**
	 * getTokenBalance($account_token='')
	 */
    public function getTokenAccountBalance($account_token='') 
	{
		if (empty($account_token)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing Account Token'),
			];
		}
		
		$api_url = $this->api_url.'?api-key='.$this->api_key;
	
		$curl = curl_init();

		$header = [
			'Content-Type: application/json',
		];
		
		$id = time();
		
		$params = '{"jsonrpc": "2.0", "id": '.$id.', "method": "getTokenAccountBalance", "params": ["'.$account_token.'"]}';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
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
		if (!empty($data['error'])) {
			
			$message = Yii::t('Error', 'Response error');
			if (!empty($data['error']['message'])) {
				$message = $data['error']['message'];
			}
				
			return [
				'error' => 1,
				'message' => $message,
			];
		}
		
		if (
			empty($data) || 
			!is_array($data) || 
			empty($data['result']) || 
			!is_array($data['result']) ||
			empty($data['result']['value']) || 
			empty($data['result']['value']['decimals']) ||
			empty($data['result']['value']['amount'])
		) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect response'),
			];
		}
		
		$amount = (int) $data['result']['value']['amount'];
		$decimals = BaseFunctions::getDecimalsNumber($data['result']['value']['decimals']);
		$coinbalance = $amount / $decimals;
		$coinbalance = number_format($coinbalance, 10, '.', '');
		$coinbalance = Exchange::formatValue($coinbalance);
		
		$data = [
			'decimals' => $data['result']['value']['decimals'],
			'balance' => $data['result']['value']['amount'],
			'coin_balance' => $coinbalance,
		];
		
		return [
			'error' => 0,
			'data' => $data,
		];
	}
	
	/**
	 * getAsset($token_mint='')
	 */
    public function getAsset($token_mint='') 
	{
		if (empty($token_mint)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing Token Mint'),
			];
		}
		
		$api_url = $this->api_url.'?api-key='.$this->api_key;
	
		$curl = curl_init();

		$header = [
			'Content-Type: application/json',
		];
		
		$id = time();
		
		$params = '{"jsonrpc": "2.0", "id": '.$id.', "method": "getAsset", "params": ["'.$token_mint.'"]}';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
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
		if (!empty($data['error'])) {
			
			$message = Yii::t('Error', 'Response error');
			if (!empty($data['error']['message'])) {
				$message = $data['error']['message'];
			}
				
			return [
				'error' => 1,
				'message' => $message,
			];
		}

		if (
			empty($data) || 
			!is_array($data) || 
			empty($data['result']) || 
			!is_array($data['result']) ||	
			empty($data['result']['token_info']) ||
			!is_array($data['result']['token_info']) ||
			empty($data['result']['token_info']['decimals']) ||
			empty($data['result']['token_info']['symbol']) ||
			empty($data['result']['token_info']['price_info']) ||
			!is_array($data['result']['token_info']['price_info']) ||
			empty($data['result']['token_info']['price_info']['price_per_token']) ||
			empty($data['result']['token_info']['price_info']['currency'])
		) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect response'),
			];
		}
		
		$name = $data['result']['token_info']['symbol'];
		if (
			!empty($data['result']['content']) &&
			is_array($data['result']['content']) &&
			!empty($data['result']['content']['metadata']) &&
			is_array($data['result']['content']['metadata']) &&			
			!empty($data['result']['content']['metadata']['name'])
		) {
			$name = $data['result']['content']['metadata']['name'];			
		}
		
		$image = '';
		if (
			!empty($data['result']['content']) &&
			is_array($data['result']['content']) &&
			!empty($data['result']['content']['links']) &&
			is_array($data['result']['content']['links']) &&
			!empty($data['result']['content']['links']['image'])
		) {
			$image = $data['result']['content']['links']['image'];			
		}

		$data = [
			'name' => $name,
			'symbol' => $data['result']['token_info']['symbol'],
			'image' => $image,
			'decimals' => $data['result']['token_info']['decimals'],
			'price' => $data['result']['token_info']['price_info']['price_per_token'],
			'currency' => $data['result']['token_info']['price_info']['currency'],
		];
		
		return [
			'error' => 0,
			'data' => $data,
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