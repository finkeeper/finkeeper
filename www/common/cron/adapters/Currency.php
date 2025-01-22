<?php
/**
 * Get exchage  https://www.cbr.ru/scripts/XML_daily.asp?date_req=17/10/2024
 */
 
 Class Currency
 {
	private $api_url;
	public $config;
	 
	function __construct($config) {
		$this->config = $config;
		$this->api_url = $config['api_url']['cbr'].'?date_req='.date('d/m/Y');
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
		
		$count_config = count($this->config['currency_rates']);
		$count_result = 0;
		foreach ($data['rates'] as $key=>$value) {
			if (empty($value['filled'])) {
				foreach ($result as $k=>$val) {
					if ($value['symbol']==$val['symbol']) {
						
						if (empty($data['rates'][$key]['name']) && !empty($val['name'])) {
							$data['rates'][$key]['name'] = $val['name'];
						}
						
						$data['rates'][$key]['rank'] = $val['rank'];
						$data['rates'][$key]['filled'] = $val['filled'];
						$data['rates'][$key]['currency'] = $val['currency'];
						$data['rates'][$key]['value'] = $val['value'];
						$data['rates'][$key]['api'] = $val['api'];
						$data['rates'][$key]['currency_type'] = 2;
						
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
			$data['crrency_status'] = true;
		}
		
		return $data;
	}
	
	/**
     * getCurrencies()
     */	 
	public function getCurrencies()
	{
		$data = [];
		
		$response  = file_get_contents($this->api_url, "r");
		if (empty($response)) {
			return false;
		}
		
		$xml = simplexml_load_string($response);

		if (empty($xml->Valute)) {
			return false;
		}
		
		foreach ($xml->Valute as $val) {
			$data[] = [
				'price' => str_replace(',', '.', $val->Value),
				'date_of_change' => date('Y-m-d H:i:s'),
				'name' => json_decode(json_encode($val->Name), true)[0],
				'symbol' => json_decode(json_encode($val->CharCode), true)[0],
				'rank' => json_decode(json_encode($val->NumCode), true)[0],
				'nominal' => json_decode(json_encode($val->Nominal), true)[0],
			];			
		}
		
		if (empty($data) || !is_array($data)) {
			return false;
		}

		return $data;
	}
	
	/**
     * dataProcessig($json='')
     */	 
	private function dataProcessig($array=[])
    {
		if (empty($array) || !is_array($array)) {
			return false;
		}
		
		$coff = 0;
		foreach ($array as $key => $value) {
			
			if (empty($value['symbol'])) {
				continue;
			}
			
			$symbol = strtolower($value['symbol']);
			
			if ($symbol==$this->config['currency']) {
				$coff = $value['price']/$value['nominal'];
				break;
			}
		}
		
		if (empty($coff)) {
			return false;
		}

		$result = [];
		foreach ($array as $key => $value) {

			if (empty($value['symbol'])) {
				continue;
			}
			
			$symbol = strtolower($value['symbol']);
			
			if ($symbol==$this->config['currency']) {
				$current_price = 1;
			} else {	
				$current_price = round(($value['price']/$value['nominal'])/$coff, 10);
			}

			if (!empty($current_price)) {
	
				$result[$key] = [
					'rank' => !empty($value['rank']) ? $value['rank'] : 0,
					'name' => !empty($value['name']) ? $value['name'] : '',
					'nominal' => 1,
					'symbol' => !empty($symbol) ? strtolower($symbol) : '',
					'currency' => $this->config['currency'],
					'value' => $current_price,
					'api' => 'cbr.ru',
					'filled' => true,
				];
			}
		}

		$result[] = [
			'rank' => 643,
			'name' => 'Российский рубль',
			'nominal' => 1,
			'symbol' => 'rub',
			'currency' => $this->config['currency'],
			'value' => 1/$coff,
			'api' => 'cbr.ru',
			'filled' => true,
		];

		return $result;
    }
 }