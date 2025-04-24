<?php

namespace frontend\modules\app\components;

use Yii;
use common\models\Exchange;
use common\components\BaseFunctions;
use frontend\modules\app\models\ApiChatbot;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use GuzzleHttp\Client;

class GoogleApi {
	
	public $client_id=''; 
	public $redirect_uri = 'https://finkeeper.pro/app';
	public $scope = 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile';
	public $api_url = '';
	public $state = '';
	public $cert = 'https://www.googleapis.com/oauth2/v3/certs';
	
	/**
	 * construct
	 */
	function __construct() {
		
		$conf = Exchange::getConfig(3);
		if (empty($conf) || !is_array($conf) || empty($conf['google_oauth']) || !is_array($conf['google_oauth'])) {
			return false;
		}
		
		if (
			empty($conf['google_oauth']['client_id']) ||
			empty($conf['google_oauth']['api_url'])
		) {
			return false;
		}

		$this->client_id = $conf['google_oauth']['client_id'];
		$this->api_url = $conf['google_oauth']['api_url'];
	}

	/**
	 * getWalletBalance($params='')
	 */
    public function getAuthLink() 
	{
		
		$data = [];
		if (empty($this->client_id)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not Client ID'),
				'data' => $data,
			];
		}

		if (empty($this->redirect_uri)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing redirect uri'),
				'data' => $data,
			];
		}
		
		if (empty($this->scope)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing param scope'),
				'data' => $data,
			];
		}
		
		if (empty($this->api_url)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing api url'),
				'data' => $data,
			];
		}
		
		$data = [
			'client_id' => $this->client_id,
			'redirect_uri' => $this->redirect_uri,
			'response_type' => 'code',
			'scope' => $this->scope,
			'state' => $this->state,
		];
		
		$url = $this->api_url.'?'.urldecode(http_build_query($data));
		
		return [
			'error' => 0,
			'message' => Yii::t('Api', 'Success'),
			'data' => $url,
		];
    }
	
	/**
	 * getWalletBalance($params='')
	 */
    public function getAuthData() 
	{
		
		$data = [];
		if (empty($this->client_id)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not Client ID'),
				'data' => $data,
			];
		}

		if (empty($this->redirect_uri)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing redirect uri'),
				'data' => $data,
			];
		}
		
		if (empty($this->scope)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing param scope'),
				'data' => $data,
			];
		}
		
		if (empty($this->api_url)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing api url'),
				'data' => $data,
			];
		}
		
		$data = [
			'client_id' => $this->client_id,
			'redirect_uri' => $this->redirect_uri,
			'response_type' => 'code',
			'scope' => $this->scope,
			'state' => $this->state,
		];

		return [
			'error' => 0,
			'message' => Yii::t('Api', 'Success'),
			'data' => $data,
		];
    }
	
	/**
	 * getSolBalance()
	 */
    public function parseJWT($idToken='') 
	{	
		if (empty($idToken)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing token'),
			];
		}
		
		if (empty($this->client_id)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not Client ID'),
			];
		}

		try {
			// 1. Получаем ключи для проверки подписи от Google
			$http = new Client();
			$response = $http->get($this->cert);
			$keys = json_decode($response->getBody()->getContents(), true);
        
			// 2. Декодируем JWT без проверки подписи, чтобы получить kid (key ID)
			$tks = explode('.', $idToken);
			$header = JWT::jsonDecode(JWT::urlsafeB64Decode($tks[0]));

			//Не найден kid в заголовке JWT
			if (empty($header->kid)) {
				return [
					'error' => 1,
					'message' => Yii::t('Error', 'Not valid token'),
				];
			}
        
			// 3. Ищем подходящий ключ для проверки
			$jwk = null;
			foreach ($keys['keys'] as $key) {
				if ($key['kid'] == $header->kid) {
					$jwk = $key; 
					break;
				}
			} 

			//Не найден подходящий ключ для проверки
			if (!$jwk) {
				return [
					'error' => 1,
					'message' => Yii::t('Error', 'Not valid token'),
				];
			}
	
			$keys2 = JWK::parseKeySet($keys);

			// 4. Проверяем подпись и декодируем JWT
			$decoded = JWT::decode(
				$idToken,
				$keys2  
			);

			// 5. Проверяем аудиторию (client ID)
			if ($decoded->aud != $this->client_id) {
				//Неверный client ID
				return [
					'error' => 1,
					'message' => Yii::t('Error', 'Not valid token'),
				];
			}
        
			// 6. Проверяем срок действия
			$now = time();
			if ($decoded->exp < $now) {
				//Срок действия токена истек
				return [
					'error' => 1,
					'message' => Yii::t('Error', 'Not valid token'),
				];
			}
        
			// 7. Проверяем издателя
			if ($decoded->iss != 'https://accounts.google.com' && $decoded->iss != 'accounts.google.com') {
				//Неверный издатель токена
				return [
					'error' => 1,
					'message' => Yii::t('Error', 'Not valid token'),
				];
			}
        
			return $decoded;
        
		} catch (Exception $e) {
			return [
				'error' => 1,
				'message' => $e->getMessage(),
			];
		}	
	}
	
	/**
	 * saveData($data=[])
	 */
	public static function saveData($data=[])
	{
		if (empty($data) || !is_array($data)) {
			return false;
		}
		
		$modelChatbotLog = new ApiChatbot;
		$modelChatbotLog->id_client = 0;

		$modelChatbotLog->error = 0;
		if (!empty($data['error'])) {
			$modelChatbotLog->error = $data['error'];
		}
			
		$modelChatbotLog->error_code = 0;
		if (!empty($data['error_code'])) {
			$modelChatbotLog->error_code = $data['error_code'];
		}
			
		$modelChatbotLog->error_message = '';
		if (!empty($data['error_message'])) {
			$modelChatbotLog->error_message = $data['error_message'];
		}
		
		$modelChatbotLog->message_id = 0;
		$modelChatbotLog->update_id = 0;
		
		$modelChatbotLog->api_date = date('Y-m-d H:i:s');
		
		$modelChatbotLog->text = '';
		$modelChatbotLog->callback_data = '';

		$modelChatbotLog->from_id = 0;
		$modelChatbotLog->from_is_bot = 0;
		
		$modelChatbotLog->from_first_name = '';
		if (!empty($data['family_name'])) {
			$modelChatbotLog->from_first_name = $data['family_name'];
		}
		
		$modelChatbotLog->from_last_name = '';
		if (!empty($data['given_name'])) {
			$modelChatbotLog->from_last_name = $data['given_name'];
		}

		$modelChatbotLog->from_username = '';
		$modelChatbotLog->from_language_code = 'en';

		$modelChatbotLog->chat_id = 0;
		
		$modelChatbotLog->chat_first_name = '';
		if (!empty($data['family_name'])) {
			$modelChatbotLog->chat_first_name = $data['family_name'];
		}
		
		$modelChatbotLog->chat_last_name = '';
		if (!empty($data['given_name'])) {
			$modelChatbotLog->chat_last_name = $data['given_name'];
		}
		
		$modelChatbotLog->chat_username = '';
		$modelChatbotLog->chat_type = '';

		if (!empty($data['email'])) {

			$modelClients = ApiChatbot::getEmailClient($data['email']);
			if (empty($modelClients)) {
				$modelClients = ApiChatbot::addEmailClient($data);
				if (!empty($modelClients) && !empty($data['referral'])) {
					
					//ApiChatbot::addReferral($data['referral'], $modelClients->id, $data['bot_id']);
				}
			} 

			if (!empty($modelClients)) {
				$modelChatbotLog->id_client = (int) $modelClients->id;
			}
		}

		$modelChatbotLog->type = 0;

		$modelChatbotLog->request = json_encode($data);
		$modelChatbotLog->bot_id = 0;
		$result = $modelChatbotLog->savelog();

		if (!empty($result)) {
			return $modelClients->id;
		}
		
		return false;
	}

	/**
	 * pstatic($className=__CLASS__)
	 */ 
	public static function pstatic($className=__CLASS__)
	{
		return new $className;
	}
}