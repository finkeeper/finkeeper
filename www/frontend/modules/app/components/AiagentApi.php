<?php

namespace frontend\modules\app\components;

use Yii;
use common\models\Exchange;
use common\models\Sendstatus;
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
    public function getQuestion($message='', $coin='', $portfolio='') 
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

		$message = urlencode(addslashes($message));
		$api_url = $this->api_url.'chat?input_text='.$message;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $portfolio);
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
			
		}
		
		$response_data = [];
		if (!empty($result['response']['function'])) {
			if (!empty($result['response']['function']['name'])) {
				
				$response_data = $result['response']['function'];
				$response_data['coin'] = $coin;
				$response_data['trnid'] = Sendstatus::createSendStatus($response_data);
	
				if (empty($response_data['trnid'])) {
					return [
						'error' => 1,
						'message' => Yii::t('Error', 'Not save transaction id'),
					];
				}
				
			} else if ($result['response']['function']['function']['name']) {
			
				$response_data = $result['response']['function']['function'];
				$response_data['coin'] = $coin;
				$response_data['trnid'] = Sendstatus::createSendStatus($response_data);
				
				if (empty($response_data['trnid'])) {
					return [
						'error' => 1,
						'message' => Yii::t('Error', 'Not save transaction id'),
					];
				}
			}
			
		} else {
			
			if (is_string($result['response'])) {
				$result['response'] = self::replaceStr($result['response']);
			}
			
			$response_data = $result['response'];
		}

		return [
			'error' => 0,
			'message' => $response_data,
		];	
    }
	
	/**
	 * replaceStr($str='')
	 */
	public static function replaceStr($str='')
	{
		if (empty($str)) {
			return $str;
		}
		
		$str = str_replace(["\n"], "<br>", $str);

		preg_match_all('/\*\*(.*?)\*\*/i', $str, $matches);
		
		if (empty($matches) || empty($matches[0]) || empty($matches[1])) {
			return $str;
		}

		$search = $matches[0];

		foreach ($matches[1] as $key=>$value) {
			$matches[1][$key] = '<b>'.$value.'</b>';
		}

		$replace = $matches[1];

		$str = str_replace($search, $replace, $str);
		
		return $str;
	}

	/**
	 * pstatic($className=__CLASS__)
	 */ 
	public static function pstatic($className=__CLASS__)
	{
		return new $className;
	}
}