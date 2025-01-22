<?php

/**
 * Get all exchage
 * https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&x_cg_demo_api_key=token
 */
 
 Class Coingecko
 {
	private $token;
	private $api_url;
	private $config;
	
	function __construct($config) {
		$this->token = $config['tokens']['coingecko'];
		$this->api_url = $config['api_url']['coingecko'].'/markets';
		$this->config = $config;
	}
	
	/**
	 * getAllData($data=[])
	 */
	public function getAllData($data=[])
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
		$count_result = 0;
		foreach ($data['rates'] as $key=>$value) {
			if (empty($value['filled'])) {
				foreach ($result as $k=>$val) {
					
					if ($value['symbol']==$val['symbol']) {

						if (empty($data['rates'][$key]['name']) && !empty($val['name'])) {
							$data['rates'][$key]['name'] = $val['name'];
						}
						
						$data['rates'][$key]['id'] = $val['id'];
						$data['rates'][$key]['rank'] = $val['rank'];
						$data['rates'][$key]['slug'] = $val['slug'];
						$data['rates'][$key]['type'] = $val['type'];
						$data['rates'][$key]['filled'] = $val['filled'];
						$data['rates'][$key]['currency'] = $val['currency'];
						$data['rates'][$key]['value'] = $val['value'];
						$data['rates'][$key]['api'] = $val['api'];
						$data['rates'][$key]['currency_type'] = 1;
						
						if (empty($data['rates'][$key]['image'])) {
							$data['rates'][$key]['image'] = $val['image'];
						}
					
						$count_result++;
						
						unset($result[$k]);
						
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
     * getCurrencies()
     */	 
	public function getCurrencies()
	{
		$url = $this->api_url;
		$url .= '?vs_currency='.$this->config['currency'];
		
		$crypto = [];
		foreach ($this->config['exchange_rates'] as $value) {
			$crypto[] = $value['id_coingecko'];
		}
		
		$url .= '&ids='.implode(',', $crypto);
		$url .= '&x_cg_demo_api_key='.$this->token;

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36");

		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_COOKIEFILE, 'user_cookie_file.txt');
		curl_setopt($curl, CURLOPT_COOKIEJAR, 'user_cookie_file.txt');
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_REFERER, $url);
		$result = curl_exec($curl);
		curl_close($curl);

		if (empty($result) || !is_string($result)) {
			return false;
		}

		$array = @json_decode($result, true);

		if (empty($array) || !is_array($array)) {
			return false;
		}

		return $array;	 
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
			$current_price = (float) number_format($value['current_price'], 2, '.', ' ');
			if (!empty($current_price)) {
	
				$result[$key] = [
					'rank' => !empty($value['market_cap_rank']) ? $value['market_cap_rank'] : 0,
					'slug' => !empty($value['id']) ? $value['id'] : '',
					'name' => !empty($value['name']) ? $value['name'] : '',
					'type' => '',
					'nominal' => 1,
					'symbol' => !empty($symbol) ? strtolower($symbol) : '',
					'filled' => true,
					'currency' => $this->config['currency'],
					'value' => $value['current_price'],
					'image' => !empty($value['image']) ? $value['image'] : '',
					'api' => 'coingecko.com',
					'id' => $value['id'],
				];
			}
		}

		return $result;
    }
 }