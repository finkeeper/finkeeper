<?php

namespace frontend\modules\app\components;

use Yii;
use common\models\Exchange;
use common\models\Chatgpt;
use frontend\modules\models\ApiChatbot;

class GPTApi2 {
	
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
			empty($conf['gpt2']['apikey']) ||
			empty($conf['gpt2']['apiurl'])
		) {
			return false;
		}

		$this->api_key = $conf['gpt2']['apikey'];
		$this->api_url = $conf['gpt2']['apiurl'];
	}

	/**
	 * getQuestion($message='') 
	 */
    public function getQuestion($message='', $id, $type=1) 
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
			'Authorization: Bearer '.$this->api_key,
		];
		
		$data = Chatgpt::findData($type);
		if (empty($data['used'])) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not Used'),
			];
		}
		
		if (!empty($data['direction'])) {
			
			$data['direction'] = str_replace(["\r", "\n"], [], $data['direction']);

			$lang = ApiChatbot::getSettingsLang($id);

			if ($lang=='ru') {
				$data['direction'] = str_replace('{language}', 'русском', $data['direction']);
			} else {
				$data['direction'] = str_replace('{language}', 'английском', $data['direction']);
			}
			
			if ($type==1) {
	
				$data['direction'] = str_replace('{portfolio}', addslashes($message), $data['direction']);
				
			} else if ($type==2) {
				
				$data['direction'] = str_replace('{active}', addslashes($message), $data['direction']);
			}
			
		} else {
			$data['direction'] = $message;
		}
	
		if (!empty($data['system'])) {
			
			$data['system'] = str_replace(["\r", "\n"], [], $data['system']);
			
			$params = '{"model": "gpt-4o-mini", "store": true, "messages": [{"role": "user", "content": "'.$data['direction'].'"}, {"role": "system", "content": "'.$data['system'].'"}]}';
			
		} else {
		
			$params = '{"model": "gpt-4o-mini", "store": true, "messages": [{"role": "user", "content": "'.$data['direction'].'"}]}';
		}

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($curl);
		curl_close($curl);

		if (empty($response) || !is_string($response)) {
			return Yii::t('Error', 'Not response');
		}
		
		$result = @json_decode($response, true);
		if (empty($result) || !is_array($result)) {
			return Yii::t('Error', 'Incorrect response');
		}

		if (!empty($result['error'])) {

			return $result['error']['message'];
			
		} else {
			
			return $result['choices'][0]['message']['content'];
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