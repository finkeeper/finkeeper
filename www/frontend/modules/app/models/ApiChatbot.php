<?php
namespace frontend\modules\app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use common\models\ChatbotLog;
use common\models\Chatbot;
use common\models\Chatgpt;
use common\models\Clients;
use common\models\Tokens;
use common\models\Targets;
use common\models\Referrals;
use common\models\ReferralsProgramm;
use common\models\PointsLog;
use common\components\Bybit;
use common\components\Cryptoprice;
use common\components\OKX;
use yii\data\ActiveDataProvider;
use yii\base\InvalidParamException;
use frontend\modules\app\AppModule;
use frontend\modules\app\components\TonApi;
use frontend\modules\app\components\SUIApi;
use frontend\modules\app\components\TelegramApi;

/**
 * ApiChatbotLog
 */
class ApiChatbot extends Model
{
	public $id;
	public $error;	
	public $error_code;
	public $error_message;
	public $message_id;
	public $update_id;
	public $api_date;
	public $creation_date;
	public $text;
	public $callback_data;
	public $from_id;
	public $from_is_bot;
	public $from_first_name;
	public $from_last_name;
	public $from_username;
	public $from_language_code;
	public $chat_id;
	public $chat_first_name;
	public $chat_last_name;
	public $chat_username;
	public $chat_type;
	public $type;
	public $request;
	public $bot_id;
	public $id_client;
	public $user_connect=0;
	public $userpic;
	public $tg_auth_token;

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['id', 'error_code', 'message_id', 'update_id', 'from_id', 'chat_id', 'type', 'error', 'from_is_bot', 'bot_id', 'id_client', 'user_connect'], 'integer'],
			[['creation_date', 'api_date'], 'string', 'max' => 60],
			[['callback_data', 'from_first_name', 'from_last_name', 'from_username', 'from_language_code', 'chat_first_name', 'chat_last_name', 'chat_username', 'chat_type'], 'string', 'max' => 255],
			[['error_message', 'text', 'request'], 'string'],
        ];
    }
	
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'error' => Yii::t('Api', 'Error'),
			'error_code' => Yii::t('Api', 'Error code'),
			'error_message' => Yii::t('Api', 'Error message'),
			'message_id' =>  Yii::t('Api', 'Message ID'),
			'update_id' =>  Yii::t('Api', 'Update ID'),
			'api_date' =>  Yii::t('Api', 'Api Date'),
			'creation_date' =>  Yii::t('Api', 'Creation date'),
			'text' =>  Yii::t('Api', 'text'),
			'callback_data' =>  Yii::t('Api', 'Callback data'),
			'from_id' =>  Yii::t('Api', 'from_id'),
			'from_is_bot' =>  Yii::t('Api', 'From is bot'),
			'from_first_name' =>  Yii::t('Api', 'From first name'),
			'from_last_name' =>  Yii::t('Api', 'From last name'),
			'from_username' =>  Yii::t('Api', 'From username'),
			'from_language_code' =>  Yii::t('Api', 'From language code'),
			'chat_id' =>  Yii::t('Api', 'Chat id'),
			'chat_first_name' =>  Yii::t('Api', 'Chat first name'),
			'chat_last_name' =>  Yii::t('Api', 'Chat last name'),
			'chat_username' =>  Yii::t('Api', 'Chat username'),
			'chat_type' =>  Yii::t('Api', 'Chat type'),
			'type' =>  Yii::t('Api', 'Type'),
			'request' =>  Yii::t('Api', 'Request'),
			'bot_id' =>  Yii::t('Api', 'Bot ID'),
			'wallet_address' => Yii::t('Api', 'Wallet Address'),
			'id_client' => Yii::t('Api', 'ID Client'),
			'user_connect' => Yii::t('Api', 'User Status Connect API'),
        ];
    }

	/**
     * update
     */
    public function savelog()
    {
		if (!$this->validate()) {
            return false;
        }

		$log = new ChatbotLog;
		
		$log->setAttributes($this->attributes, false);

		if ($log->save()) {
			return $log->id;
		}

        return false;
    }

	/**
	 * getBot($id=0)
	 */
	public static function getBot($id=0)
	{
		return Chatbot::findOne(['id_bot' => $id]);
	}
	
	/**
	 * getBots()
	 */
	public static function getBots()
	{
		return Chatbot::find()->where(['deleted'=>0])->all();
	}

	/**
	 * saveTokens($type=0, $identify1=0, $identify2=0, $identify3=0)
	 */
	public static function saveTokens(
		$type=0, 
		$log_id=0,
		$identify1='', 
		$identify2='', 
		$identify3='',
		$identify4='', 
		$identify5=''
	) {
		$type = (int) $type;
		if (empty($type)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not api service type'),
			];
		}
		
		$log_id = (int) $log_id;
		if (empty($log_id)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not User ID'),
			];
		}
		
		$modelChatbotLog = ChatbotLog::findOne(['id' => $log_id]);
		if (empty($modelChatbotLog)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'User Not Found'),
			];		
		}
		
		if ($type==1) {

			if (empty($identify1)) {
				return [
					'error' => 1,
					'message' => Yii::t('Error', 'Not Wallet Address'),
				];
			}

		} else if ($type==2) {
			
			if (empty($identify1) || empty($identify2) || empty($identify3)) {
				return [
					'error' => 1,
					'message' => Yii::t('Error', 'Missing Api Key'),
				];
			}
			
		} else if ($type==3) {
			
			if (empty($identify1) || empty($identify2) || empty($identify3) || empty($identify4)) {
				return [
					'error' => 1,
					'message' => Yii::t('Error', 'Missing Api Key'),
				];
			}
			
		} else if ($type==4) {
			
			if (empty($identify1)) {
				return [
					'error' => 1,
					'message' => Yii::t('Error', 'Missing SOL Address Wallet'),
				];
			}
			
		} else if ($type==5) {
			
			if (empty($identify1)) {
				return [
					'error' => 1,
					'message' => Yii::t('Error', 'Missing SUI Address Wallet'),
				];
			}

		} else {
			
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect api service type'),
			];
		}

		$modelTokens = Tokens::findOne([
			'service_type' => $type, 
			'id_client' => $modelChatbotLog->id_client, 
			'deleted'=>Tokens::STATUS_NOT_DELETED,
			//'identify1' => $identify1,
		]);

		$points = 0;
		if (empty($modelTokens)) {
			
			$modelProgramm = self::getReferralProgramm($modelChatbotLog->bot_id);
			if (!empty($modelProgramm) && is_array($modelProgramm)) {

				foreach ($modelProgramm as $programm) {
	
					if ($programm->ref_type==2) {
		
						$points += $programm->points;
						break;
					}
				}
			}
			
			$modelTokens = new Tokens;
		}
		
		$change_token = 0;
		if ($modelTokens->identify1!=$identify1) {
			$change_token = 1;
		}

		$modelTokens->service_type = $type;
		$modelTokens->id_client = $modelChatbotLog->id_client;
		$modelTokens->identify1 = $identify1;
		$modelTokens->identify2 = $identify2;
		$modelTokens->identify3 = $identify3;
		$modelTokens->identify4 = $identify4;
		$modelTokens->identify5 = $identify5;
		
		if ($modelTokens->save()) {
			
			$modelClients = self::findClient($modelChatbotLog->id_client);

			if (!empty($modelClients) && !empty($points)) {
				// Создаем запись в логах баллов и за какой реферал
				if (self::addPointsLog($points, $modelChatbotLog->id_client, $modelTokens->id_token, 2)) {
						
					// Начисляем баллы
					$modelClients->points += $points;
					if (!$modelClients->save()) {
						error_log('Error add: '.$programm->points.' points, wallet: '.$modelTokens->id_token."\r\n".PHP_EOL, 3, dirname(__FILE__).'/log.log');
					}
				}
			}
			
			return [
				'error' => 0,
				'message' => Yii::t('Api', 'Success'),
				'id' => $modelTokens->id_token,
				'change_token' => $change_token,
			];
		}

        return [
			'error' => 1,
			'message' => Yii::t('Error', 'Error save token'),
		];	
	}
	
	/**
	 * saveStatsConnect($id=0)
	 */
	public static function saveStatusConnect($type=0, $log_id=0, $status=0)
	{
		if (empty($log_id) || empty($type)) {
			return false;
		}
		
		$modelChatbotLog = ChatbotLog::findOne(['id' => $log_id]);
		if (empty($modelChatbotLog)) {
			return false;
		}
		
		$modelTokens = Tokens::findOne([
			'service_type' => $type, 
			'id_client' => $modelChatbotLog->id_client, 
			'deleted'=>Tokens::STATUS_NOT_DELETED,
		]);

		if (empty($modelTokens)) {
			return false;
		}
		
		if (empty($status)) {
			$modelTokens->user_connect = 0;
		} else {
			$modelTokens->user_connect = 1;
		}
		
		if ($modelTokens->save()) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * getStatusConnect($log_id=0)
	 */
	public static function getStatusConnect($log_id=0)
	{
		$status = [
			'ton' => 0,
			'bybit' => 0,
			'okx' => 0,
			'sol' => 0,
			'sui' => 0,
		];
		
		if (empty($log_id)) {
			return $status;
		}
		
		$modelChatbotLog = ChatbotLog::findOne(['id' => $log_id]);
		if (empty($modelChatbotLog)) {
			return $status;		
		}

		$modelTokens1 = Tokens::findOne([
			'service_type' => 1, 
			'id_client' => $modelChatbotLog->id_client, 
			'deleted'=>Tokens::STATUS_NOT_DELETED,
		]);

		if (!empty($modelTokens1) && !empty($modelTokens1->user_connect)) {
			$status['ton'] = 1;
		}
		
		$modelTokens2 = Tokens::findOne([
			'service_type' => 2, 
			'id_client' => $modelChatbotLog->id_client, 
			'deleted'=>Tokens::STATUS_NOT_DELETED,
		]);

		if (!empty($modelTokens2) && !empty($modelTokens2->user_connect)) {
			$status['bybit'] = 1;
		}
		
		$modelTokens3 = Tokens::findOne([
			'service_type' => 3, 
			'id_client' => $modelChatbotLog->id_client, 
			'deleted'=>Tokens::STATUS_NOT_DELETED,
		]);

		if (!empty($modelTokens3) && !empty($modelTokens3->user_connect)) {
			$status['okx'] = 1;
		}
		
		$modelTokens4 = Tokens::findOne([
			'service_type' => 4, 
			'id_client' => $modelChatbotLog->id_client, 
			'deleted'=>Tokens::STATUS_NOT_DELETED,
		]);

		if (!empty($modelTokens4) && !empty($modelTokens4->user_connect)) {
			$status['sol'] = 1;
		}
		
		$modelTokens5 = Tokens::findOne([
			'service_type' => 5, 
			'id_client' => $modelChatbotLog->id_client, 
			'deleted'=>Tokens::STATUS_NOT_DELETED,
		]);

		if (!empty($modelTokens5) && !empty($modelTokens5->user_connect)) {
			$status['sui'] = 1;
		}
		
		return $status;
	}
	
	/**
	 * getStatusConnect($log_id=0)
	 */
	public static function getTokens($log_id=0)
	{
		$tokens = [
			'ton' => [
				'address' => '',
			],
			'bybit' => [
				'uid' => '',
				'apikey' => '',
				'apisecret' => '',
			],
			'okx' => [
				'password' => '',
				'apikey' => '',
				'apisecret' => '',
				'uid' => '',
			],
		];
		
		if (empty($log_id)) {
			return $tokens;
		}
		
		$modelChatbotLog = ChatbotLog::findOne(['id' => $log_id]);
		if (empty($modelChatbotLog)) {
			return $tokens;		
		}

		$modelTokens1 = Tokens::findOne([
			'service_type' => 1, 
			'id_client' => $modelChatbotLog->id_client, 
			'deleted'=>Tokens::STATUS_NOT_DELETED,
		]);

		if (!empty($modelTokens1) && !empty($modelTokens1->identify1)) {
			$tokens['ton']['address'] = $modelTokens1->identify1;
		}
		
		$modelTokens2 = Tokens::findOne([
			'service_type' => 2, 
			'id_client' => $modelChatbotLog->id_client, 
			'deleted'=>Tokens::STATUS_NOT_DELETED,
		]);

		if (
			!empty($modelTokens2) && 
			!empty($modelTokens2->identify1) && 
			!empty($modelTokens2->identify2) &&
			!empty($modelTokens2->identify3)
		) {
			$tokens['bybit'] = [
				'uid' => $modelTokens2->identify1,
				'apikey' => $modelTokens2->identify2,
				'apisecret' => $modelTokens2->identify3,
			];
		}
		
		$modelTokens3 = Tokens::findOne([
			'service_type' => 3, 
			'id_client' => $modelChatbotLog->id_client, 
			'deleted'=>Tokens::STATUS_NOT_DELETED,
		]);

		if (
			!empty($modelTokens3) && 
			!empty($modelTokens3->identify1) && 
			!empty($modelTokens3->identify2) &&
			!empty($modelTokens3->identify3) &&
			!empty($modelTokens3->identify4)
		) {
			$tokens['okx'] = [
				'password' => $modelTokens3->identify1,
				'apikey' => $modelTokens3->identify2,
				'apisecret' => $modelTokens3->identify3,
				'uid' => $modelTokens3->identify4,
			];
		}
		
		return $tokens;
	}
	
	/**
	 * getHash($int1=0, $int2=0)
	 */
	public static function getHash($int1=0, $int2=0)
	{
		$salt = Yii::$app->params['cert_salt'];
		return md5(md5($int1.$salt).md5($salt.$int2));
		
	}
	
	/**
	 * getClient($chat_id=0)
	 */
	public static function getClient($chat_id=0)
	{
		$modelClient = Clients::findOne(['tg_chat_id' => $chat_id, 'deleted' => Clients::STATUS_NOT_DELETED]);
		if (!empty($modelClient)) {
			return $modelClient;
		}

		return false;		
	}
	
	/**
	 * findClient($id_client=0)
	 */
	public static function findClient($id_client=0)
	{
		$modelClient = Clients::findOne(['id' => $id_client, 'deleted' => Clients::STATUS_NOT_DELETED]);
		if (!empty($modelClient)) {
			return $modelClient;
		}

		return false;		
	}
	
	/**
	 * getReferral($ref_id=0)
	 */
	public static function getReferral($ref_id='')
	{
		$modelClient = Clients::findOne(['referral_token' => $ref_id, 'deleted' => Clients::STATUS_NOT_DELETED]);
		if (!empty($modelClient)) {
			return $modelClient;
		}

		return false;		
	}
	
	/**
	 * getReferralProgramm($id_bot=0)
	 */
	public static function getReferralProgramm($id_bot=0)
	{		
		$modelReferralsProgramm = ReferralsProgramm::find()->where([
			'id_bot' => $id_bot, 
			'deleted' => ReferralsProgramm::STATUS_NOT_DELETED,
			'used' => 1,
		])->all();

		if (!empty($modelReferralsProgramm)) {
			return $modelReferralsProgramm;
		}

		return false;		
	}
	
	/**
	 * getReferralsLog($id_1=0,$id_2=0, $type)
	 */
	public static function getReferralsLog($id_1=0,$id_2=0, $type=0)
	{		
		$modelReferrals = Referrals::findOne(['id_client' => $id_1, 'id_referral' => $id_2, 'ref_type' => $type, 'deleted' => Referrals::STATUS_NOT_DELETED]);
		if (!empty($modelReferrals)) {
			return $modelReferrals;
		}

		return false;		
	}
	
	/**
	 * getReferralsLog($id_1=0,$id_2=0, $type)
	 */
	public static function getParentReferralsLog($id_1=0)
	{		
		$modelReferrals = Referrals::findOne(['id_client' => $id_1, 'deleted' => Referrals::STATUS_NOT_DELETED]);
		if (!empty($modelReferrals)) {
			return $modelReferrals->id_referral;
		}

		return false;		
	}
	
	/**
	 * getPointsLog($id_client=0, $type=0)
	 */
	public static function getPointsLog($id_client=0, $type=0)
	{	
		$modelPointsLog = PointsLog::findOne(['id_client' => $id_client, 'point_type' => $type, 'deleted' => Referrals::STATUS_NOT_DELETED]);
		if (!empty($modelPointsLog)) {
			return $modelPointsLog->id_point;
		}

		return false;
	}
	
	/**
	 * addPointsLog($points, $id_2, $type)
	 */
	public static function addPointsLog($points=0, $id_2=0, $id_log=0, $type=0)
	{		
		$modelPointsLog = new PointsLog;
		
		// Тип бонусной программы
		$modelPointsLog->point_type = $type;
		
		// Запись в логх рефералов или токенов
		$modelPointsLog->id_log = $id_log;
		
		// Какому клиенту
		$modelPointsLog->id_client = $id_2;
		
		// Сколько баллов
		$modelPointsLog->point = $points;
		
		if (!$modelPointsLog->save()) {
			error_log('Error add: '.$programm->points.' points, points log: '.$id_2."\r\n".PHP_EOL, 3, dirname(__FILE__).'/log.log');
			
			return false;
		}			
		
		return true;
	}
	
	/**
	 * addReferralsLog($id_1, $id_2, $type)
	 */
	public static function addReferralsLog($id_1, $id_2, $type)
	{		
		$modelReferralsLog = new Referrals;
		
		// За какую бонусную программу
		$modelReferralsLog->ref_type = $type;
		
		// Новый клиент
		$modelReferralsLog->id_client = $id_1;
		
		// Кто привел
		$modelReferralsLog->id_referral = $id_2;
		
		if (!$modelReferralsLog->save()) {
			error_log('Error add: '.$programm->points.' points, points log: '.$id_2."\r\n".PHP_EOL, 3, dirname(__FILE__).'/log.log');
			
			return false;
		}	

		return $modelReferralsLog->id_ref;
	}

	/**
	 * addClient($ref_id=0)
	 */
	public static function addReferral($ref='', $id_client=0, $id_bot=0)
	{
		$modelReferral = self::getReferral($ref);
		if (empty($modelReferral)) {
			return false;											
		}

		$modelClients = self::findClient($id_client);
		if (empty($modelClients)) {
			return false;											
		}

		$modelProgramm = self::getReferralProgramm($id_bot);
		if (empty($modelProgramm) || !is_array($modelProgramm)) {
			return false;											
		}

		foreach ($modelProgramm as $programm) {
			
			if (!empty($programm->ref_type==1)) {
				
				// Проверяем ранее созданные рефералы
				$modelReferralsLog = self::getReferralsLog($modelClients->id, $modelReferral->id, $programm->id_prog);
				if (!empty($modelReferralsLog)) {
					continue;											
				}
				
				// Создаем запись в рефералах
				$referr_id = self::addReferralsLog($modelClients->id, $modelReferral->id, $programm->id_prog);
				
				if (!empty($referr_id)) {
					
					// Создаем запись в логах баллов и за какой реферал
					if (self::addPointsLog($programm->points, $modelReferral->id, $referr_id, $programm->ref_type)) {
						
						// Начисляем баллы
						$modelReferral->points += $programm->points;
						if (!$modelReferral->save()) {
							error_log('Error add: '.$programm->points.' points, referral: '.$modelReferral->id."\r\n".PHP_EOL, 3, dirname(__FILE__).'/log.log');
						}
					}
				}

			} else if ($programm->ref_type==3) {
				
				// Проверяем кто привел реферала
				$id_parent_referral = self::getParentReferralsLog($modelReferral->id);
				if (empty($id_parent_referral)) {
					continue;
				}
					
				$modelParentReferral = self::findClient($id_parent_referral);
				if (empty($modelParentReferral)) {
					continue;											
				}
				
				if (self::addPointsLog($programm->points, $modelParentReferral->id, $referr_id, $programm->ref_type)) {
					
					// Начисляем баллы
					$modelParentReferral->points += $programm->points;
					if (!$modelParentReferral->save()) {
						error_log('Error add: '.$programm->points.' points, referral: '.$modelParentReferral->id."\r\n".PHP_EOL, 3, dirname(__FILE__).'/log.log');
					}
				}
			}	
		}
	}
	/**
	 * addClient($chat=[])
	 */
	public static function addClient($chat=[])
	{
		if (empty($chat) || !is_array($chat) || empty($chat['id'])) {
			return false;
		}
		
		$modelClient = new Clients;
		
		$modelClient->tg_chat_id = $chat['id'];

		if (!empty($chat['first_name'])) {
			$modelClient->name = $chat['first_name'];
		}
		
		if (!empty($chat['last_name'])) {
			$modelClient->surname = $chat['last_name'];
		}
		
		$modelClient->generateAuthKey();
		$modelClient->generateEmailVerificationToken();
		$modelClient->getClientIP();
		$modelClient->getHttpToken($modelClient->tg_chat_id);
		$modelClient->setPassword($modelClient->tg_chat_id);
		$modelClient->getIdentify();
		$modelClient->login = $modelClient->generateLogin($modelClient->tg_chat_id);
		
		if ($modelClient->save()) {
			$modelClient->referral_token = self::getHash($modelClient->id, $modelClient->tg_chat_id);
			if ($modelClient->save()) {
				return $modelClient;
			}
		}
		
		return false;
	}
	
	/**
	 * getRef()
	 */
	public static function getRef($bot_id=0, $chat_id=0)
	{
		$bot_url = '';
		
		if (empty($bot_id) || empty($chat_id)) {
			return $bot_url;
		}
		
		$modelBot = self::getBot($bot_id);
		if (empty($modelBot) || !empty($modelBot->deleted)) {
			return $bot_url;
		}
		
		$bot_url = 'https://'.$modelBot->bot_url;
		
		$modelClient = self::getClient($chat_id);
		if (empty($modelClient)) {
			return $bot_url;
		}
		
		return $bot_url.'?start='.$modelClient->referral_token;
	}
	
	/**
	 * getReferralsData($id)
	 */
	public static function getReferralsData($id=0)
	{
		$data = [
			'friends' => 0,
			'points' => 0,
			'link' => '',
			'referrals' => [],
			'awards' => [],
		];
		
		if (empty($id)) {
			return $data;
		}
		
		$modelChatbotLog = ChatbotLog::findOne(['id' => $id]);
		if (!empty($modelChatbotLog)) {
			$data['link'] = ApiChatbot::getRef($modelChatbotLog->bot_id, $modelChatbotLog->chat_id);

			$arrayReferrals = Referrals::find()
				->select('{{%chatbot_log}}.chat_username AS name')
				->leftJoin('{{%chatbot_log}}', '{{%chatbot_log}}.id_client = {{%referrals}}.id_client')
				->where([
					'{{%referrals}}.id_referral' => $modelChatbotLog->id_client,
					'{{%referrals}}.deleted' => Referrals::STATUS_NOT_DELETED,
					'{{%referrals}}.ref_type' => 1,
				])
				->groupBy(['{{%chatbot_log}}.id_client'])
				->asArray()
				->all();
			if (!empty($arrayReferrals) && is_array($arrayReferrals)) {
				$data['friends'] = count($arrayReferrals);
				
				foreach ($arrayReferrals as $referral) {
					$data['referrals'][] = $referral['name'];
				}
			}
		
			$modelClients = self::findClient($modelChatbotLog->id_client);
			if (!empty($modelClients)) {
				$data['points'] = $modelClients->points;
			}
			
			$arrayTokens = Tokens::find()
				->select('service_type')
				->where(['id_client' => $modelChatbotLog->id_client])
				->groupBy(['service_type'])
				->asArray()
				->all();
			if (!empty($arrayTokens) && !empty(count($arrayTokens))) {
				foreach ($arrayTokens as $token_type) {
					$data['awards'][$token_type['service_type']] = $token_type['service_type'];
				}
			}
		}

		return $data;
	}
	
	/**
	 * getChatbotLog($id=0)
	 */
	public static function getChatbotLog($id=0)
	{
		return ChatbotLog::findOne(['id' => $id]);
	}
	
	/**
	 * getBybitTokens($id=0)
	 */
	public static function getBybitTokens($id=0)
	{
		return Tokens::findOne([
			'service_type' => 2, 
			'id_client' => $id, 
			'deleted'=>Tokens::STATUS_NOT_DELETED,
		]);
	}
	
	/**
	 * getChatbotLog($id=0)
	 */
	public static function getTonTokens($id=0)
	{
		return Tokens::findOne([
			'service_type' => 1, 
			'id_client' => $id, 
			'deleted'=>Tokens::STATUS_NOT_DELETED,
		]);
	}
	
	/**
	 * getOkxTokens($id=0)
	 */
	public static function getOkxTokens($id=0)
	{
		return Tokens::findOne([
			'service_type' => 3, 
			'id_client' => $id, 
			'deleted'=>Tokens::STATUS_NOT_DELETED,
		]);
	}
	
	/**
	 * getSolTokens($id=0)
	 */
	public static function getSolTokens($id=0)
	{
		return Tokens::findOne([
			'service_type' => 4, 
			'id_client' => $id, 
			'deleted'=>Tokens::STATUS_NOT_DELETED,
		]);
	}
	
	/**
	 * getSolTokens($id=0)
	 */
	public static function getSuiTokens($id=0)
	{
		return Tokens::findOne([
			'service_type' => 5, 
			'id_client' => $id, 
			'deleted'=>Tokens::STATUS_NOT_DELETED,
		]);
	}
	
	/**
	 * getChatbotLog($id=0)
	 */
	public static function getTelegramData($id=0)
	{
		$data = [
			'bot_token' => '',
			'chat_id' => '',
			'id_client' => 0,
			'bot_name' => '',
		];
		
		$modelChatbotLog = ChatbotLog::findOne([
			'id' => $id, 
		]);
		
		if (!empty($modelChatbotLog)) {
			$data['chat_id'] = $modelChatbotLog->chat_id;
			$data['id_client'] = $modelChatbotLog->id_client;
			
			$modelChatbot = Chatbot::findOne([
				'id_bot' => $modelChatbotLog->bot_id, 
			]);
			
			if (!empty($modelChatbot)) {
				$data['bot_token'] = $modelChatbot->bot_token;
				$data['bot_name'] = $modelChatbot->bot_name;
			}
		}
		
		return $data;
	}
	
	/**
	 * sendMessageConnectedTon($id=0, $type=0)
	 */
	public static function sendMessageConnectedTon($id=0, $type=0)
	{
		if (empty($id) || empty($type)) {
			return false;
		}
		
		$hash = TelegramApi::tg()->generateUserToken($id);
		$services = TelegramApi::getButtonService();
		$btn = [];
		if (!empty($services) && is_array($services)) {
			foreach ($services as $service) {				
				if ($service['id']=='conv') {
					$btn['inline_keyboard'][][] = [
						'text'=> $service['name'], 
						'web_app' => [
							'url' =>$service['url'].'?id='.$id.'&sc='.$hash,
						],
					];
					
					break;
				}
			}
		}

		$data = ApiChatbot::getTelegramData($id);
		if (
			!empty($data) && 
			!empty($data['chat_id']) && 
			!empty($data['bot_token']) &&
			!empty($data['id_client'])
		) {
			if ($type==1) {
			
				$modelTokens = self::getTonTokens($data['id_client']);
				if (empty($modelTokens)) {
					return false;
				}

				$bs64address = TonApi::pstatic()->getAddressParse($modelTokens->identify1);
				if (!empty($bs64address) && empty($bs64address['error'])) {
					$send = [];
					$send['chat_id'] = $data['chat_id'];
					$send['parse_mode'] = 'HTML';
					$send['disable_web_page_preview'] = true;
					$bs64address = substr_replace($bs64address, '...', 8, -8);
					$send['text'] = Yii::t('Api', 'Ton Wallet').' '.$bs64address.' '.Yii::t('Api', 'successfully connected to FinKeeper').'.  <a href="https://t.me/finkeeper_ru/26">FinKeeper(RU)</a>';
					
					if (!empty($btn)) {
						$send['reply_markup'] = $btn;
					}
	
					if (TelegramApi::sendData($send, $data['bot_token'])) {
						return true;
					}
				}
				
			} else if ($type==2) {
				
				$modelTokens = self::getTonTokens($data['id_client']);
				if (empty($modelTokens)) {
					return false;
				}
			
				$bs64address = TonApi::pstatic()->getAddressParse($modelTokens->identify1);
				if (!empty($bs64address) && empty($bs64address['error'])) {
					$send = [];
					$send['chat_id'] = $data['chat_id'];
					$send['parse_mode'] = 'HTML';
					$send['disable_web_page_preview'] = true;
					$bs64address = substr_replace($bs64address, '...', 8, -8);
	
					$send['text'] = Yii::t('Api', 'Ton Wallet').' '.$bs64address.' '.Yii::t('Api', 'disconnected from FinKeeper').'.  <a href="https://t.me/finkeeper_ru/26">FinKeeper(RU)</a>';
					
					if (!empty($btn)) {
						$send['reply_markup'] = $btn;
					}
						
					if (TelegramApi::sendData($send, $data['bot_token'])) {
						return true;
					}		
				}
				
			} else if ($type==3) {

				$modelTokens = self::getBybitTokens($data['id_client']);
				if (empty($modelTokens)) {
					return false;
				}

				$send = [];
				$send['chat_id'] = $data['chat_id'];
				$send['parse_mode'] = 'HTML';
				$send['disable_web_page_preview'] = true;
				//$bs64address = substr_replace($modelTokens->identify1, '...', 8, -8);
				$uid = $modelTokens->identify1;
		
				$send['text'] = Yii::t('Api', 'Account').' '.$uid.' Bybit '.Yii::t('Api', 'successfully connected to FinKeeper').'.  <a href="https://t.me/finkeeper_ru/26">FinKeeper(RU)</a>';
				
				if (!empty($btn)) {
					$send['reply_markup'] = $btn;
				}
						
				if (TelegramApi::sendData($send, $data['bot_token'])) {
					return true;
				}	

			} else if ($type==4) {
				
				$modelTokens = self::getBybitTokens($data['id_client']);
				if (empty($modelTokens)) {
					return false;
				}
				
				$send = [];
				$send['chat_id'] = $data['chat_id'];
					
				//$bs64address = substr_replace($modelTokens->identify1, '...', 8, -8);
				$uid = $modelTokens->identify1;
				$send['parse_mode'] = 'HTML';
				$send['disable_web_page_preview'] = true;
				$send['text'] = Yii::t('Api', 'Account').' '.$uid.' Bybit '.Yii::t('Api', 'disconnected from FinKeeper').'.  <a href="https://t.me/finkeeper_ru/26">FinKeeper(RU)</a>';
				
				if (!empty($btn)) {
					$send['reply_markup'] = $btn;
				}
						
				if (TelegramApi::sendData($send, $data['bot_token'])) {
					return true;
				}	
				
			} else if ($type==5) {

				$modelTokens = self::getOkxTokens($data['id_client']);
				if (empty($modelTokens)) {
					return false;
				}

				$send = [];
				$send['chat_id'] = $data['chat_id'];
				$send['parse_mode'] = 'HTML';
				$send['disable_web_page_preview'] = true;
				//$bs64address = substr_replace($modelTokens->identify1, '...', 8, -8);
				$uid = $modelTokens->identify4;
		
				$send['text'] = Yii::t('Api', 'Account').' '.$uid.' OKX '.Yii::t('Api', 'successfully connected to FinKeeper').'.  <a href="https://t.me/finkeeper_ru/26">FinKeeper(RU)</a>';
				
				if (!empty($btn)) {
					$send['reply_markup'] = $btn;
				}
						
				if (TelegramApi::sendData($send, $data['bot_token'])) {
					return true;
				}	

			} else if ($type==6) {
				
				$modelTokens = self::getOkxTokens($data['id_client']);
				if (empty($modelTokens)) {
					return false;
				}
				
				$send = [];
				$send['chat_id'] = $data['chat_id'];
					
				//$bs64address = substr_replace($modelTokens->identify1, '...', 8, -8);
				$uid = $modelTokens->identify4;
				$send['parse_mode'] = 'HTML';
				$send['disable_web_page_preview'] = true;
				$send['text'] = Yii::t('Api', 'Account').' '.$uid.' OKX '.Yii::t('Api', 'disconnected from FinKeeper').'.  <a href="https://t.me/finkeeper_ru/26">FinKeeper(RU)</a>';
				
				if (!empty($btn)) {
					$send['reply_markup'] = $btn;
				}
						
				if (TelegramApi::sendData($send, $data['bot_token'])) {
					return true;
				}	
				
				
			} else if ($type==7) {
				
				$modelTokens = self::getSolTokens($data['id_client']);
				if (empty($modelTokens)) {
					return false;
				}

				$send = [];
				$send['chat_id'] = $data['chat_id'];
				$send['parse_mode'] = 'HTML';
				$send['disable_web_page_preview'] = true;
				$bs64address = substr_replace($modelTokens->identify1, '...', 8, -8);
				$send['text'] = Yii::t('Api', 'SOL Wallet').' '.$bs64address.' '.Yii::t('Api', 'successfully connected to FinKeeper').'.  <a href="https://t.me/finkeeper_ru/26">FinKeeper(RU)</a>';
				
				if (!empty($btn)) {
					$send['reply_markup'] = $btn;
				}

				if (TelegramApi::sendData($send, $data['bot_token'])) {
					return true;
				}
				
			} else if ($type==8) {
				
				$modelTokens = self::getSolTokens($data['id_client']);
				if (empty($modelTokens)) {
					return false;
				}

				$send = [];
				$send['chat_id'] = $data['chat_id'];
				$send['parse_mode'] = 'HTML';
				$send['disable_web_page_preview'] = true;
				$bs64address = substr_replace($modelTokens->identify1, '...', 8, -8);

				$send['text'] = Yii::t('Api', 'SOL Wallet').' '.$bs64address.' '.Yii::t('Api', 'disconnected from FinKeeper').'.  <a href="https://t.me/finkeeper_ru/26">FinKeeper(RU)</a>';
				
				if (!empty($btn)) {
					$send['reply_markup'] = $btn;
				}
					
				if (TelegramApi::sendData($send, $data['bot_token'])) {
					return true;
				}		
				
			} else if ($type==9) {
				
				$modelTokens = self::getSuiTokens($data['id_client']);
				if (empty($modelTokens)) {
					return false;
				}

				$send = [];
				$send['chat_id'] = $data['chat_id'];
				$send['parse_mode'] = 'HTML';
				$send['disable_web_page_preview'] = true;
				$bs64address = substr_replace($modelTokens->identify1, '...', 8, -8);
				$send['text'] = Yii::t('Api', 'SUI Wallet').' '.$bs64address.' '.Yii::t('Api', 'successfully connected to FinKeeper').'.  <a href="https://t.me/finkeeper_ru/26">FinKeeper(RU)</a>';
				
				if (!empty($btn)) {
					$send['reply_markup'] = $btn;
				}

				if (TelegramApi::sendData($send, $data['bot_token'])) {
					return true;
				}
				
			} else if ($type==10) {
				
				$modelTokens = self::getSuiTokens($data['id_client']);
				if (empty($modelTokens)) {
					return false;
				}

				$send = [];
				$send['chat_id'] = $data['chat_id'];
				$send['parse_mode'] = 'HTML';
				$send['disable_web_page_preview'] = true;
				$bs64address = substr_replace($modelTokens->identify1, '...', 8, -8);

				$send['text'] = Yii::t('Api', 'SUI Wallet').' '.$bs64address.' '.Yii::t('Api', 'disconnected from FinKeeper').'.  <a href="https://t.me/finkeeper_ru/26">FinKeeper(RU)</a>';
				
				if (!empty($btn)) {
					$send['reply_markup'] = $btn;
				}
					
				if (TelegramApi::sendData($send, $data['bot_token'])) {
					return true;
				}		
				
			} else {
				return false;
			}
		}

		return 	false;	
	}
	
	/**
	 * getSettingsLang($id_log)
	 */
	public static function getSettingsLang($id_log=0)
	{
		if (empty($id_log)) {
			return false;
		}
		
		$modelChatbotLog = ChatbotLog::findOne([
			'id' => $id_log, 
		]);
		
		if (empty($modelChatbotLog)) {
			return false;
		}

		$modelClient = Clients::findOne(['id' => $modelChatbotLog->id_client, 'deleted' => Clients::STATUS_NOT_DELETED]);
		
		if (empty($modelClient) || empty($modelClient->lang)) {
			return false;
		}
		
		return $modelClient->lang;	
	}
	
	/**
	 * setSettingsLang($id_log)
	 */
	public static function setSettingsLang($id_log=0, $lang='')
	{
		$exist_lang = Yii::$app->params['supported_lang'];
		if (empty($id_log) || !in_array($lang, $exist_lang)) {
			return false;
		}
		
		$modelChatbotLog = ChatbotLog::findOne([
			'id' => $id_log, 
		]);
		
		if (empty($modelChatbotLog)) {
			return false;
		}

		$modelClient = Clients::findOne(['id' => $modelChatbotLog->id_client, 'deleted' => Clients::STATUS_NOT_DELETED]);
		
		if (empty($lang)) {
			$lang = $modelChatbotLog->from_language_code;
		}
		
		$modelClient->lang = $lang;
		
		if ($modelClient->save()) {
			return true;
		}
		
		return false;	
	}
	
	/**
	 * removeKeyboard($id_log=0)
	 */
	public static function removeKeyboard($id_log=0)
	{
		$data = self::getTelegramData($id_log);
		if (
			empty($data) || 
			empty($data['chat_id']) || 
			empty($data['bot_token']) ||
			empty($data['id_client'])
		) {
			return false;
		}
		
		$hash = TelegramApi::tg()->generateUserToken($id_log);
		
		$btn_url = TelegramApi::ApiUrlConv.'?id='.$id_log.'&sc='.$hash;
		
		$send = [];
		$send['chat_id'] = $data['chat_id'];
		$send['text'] = Yii::t('Api', 'Welcome to').' '.$data['bot_name'];
		$send['reply_markup'] = [
			'remove_keyboard' => true,
		];
		$send['reply_markup']['inline_keyboard'][][] = [
			'text'=> Yii::t('Title', 'Launch application'), 
			'web_app' => [
				'url' =>$btn_url,
			],
		];

		if (TelegramApi::sendData($send, $data['bot_token'])) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * addMenuButton($id_log=0)
	 */
	public static function addMenuButton($id_log=0)
	{
		$data = self::getTelegramData($id_log);
		if (
			empty($data) || 
			empty($data['chat_id']) || 
			empty($data['bot_token']) ||
			empty($data['id_client'])
		) {
			return false;
		}
		
		$hash = TelegramApi::tg()->generateUserToken($id_log);
		
		$btn_url = TelegramApi::ApiUrlConv.'?id='.$id_log.'&sc='.$hash;
		
		$send = [];
		$send['chat_id'] = $data['chat_id'];		
		$send['menu_button'] = json_encode([
			'type' => 'web_app',
			'text' => Yii::t('Title', 'Launch application'),
			'web_app' => [
				'url' => $btn_url,
			]
		]);
		
		if (TelegramApi::sendMenuButton($send, $data['bot_token'])) {
			return true;
		}
		
		return false;
	}
	
	/** 
	 * getUserLang($id_log=0)
	 */
	public static function getUserLang($id_log=0, $lang='')
	{
		if (empty($id_log)) {
			return false;
		}

		if (empty($lang)) {
			if (!empty($id_log)) {
				$lang = self::getSettingsLang($id_log);
			} else {
				$lang = Yii::$app->session->get('lang'); 
			}
		} 

		if (!empty($lang)) {
			
			Yii::$app->language = strtolower($lang).'-'.strtoupper($lang);
			Yii::$app->response->cookies->add(new \yii\web\Cookie([
				'name' => 'lang',
				'value' => $lang,
				'expire' => time() + (365 * 24 * 60),
			]));
			
			Yii::$app->session->set('lang', $lang); 			
		}
	}
	
	/**
	 * saveTargets($log_id=0, $symbol='', $price='', $coins='', $discription='')
	 */
	public static function saveTargets(
		$log_id=0,
		$symbol='', 
		$price='', 
		$coins='',
		$discription='',
		$current_price='',
		$multiply=0
	) {
		
		$log_id = (int) $log_id;
		if (empty($log_id)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not User ID'),
			];
		}
		
		$modelChatbotLog = ChatbotLog::findOne(['id' => $log_id]);
		if (empty($modelChatbotLog)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'User Not Found'),
			];		
		}

		if (empty($symbol) || empty($price) || empty($coins) || empty($current_price) || empty($multiply)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Missing Save Data'),
			];
		}

		$modelTargets = Targets::findOne([
			'symbol' => $symbol, 
			'id_client' => $modelChatbotLog->id_client, 
			'deleted'=>Targets::STATUS_NOT_DELETED,
		]);

		$change_target = 1;
		$message = Yii::t('Api', 'Success Change Target');
		if (empty($modelTargets)) {
			$modelTargets = new Targets;
			$change_target = 0;
			$message = Yii::t('Api', 'Success Add Target');
		}

		$modelTargets->id_client = $modelChatbotLog->id_client;
		$modelTargets->symbol = $symbol;
		$modelTargets->price = $price;
		$modelTargets->coins = $coins;
		$modelTargets->description = !empty($description) ? $description : '';
		$modelTargets->current_price = $current_price;
		$modelTargets->multiply = $multiply;
		
		if ($modelTargets->save()) {

			return [
				'error' => 0,
				'message' => Yii::t('Api', 'Success Add Target'),
				'id' => $modelTargets->id_target,
				'change_target' => $change_target,
			];
		}

        return [
			'error' => 1,
			'message' => Yii::t('Error', 'Error save target'),
		];	
	}
	
	/**
	 * getTargets($log_id=0)
	 */
	public static function getTargets($log_id=0)
	{
		$log_id = (int) $log_id;
		if (empty($log_id)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not User ID'),
			];
		}
		
		$modelChatbotLog = ChatbotLog::findOne(['id' => $log_id]);
		if (empty($modelChatbotLog)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'User Not Found'),
			];		
		}
		
		$targets = Targets::find()->where([
			'deleted' => Targets::STATUS_NOT_DELETED, 
			'id_client' => $modelChatbotLog->id_client,
		])->all();
		
		$data = [];
		
		if (empty($targets) || !is_array($targets)) {
			return $data;
		}
	
		foreach ($targets as $modelTargets) {
			$data[] = [
				'symbol' => $modelTargets->symbol,
				'price' => $modelTargets->price,
				'current' => $modelTargets->current_price,
				'multiply' => $modelTargets->multiply,
			];
		}
		
		if (empty($data) || !is_array($data)) {
			return $data;
		}
		
		return $data;
	}
	
	/**
	 * getPrice($sumbol='', $currency='usd')
	 * $api = 0 default(bybit, okx, crypoprice, tonapi)
	 * $api = 1 bybit(bybit, okx, crypoprice, tonapi)
	 * $api = 2 tonapi(tonapi, crypoprice, bybit, okx)
	 * $api = 3 okx(okx, bybit, crypoprice, tonapi)
	 */
	public static function getPrice($sumbol='', $currency='usd', $api=0)
	{
		$price = [
			'error' => 0,
			'data' => 0,
		];
		
		if (empty($sumbol)) {
			return $price;
		}
		
		if ($api==1) {
			
			$price = Bybit::getPrice($sumbol, $currency);
			if (!empty($price['data'])) {
				return $price;	
			}
			
			$price = OKX::getPrice($sumbol, $currency);
			if (!empty($price['data'])) {
				return $price;	
			}

			$price = Cryptoprice::getPrice($sumbol);
			if (!empty($price['data'])) {
				return $price;	
			}

			$price = TonApi::pstatic()->getTonApiPrice($sumbol, $currency);
			if (!empty($price['data'])) {
				return $price;	
			}
			
		} else if ($api==2) {
			
			$price = TonApi::pstatic()->getTonApiPrice($sumbol, $currency);
			if (!empty($price['data'])) {
				return $price;	
			}

			$price = Cryptoprice::getPrice($sumbol);
			if (!empty($price['data'])) {
				return $price;	
			}

			$price = Bybit::getPrice($sumbol, $currency);
			if (!empty($price['data'])) {
				return $price;	
			}
			
			$price = OKX::getPrice($sumbol, $currency);
			if (!empty($price['data'])) {
				return $price;	
			}
			
		} else if ($api==3) {
			
			$price = OKX::getPrice($sumbol, $currency);
			if (!empty($price['data'])) {
				return $price;	
			}
			
			$price = Bybit::getPrice($sumbol, $currency);
			if (!empty($price['data'])) {
				return $price;	
			}
			
			$price = Cryptoprice::getPrice($sumbol);
			if (!empty($price['data'])) {
				return $price;	
			}
			
			$price = TonApi::pstatic()->getTonApiPrice($sumbol, $currency);
			if (!empty($price['data'])) {
				return $price;	
			}

		} else {
			
			$price = Bybit::getPrice($sumbol, $currency);
			if (!empty($price['data'])) {
				return $price;	
			}
			
			$price = OKX::getPrice($sumbol, $currency);
			if (!empty($price['data'])) {
				return $price;	
			}

			$price = Cryptoprice::getPrice($sumbol);
			if (!empty($price['data'])) {
				return $price;	
			}

			$price = TonApi::pstatic()->getTonApiPrice($sumbol, $currency);
			if (!empty($price['data'])) {
				return $price;	
			}
		}

		return $price;		
	}
	
	/**
	 * sendMessageToChat($id=0, $message='')
	 */
	public static function sendMessageToChat($id=0, $message='')
	{
		if (empty($id) || empty($message)) {
			return false;
		}

		$hash = TelegramApi::tg()->generateUserToken($id);
		$services = TelegramApi::getButtonService();
		$btn = [];
		if (!empty($services) && is_array($services)) {
			foreach ($services as $service) {				
				if ($service['id']=='conv') {
					$btn['inline_keyboard'][][] = [
						'text'=> $service['name'], 
						'web_app' => [
							'url' =>$service['url'].'?id='.$id.'&sc='.$hash,
						],
					];
					
					break;
				}
			}
		}

		$data = ApiChatbot::getTelegramData($id);
		if (
			!empty($data) && 
			!empty($data['chat_id']) && 
			!empty($data['bot_token']) &&
			!empty($data['id_client'])
		) {
			$send['chat_id'] = $data['chat_id'];
			$send['parse_mode'] = 'HTML';
			$send['disable_web_page_preview'] = true;
			$send['text'] = $message;
					
			if (!empty($btn)) {
				$send['reply_markup'] = $btn;
			}

			if (TelegramApi::sendData($send, $data['bot_token'])) {
				return true;
			}
		}

		return 	false;	
	}
	
	/**
	 * getUserid($id_log=0)
	 */
	public static function getUserid($id_log=0)
	{
		if (empty($id_log)) {
			return false;
		}
		
		$modelChatbotLog = ChatbotLog::findOne(['id' => $id_log]);
		if (empty($modelChatbotLog) || empty($modelChatbotLog->id_client)) {
			return false;
		}
		
		return $modelChatbotLog->id_client;
	}
	
	/**
	 * getUserLog($id=0)
	 */
	public static function getUserLog($id=0)
	{
		if (empty($id)) {
			return false;
		}
		
		$modelChatbotLog = ChatbotLog::findOne(['id_client' => $id]);
		if (empty($modelChatbotLog)) {
			return false;
		}
		
		return $modelChatbotLog;
	}
	
	/**
	 * getUserid($id_log=0)
	 */
	public static function getUsedGPTChat($id=1)
	{
		$modelGPT = Chatgpt::findData($id);
		if (!empty($modelGPT) && !empty($modelGPT['used'])) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * saveUserpic($id=0, $image='')
	 */
	public static function saveUserpic($chat_id=0, $image='')
	{
		$modelClient = Clients::findOne(['tg_chat_id' => $chat_id, 'deleted' => Clients::STATUS_NOT_DELETED]);
		if (empty($modelClient)) {
			return false;
		}
		
		if (!empty($modelClient->userpic)) {
			return false;
		}
		
		$modelClient->userpic = $image;
		return $modelClient->save();
	}	
	
	/**
	 * saveUserpic($id=0, $image='')
	 */
	public static function saveTGToken($chat_id=0, $token='')
	{
		$modelClient = Clients::findOne(['tg_chat_id' => $chat_id, 'deleted' => Clients::STATUS_NOT_DELETED]);
		if (empty($modelClient)) {
			return false;
		}
		
		$modelClient->tg_auth_token = $token;
		return $modelClient->save();
	}	
	
	/**
	 * getWallet($id=0) 
	 */
	public static function getWallet($id=0) 
	{
		if (empty($id)) {
			return false;
		} 
		
		$modelTokens = Tokens::findOne(['id_client' => $id, 'service_type' => 6, 'deleted' => Clients::STATUS_NOT_DELETED]);
		if (empty($modelTokens) || empty($modelTokens->identify1)) {
			return false;
		}
		
		$sui = new SUIApi;
		$sui->address = $modelTokens->identify1;
		$balance = 0;
		
		$response = $sui->getWalletBalance();
		if (empty($response['error'])) {
			if (!empty($response['data']) && !empty($response['data'][0])) {
				
				foreach ($response['data'][0] as $val) {
					
					if (empty($val['balance']) || empty($val['symbolid'])) {
						continue;
					}
					
					$balance = $val['balance'];

					if (!empty($balance)) {
						if (is_float($balance)) {
							$valbalance = number_format($balance, 12, '.', '');
						} else if (is_int($balance)) {
							$balance = number_format($balance, 12, '.', '');
						} else {
							$balance = $balance*1;
							$balance = number_format($balance, 12, '.', '');
						}
					}
					
					$balance = Exchange::formatValue($balance);					
				}
			}
		}
		
		return ['address' => $modelTokens->identify1, 'balance' => $balance];
	}
}