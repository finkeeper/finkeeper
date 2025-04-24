<?php

namespace frontend\modules\app\components;

use Yii;
use common\models\Exchange;
use common\components\BaseFunctions;
use frontend\modules\app\models\ApiChatbot;

class ETHApi {
	
	public $address=''; 
	public $api_key = '';
	public $api_url = '';
	
	/**
	 * construct
	 */
	function __construct() {
		
		$conf = Exchange::getConfig(3);
		if (empty($conf) || !is_array($conf) || empty($conf['eth']) || !is_array($conf['eth'])) {
			return false;
		}
		
		if (
			empty($conf['eth']['apikey']) || 
			empty($conf['eth']['apiurl'])
		) {
			return false;
		}

		$this->api_key = $conf['eth']['apikey'];
		$this->api_url = $conf['eth']['apiurl'];
	}

	/**
	 * getWalletBalance($params='')
	 */
    public function getWalletBalance() 
	{
		$currency = Exchange::getDefaultCurrency();
		$grafema = Exchange::getGrafemCurrency($currency);
		$data = [];

		if (empty($this->address)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not Address'),
				'data' => $data,
			];
		}
		
		$sort = 'value';
		$filter=[
			'positions' => 'only_simple',
			'trash' => 'only_non_trash',
		];
		
		$result = $this->getEthBalance($currency, $sort, $filter);
		if (!empty($result['error'])) {
			return [
				'error' => 1,
				'message' => $result['message'],
				'data' => $data,
			];
		}
		
		foreach ($result['data'] as $key=>$val) {
			
			if (
				empty($val['attributes']) || 
				!is_array($val['attributes']) ||
				empty($val['attributes']['quantity']) || 
				!is_array($val['attributes']['quantity']) ||
				empty($val['attributes']['quantity']['float']) || 
				empty($val['attributes']['fungible_info']) || 
				!is_array($val['attributes']['fungible_info']) ||
				empty($val['attributes']['fungible_info']['flags']) || 
				!is_array($val['attributes']['fungible_info']['flags']) ||
				empty($val['attributes']['fungible_info']['flags']['verified']) ||
				empty($val['attributes']['fungible_info']['symbol']) ||
				empty($val['relationships']) ||
				!is_array($val['relationships']) ||
				empty($val['relationships']['chain']) ||
				!is_array($val['relationships']['chain']) ||
				empty($val['relationships']['chain']['data']) ||
				!is_array($val['relationships']['chain']['data'])
			) {
				continue;
			}
			
			$value = 0;
			$price = [];
			$symbol = $val['attributes']['fungible_info']['symbol'];
			$symbolid = strtolower($symbol);
			$coinid = strtolower($symbolid).$key;
			$balance = number_format($val['attributes']['quantity']['float'], 12, '.', '');
			
			if (!empty($balance)) {
				if (is_float($balance)) {
					$balance = number_format($balance, 12, '.', '');
				} else if (is_int($balance)) {
					$balance = number_format($balance, 12, '.', '');
				} else {
					$balance = $balance*1;
					$balance = number_format($balance, 12, '.', '');
				}
			}

			if (empty($val['attributes']['price'])) {
				$price = ApiChatbot::getPrice($symbolid, $currency, 1);
				if (!empty($price['error']) || empty($price['data'])) {
					$price['data'] = 0;
				}
			} else {
				$price['data'] = $val['attributes']['price'];
			}

			if (empty($val['attributes']['value'])) {
				$value = $price['data']*$balance;	
			} else {	
				$value = $val['attributes']['value'];
			}
			
			if (!empty($value)) {
				if (is_float($value)) {
					$value = number_format($value, 12, '.', '');
				} else if (is_int($value)) {
					$value = number_format($value, 12, '.', '');
				} else {
					$value = $value*1;
					$value = number_format($value, 12, '.', '');
				}
			}

			$img = '/images/cryptologo/default_coin.webp';
			if (!empty($val['attributes']['fungible_info']['icon']) && !empty($val['attributes']['fungible_info']['icon']['url'])) {
				$img = $val['attributes']['fungible_info']['icon']['url'];
			} else {
				$img_name = strtolower($val['attributes']['fungible_info']['symbol']).'.webp';
				$path = getcwd().'/images/cryptologo/'.$img_name;
				if (file_exists($path)) {
					$img = '/images/cryptologo/'.$img_name;
				}
			}

			$name = strtoupper($val['attributes']['fungible_info']['symbol']);
			if (!empty($val['attributes']['fungible_info']['name'])) {
				$name = $val['attributes']['fungible_info']['name'];
			}
			
			if (!empty($val['id'])) {
				//if ($this->parseCoin($value['id'])) {
					//$symbol = $this->parseCoin($value['id']);
				//}
			}

			$network = '';
			$network_icon = '';
			if (!empty($val['relationships']['chain']['data']['id'])) {
				$network = $val['relationships']['chain']['data']['id'];
				$network_icon = $this->getIconParse($val['relationships']['chain']['data']['id']);
			}
			
			$currency_value = Exchange::formatValue($value);
			$class = 'middle_value';
			if ($currency_value<1) {
				$class = 'small_value';
			}
			
			$address = $this->getAddressParse($this->address);
			
			$position_type = '';
			if (!empty($val['attributes']['position_type'])) {
				$position_type = '<br>'.Yii::t('Api', 'Position type').': '.$val['attributes']['position_type'];
			}
			
			$data[] = [
				'balance' => $balance,
				'negative' => 0,
				'name' => $name,
				'currency' => $currency,
				'sort' => $value,
				'currency_value' => $currency_value,
				'img' => $img,
				'symbol' => $symbol,
				'symbolid' => $symbolid,
				'coinid' => $coinid,
				'grafema' => $grafema,
				'class' => $class,
				'price' => $price['data'],
				'network' => Yii::t('Frontend', 'Wallet').' ETH | '.Yii::t('Api', 'Network').': '.$network.$position_type,
				'network_icon' => $network_icon,
				'network_link' => '',
				'apr' => '',
				'asset' => $address,
				'protocol' => '',
			];		
		}
		
		// Position Loan
		$sort = 'value';
		$filter=[
			'positions' => 'only_complex',
			'trash' => 'only_non_trash',
		];
		
		$result = $this->getEthBalance($currency, $sort, $filter);
		if (!empty($result['error'])) {
			return [
				'error' => 1,
				'message' => $result['message'],
				'data' => $data,
			];
		}
		
		foreach ($result['data'] as $key=>$val) {

			if (
				empty($val['attributes']) || 
				!is_array($val['attributes']) ||
				empty($val['attributes']['quantity']) || 
				!is_array($val['attributes']['quantity']) ||
				empty($val['attributes']['quantity']['float']) || 
				empty($val['attributes']['fungible_info']) || 
				!is_array($val['attributes']['fungible_info']) ||
				empty($val['attributes']['fungible_info']['flags']) || 
				!is_array($val['attributes']['fungible_info']['flags']) ||
				empty($val['attributes']['fungible_info']['flags']['verified']) ||
				empty($val['attributes']['fungible_info']['symbol']) ||
				empty($val['relationships']) ||
				!is_array($val['relationships']) ||
				empty($val['relationships']['chain']) ||
				!is_array($val['relationships']['chain']) ||
				empty($val['relationships']['chain']['data']) ||
				!is_array($val['relationships']['chain']['data'])
			) {
				continue;
			}

			$negative = 0;
			if ($val['attributes']['position_type']=='loan') {
				$negative = 1;
			}

			$value = 0;
			$price = [];
			$symbol = $val['attributes']['fungible_info']['symbol'];
			$symbolid = strtolower($symbol);
			$coinid = strtolower($symbolid).$key;
			$balance = number_format($val['attributes']['quantity']['float'], 12, '.', '');
			
			if (!empty($balance)) {
				if (is_float($balance)) {
					$balance = number_format($balance, 12, '.', '');
				} else if (is_int($balance)) {
					$balance = number_format($balance, 12, '.', '');
				} else {
					$balance = $balance*1;
					$balance = number_format($balance, 12, '.', '');
				}
			}

			if (empty($val['attributes']['price'])) {
				$price = ApiChatbot::getPrice($symbolid, $currency, 1);
				if (!empty($price['error']) || empty($price['data'])) {
					$price['data'] = 0;
				}
			} else {
				$price['data'] = $val['attributes']['price'];
			}

			if (empty($val['attributes']['value'])) {
				$value = $price['data']*$balance;	
			} else {	
				$value = $val['attributes']['value'];
			}
			
			if (!empty($value)) {
				if (is_float($value)) {
					$value = number_format($value, 12, '.', '');
				} else if (is_int($value)) {
					$value = number_format($value, 12, '.', '');
				} else {
					$value = $value*1;
					$value = number_format($value, 12, '.', '');
				}
			}

			$img = '/images/cryptologo/default_coin.webp';
			if (!empty($val['attributes']['fungible_info']['icon']) && !empty($val['attributes']['fungible_info']['icon']['url'])) {
				$img = $val['attributes']['fungible_info']['icon']['url'];
			} else {
				$img_name = strtolower($val['attributes']['fungible_info']['symbol']).'.webp';
				$path = getcwd().'/images/cryptologo/'.$img_name;
				if (file_exists($path)) {
					$img = '/images/cryptologo/'.$img_name;
				}
			}

			$name = strtoupper($val['attributes']['fungible_info']['symbol']);
			if (!empty($val['attributes']['fungible_info']['name'])) {
				$name = $val['attributes']['fungible_info']['name'];
			}
			
			if (!empty($val['id'])) {
				//if ($this->parseCoin($value['id'])) {
					//$symbol = $this->parseCoin($value['id']);
				//}
			}

			$network = '';
			$network_icon = '';
			if (!empty($val['relationships']['chain']['data']['id'])) {
				$network = $val['relationships']['chain']['data']['id'];
				$network_icon = $this->getIconParse($val['relationships']['chain']['data']['id']);
			}
			
			$currency_value = Exchange::formatValue($value);
			$class = 'middle_value';
			if ($currency_value<1) {
				$class = 'small_value';
			}
			
			$address = $this->getAddressParse($this->address);
			
			$position_type = '';
			if (!empty($val['attributes']['position_type'])) {
				$position_type = '<br>'.Yii::t('Api', 'Position type').': '.$val['attributes']['position_type'];
			}
			
			$network_link = '';
			if (!empty($val['attributes']['application_metadata']['url'])) {	
				$network_link = $val['attributes']['application_metadata']['url'];	
			}
			
			$protocol_name = '';
			if (!empty($val['attributes']['application_metadata']['name'])) {	
				$protocol_name = Yii::t('Api', 'Position protocol').': '.$val['attributes']['application_metadata']['name'];	
			}

			$data[] = [
				'balance' => $balance,
				'negative' => $negative,
				'name' => $name,
				'currency' => $currency,
				'sort' => $value,
				'currency_value' => $currency_value,
				'img' => $img,
				'symbol' => $symbol,
				'symbolid' => $symbolid,
				'coinid' => $coinid,
				'grafema' => $grafema,
				'class' => $class,
				'price' => $price['data'],
				'network' => Yii::t('Frontend', 'Wallet').' ETH | '.Yii::t('Api', 'Network').': '.$network.$position_type,
				'network_icon' => $network_icon,
				'network_link' => $network_link,
				'apr' => '',
				'asset' => $address,
				'protocol' => $protocol_name,
			];		
		}

		return [
			'error' => 0,
			'data' => $data,
		];
    }
	
	/**
	 * getAptBalance()
	 * filter = [
	 * 	   'positions' => 'only_simple',
	 *     'trash' => 'only_non_trash',
	 *     'chain_ids' => 'ethereum',
	 * ];
	 */
    public function getEthBalance($currency='usd', $sort = '', $filter=[]) 
	{	

		$api_url = $this->api_url;
		$api_url = $api_url.'/v1/wallets/'.$this->address.'/positions/?currency='.$currency;

		if (!empty($filter['positions'])) {
			$api_url .= '&filter[positions]='.$filter['positions'];
		}
		
		if (!empty($filter['trash'])) {
			$api_url .= '&filter[trash]='.$filter['trash'];
		}
		
		if (!empty($sort)) {
			$api_url .= '&sort='.$sort;
		}

		$header = [
			'Content-Type: application/json',
			'Authorization: Basic '.base64_encode($this->api_key.':'),
		];

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $api_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		$response = curl_exec($curl);
		curl_close($curl);

		if (empty($response)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Not response'),
			];
		}
	
		if (!is_string($response)) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect response'),
			];
		}

		$data = json_decode($response, true);
		if (empty($data) || !is_array($data) || !isset($data['data']) || !is_array($data['data'])) {
			return [
				'error' => 1,
				'message' => Yii::t('Error', 'Incorrect response'),
			];
		}

		return [
			'error' => 0,
			'data' => $data['data'],	
		];
	}
	
	/**
	 * getAddressParse($address='')
	 */ 
	public function getAddressParse($address='')
	{
		return $address;
	}
	
	/**
	 * getAddressParse($address='')
	 */ 
	public function getIconParse($id='')
	{
		$default_icon = '/images/logos/eth2.png';
		if (empty($id)) {
			return $default_icon;
		}
		
		$icon = [
			'binance-smart-chain' => '/images/logos/bnb2.png',
			'zksync-era' => '/images/logos/zks2.png',
			'optimism' => '/images/logos/op2.png',
			'arbitrum' => '/images/logos/arb2.png',
			'scroll' => '/images/logos/scroll2.png',
			'linea' => '/images/logos/linea2.png',
			'zero' => '/images/logos/zro2.png',
			'base' => '/images/logos/base2.png',
			'ethereum' => $default_icon,
			'polygon' => '/images/logos/polygon2.png',
			'celo' => '/images/logos/celo2.png',
			'sonic' => '/images/logos/sonic2.png',
			'avalanche' => '/images/logos/avalanche2.png',
			'gravity-alpha' => '/images/logos/gravitya2.png',
			'blast' => '/images/logos/blast2.png',
			'zora' => '/images/logos/zora2.png',
			'xdai' => '/images/logos/xdai2.png',
			'polygon-zkevm' => '/images/logos/polygonzke2.png',
			'soneium' => '/images/logos/soneium2.png',
		];
		
		if (empty($icon[$id])) {
			return $default_icon;
		}
		
		return $icon[$id];
	}
	
	/**
	 * networkParse($symbol, $network)
	 */
	public function networkParse($symbol='', $network='')
	{
		if (empty($symbol) || empty($network)) {
			return $sumbol;
		}
		
		$array = [
			'avaxavalanche' => 'avax',
			'ethethereum' => 'eth',
			'arbarbitrum' => 'arb',
			'bnbbnb-binance-smart-chain' => 'bnb',
			'opoptimism' => 'op',
			'polpolygon' => 'pol',
		]; 

		$array_id = strtolower($symbol).$network;

		if (!empty($array[$array_id])) {
			return $array[$array_id];
		}

		return $symbol.'-'.$network;
	}
	
	/**
	 * parseCoin(token)
	 */
	public function parseCoin($token='')
	{
		if (empty($token)) {
			return false;
		}
		
		return false;
		
		$tokens = [
			'base-arbitrum-asset-asset' => 'ETH (arbitrum)',
			'base-scroll-asset-asset' => 'ETH (scroll)',
			'base-linea-asset-asset' => 'ETH (linea)',
			'base-optimism-asset-asset' => 'ETH (optimism)',
			'base-zero-asset-asset' => 'ETH (zero)',
			'0xfd086bc7cd5c481dcc9c85ebe478a1c0b69fcbb9-arbitrum-asset-asset' => 'USDT (arbitrum)',
			'base-base-asset-asset' => 'ETH (base)',
			'base-ethereum-asset-asset' => 'ETH (ethereum)',
			'base-zksync-era-asset-asset' => 'ETH (zksync)',
			'base-blast-asset-asset' => 'ETH (blast)',
			'base-zora-asset-asset' => 'ETH (zora)',
			'base-polygon-zkevm-asset-asset' => 'ETH (polygon)',
			'base-soneium-asset-asset' => 'ETH (soneium)',
		];
		
		if (empty($tokens[$token])) {
			return false;
		}
		
		return $tokens[$token];	
	}
	
	/**
	 * pstatic($className=__CLASS__)
	 */ 
	public static function pstatic($className=__CLASS__)
	{
		return new $className;
	}
}