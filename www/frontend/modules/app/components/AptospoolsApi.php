<?php

namespace frontend\modules\app\components;

use Yii;
use common\models\Exchange;

class AptospoolsApi {
	
	public $api_key = '';
	public $api_url = '';
	public $token = '';
	
	/**
	 * construct
	 */
	function __construct() {
		
		$conf = Exchange::getConfig(3);

		if (empty($conf) || !is_array($conf) || empty($conf['launchpools']) || !is_array($conf['launchpools'])) {
			return false;
		}
		
		if (empty($conf['aptospools']['apikey'])) {
			return false;
		}
		
		$this->api_key = $conf['aptospools']['apikey'];
		
		if (!empty($conf['aptospools']['apiurl'])) {
			$this->api_url = $conf['aptospools']['apiurl'];
		}
	}
	
	/**
	 * getPools($message='')
	 */
    public function getPools() 
	{
		if (empty($this->token)) {
			return [
				'error'=>1,
				'message' => Yii::t('Error', 'Missing token'),
			];
		}
		
		if (empty($this->api_key)) {
			return [
				'error'=>1,
				'message' => Yii::t('Error', 'Missing api key'),
			];
		}
		
		if (empty($this->api_url)) {
			return [
				'error'=>1,
				'message' => Yii::t('Error', 'Missing api url'),
			];
		}
		
		$header = [
			'Content-Type: application/json',
			'Authorization: Basic '.$this->api_key,
		];
		
		$api_url = $this->api_url;

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
				'error' => 0,
				'data' => [],
			];
		}
		
		if (!is_string($response)) {
			return [
				'error' => 0,
				'data' => [],
			];
		}
		
		$source = @json_decode($response, true);
		if (empty($source) || empty($source['data'])) {
			return [
				'error' => 0,
				'data' => [],
			];
		}
		
		$data = $this->parserData($source['data']);
		return [
			'error' => 0,
			'data' => $data,
		];
	}
	
	/**
	 * parserData()
	 */
	public function parserData($array=[])
	{
		$data = [];
		if (empty($array) || !is_array($array)) {
			return $data;
		}
		
		foreach ($array as $pool) {			
			if (preg_match('/'.$this->token.'/i', $pool['asset'])) {
				
				$protocol_icon = '';
				$protocol_link = '';
				
				if ($pool['protocol']=='Joule') {
					
					$protocol_icon = '/images/logos/joule2.png';
					$protocol_link = 'https://app.joule.finance/rewards?tabId=referral&referralAddress=0x56ff2fc971deecd286314fe99b8ffd6a5e72e62eacdc46ae9b234c5282985f97';
					
				} else if ($pool['protocol']=='Echelon') {
					
					$protocol_icon = '/images/logos/echelon2.png';
					$protocol_link = 'https://app.echelon.market/dashboard?network=aptos_mainnet';
					
				} else if ($pool['protocol']=='Aries') {
					
					$protocol_icon = '/images/logos/aries3.png';
					$protocol_link = 'https://app.ariesmarkets.xyz/lending';
				}

				$data[] = [
					'asset' => $pool['asset'],
					'provider' => $pool['provider'],
					'totalAPY' => $pool['totalAPY'],
					'protocol' => $pool['protocol'],
					'protocol_icon' => $protocol_icon,
					'protocol_link' => $protocol_link,
				];
			}
		}
		
		usort($data, [$this, 'cmp']);
		
		return $data;
	}
	
	/**
	 * cmp($a=[], $b=[])
	 */
	public function cmp($a=[], $b=[])
	{	
		return ($a['totalAPY'] < $b['totalAPY']);
	}

	/**
	 * pstatic($className=__CLASS__)
	 */ 
	public static function pstatic($className=__CLASS__)
	{
		return new $className;
	}
}