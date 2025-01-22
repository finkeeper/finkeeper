<?php

include dirname(dirname(__DIR__)) . '/components/Bybit.php';
include dirname(dirname(__DIR__)) . '/components/Cryptoprice.php';
include dirname(dirname(__DIR__)) . '/components/OKX.php';
include dirname(dirname(__DIR__)) . '/components/Tonapi.php';

/**
 * Notifications
 */
 
 Class Notifications
 {
	public $type;
	public $sent_count;
	public $config;
	
	private $salt = '#dv5f1d5d8&*455d5dv5jgjfkf';
	private $telegram_api_url = 'https://api.telegram.org/bot{token}/sendMessage';
	private $web_app_url = 'https://app.finkeeper.pro/v3/datas/converter';
	
	/**
	 * __construct()
	 */
	function __construct() {
        $this->config = require dirname(dirname(__DIR__)) . '/config/exchange.php';
    }
	
	/**
	 * processedTargets()
	 */
	public function processedTargets()
	{
		$this->type = 1;
		$this->sent_count = 1;
		
		$database = new DB;
		
		$targets = $database->getTargets();
		if (empty($targets) || empty($targets['completed']) || !is_array($targets['completed'])) {
			return false;
		}
	
		foreach ($targets['completed'] as $value) {
			
			$results = $database->getNotification($this->type, $value['id_target'], $value['id_client']);
			
			if (empty($value['notification_count'])) {
				
				if (!empty($results) && !empty($results['completed'])) {
					continue;
				}
				
			} else {
				
				$count_sent = 0;
				if (!empty($results) &&  !empty($results['completed'])) {
					
					$count_sent = count($results['completed']);
					if ($value['notification_count']>=$count_sent) {
						continue;
					}
				}
			}
			
			if ($this->noticeTargetCondition($value['symbol'], $value['price'], $value['id_target'])) {
				$database->addNotifications($this->type, $value['id_target'], $value['id_client']);
			}
		}
	}
	
	/**
	 * noticeTargetCondition($symbol='', $price='')
	 */
	public function noticeTargetCondition($symbol='', $price=0, $id_target=0)
	{
		if (empty($id_target)) {
			return false;
		}

		$database = new DB;
		$res = $database->updateTargetInspectDate($id_target);

		if (empty($symbol) || empty($price)) {
			return false;
		}
		
		$currency = '';
		if (!empty($this->config['currency'])) {
			$currency = $this->config['currency'];
		}

		$data = self::getPrice($symbol, $currency);
		if (!empty($data['error']) || empty($data['data'])) {
			$database->updateTargetNotificationSent($id_target);
			return false;
		}
		
		$data['data'] = (float) $data['data'];
		$price = (float) $price;
		
		if ($price<=$data['data']) {
			return true;
		}

		return false;
	}
	
	/**
	 * getPrice($sumbol='', $currency='usd')
	 */
	public static function getPrice($sumbol='', $currency='usd')
	{
		$price = [
			'error' => 0,
			'data' => 0,
		];
		
		if (empty($sumbol)) {
			return $price;
		}

		$bybit = new common\components\Bybit;
		$price = $bybit::getPrice($sumbol, $currency);
		if (!empty($price['data'])) {
			return $price;	
		}
		
		$okx = new common\components\OKX;
		$price = $okx::getPrice($sumbol, $currency);
		if (!empty($price['data'])) {
			return $price;	
		}
		
		$cryptoprice = new common\components\Cryptoprice;
		$price = $cryptoprice::getPrice($sumbol);
		if (!empty($price['data'])) {
			return $price;	
		}

		$tonapi = new common\components\Tonapi;
		$price = $tonapi::getPrice('tston');
		if (!empty($price['data'])) {
			return $price;	
		}

		return $price;			
	}
	
	/**
	 * processedNotifications()
	 */
	public function processedNotifications()
	{
		$this->type = 1;
		$this->sent_count = 1;
		
		$database = new DB;
		
		$notifications = $database->getNotifications($this->type);
		if (empty($notifications) || empty($notifications['completed']) || !is_array($notifications['completed'])) {
			return false;
		}
	
		foreach ($notifications['completed'] as $value) {			
			if ($database->updateNotificationStatus($value['id'])) {
			
				$title = $value['title'];
			
				$target = $database->getTarget($value['id_type']);
				if (!empty($target) && !empty($target['completed'])) {
					$symbol = strtoupper($target['completed']['symbol']);
					$message = str_replace('{coins}', $symbol, $value['message']);
					
				} else {
					
					$message = str_replace('{coins}', '', $value['message']);
				}
				
				$this->sentNotifications($message, $value['id_client'], $value['id']);
			}
		}
	}
	
	/**
	 * sentNotifications($message='', $id_client=0)
	 */
	public function sentNotifications($message='', $id_client=0, $id=0) 
	{
		if (empty($id)) {
			return false;
		}
		
		$database = new DB;
		
		if (empty($message) || empty($id_client) ) {
			$database->updateNotificationSent($id);
			return false;
		}
		
		$client = $database->getClient($id_client);
		if (empty($client)) {
			$database->updateNotificationSent($id);
			return false;
		}
		
		$log = $database->getClientLog($id_client);
		if (empty($log)) {
			$database->updateNotificationSent($id);
			return false;
		}
		
		$bot = $database->getChatBot($log['bot_id']);
		if (empty($log)) {
			$database->updateNotificationSent($id);
			return false;
		}

		$hash = $this->generateUserToken($log['id']);
		if (empty($hash)) {
			$database->updateNotificationSent($id);
			return false;
		}
		
		$name = $database->getMessage('Launch application', $id_client);

		$btn['inline_keyboard'][][] = [
			'text'=> $name, 
			'web_app' => [
				'url' =>$this->web_app_url.'?id='.$log['id'].'&sc='.$hash,
			],
		];
		
		$send = [];
		$send['chat_id'] = $client['tg_chat_id'];
		$send['parse_mode'] = 'HTML';
		$send['disable_web_page_preview'] = true;
		$send['text'] = $message;
		$send['reply_markup'] = $btn;
		
		$telegram_api_url = str_replace('{token}', $bot['bot_token'], $this->telegram_api_url);

		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_URL => $telegram_api_url,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => json_encode($send),
			CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
		]);
		
		$result = curl_exec($ch);		
		curl_close($ch);
		$res = json_decode($result, true);
		
		if (empty($res['ok'])) {
			error_log($result."\r\n".PHP_EOL, 3, dirname(__FILE__).'/notifications.log');
			$database->updateNotificationSent($id);
			return false;
		}
	
		$database->updateNotificationSent($id);
		return true;		
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
 }