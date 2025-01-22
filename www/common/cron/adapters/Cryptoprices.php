<?php

/**
 * Get exchage  https://cryptoprices.cc/BTC
 */
 
 Class Cryptoprices
 {
	private $api_url;
	public $config;
	
	function __construct($config) {
		$this->api_url = $config['api_url']['cryptoprices'];
		$this->config = $config;
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
		$count_result = 0;
		foreach ($data['rates'] as $key=>$value) {
			if (empty($value['filled'])) {
				foreach ($this->config['exchange_rates'] as $config) {
					$symbol = strtolower($config['symbol']);
					if ($value['symbol']==$symbol) {
						$array = $this->getCurrencies($config['symbol']);
						$result = $this->dataProcessig($array);

						$data['rates'][$key]['value'] = $result['value'];
						$data['rates'][$key]['api'] = $result['api'];
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
     * getCurrencies()
     */	 
	private function getCurrencies($symbol='')
	{
		if (empty($symbol)) {
			return false;
		}
		
		$url = $this->api_url . $symbol;
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		$result = [
			'symbol' => $symbol,
			'value' => curl_exec($curl),
		];
		
		curl_close($curl);

		if (empty($result['value'])) {
			return false;
		}

		return $result;	
	}		
	 
	/**
     * dataProcessig($json='')
     */	 
	private function dataProcessig($array='')
    {
		$result = [];
		
		$symbol = strtolower($array['symbol']);

		$value = (float) number_format($array['value'], 2, '.', ' ');
		if (!empty($value)) {
			$result = [
				'rank' => 0,
				'slug' => '',
				'name' => '',
				'type' => '',
				'nominal' => 1,
				'symbol' => !empty($symbol) ? strtolower($symbol) : '',
				'currency' => 'usd',
				'value' => $array['value'],
				'image' => '',
				'api' => 'cryptoprices.cc',
			];
		}
		
		return $result;
    }			 
 }