<?php

namespace frontend\modules\app\components;

use Yii;
use common\models\Exchange;

class GPTApi {
	
	public $api_key = '';
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
			empty($conf['gpt']['apikey']) ||
			empty($conf['gpt']['apiurl'])
		) {
			return false;
		}

		$this->api_key = $conf['gpt']['apikey'];
		$this->api_url = $conf['gpt']['apiurl'];
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

		$api_url = $this->api_url;
	
		$curl = curl_init();

		$header = [
			'Content-Type: application/json',
			'X-Api-Key: '.$this->api_key,
		];
		
		$user_agent = '"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36"';

		$params = json_encode([
			'query' => json_encode($message),
		]);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_REFERER, $api_url);
		curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);
		$response = curl_exec($curl);
		curl_close($curl);
		
		if (empty($response) || !is_string($response)) {
			return Yii::t('Error', 'Not response');
		}
		
		$result = @json_decode($response, true);
		if (empty($result) || !is_array($result)) {
			return Yii::t('Error', 'Incorrect response');
		}

		if (empty($result['message'])) {
			//$answer = Yii::t('Api', 'You have SOL tokens');
			if (is_array($result['detail'])) {
				//$result['detail'][0]['message']
			} else {
				//$result['detail'];
			}
			
			return Yii::t('Error', 'Error application');
			
		} else {
			return $result['message'];
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