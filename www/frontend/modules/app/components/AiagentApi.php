<?php

namespace frontend\modules\app\components;

use Yii;
use common\models\Exchange;
//use common\models\Chatgpt;
//use api\modules\v3\models\ApiChatbot;

class AiagentApi {
	
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
    public function getQuestion($message='') 
	{
		if (empty($message)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not Message'),
			];
		}

		$header = [
			'Content-Type: application/json',
			'Authorization: Basic '.$this->api_key,
		];

		$message = addslashes($message);
		$api_url = $this->api_url.'chat?input_text='.urlencode($message);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);  
		curl_setopt($curl, CURLOPT_PORT, 8443);
		$response = curl_exec($curl);
		curl_close($curl);

		if (empty($response) || !is_string($response)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not response'),
			];
		}
		
		$result = @json_decode($response, true);
		if (empty($result) || !is_array($result)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect response'),
			];
		}

		if (empty($result['response'])) {

			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not response'),
			];
			
		} else {

			return [
				'error' => 0,
				'message' => $result['response'],
			];
		}	
    }

	/**
	 * pstatic($className=__CLASS__)
	 */ 
	public static function pstatic($className=__CLASS__)
	{
		return new $className;
	}
}