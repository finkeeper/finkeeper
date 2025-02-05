<?php
namespace api\modules\v2\components;

use Yii;
use api\modules\v2\models\ApiChatbot;
use common\models\Exchange;
use CURLFile;

class TelegramApi {
	
	const TelegramBotApiUrl = 'https://api.telegram.org/bot';
	const BotApiUrl = 'https://app.finkeeper.pro/v2/datas';
	
	const TELEGRAMRU = 'https://t.me/finkeeper_ru';
	const TELEGRAMEN = 'https://t.me/finkeeper_en';
	const XCOM = 'https://x.com/FinKeeper/';
	const GITBOOK = 'https://finkeeper.gitbook.io/finkeeper';
	
	const ApiUrlCalc = 'https://app.finkeeper.pro/v2/datas/stakingcalc';
	const ApiUrlConv = 'https://app.finkeeper.pro/v2/datas/converter';
	const ApiUrlBybit = 'https://app.finkeeper.pro/v2/datas/bybitbalance';
	const ApiUrlTon = 'https://app.finkeeper.pro/v2/datas/tonconnect';

	private $salt;
	
	/**
	 * construct
	 */
	function __construct() {
		
		$conf = Exchange::getConfig(3);
		if (empty($conf) || !is_array($conf) || empty($conf['salt'])) {
			return false;
		}

		$this->salt = $conf['salt'];
	}

	/**
	 * setWebhook($bot_id, $bot_token) 
	 */
	public static function setWebhook($bot_id=0, $bot_token='') 
	{
		if (empty($bot_id) || empty($bot_token)) {
			return false;
		}
		
		$url = self::getApiUrl($bot_token) . '/setWebhook?url=' . self::BotApiUrl . '?id=' . $bot_id;
		
		return self::sendActionToBot($url);
	}
	
	/**
	 * deleteWebhook($bot_token)
	 */
	public static function deleteWebhook($bot_token='') 
	{
		if (empty($bot_token)) {
			return false;
		}
		
		$url = self::getApiUrl($bot_token) . '/deleteWebhook';
		
		return self::sendActionToBot($url);
	}
	
	/**
	 * getApiUrl($api_token = '')
	 */
    public static function getApiUrl($bot_token = '') 
	{
        if (empty($bot_token)) {
			return false;
		}
		
		return self::TelegramBotApiUrl . $bot_token;
    }
	
	/**
	 *
	  */
	 public static function getButtonService()
	 {
		return [
			0 => [
				'name' => Yii::t('Title', 'Calculator'),
				'url' => TelegramApi::ApiUrlCalc,
				'callback_data' => '/calculator',
				'text' => Yii::t('Api', 'Staking Profitability Calculator'), 
				'text_button' => Yii::t('Api', 'btnOpen'),
				'id' => 'calc',
			],
			1 => [
				'name' => Yii::t('Title', 'Launch application'),
				'url' => TelegramApi::ApiUrlConv,
				'callback_data' => '/converter',
				'text' => Yii::t('Api', 'Currency converter'),
				'text_button' => Yii::t('Api', 'btnOpen'),
				'id' => 'conv',
				'link' => [
					'tg_ru' => self::TELEGRAMRU,
					'tg_en' => self::TELEGRAMEN,
					'x_com' => self::XCOM,
					'git_book' => self::GITBOOK,
				],
			],
			2 => [
				'name' => Yii::t('Title', 'Bybit'),
				'url' => TelegramApi::ApiUrlBybit,
				'callback_data' => '/bybit',
				'text' => Yii::t('Api', 'Bybit wallet balance'),
				'text_button' => Yii::t('Api', 'btnOpen'),
				'id' => 'bybit',
			],
			3 => [
				'name' => Yii::t('Title', 'Tonconnect'),
				'url' => TelegramApi::ApiUrlTon,
				'callback_data' => '/tonconnect',
				'text' => Yii::t('Api', 'Tonconnect'),
				'text_button' => Yii::t('Api', 'btnOpen'),
				'id' => 'tonconn',
			],
		]; 
	 }
	
	/**
	 * sendDataToBot()
	 */
	public static function sendData($data= [], $token='')
	{
		if (empty($data) || !is_array($data) || empty($token)) {
			return false;
		}
		
		$url = self::getApiUrl($token) . '/sendMessage';
		
		return self::sendDataToBot($url, $data);
	}
	
	/**
	 * sendMenuButton($data= [], $token='')
	 */
	public static function sendMenuButton($data= [], $token='')
	{
		if (empty($data) || !is_array($data) || empty($token)) {
			return false;
		}
		
		$url = self::getApiUrl($token) . '/setChatMenuButton';
		
		return self::sendDataToBot($url, $data);
	}
	
	/**
	 * sendPhoto($data= [], $token='')
	 */
	public static function sendPhoto($data= [], $token='')
	{
		if (empty($data) || !is_array($data) || empty($token)) {
			return false;
		}
		
		$url = self::getApiUrl($token) . '/sendPhoto';
		
		return self::sendFileToBot($url, $data);
	}
	
	/**
	 * sendDataToBot()
	 */
	public static function editData($data= [], $token='')
	{
		if (empty($data) || !is_array($data) || empty($token)) {
			return false;
		}
		
		$url = self::getApiUrl($token) . '/editMessageText';
		
		return self::sendDataToBot($url, $data);
	}
	
	/**
	 * sendActionToBot($botUrl) 
	 */
	public static function sendActionToBot($botUrl) 
	{
		if (empty($botUrl) || !is_string($botUrl)) {
			return null;
		}
		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_URL => $botUrl,
			CURLOPT_POST => true,
			CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
		]);
		$result = curl_exec($ch);
		curl_close($ch);
		return json_decode($result, true);
	}
	
	/**
	 * sendDataToBot($botUrl, $data) 
	 */
	public static function sendDataToBot($botUrl, $data) 
	{
		if (empty($botUrl) || !is_string($botUrl)) {
			return false;
		}
		if (empty($data) || !is_array($data)) {
			return false;
		}
		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_URL => $botUrl,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => json_encode($data),
			CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
		]);
		$result = curl_exec($ch);		
		curl_close($ch);
		return json_decode($result, true);
	}
	
	/**
	 * sendDataToBot($botUrl, $data) 
	 */
	public static function sendFileToBot($botUrl, $data) 
	{
		if (empty($botUrl) || !is_string($botUrl)) {
			return false;
		}
		
		$botUrl .= '?chat_id='.$data['chat_id'];
		
		$data['photo'] = new CURLFile(realpath(getcwd().$data['photo']));
		
		if (empty($data) || !is_array($data)) {
			return false;
		}
		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_URL => $botUrl,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $data,
			CURLOPT_HTTPHEADER => ["Content-Type:multipart/form-data"],
		]);
		$result = curl_exec($ch);		
		curl_close($ch);
		return json_decode($result, true);
	}
	
	/**
	 * parseData($input='') 
	 */
	public static function parseData($input='') 
	{
		$data = [
			'error' => 0,
			'error_message' => '',
			'error_code' => 0,
			'bot_id' => 0,
			'message_id' =>  0,
			'update_id' =>  0,
			'date' => '',
			'text' => '',
			'callback_data' => '',
			'type' => '',
			'from' => [
				'id' => 0,
				'is_bot' => 0,
				'first_name' => '',
				'last_name' => '',
				'username' => '',
				'language_code' => ''
			],
			'chat' => [
				'id' => 0,
				'first_name' => '',
				'last_name' => '',
				'username' => '',
				'type' => '',
			],
			'request' => $input,
			'bot_id' => 0,
			'bot_token' => 0,
			'bot_name' => '',
			'referral' => '',
		];
		
		$id = (int) Yii::$app->request->get('id');
		if (empty($id)) {
			$data['error'] = 1;
			$data['error_code'] = 10201;
			$data['error_message'] = Yii::t('Error', 'Missing ID bot');
			return $data;
		}
		
		
		$model = ApiChatbot::getBot($id);
		if (empty($model) || empty($model->bot_token)) {
			$data['error'] = 1;
			$data['error_code'] = 10210;
			$data['error_message'] = Yii::t('Error', 'Missing bot');
			return $data;
		}
		
		$data['bot_id'] = $model->id_bot;
		$data['bot_token'] = $model->bot_token;
		$data['bot_name'] = $model->bot_name;

		$array = @json_decode($input, true);
		if (empty($array) || !is_array($array)) {			
			$data['error'] = 1;
			$data['error_code'] = 10204;
			$data['error_message'] = Yii::t('Error', 'Incorreect TG Data');
			return $data;
		}
		
		if (!empty($array['update_id'])) {
			
			$data['update_id'] = $array['update_id'];
			$data['type'] = 1;
			
			if (empty($array['message'])) {
				$data['error'] = 1;
				$data['error_code'] = 10205;
				$data['error_message'] = Yii::t('Error', 'Missing TG Message');
				return $data;	
			}
			
			if (!empty($array['message']['message_id'])) {
				$data['message_id'] = $array['message']['message_id'];
			}
			
			if (!empty($array['message']['date'])) {
				$data['date'] = date('Y-m-d H:i:s', $array['message']['date']);
			}
			
			if (!empty($array['message']['text'])) {
				$data['text'] = $array['message']['text'];
			}
			
			if (empty($array['message']['from'])) {
				$data['error'] = 1;
				$data['error_code'] = 10206;
				$data['error_message'] = Yii::t('Error', 'Missing TG Sender Data');
				return $data;	
			}
			
			if (!empty($array['message']['from']['id'])) {
				$data['from']['id'] = $array['message']['from']['id'];
			}
			
			if (!empty($array['message']['from']['is_bot'])) {
				$data['from']['is_bot'] = $array['message']['from']['is_bot'];
			}
			
			if (!empty($array['message']['from']['first_name'])) {
				$data['from']['first_name'] = $array['message']['from']['first_name'];
			}
			
			if (!empty($array['message']['from']['last_name'])) {
				$data['from']['last_name'] = $array['message']['from']['last_name'];
			}
			
			if (!empty($array['message']['from']['username'])) {
				$data['from']['username'] = $array['message']['from']['username'];
			}
			
			if (!empty($array['message']['from']['language_code'])) {
				$data['from']['language_code'] = $array['message']['from']['language_code'];
			}
			
			if (empty($array['message']['chat'])) {
				$data['error'] = 1;
				$data['error_code'] = 10207;
				$data['error_message'] = Yii::t('Error', 'Missing TG Chat Data');
				return $data;	
			}

			if (!empty($array['message']['chat']['id'])) {
				$data['chat']['id'] = $array['message']['chat']['id'];
			}
			
			if (!empty($array['message']['chat']['first_name'])) {
				$data['chat']['first_name'] = $array['message']['chat']['first_name'];
			}
			
			if (!empty($array['message']['chat']['last_name'])) {
				$data['chat']['last_name'] = $array['message']['chat']['last_name'];
			}
			
			if (!empty($array['message']['chat']['username'])) {
				$data['chat']['username'] = $array['message']['chat']['username'];
			}
			
			if (!empty($array['message']['chat']['type'])) {
				$data['chat']['type'] = $array['message']['chat']['type'];
			}

		} else {
			
			$data['type'] = 2;
			
			if (empty($array['ok'])) {
				
				$data['error'] = 1;
				$data['error_code'] = $array['error_code'];
				$data['error_message'] = $array['description'];
				return $data;
			}
			
			if (empty($array['result'])) {
				$data['error'] = 1;
				$data['error_code'] = 10208;
				$data['error_message'] = Yii::t('Error', 'Missing TG Result');
				return $data;	
			}
			
			if (!empty($array['result']['message_id'])) {
				$data['message_id'] = $array['result']['message_id'];
			}
			
			if (!empty($array['result']['date'])) {
				$data['date'] = date('Y-m-d H:i:s', $array['result']['date']);
			}
			
			if (!empty($array['result']['text'])) {
				$data['text'] = $array['result']['text'];
			}
			
			if (empty($array['result']['from'])) {
				$data['error'] = 1;
				$data['error_code'] = 10209;
				$data['error_message'] = Yii::t('Error', 'Missing TG Sender Data');
				return $data;	
			}
			
			if (!empty($array['result']['from']['id'])) {
				$data['from']['id'] = $array['result']['from']['id'];
			}
			
			if (!empty($array['result']['from']['is_bot'])) {
				$data['from']['is_bot'] = $array['result']['from']['is_bot'];
			}
			
			if (!empty($array['result']['from']['first_name'])) {
				$data['from']['first_name'] = $array['result']['from']['first_name'];
			}
			
			if (!empty($array['result']['from']['last_name'])) {
				$data['from']['last_name'] = $array['result']['from']['last_name'];
			}
			
			if (!empty($array['result']['from']['username'])) {
				$data['from']['username'] = $array['result']['from']['username'];
			}
			
			if (!empty($array['result']['from']['language_code'])) {
				$data['from']['language_code'] = $array['result']['from']['language_code'];
			}
			
			if (empty($array['result']['chat'])) {
				$data['error'] = 1;
				$data['error_code'] = 10208;
				$data['error_message'] = Yii::t('Error', 'Missing TG Chat Data');
				return $data;	
			}

			if (!empty($array['result']['chat']['id'])) {
				$data['chat']['id'] = $array['result']['chat']['id'];
			}
			
			if (!empty($array['result']['chat']['first_name'])) {
				$data['chat']['first_name'] = $array['result']['chat']['first_name'];
			}
			
			if (!empty($array['result']['chat']['last_name'])) {
				$data['chat']['last_name'] = $array['result']['chat']['last_name'];
			}
			
			if (!empty($array['result']['chat']['username'])) {
				$data['chat']['username'] = $array['result']['chat']['username'];
			}
			
			if (!empty($array['result']['chat']['type'])) {
				$data['chat']['type'] = $array['result']['chat']['type'];
			}
			
			if (!empty($array['result']['reply_markup'])) {
				if (!empty($array['result']['reply_markup']['inline_keyboard'])) {
					
					if (!empty($array['result']['reply_markup']['inline_keyboard'][0])) {
						if (!empty($array['result']['reply_markup']['inline_keyboard'][0][0])) {
							if (!empty($array['result']['reply_markup']['inline_keyboard'][0][0]['callback_data'])) {
								$data['callback_data'] = $array['result']['reply_markup']['inline_keyboard'][0][0]['callback_data'];
							}
						}						
					}
				}		
			}	
		}
		
		if (preg_match('/^(\/start[\s])([0-9a-z]{1,})$/i', $data['text'])) {
			$data['referral'] = str_replace(['/', 'start', ' '], ['', '', ''], $data['text']);
		}

		return $data;
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
		if (!empty($data['message_id'])) {
			$modelChatbotLog->message_id = $data['message_id'];
		}
		
		$modelChatbotLog->update_id = 0;
		if (!empty($data['update_id'])) {
			$modelChatbotLog->update_id = $data['update_id'];
		}
		
		$modelChatbotLog->api_date = '';
		if (!empty($data['date'])) {
			$modelChatbotLog->api_date = $data['date'];
		}
		
		$modelChatbotLog->text = '';
		if (!empty($data['text'])) {
			$modelChatbotLog->text = $data['text'];
		}
		
		$modelChatbotLog->callback_data = '';
		if (!empty($data['callback_data'])) {
			$modelChatbotLog->callback_data = $data['callback_data'];
		}
		
		$modelChatbotLog->from_id = 0;
		$modelChatbotLog->from_is_bot = 0;
		$modelChatbotLog->from_first_name = '';
		$modelChatbotLog->from_last_name = '';
		$modelChatbotLog->from_username = '';
		$modelChatbotLog->from_language_code = '';
		
		if (!empty($data['from']) && is_array($data['from'])) {
			
			if (!empty($data['from']['id'])) {
				$modelChatbotLog->from_id = $data['from']['id'];
			}
			
			if (!empty($data['from']['is_bot'])) {
				$modelChatbotLog->from_is_bot = $data['from']['is_bot'];
			}
			
			if (!empty($data['from']['first_name'])) {
				$modelChatbotLog->from_first_name = $data['from']['first_name'];
			}
			
			if (!empty($data['from']['last_name'])) {
				$modelChatbotLog->from_last_name = $data['from']['last_name'];
			}
			
			if (!empty($data['from']['username'])) {
				$modelChatbotLog->from_username = $data['from']['username'];
			}
			
			if (!empty($data['from']['language_code'])) {
				$modelChatbotLog->from_language_code = $data['from']['language_code'];
			}
		}
		
		$modelChatbotLog->chat_id = 0;
		$modelChatbotLog->chat_first_name = '';
		$modelChatbotLog->chat_last_name = '';
		$modelChatbotLog->chat_username = '';
		$modelChatbotLog->chat_type = '';

		if (!empty($data['chat']) && is_array($data['chat'])) {
			
			if (!empty($data['chat']['id'])) {

				$modelClients = ApiChatbot::getClient($data['chat']['id']);
				if (empty($modelClients)) {
					$modelClients = ApiChatbot::addClient($data['chat']);
					if (!empty($modelClients) && !empty($data['referral'])) {
						
						ApiChatbot::addReferral($data['referral'], $modelClients->id, $data['bot_id']);
					}
				} 

				if (!empty($modelClients)) {
					$modelChatbotLog->id_client = (int) $modelClients->id;
				}

				$modelChatbotLog->chat_id = $data['chat']['id'];
			}
			
			if (!empty($data['chat']['first_name'])) {
				$modelChatbotLog->chat_first_name = $data['chat']['first_name'];
			}
			
			if (!empty($data['chat']['last_name'])) {
				$modelChatbotLog->chat_last_name = $data['chat']['last_name'];
			}
			
			if (!empty($data['chat']['username'])) {
				$modelChatbotLog->chat_username = $data['chat']['username'];
			}
			
			if (!empty($data['chat']['type'])) {
				$modelChatbotLog->chat_type = $data['chat']['type'];
			}
		}

		$modelChatbotLog->type = 0;
		if (!empty($data['type'])) {
			$modelChatbotLog->type = $data['type'];
		}
		
		$modelChatbotLog->request = '';
		if (!empty($data['request'])) {
			$modelChatbotLog->request = $data['request'];
		}
		
		$modelChatbotLog->bot_id = 0;
		if (!empty($data['bot_id'])) {
			$modelChatbotLog->bot_id = $data['bot_id'];
		}

		$result = $modelChatbotLog->savelog();

		if (!empty($result)) {
			return $result;
		}
		
		return false;
	}
	
	/**
	 * generateToken($id_bot=0)
	 */
	public function generateToken($id_bot=0)
	{
		if (empty($id_bot)) {
			return false;
		}
		
		return md5(md5($id_bot.$this->salt));
	}
	
	/**
	 * generateUserToken($id_log=0)
	 */
	public function generateUserToken($id_log=0)
	{
		if (empty($id_log)) {
			return false;
		}
		
		return md5(md5($id_log.$this->salt).md5($this->salt));
	}
	
	/**
	 * validateUser($id=0, $token='')
	 */
	public static function validateUser($id=0, $token='')
	{
		if (empty($id) || empty($token)) {
			return false;
		}
		
		$hash = TelegramApi::tg()->generateUserToken($id);
		if (!empty($hash) && $hash==$token) {
			return true;
		}
		
		return false;
	}
	
	/**
     * validate initData to ensure that it is from Telegram.
     *
     * @param string $botToken your bot token
     * @param string $initData init data from Telegram (`Telegram.WebApp.initData`)
     *
     * @return bool return true if its from Telegram otherwise false
     */
    public static function isSafe(string $initData): bool
    {
        [$checksum, $sortedInitData] = self::convertInitData($initData);
		
		$bots = ApiChatbot::getBots();
		foreach ($bots as $bot) {
		
			$secretKey = hash_hmac('sha256', $bot->bot_token, 'WebAppData', true);
			$hash  = bin2hex(hash_hmac('sha256', $sortedInitData, $secretKey, true));
			
			if (strcmp($hash, $checksum)===0) {
				return true;
			}
		}
		
		return false;
    }
	
	 /**
     * convert init data to `key=value` and sort it `alphabetically`.
     *
     * @param string $initData init data from Telegram (`Telegram.WebApp.initData`)
     *
     * @return string[] return hash and sorted init data
     */
    private static function convertInitData(string $initData): array
    {
        $initDataArray = explode('&', rawurldecode($initData));
        $needle = 'hash=';
        $hash = '';

        foreach ($initDataArray as &$data) {
            if (substr($data, 0, \strlen($needle)) === $needle) {
                $hash = substr_replace($data, '', 0, \strlen($needle));
                $data = null;
            }
        }
		
        $initDataArray = array_filter($initDataArray);
        sort($initDataArray);

        return [$hash, implode("\n", $initDataArray)];
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BotUsers the static model class
	 */
	public static function tg($className=__CLASS__)
	{
		return new $className;
	}
}