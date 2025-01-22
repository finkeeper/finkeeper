<?php

Class DB
{
	protected $config;
	
	function __construct() {
		$this->config = require dirname(__DIR__) . '/config/database.php';
	}
	
	/**
	 * getConfigExchange()
	 */
	public function getConfigExchange()
	{
		$table = $this->config['prefix'].'exchange_config';
		
		$sql = 'SELECT * FROM `'.$table.'` WHERE `deleted` = 0';
		$res = $this->query($sql, 'fetchAll');
	
		return $res;
	}
	
	/**
	 * getConfigExchange()
	 */
	public function getConfigCurrency()
	{
		$table = $this->config['prefix'].'currency_config';
		
		$sql = 'SELECT * FROM `'.$table.'` WHERE `deleted` = 0';
		$res = $this->query($sql, 'fetchAll');
	
		return $res;
	}
	
	/**
	 * getData($data=[])
	 */
	public function saveData($data=[])
	{
		if (
			empty($data) || 
			!is_array($data) || 
			empty($data['rates']) || 
			!is_array($data['rates'])
		) {
			return false;
		}
		
		$table = $this->config['prefix'].'exchange';

		$sql = 'TRUNCATE TABLE `'.$table.'`';
		$res = $this->query($sql, 'non');

		if (!empty($res) && !empty($res['completed'])) {
			
			foreach ($data['rates'] as $value) {
				
				$set=[
					'rank' => (int) $value['rank'],
					'slug' => '',
					'name' => '',
					'symbol' => '',
					'type' => '',
					'nominal' => (int) $value['nominal'],
					'value' => (float) $value['value'],
					'date_of_change' => date('Y-m-d H:i:s'),
					'currency' => '',
					'image' => '',
					'api' => $value['api'],
					'id_config' => $value['id_config'],
					'currency_type' => $value['currency_type'],
				];
				
				if ($value['api']=='coingecko.com') {
					
					$set['id_api'] = $value['id'];
					
				} else if($value['api']=='cryptorank.io') {
					
					$set['id_api'] = $value['id'];
				}

				if (preg_match('/^[a-z]{1,}$/i', $value['slug'])) {
					$set['slug'] = $value['slug'];
				}

				if (preg_match('/^[a-z 0-9 а-я]{1,}$/iu', $value['name'])) {
					$set['name'] = $value['name'];
				}

				if (preg_match('/^[a-z]{1,}$/i', $value['symbol'])) {
					$set['symbol'] = strtolower($value['symbol']);
				}

				if (preg_match('/^[a-z]{1,}$/i', $value['type'])) {
					$set['type'] = $value['type'];
				}

				if (preg_match('/^[a-z]{1,}$/i', $value['currency'])) {
					$set['currency'] = $value['currency'];
				}
				
				if (!empty($value['image'])) {
					$set['image'] = $value['image'];
				}

				$this->insertParams($table, $set);
			}
		}
	}
	
	/**
	 * getData($data=[])
	 */
	public function changeConfigCoingecko($symbol='', $id_coingecko='')
	{
		if (empty($symbol) || empty($id_coingecko)) {
			return false;
		}
		
		$table = $this->config['prefix'].'exchange_config';
		
		$sql = 'SELECT * FROM `'.$table.'` WHERE symbol=:symbol';
		
		$params = [
			':symbol' => $symbol,
		];
		
		$res = $this->queryParams($sql, $params, $type='fetchAll');
		if (!empty($res) && !empty($res['completed'])) {
			
			foreach ($res['completed'] as $value) {
				if ($value['id_coingecko']==$id_coingecko) {
					return false;
				}
			}
			
			foreach ($res['completed'] as $value) {
				if (empty($value['id_coingecko'])) {
					
					$set = [
						'id_coingecko' => $id_coingecko, 
					];
					
					$where=[
						'id' => $value['id'],
					];
						
					$this->insertParams($table, $set);
					
					return true;
				}
			}
			
			$set = [
				'symbol' => $symbol,
				'id_coingecko' => $id_coingecko, 
			];
				
			$this->updateParams($table, $set, $where);
		}
	}
	
	/**
	 * getTarget()
	 */
	public function getTarget($id_target=0)
	{
		if (empty($id_target)) {
			return false;
		}
		
		$table = $this->config['prefix'].'targets';
		
		$sql = 'SELECT * FROM `'.$table.'` WHERE deleted=:deleted AND id_target=:id_target';
		
		$params = [
			':deleted' => 0,
			':id_target' => $id_target,
		];

		return $this->queryParams($sql, $params, $type='fetch');	
	}
	
	/**
	 * getTargets()
	 */
	public function getTargets()
	{
		$table = $this->config['prefix'].'targets';
		$current_date = time();
		$inspect_date = $current_date+86400;
		
		$sql = 'SELECT * FROM `'.$table.'` WHERE deleted=:deleted AND notification_sent=:notification_sent AND (inspect_date>:inspect_date OR inspect_date=0) LIMIT 5';
		
		$params = [
			':deleted' => 0,
			':notification_sent' => 0,
			':inspect_date' => $inspect_date,
		];

		return $this->queryParams($sql, $params, $type='fetchAll');	
	}
	
	/**
	 * getTargets()
	 */
	public function getNotification($notifications_type=0, $id_type=0, $id_client=0)
	{
		if (empty($notifications_type) || empty($id_client) || empty($id_type)) {
			return false;
		}

		$table = $this->config['prefix'].'notifications';
		
		$sql = 'SELECT * FROM `'.$table.'` WHERE deleted=:deleted AND type=:type AND id_client=:id_client AND id_type=:id_type';

		$params = [
			':deleted' => 0,
			':type' => $notifications_type,
			':id_client' => $id_client,
			':id_type' => $id_type,			
		];

		return $this->queryParams($sql, $params, $type='fetch');	
	}
	
	public function getNotifications($notifications_type=0)
	{
		if (empty($notifications_type)) {
			return false;
		}

		$table = $this->config['prefix'].'notifications';
		
		$sql = 'SELECT * FROM `'.$table.'` WHERE deleted=:deleted AND type=:type AND sent_date<:sent_date AND sent=:sent AND status=:status';

		$params = [
			':deleted' => 0,
			':type' => $notifications_type,
			':sent_date' => date('Y-m-d H:i:s'),
			':sent' => 0,
			':status' => 0,
		];

		return $this->queryParams($sql, $params, $type='fetchAll');	
	}
	
	/**
	 * addNotifications($id_client=0, $id_type=0)
	 */
	public function addNotifications($type=0, $id_type=0, $id_client=0)
	{
		if (empty($id_type) || empty($id_client)) {
			return false;
		}
		
		$table = $this->config['prefix'].'notifications';
		
		$sql = 'SELECT * FROM `'.$table.'` WHERE id_type=:id_type';
		
		$params = [
			':id_type' => $id_type,
		];
		
		$res = $this->queryParams($sql, $params, $type='fetch');
		if (!empty($res) && !empty($res['completed'])) {
			$this->updateTargetNotificationSent($id_type);
			return false;
		}
		
		$title = $this->getMessage('You have reached', $id_client);
		$message = $this->getMessage('You have reached coins', $id_client);
	
		$set = [
			'type' => 1,
			'creation_date' => date('Y-m-d H:i:s'),
			'sent_date' => date('Y-m-d H:i:s', strtotime("+1 hours")),
			'title' => $title,
			'message' => $message,
			'sender' => 'Finkeeper',
			'recipients' => 1,
			'id_client' => $id_client,
			'id_type' => $id_type,
		];
	
		$this->insertParams($table, $set);
	}
	
	/**
	 * updateTargetInspectDate($id_target=0)
	 */
	public function updateTargetInspectDate($id_target=0)
	{
		if (empty($id_target)) {
			return false;
		}
		
		$table = $this->config['prefix'].'targets';

		$current_date = time();
	
		$set = [
			'inspect_date' => $current_date,
		];
		
		$where=[
			'id_target' => $id_target,
		];
				
		$this->updateParams($table, $set, $where);
	}
	
	/**
	 * updateTargetNotificationSent($id_target=0)
	 */
	public function updateTargetNotificationSent($id_target=0)
	{
		if (empty($id_target)) {
			return false;
		}
		
		$table = $this->config['prefix'].'targets';
		
		$current_date = time();
	
		$set = [
			'notification_sent' => 1,
		];
		
		$where=[
			'id_target' => $id_target,
		];
				
		$this->updateParams($table, $set, $where);
	}
	
	/**
	 * updateTargetNotificationSent($id_target=0)
	 */
	public function getMessage($message='', $id_client=0)
	{
		if (empty($message)) {
			return '';
		}
		
		$lang = $this->getLanguage($id_client);

		$table = $this->config['prefix'].'source_message';
		
		$sql = 'SELECT `id` FROM `'.$table.'` WHERE category=:category AND message=:message';
		
		$params = [
			':category' => 'Api',
			':message' => $message,
		];

		$source_message = $this->queryParams($sql, $params, $type='fetch');	
		if (empty($source_message) || empty($source_message['completed'])) {
			return $message;
		}
		
		$table = $this->config['prefix'].'message';
		
		$sql = 'SELECT `translation` FROM `'.$table.'` WHERE id=:id AND language=:language';
		
		$params = [
			':id' => $source_message['completed']['id'],
			':language' => $lang,
		];

		$message = $this->queryParams($sql, $params, $type='fetch');	
		if (empty($message) || empty($message['completed'])) {
			return $message;
		}
	
		return $message['completed']['translation'];
	}
	
	/**
	 * getLanguage($id_client=0)
	 */
	public function getLanguage($id_client=0)
	{
		$lang = 'en-EN';
		
		if (empty($id_client)) {
			return $lang;
		}
		
		$table = $this->config['prefix'].'clients';
		
		$sql = 'SELECT `lang` FROM `'.$table.'` WHERE id=:id_client';
		
		$params = [
			':id_client' => $id_client,
		];

		$clients = $this->queryParams($sql, $params, $type='fetch');	
		if (empty($clients) || empty($clients['completed'])) {
			return $lang;
		}
		
		return $clients['completed']['lang'].'-'.strtoupper($clients['completed']['lang']);
	}
	
	/**
	 * getClient($id_client=0)
	 */
	public function getClient($id_client=0)
	{
		if (empty($id_client)) {
			return false;
		}
		
		$table = $this->config['prefix'].'clients';
		
		$sql = 'SELECT * FROM `'.$table.'` WHERE deleted=:deleted AND id=:id_client';
		
		$params = [
			':id_client' => $id_client,
			'deleted' => 0,
		];

		$clients = $this->queryParams($sql, $params, $type='fetch');	
		if (empty($clients) || empty($clients['completed'])) {
			return false;
		}
		
		return $clients['completed'];
	}
	
	/**
	 * getClient($id_client=0)
	 */
	public function getClientLog($id_client=0)
	{
		if (empty($id_client)) {
			return false;
		}
		
		$table = $this->config['prefix'].'chatbot_log';
		
		$sql = 'SELECT * FROM `'.$table.'` WHERE id_client=:id_client';
		
		$params = [
			':id_client' => $id_client,
		];

		$log = $this->queryParams($sql, $params, $type='fetch');	
		if (empty($log) || empty($log['completed'])) {
			return false;
		}
		
		return $log['completed'];
	}
	
	/**
	 * getClient($id_client=0)
	 */
	public function getChatBot($id_bot=0)
	{
		if (empty($id_bot)) {
			return false;
		}
		
		$table = $this->config['prefix'].'chatbot';
		
		$sql = 'SELECT * FROM `'.$table.'` WHERE deleted=:deleted AND id_bot=:id_bot';
		
		$params = [
			':id_bot' => $id_bot,
			'deleted' => 0,
		];

		$bot = $this->queryParams($sql, $params, $type='fetch');	
		if (empty($bot) || empty($bot['completed'])) {
			return false;
		}
		
		return $bot['completed'];
	}
	
	/**
	 * updateNotificationStatus($id)
	 */
	public function updateNotificationStatus($id)
	{
		if (empty($id)) {
			return false;
		}
		
		$table = $this->config['prefix'].'notifications';
	
		$set = [
			'status' => 1,
		];
		
		$where=[
			'id' => $id,
		];
		
		$res = $this->updateParams($table, $set, $where);
		if (!empty($res) && !empty($res['completed'])) {
			return true;
		}
				
		return false;		
	}
	
	/**
	 * updateNotificationSent($id)
	 */
	public function updateNotificationSent($id)
	{
		if (empty($id)) {
			return false;
		}
		
		$table = $this->config['prefix'].'notifications';
	
		$set = [
			'status' => 2,
			'sent' => 1,
			'sent_to_date' => date('Y-m-d H:i:s'),
		];
		
		$where=[
			'id' => $id,
		];
		
		$res = $this->updateParams($table, $set, $where);
		if (!empty($res) && !empty($res['completed'])) {
			return true;
		}
				
		return false;		
		
	}

	/**
	 * connection()
	 */
	protected function connection()
	{
		static $connection = null;
		try {
			$connection = new PDO('mysql:host=' . $this->config['host'] . ';port=' . $this->config['port'] . ';dbname=' . $this->config['dbname'] . ';charset='.$this->config['charset'], $this->config['user'], $this->config['password']);
			$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			die($e->getMessage());
		}

		$connection->exec('set names '.$this->config['charset']);
		
		return $connection;
	}
	
	/**
	 * queryParams($sql, $params)
	 * LIMIT 1
	 */
	public function queryParams($sql, $params, $type='fetch')
	{
		if (!is_array($params)) {
			return false;
		}

		try {
			
			$connection = $this->connection();
			$query = $connection->prepare($sql);
			$query->execute($params);
			$result = $query->$type();
			$info = $query->errorInfo();	
			
		} catch (PDOException $e) {
			
			$info = $e->getMessage();
			$result = false;	
			
		}

		return [
			'info' => $info,
			'completed' => $result,
		];
	}
	
	/**
	 * query($sql)
	 */
	protected function query($sql, $type='fetch')
	{
		if (empty($sql)) {
			return false;
		}
		
		try {
			
			$connection = $this->connection();
			$query = $connection->query($sql);

			if ($type=='non') {
				
				$result = $query;
				
			} else {
				
				$result = $query->$type();
			}
			
			$info = $query->errorInfo();	
			
		} catch (PDOException $e) {
			
			$info = $e->getMessage();
			$result = false;	
			
		}

		return [
			'info' => $info,
			'completed' => $result,
		];
	}

	/**
	 * updateParams($table, $data=[])
	 */
	protected function updateParams($table, $set=[], $where=[])
	{
		if (empty($set)) {
			return false;
		}
		
		$sql = "UPDATE `".$table."` SET ";
		foreach ($set as $key => $value) {
			$sql .= "`".$key."` = :".$key."s, ";
			$data[$key.'s'] = $value;
		}
		
		$sql = preg_replace('/\,$/i', '', trim($sql));
		
		if (!empty($where)) {
			$sql .= " WHERE ";
			foreach ($where as $key => $value) {
				$sql .= "`".$key."` = :".$key."w, "; 
				$data[$key.'w'] = $value;
			}
		}

		$sql = preg_replace('/\,$/i', '', trim($sql));

		try {
			
			$connection = $this->connection();
			$query = $connection->prepare($sql);
			
			$result = $query->execute($data);
			$info = $query->errorInfo();
			
		} catch (PDOException $e) {
			
			$info = $e->getMessage();
			$result = false;	
			
		}

		return [
			'info' => $info,
			'completed' => $result,
		];
	}
	
	/**
	 * insertParams($table, $data=[])
	 */
	protected function insertParams($table, $set=[])
	{
		if (empty($set)) {
			return false;
		}

		$sql = "INSERT INTO `".$table."` SET ";
		foreach ($set as $key => $value) {
			$sql .= "`".$key."` = :".$key.", "; 
		}

		$sql = preg_replace('/\,$/i', '', trim($sql));

		try {
			
			$connection = $this->connection();
			$query = $connection->prepare($sql);
			
			$result = $query->execute($set);
			$info = $query->errorInfo();
			$id = $connection->lastInsertId();
			
		} catch (PDOException $e) {
			
			$info = $e->getMessage();
			$result = false;	
			$id = 0;
			
		}

		return [
			'id' => $id,
			'info' => $info,
			'completed' => $result,
		];
	}
	
	/**
	 * execute($sql)
	 */
	protected function executeParams($sql='', $params=[], $type='fetchAll')
	{
		if (empty($sql) || empty($params)) {
			return false;
		}

		try {
			
			$connection = $this->connection();
			$query = $connection->prepare($sql);
			
			$query->execute($params);
			$result = $query->$type();
			$info = $query->errorInfo();	
				
		} catch (PDOException $e) {
				
			$info = $e->getMessage();
			$result = false;	
				
		}

		return [
			'info' => $info,
			'completed' => $result,
		];
	}
}
