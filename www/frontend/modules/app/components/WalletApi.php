<?php

namespace frontend\modules\app\components;

use Yii;
use common\components\Enc;
use common\models\Tokens;
use common\models\Exchange;
use common\models\Userdata;
use common\models\ChatbotLog;
use common\components\BaseFunctions;

class WalletApi {

	public $api_key = '';
	public $api_url = '';
	
	/**
	 * construct
	 */
	function __construct() {
		
		$conf = Exchange::getConfig(3);

		if (empty($conf) || !is_array($conf) || empty($conf['aiagent']) || !is_array($conf['aiagent'])) {
			return false;
		}
		
		if (
			empty($conf['aiagent']['apikey']) ||
			empty($conf['aiagent']['apiurl'])
		) {
			return false;
		}

		$this->api_key = $conf['aiagent']['apikey'];
		$this->api_url = $conf['aiagent']['apiurl'];
	}

	/**
	 * getQuestion($message='')
	 */
    public function createWallet($log_id=0) 
	{
		$header = [
			'Content-Type: application/json',
			'Authorization: Basic '.$this->api_key,
		];

		$hash = $this->getHash($log_id);
		$api_url = $this->api_url.'node/create_wallet?hash='.$hash;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		//curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);  
		curl_setopt($curl, CURLOPT_PORT, 8443);
		$response = curl_exec($curl);
		curl_close($curl);

		//error_log($response."\r\n".PHP_EOL, 3, dirname(__FILE__).'/log.log');

		if (empty($response)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'No response'),
				'code' => 100,
			];
		}
		
		if (!is_string($response)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect type response'),
				'code' => 101,
			];
		}
		
		$source = @json_decode($response, true);
		if (empty($source)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect json response'),
				'code' => 102,
			];
		}
		
		if (!is_array($source)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect decode json response'),
				'code' => 103,
			];
		}
		
		$data = [];
		if (!empty($source['wallet'])) {
		
			$data = $source['wallet'];
		
		} else if ($source['result']) {
			
			$data = $source['result'];
			
		} else {
			
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'No key wallet response'),
				'code' => 104,
			];
		}

		preg_match("/\{(.+?)\}/", $data, $matches);
		if (empty($matches)) { 
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect json response'),
				'code' => 105,
			];
		} 
		
		if (!is_array($matches)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect json response'),
				'code' => 106,
			];
		}
		
		if (empty($matches[0])) { 
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'No key wallet response'),
				'code' => 107,
			];
		} 
		
		$result = @json_decode($matches[0], true);
		if (empty($result)) { 
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect json response'),
				'code' => 108,
			];
		} 
		
		if (!is_array($result)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect decode json response'),
				'code' => 109,
			];
		}
		
		if (empty($result['address'])) { 
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'No data wallet'),
				'code' => 110,
			];
		}
		
		if (empty($result['publicKey'])) { 
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'No data wallet'),
				'code' => 111,
			];
		}
		
		if (empty($result['privateKey'])) { 
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'No data wallet'),
				'code' => 112,
			];
		}
		
		if (empty($result['mnemonic'])) { 
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'No data wallet'),
				'code' => 113,
			];
		}
		
		
		$log = ChatbotLog::findLog($log_id);
		if (empty($log) || empty($log->id_client)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing user'),
				'code' => 113,
			];			
		}

		$enc = new Enc;
		$answer['id'] = $log->id_client;
		$answer['prk'] = $enc->encryptMC($result['privateKey']);
		$answer['mnm'] = $enc->encryptMC($result['mnemonic']);
		$answer['pbk'] = $result['publicKey'];
		$answer['ads'] = $result['address'];
		if (!Tokens::saveWalletData($answer) || !Userdata::saveWalletData($answer)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'No save wallet data'),
				'code' => 114,
			];
		}

		return [
			'error' => 0,
			'message' => $answer['ads'],
		];
    }
	
	/**
	 * transferWallet($data=[]) 
	 */
    public function transferWallet($data=[]) 
	{
		if (
			empty($data) || 
			empty($data['data']['amount']) || 
			empty($data['data']['address'])
		) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing amount or address'),
				'code' => 116,
			];
		}
		
		if (empty($data['log_id'])) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing user'),
				'code' => 117,
			];
		}

		$log = ChatbotLog::findOne(['id' => $data['log_id']]);
		if (empty($log) || empty($log->id_client)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing user'),
				'code' => 118,
			];			
		}
		
		$mnm = $modelUserdata = Userdata::findOne([
			'uid' => $log->id_client, 
			'type' => 1,
			'key' => 'mnm',
		]);
		
		if (empty($mnm) || empty($mnm->value)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing user data'),
				'code' => 119,
			];			
		}
		
		$enc = new Enc;
		$mnm_value = $enc->decryptMC($mnm->value);
		
		if (empty($mnm_value)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing user data'),
				'code' => 120,
			];			
		}
		
		$decimals = BaseFunctions::getDecimalsNumber(9);
		
		$send = [
			'recipient' => $data['data']['address'],
			'amount' => strval($data['data']['amount']*$decimals),
			'mnemonic' => $mnm_value,
		];
		
		$header = [
			'Content-Type: application/json',
			'Accept: application/json',
			'Authorization: Basic '.$this->api_key,
		];

		$api_url = $this->api_url.'node/transfer/';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($send));
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);  
		curl_setopt($curl, CURLOPT_PORT, 8443);
		$response = curl_exec($curl);
		curl_close($curl);

		//error_log($response."\r\n".PHP_EOL, 3, dirname(__FILE__).'/log.log');

		if (empty($response)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'No response'),
				'code' => 121,
			];
		}
		
		if (!is_string($response)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect type response'),
				'code' => 122,
			];
		}
		
		$source = @json_decode($response, true);
		if (empty($source)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect json response'),
				'code' => 123,
			];
		}

		if (!is_array($source)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect decode json response'),
				'code' => 124,
			];
		}
		
		$res = [];
		if (!empty($source['raw_output'])) {
		
			$res = $source['raw_output'];
		
		} else {
			
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'No transaction data'),
				'code' => 125,
			];
		}
		preg_match("/\{(.+?)\}/", $res, $matches);
		if (empty($matches)) { 
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect json response'),
				'code' => 126,
			];
		} 
		
		if (!is_array($matches)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect json response'),
				'code' => 127,
			];
		}
		
		if (empty($matches[0])) { 
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'No transaction data'),
				'code' => 128,
			];
		} 
		
		$result = @json_decode($matches[0], true);
		if (empty($result)) { 
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect json response'),
				'code' => 129,
			];
		} 
		
		if (!is_array($result)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect decode json response'),
				'code' => 130,
			];
		}
		
		if (empty($result['digest'])) { 
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'No transaction data'),
				'code' => 131,
			];
		}
		
		return [
			'error' => 0,
			'message' => $result['digest'],
		];
    }
	
	/**
	 * depositWallet($data=[]) 
	 */
    public function depositWallet($data=[]) 
	{
		if (
			empty($data) || 
			empty($data['data']['amount']) || 
			empty($data['data']['token'])
		) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing amount or token'),
				'code' => 131,
			];
		}
		
		if (empty($data['log_id'])) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing user'),
				'code' => 132,
			];
		}
		
		$log = ChatbotLog::findOne(['id' => $data['log_id']]);
		if (empty($log) || empty($log->id_client)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing user'),
				'code' => 133,
			];			
		}
		
		$mnm = $modelUserdata = Userdata::findOne([
			'uid' => $log->id_client, 
			'type' => 1,
			'key' => 'mnm',
		]);
		
		if (empty($mnm) || empty($mnm->value)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing user data'),
				'code' => 134,
			];			
		}
		
		$enc = new Enc;
		$mnm_value = $enc->decryptMC($mnm->value);
		
		if (empty($mnm_value)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing user data'),
				'code' => 135,
			];			
		}
		
		$decimals = BaseFunctions::getDecimalsNumber(9);
		
		$send = [
			'token' => strtoupper($data['data']['token']),
			'amount' => $data['data']['amount']*$decimals,
			'mnemonic' => $mnm_value,
		];

		$header = [
			'Content-Type: application/json',
			'Accept: application/json',
			'Authorization: Basic '.$this->api_key,
		];

		$api_url = $this->api_url.'navi/deposit/';
	
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($send));
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);  
		curl_setopt($curl, CURLOPT_PORT, 8443);
		$response = curl_exec($curl);
		curl_close($curl);

		if (empty($response)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'No response'),
				'code' => 121,
			];
		}
		
		if (!is_string($response)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect type response'),
				'code' => 122,
			];
		}
		
		$source = @json_decode($response, true);
		if (empty($source)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect json response'),
				'code' => 123,
			];
		}

		if (!is_array($source)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect decode json response'),
				'code' => 124,
			];
		}

		if (empty($source['digest'])) { 
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'No transaction data'),
				'code' => 131,
			];
		}
		
		return [
			'error' => 0,
			'message' => $result['digest'],
		];
	}
	
	/**
	 * depositWallet($data=[]) 
	 */
    public function withdrawWallet($data=[]) 
	{
		if (
			empty($data) || 
			empty($data['data']['amount']) || 
			empty($data['data']['token'])
		) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing amount or token'),
				'code' => 116,
			];
		}
		
		if (empty($data['log_id'])) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing user'),
				'code' => 117,
			];
		}
		
		$log = ChatbotLog::findOne(['id' => $data['log_id']]);
		if (empty($log) || empty($log->id_client)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing user'),
				'code' => 118,
			];			
		}
		
		$mnm = $modelUserdata = Userdata::findOne([
			'uid' => $log->id_client, 
			'type' => 1,
			'key' => 'mnm',
		]);
		
		if (empty($mnm) || empty($mnm->value)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing user data'),
				'code' => 119,
			];			
		}
		
		$enc = new Enc;
		$mnm_value = $enc->decryptMC($mnm->value);
		
		if (empty($mnm_value)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing user data'),
				'code' => 120,
			];			
		}
		
		$decimals = BaseFunctions::getDecimalsNumber(9);
		
		$send = [
			'token' => strtoupper($data['data']['token']),
			'amount' => $data['data']['amount']*$decimals,
			'mnemonic' => $mnm_value,
		];
		
		$header = [
			'Content-Type: application/json',
			'Accept: application/json',
			'Authorization: Basic '.$this->api_key,
		];

		$api_url = $this->api_url.'navi/withdraw/';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($send));
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);  
		curl_setopt($curl, CURLOPT_PORT, 8443);
		$response = curl_exec($curl);
		curl_close($curl);
		
		
		
		
		
		
		
		
	
	}
	
	/**
	 * getHash()
	 */
	private function getHash($log_id=0) 
	{
		return hash('sha256', time().$log_id);
	}

	/**
	 * pstatic($className=__CLASS__)
	 */ 
	public static function pstatic($className=__CLASS__)
	{
		return new $className;
	}
}