<?php

/**
 * Get exchage  https://cryptorank.io/ru/public-api
 * https://cryptorank.io/ru/public-api/keys
 * https://api.cryptorank.io/docs
 * https://api.cryptorank.io/v1/currencies?api_key=$token
 *
 * Missing MANTA
 */
 
 Class Cryptorank
 {
	private $token;
	private $api_url;
	public $config;
	
	function __construct($config) {
		$this->token = $config['tokens']['cryptorank'];
		$this->api_url = $config['api_url']['cryptorank'];
		$this->config = $config;
	}
	
	/**
	 * getTopData($data=[])
	 */
	public function getTopData($data=[])
	{
		if (empty($data) || !is_array($data)) {
			return $data;
		}
		
		$array = $this->getCurrencies();
		if (empty($array) || !is_array($array)) {
			return $data;
		}
			
		$result = $this->dataProcessig($array);	
		if (empty($result) || !is_array($result)) {
			return $data;
		}
	
		$count_config = count($this->config['exchange_rates']);
		$count_result = 1;
		foreach ($data['rates'] as $key=>$value) {
			if (empty($value['filled'])) {
				foreach ($result as $val) {
					
					if ($value['symbol']==$val['symbol']) {
		
						if (empty($data['rates'][$key]['name']) && !empty($val['name'])) {
							$data['rates'][$key]['name'] = $val['name'];
						}
						
						$data['rates'][$key]['rank'] = $val['rank'];
						$data['rates'][$key]['slug'] = $val['slug'];
						$data['rates'][$key]['type'] = $val['type'];
						$data['rates'][$key]['filled'] = $val['filled'];
						$data['rates'][$key]['currency'] = $val['currency'];
						$data['rates'][$key]['value'] = $val['value'];
						$data['rates'][$key]['api'] = $val['api'];
						$data['rates'][$key]['currency_type'] = 1;

						$count_result++;
						break;
					}
				}	
			} else {
				$count_result++;
			}				
		}

		if ($count_config==$count_result) {
			$data['exchange_status'] = true;
		}

		return $data;	
	}
	
	/**
	 * getData($data=[])
	 */
	public function getData($data=[])
	{
		if (empty($data) || !is_array($data)) {
			return $data;
		}

		if (
			empty($this->config) || 
			!is_array($this->config) || 
			empty($this->config['exchange_rates']) || 
			!is_array($this->config['exchange_rates'])
		) {
			return $data;
		}

		$count_config = count($this->config['exchange_rates']);
		$count_result = 1;
		foreach ($data['exchange_rates'] as $key=>$value) {
			if (empty($value['filled'])) {
				foreach ($this->config['exchange_rates'] as $config) {
					$array[0] = $this->getCurrencies($config['id_cryptorank']);
					$result = $this->dataProcessig($array);
					if (!empty($data['rates'][$result[0]['symbol']])) {
						$data['rates'][$result[0]['symbol']] = $result[0];
						$count_result++;
					}
				}	
			} else {
				$count_result++;
			}				
		}
		
		if ($count_config==$count_result) {
			$data['status'] = true;
		}

		return $data;
	}
	 
	/**
     * getCurrencies()
     */	 
	private function getCurrencies($id=0)
	{
		if (empty($id)) {
			$url = $this->api_url . 'currencies?api_key=' . $this->token;
		} else {
			$url = $this->api_url . 'currencies/'.$id.'?api_key=' . $this->token;
		}
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($curl);
		curl_close($curl);
		
		if (empty($result) || !is_string($result)) {
			return false;
		}

		$array = @json_decode($result, true);
		if (empty($array) || !is_array($array) || empty($array['data']) || !is_array($array['data'])) {
			return false;
		}

		return $array['data'];	 
	}		
	 
	/**
     * dataProcessig($json='')
     */	 
	private function dataProcessig($array=[])
    {
		if (empty($array) || !is_array($array)) {
			return false;
		}

		$result = [];
		foreach ($array as $key => $value) {

			if (empty($value['symbol'])) {
				continue;
			}
			
			$symbol = strtolower($value['symbol']);

			$result[$key] = [
				'rank' => !empty($value['rank']) ? $value['rank'] : 0,
				'slug' => !empty($value['slug']) ? $value['slug'] : '',
				'name' => !empty($value['name']) ? $value['name'] : '',
				'type' => !empty($value['type']) ? $value['type'] : '',
				'nominal' => 1,
				'symbol' => !empty($symbol) ? strtolower($symbol) : '',
				'image' => '',
				'api' => 'cryptorank.io',
				'id' => $value['id'],
			];

			if (!empty($value['values']) && is_array($value['values'])) {
				foreach ($value['values'] as $currency => $val) {
					if ($currency==strtoupper($this->config['currency'])) {
						
						$price = (float) number_format($val['price'], 2, '.', ' ');
						if (!empty($price)) {
							$result[$key]['currency'] = $this->config['currency'];
							$result[$key]['value'] = $val['price'];
							$result[$key]['filled'] = true;
						}
						break;
					}
				}			
			}
		}
		
		return $result;
    }
 }