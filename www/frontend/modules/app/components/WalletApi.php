<?php

namespace frontend\modules\app\components;

use Yii;
use common\components\Enc;
use common\models\Tokens;
use common\models\Exchange;
use common\models\Userdata;
use common\models\ChatbotLog;
use common\models\Sendstatus;
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
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);  
		curl_setopt($curl, CURLOPT_PORT, 8443);
		$response = curl_exec($curl);
		curl_close($curl);

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
	 * sendButtonProcess($data=[])
	 */
	public function sendButtonProcess($trnid=0, $log_id=0) 
	{
		if (empty($trnid)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not ID Message'),
				'code' => 116,
			];
		}
		
		if (empty($log_id)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing id'),
				'code' => 117,
			];
		}
		
		$model = Sendstatus::findSendStatus($trnid);
		if (empty($model)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not Message'),
				'code' => 118,
			];
		}
		
		$data = [
			'log_id' => $log_id,
			'amount' => $model->amount,
			'address' => $model->address,
			'token' => $model->type,
		];
		
		$model->status = 1;
		
		if (!$model->save()) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not status save'),
				'code' => 118,
			];
		}
		
		if ($model->func==1) {

			return $this->transferWallet($data);
			
		} else if ($model->func==2) {
			
			return $this->depositWallet($data);
			
		} else if ($model->func==3) {
			
			return $this->withdrawWallet($data);
			
		} else {
			
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect ID Message'),
				'code' => 119,
			];
		}
		
		$data = [];
		
	}
	
	
	/**
	 * transferWallet($data=[]) 
	 */
    public function transferWallet($data=[]) 
	{
		if (
			empty($data) || 
			empty($data['amount']) || 
			empty($data['address'])
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
			'recipient' => $data['address'],
			'amount' => strval($data['amount']*$decimals),
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
			empty($data['amount']) || 
			empty($data['token'])
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
			'token' => strtoupper($data['token']),
			'amount' => $data['amount']*$decimals,
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
		
		//print_r($source);
	
		
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
			'message' => $source['digest'],
		];
	}
	
	/**
	 * depositWallet($data=[]) 
	 */
    public function withdrawWallet($data=[]) 
	{
		if (
			empty($data) || 
			empty($data['amount']) || 
			empty($data['token'])
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
			'token' => strtoupper($data['token']),
			'amount' => $data['amount']*$decimals,
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
			'message' => $source['digest'],
		];
	}
	
	/**
	 * getAPR($coin='')
	 */
	public function getAPR($coin='')
	{
		if (empty($coin)) {
			return false;
		}
		$header = [
			'Content-Type: application/json',
			'Authorization: Basic '.$this->api_key,
		];

		$api_url = $this->api_url.'navi/pool/'.strtoupper($coin);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);  
		curl_setopt($curl, CURLOPT_PORT, 8443);
		$response = curl_exec($curl);
		curl_close($curl);
		
		if (empty($response)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'No response'),
				'code' => 132,
			];
		}
		
		if (!is_string($response)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect type response'),
				'code' => 133,
			];
		}
		
		$source = @json_decode($response, true);
		if (empty($source)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect json response'),
				'code' => 134,
			];
		}
		
		$apr = 0;
		if (empty($source['base_supply_rate'])) {
			return [
				'error' => 0,
				'message' => ['apr' => $apr],
				'code' => 135,
			];
		}	
		
		$apr = $source['base_supply_rate'];
		
		if (!empty($source['boosted_supply_rate'])) {
			
			$apr += $source['boosted_supply_rate'];
		}
			
		return Exchange::formatValue($apr, 1);	
	}
	
	/** 
	 * getNaviBalance()
	 */
	public function getNaviBalance($id)
	{
		if (empty($id)) {
			return false;
		}
		
		$mnm = $modelUserdata = Userdata::findOne([
			'uid' => $id, 
			'type' => 1,
			'key' => 'mnm',
		]);
		
		if (empty($mnm) || empty($mnm->value)) {
			return false;			
		}
		
		$enc = new Enc;
		$mnm_value = $enc->decryptMC($mnm->value);
		$send = [
			'mnemonic' => $mnm_value,
		];
		
		$header = [
			'Content-Type: application/json',
			'Authorization: Basic '.$this->api_key,
		];

		$api_url = $this->api_url.'navi/portfolio';

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
				'code' => 132,
			];
		}
		
		if (!is_string($response)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect type response'),
				'code' => 133,
			];
		}
		
		$source = @json_decode($response, true);
		if (empty($source['success']) || empty($source['portfolio'])) {
			return false;
		}	
		
		$decimals = BaseFunctions::getDecimalsNumber(9);
		
		$navi = 0;
		foreach ($source['portfolio'] as $value) {
			if (!empty($value['supplyBalance'])) {
				$navi = $value['supplyBalance']/$decimals;
			}
		}

		return Exchange::formatValue($navi, 1);
	}
	
	/** 
	 * getNaviBalance()
	 */
	public function getNaviRewards($id)
	{
		if (empty($id)) {
			return false;
		}
		
		$mnm = $modelUserdata = Userdata::findOne([
			'uid' => $id, 
			'type' => 1,
			'key' => 'mnm',
		]);
		
		if (empty($mnm) || empty($mnm->value)) {
			return false;			
		}
		
		$enc = new Enc;
		$mnm_value = $enc->decryptMC($mnm->value);
		$send = [
			'mnemonic' => $mnm_value,
		];
		
		$header = [
			'Content-Type: application/json',
			'Authorization: Basic '.$this->api_key,
		];

		$api_url = $this->api_url.'navi/rewards';

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
				'code' => 132,
			];
		}
		
		if (!is_string($response)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect type response'),
				'code' => 133,
			];
		}
		
		$source = @json_decode($response, true);
		if (empty($source['success']) || empty($source['rewards'])) {
			return false;
		}	

		$rewards = 0;
		foreach ($source['rewards'] as $value) {
			if (!empty($value['available'])) {
				$rewards = $value['available'];
			}
		}

		return Exchange::formatValue($rewards, 1);
	}
	
	/** 
	 * getNaviBalance()
	 */
	public function getClaimallNaviRewards($id)
	{
		if (empty($id)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'No user id'),
				'code' => 132,
			];
		}
		
		$mnm = $modelUserdata = Userdata::findOne([
			'uid' => $id, 
			'type' => 1,
			'key' => 'mnm',
		]);
		
		if (empty($mnm) || empty($mnm->value)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'No user data'),
				'code' => 132,
			];			
		}
		
		$enc = new Enc;
		$mnm_value = $enc->decryptMC($mnm->value);

		$send = [
			'mnemonic' => $mnm_value,
		];
		
		$header = [
			'Content-Type: application/json',
			'Authorization: Basic '.$this->api_key,
		];

		$api_url = $this->api_url.'/navi/claimall';

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
				'code' => 134,
			];
		}
		
		if (!is_string($response)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect type response'),
				'code' => 135,
			];
		}
		
		$source = @json_decode($response, true);
		if (empty($source['success'])) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Error claimall process'),
				'code' => 136,
			];
		}	

		$digest = '';
		if (!empty($source['digest'])) {
			$digest = $source['digest'];
		}

		return $digest;
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