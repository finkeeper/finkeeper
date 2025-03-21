<?php

namespace frontend\modules\app\components;

use Yii;
use common\models\Exchange;

class LaunchpoolsApi {
	
	public $api_key = '';
	public $api_url_bybit = '';
	public $api_url_bitget = '';
	
	/**
	 * construct
	 */
	function __construct() {
		
		$conf = Exchange::getConfig(3);

		if (empty($conf) || !is_array($conf) || empty($conf['launchpools']) || !is_array($conf['launchpools'])) {
			return false;
		}
		
		if (empty($conf['launchpools']['apikey'])) {
			return false;
		}
		
		$this->api_key = $conf['launchpools']['apikey'];
		
		if (!empty($conf['launchpools']['apiurl_bybit'])) {
			$this->api_url_bybit = $conf['launchpools']['apiurl_bybit'];
		}

		if (!empty($conf['launchpools']['apiurl_bitget'])) {
			$this->api_url_bitget = $conf['launchpools']['apiurl_bitget'];
		}
	}
	
	/**
	 * getPools($message='')
	 */
    public function getPools() 
	{
		$exchange[] = $this->getPoolsBybit();
		$exchange[] = $this->getPoolsBitget();
		
		return $exchange;
	}
	
	/**
	 * getPoolsBybit($message='')
	 */
    public function getPoolsBybit() 
	{
		$header = [
			'Content-Type: application/json',
			'Authorization: Basic '.$this->api_key,
		];
		
		if (empty($this->api_url_bybit)) {
			return false;
		}

		$api_url = $this->api_url_bybit;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		//curl_setopt($curl, CURLOPT_POST, true);
		//curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($send));
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);  
		//curl_setopt($curl, CURLOPT_PORT, 8443);
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
		
		$data = [
			'time' => 0,
			'exchange' => 'Bybit',
			'exchange_link' => 'https://www.bybit.com/ru-RU/trade/spot/launchpool?ref=GNMZ3G',
			'exchange_icon' => 'https://finkeeper.pro/images/logos/bybit2.png',
			'pools' => [],
		];
		
		if (!empty($source['timestamp'])) {
			
			$time = strtotime($source['timestamp']) + 10800;
			$current_time = time();
			$new_time = $current_time - $time;
			$new_time = round($new_time/60);

			$data['time'] = $new_time;
		}
		
		if (!empty($source['pools']) && is_array($source['pools'])) {
			foreach ($source['pools'] as $key1=>$pools) {
				
				$status = 0;
				if ($current_time>=($pools['stakeBeginTime']/1000)) {
					$status = 1;
				}

				$data['pools'][$key1] = [
					'stake_start' => date('d.m.y H:i', $pools['stakeBeginTime']/1000),
					'stake_end' => date('d.m.y H:i', $pools['stakeEndTime']/1000),
					'stake_status' => $status,
					'coin' => $pools['returnCoin'],
					'coini_icon' => $pools['returnCoinIcon'],
					'list' => [],
					'btm' => $pools['stakeBeginTime'],
				];
				
				if (!empty($pools['stakePoolList']) && is_array($pools['stakePoolList'])) {
					foreach ($pools['stakePoolList'] as $list) {
						$data['pools'][$key1]['list'][] = [
							'coin' => $list['stakeCoin'],
							'coini_icon' => $list['stakeCoinIcon'],
							'apr' => Exchange::formatValue($list['apr'], 1),
							'min' => (int) $list['minStakeAmount'],
							'max' => (int) $list['maxStakeAmount'],
						];
						
					}
				}				
			}
		}
		
		return $data;
	}
	
	/**
	 * getPoolsBybit($message='')
	 */
    public function getPoolsBitget() 
	{
		$header = [
			'Content-Type: application/json',
			'Authorization: Basic '.$this->api_key,
		];
		
		if (empty($this->api_url_bitget)) {
			return false;
		}

		$api_url = $this->api_url_bitget;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		//curl_setopt($curl, CURLOPT_POST, true);
		//curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($send));
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);  
		//curl_setopt($curl, CURLOPT_PORT, 8443);
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

		$data = [
			'time' => 0,
			'exchange' => 'Bitget',
			'exchange_link' => 'https://www.bitget.com/ru/events/poolx',
			'exchange_icon' => 'https://finkeeper.pro/images/logos/bitget.png',
			'pools' => [],
		];

		if (!empty($source['timestamp'])) {
			
			$time = strtotime($source['timestamp']) + 10800;
			$current_time = time();
			$new_time = $current_time - $time;
			$new_time = round($new_time/60);

			$data['time'] = $new_time;
		}

		if (!empty($source['pools']) && is_array($source['pools'])) {
			foreach ($source['pools'] as $key1=>$pools) {
				
				$start_time = 0;
				if (!empty($pools['startTime'])) {
					$start_time = $pools['startTime']/1000;
				}
				
				$status = 0;
				if ($current_time>=$start_time) {
					$status = 1;
				}

				$data['pools'][$key1] = [
					'stake_start' => date('d.m.y H:i', $start_time),
					'stake_end' => date('d.m.y H:i', $pools['endTime']/1000),
					'stake_status' => $status,
					'coin' => $pools['productCoinName'],
					'coini_icon' => $pools['productCoinImgUrl'],
					'list' => [],
				];
				
				if (!empty($pools['productSubList']) && is_array($pools['productSubList'])) {
					foreach ($pools['productSubList'] as $list) {
						
						$min = 0;
						if (!empty($list['userMinAmount'])) {
							$min = $list['userMinAmount'];
						}
						
						
						$data['pools'][$key1]['list'][] = [
							'coin' => $list['productSubCoinName'],
							'coini_icon' => $list['productSubCoinImgUrl'],
							'apr' => Exchange::formatValue($list['apr'], 1),
							'min' => $min,
							'max' => (int) $list['userMaxAmount'],
						];
						
					}
				}				
			}
		}
		
		return $data;
	}

	/**
	 * pstatic($className=__CLASS__)
	 */ 
	public static function pstatic($className=__CLASS__)
	{
		return new $className;
	}
}