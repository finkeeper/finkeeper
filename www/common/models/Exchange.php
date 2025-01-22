<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\ExchangeConfig;
use yii\data\ActiveDataProvider;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;

/**
 * Exchange model
 *
 * @property integer $id_crupto
 * @property integer $rank
 * @property string $slug
 * @property string $name
 * @property string $symbol
 * @property string $type
 * @property integer $nominal
 * @property integer $value
 * @property string $date_of_change
 * @property string $currency
 */
class Exchange extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%exchange}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
				'class' => '\yii\behaviors\TimestampBehavior' ,
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => ['date_of_change'],
					ActiveRecord::EVENT_BEFORE_DELETE => ['date_of_change'],
				] ,
				'value' => new \yii\db\Expression ('NOW()'),
			] ,
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['rank', 'nominal', 'id_config', 'currency_type'], 'integer'],
			[['date_of_change'], 'string', 'max' => 60],
			[['slug', 'name', 'symbol', 'type', 'currency', 'api', 'id_api'], 'string'],
			[['value'], 'string', 'max' => 13],
        ];
    }
	
	/**
	 * @beforeSave($insert)
	 */
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			
			$this->date_of_change = date('Y-m-d H:i:s');

			return true;
		}
		return false;
	}
	
	/**
	 * search()
	 */
	public function search($type=1)
	{
		$query = static::find()->where(['currency_type'=>$type])->orderBy('id_crupto');
		   
		return new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => Yii::$app->params['pagination'],
			],
			'sort' => [
				'defaultOrder' => [
					'id_crupto' => SORT_ASC,
				]
			],
		]);
	}
	
	/**
	 * sanitizeQuery($str='')
	 */
	public static function sanitizeQuery($str='')
	{
		if (empty($str) || !is_string($str)) {
			return false;
		}
		
		$str = str_replace(
			[
				'/', 
				'?',
			], 
			[
				'', 
				'',
			]
		, $str);
		
		if (!preg_match('/^[0-9a-z]{1,}$/i', $str)) {
			return false;
		}
		
		$str = strtolower($str);
		
		$replace = [
			'toncoin' => 'ton',
		];
		
		if (!empty($replace[$str])) {
			$str = $replace[$str];
		}
		
		
		return $str;
	}
	
	/**
	 * getDataCoingecko()
	 */
	public static function getDataCoingecko()
	{
		$config = self::getConfig(1);
		$array = [];
		
		$url = $config['api_url']['coingecko'].'/list&x_cg_demo_api_key='.$config['tokens']['coingecko'];

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

		$tmp = @json_decode($result, true);
		if (!empty($tmp) && is_array($tmp) && empty($tmp['error'])) {
			foreach ($tmp as $value) {
				if (empty($array[$value['symbol']])) {
					$array[$value['symbol']] = $value;
				}
			}
		}

		if (empty($array) || !is_array($array) || !empty($array['error'])) {
			
			$path = dirname(__DIR__) . '/config/coingecko.json';
			$result = file_get_contents($path);	

			if (empty($result) || !is_string($result)) {
				return false;
			}
			
			$tmp = @json_decode($result, true);
			foreach ($tmp as $value) {
				if (empty($array[$value['symbol']])) {
					$array[$value['symbol']] = $value;
				}
			}
		}
		
		return $array;
	}
	
	/**
	 * getDataCurrency()
	 */
	public static function getDataCurrency()
	{
		$config = self::getConfig(2);
		
		$url = $config['api_url']['cbr'].'?date_req='.date('d/m/Y');
		
		$response  = file_get_contents($url, "r");
		if (empty($response)) {
			return false;
		}
		
		$xml = simplexml_load_string($response);

		if (empty($xml->Valute)) {
			return false;
		}
		
		foreach ($xml->Valute as $val) {
			$key = strtolower(trim($val->CharCode));
			$array[$key] = [
				'price' => str_replace(',', '.', $val->Value),
				'date_of_change' => date('Y-m-d H:i:s'),
				'name' => json_decode(json_encode($val->Name), true)[0],
				'symbol' => json_decode(json_encode($val->CharCode), true)[0],
				'rank' => json_decode(json_encode($val->NumCode), true)[0],
				'nominal' => json_decode(json_encode($val->Nominal), true)[0],
			];			
		}
		
		return $array;
	}
	
	/**
	 * getDataCoingecko()
	 */
	public static function getConfig($type=1)
	{
		if ($type==1) {
			$res = ExchangeConfig::getConfig();
		} else if ($type==2) {
			$res = CurrencyConfig::getConfig();
		} else if ($type==3) {
			return require dirname(__DIR__) . '/config/exchange.php';
		} else {
			return false;
		}
		
		if (empty($res) || !is_array($res)) {
			return false;
		}
		
		$rates = [];
		foreach ($res as $value) {
			$rates[] = [
				'id' => $value['id'],
				'symbol' => $value['symbol'],
				'id_coingecko' => !empty($value['id_coingecko']) ? $value['id_coingecko'] : '',
				'deleted' => $value['deleted'],
			];
		}

		$config = require dirname(__DIR__) . '/config/exchange.php';
		if (
			empty($config) || 
			!is_array($config)
		) {
			return false;
		}
		
		$config['rates'] = $rates;
		
		return $config;
	}
	
	/**
	 * getData()
	 */
	public static function getData()
	{
		$data = [
			'USD' => [
				'id' => 0,
				'name' => '⌛',
				'price' => 0,
				'text_name' => '⌛',
				'img' => '/images/icons/lock.svg',
				'type' => 0,
				'currency' => '⌛',
			],
			'RUB' => [
				'id' => 0,
				'name' => '⌛',
				'price' => 0,
				'text_name' => '⌛',
				'img' => '/images/icons/lock.svg',
				'type' => 0,
				'currency' => '⌛',
			],
			'BTC' => [
				'id' => 0,
				'name' => '⌛',
				'price' => 0,
				'text_name' => '⌛',
				'img' => '/images/icons/lock.svg',
				'type' => 0,
				'currency' => '⌛',
			],
		];
	
		$exchange = static::find()
			->orderBy('id_crupto')
			->all();
			
		if (empty($exchange) || !is_array($exchange)) {
			return $data;
		}
		
		foreach ($exchange as $val) {
			$data[strtoupper($val->symbol)] = [
				'id' => $val->id_crupto,
				'name' => strtoupper($val->symbol),
				'price' => str_replace(',', '.', $val->value),
				'text_name' => $val->name,
				'img' => $val->image,
				'type' => $val->currency_type,
				'currency' => $val->currency,
			];
		}

		return $data;
	}
	
	/**
	 * getFormat($str='', $type=1)
	 */
	public static function getFormat($str='', $type=1, $decimal=4)
	{
		if (empty($str)) {
			return $str;
		}
		
		if ($type==1) {
			for ($i=$decimal; $i<=10; $i++) {
				$tmp = $str;
				$tmp = number_format($tmp, $i, '.', ' ');
				$tmp = str_replace(',', '.', $tmp);
				$tmp = preg_replace('/[0]{1,}$/i', '', $tmp);
				$tmp = preg_replace('/[.]{1}$/i', '', $tmp);

				if (!empty($tmp)) {
					break;
				}
				
				$array_str = explode('.', (string) $tmp);
				if (!empty($array_str) && is_array($array_str) && isset($array_str[1])) {
					$num = strlen((string) $array_str[1]); 
					if ($num==1) {
						$tmp = $tmp . 0;
					}
				}
			}
		}
		
		return $tmp;
	}
	
	/**
	 * getHandBook()
	 */
	public static function getHandBook($array=[])
	{
		if (empty($array) || !is_array($array)) {
			return ['error'=>1, 'message'=>Yii::t('Error', 'Missing Convert Value')];
		}
		
		$hand_book = [
			'base' => [],
			'conv1' => [],
			'conv2' => [],
			'conv3' => [],
		];
		
		$exchange = self::getData();
		if (empty($exchange) || !is_array($exchange)) {
			return ['error'=>1, 'message'=>Yii::t('Error', 'Missing Convert Value')];
		}
		
		foreach ($exchange as $value) {
				
			if ($value['id']==$array['base']) {
				$hand_book['base'] = $value;
				continue;
			}
			
			if ($value['id']==$array['conv1']) {
				$hand_book['conv1'] = $value;
				continue;
			}
			
			if ($value['id']==$array['conv2']) {
				$hand_book['conv2'] = $value;
				continue;
			}
			
			if ($value['id']==$array['conv3']) {
				$hand_book['conv3'] = $value;
				continue;
			}
		}
		
		return $hand_book;
	}
	
	/**
	 * getHandBook()
	 */
	public static function valdateData($input='')
	{
		$array = @json_decode($input, true);
		
		if (empty($array) || !is_array($array)) {
			return ['error'=>1, 'message'=>Yii::t('Error', 'Missing Data')];
		}
		
		foreach ($array as $key=>$value) {
			if ($key=='num') {
				$array[$key] = (int) $array[$key];
			} else {
				$array[$key] = (float) $array[$key];
			}
		}
		
		if (empty($array['base'])) {
			return ['error'=>1, 'message'=>Yii::t('Error', 'Missing Base Value')];
		}

		if (empty($array['num'])) {
			return ['error'=>1, 'message'=>Yii::t('Error', 'Missing Num Value')];
		}

		if (empty($array['conv1'])) {
			return ['error'=>1, 'message'=>Yii::t('Error', 'Missing Convert Value')];
		}
		
		if (empty($array['conv2'])) {
			return ['error'=>1, 'message'=>Yii::t('Error', 'Missing Convert Value')];
		}

		if (empty($array['conv3'])) {
			return ['error'=>1, 'message'=>Yii::t('Error', 'Missing Convert Value')];
		}
		
		if (empty($array['conv4'])) {
			return ['error'=>1, 'message'=>Yii::t('Error', 'Missing Convert Value')];
		}
		
		if (empty($array['conv5'])) {
			return ['error'=>1, 'message'=>Yii::t('Error', 'Missing Convert Value')];
		}
		
		return $array;
	}
	
	/**
	 * getImgCurrency($symbol='')
	 */
	public static function getImgCurrency($symbol='') 
	{
		if (empty($symbol)) {
			return false;
		}
		
		$symbol = strtolower ($symbol);
		
		$url = '/images/svg/currency/'.$symbol.'.svg';
		$path = getcwd().$url;
		if (!file_exists($path)) {
			return false;
		}
		
		return Html::img($url, [
			'alt' => 'icon currency',
			'style' => 'width:10px',
			'id' => 'img-conv-1',
		]);
	}
	
	/**
	 * getImgCurrency($symbol='')
	 */
	public static function getGrafemCurrency($str='') 
	{

		$symbol = '';
	
		if (empty($str)) {
			return $symbol;
		}
	
		$str = strtolower($str);

		$currency = [
			'aud' => '$',
			'amd' => 'Դ',
			'cad' => '$',
			'cny' => '¥',
			'czk' => 'Kč',
			'dkk' => 'kr',
			'huf' => 'ƒ',
			'inr' => '₹',
			'jpy' => '¥',
			'kzt' => '₸',
			'krw' => '₩',
			'kgs' => 'KGS',
			'lvl' => 'LVL',
			'ltl' => 'LTL',
			'mdl' => 'MDL',
			'nok' => 'kr',
			'sgd' => '$',
			'zar' => 'ZAR',
			'sek' => 'kr',
			'chf' => 'Fr',
			'gbp' => '£',
			'usd' => '$',
			'uzs' => 'Soʻm',
			'tmt' => 'TMT',
			'azn' => '₼',
			'ron' => 'RON',
			'try' => '₺',
			'xdr' => 'XDR',
			'tjs' => 'TJS',
			'byr' => 'BYN',
			'bgn' => 'BGN',
			'eur' => '€',
			'uah' => '₴',
			'pln' => 'zł',
			'brl' => '$',
			'rub' => '₽',
		];
		
		if (empty($currency[$str])) {
			return $symbol;
		}
		
		return $currency[$str];
	}
	
	/**
	 * getDefaultCurrency()
	 */
	public static function getDefaultCurrency()
	{
		$config = require dirname(__DIR__) . '/config/exchange.php';
		if (
			empty($config) || 
			!is_array($config) ||
			empty($config['currency'])
		) {
			return 'usd';
		}

		return $config['currency'];
	}
	
	/**
	 * formatValue($value=0)
	 */
	public static function formatValue($value=0, $type=0)
	{
		if (empty($value)) {
			return 0;
		}

		if (is_float($value)) {
			$value = number_format($value, 12, '.', '');
		} else if (is_int($value)) {
			$value = number_format($value, 12, '.', '');
		} else {
			$value = $value*1;
			$value = number_format($value, 12, '.', '');
		}
		
		if ($type==1) {		
			if ($value>0.1) {
				
				$bcdiv = bcdiv($value, 1, 2);
				$bcdiv = rtrim($bcdiv, '0');
				$bcdiv = rtrim($bcdiv, '.');
				
				return $bcdiv;
			}		
		}

		if ($value>0.01) {
			
			$bcdiv = bcdiv($value, 1, 3);
			$bcdiv = rtrim($bcdiv, '0');
			$bcdiv = rtrim($bcdiv, '.');
			
			return $bcdiv;
			
		} else if ($value>0.001) {	
			
			$bcdiv = bcdiv($value, 1, 4);
			$bcdiv = rtrim($bcdiv, '0');
			$bcdiv = rtrim($bcdiv, '.');

			return  $bcdiv;
			
		} else if ($value>0.0001) {	
			
			$bcdiv = bcdiv($value, 1, 5);
			$bcdiv = rtrim($bcdiv, '0');
			$bcdiv = rtrim($bcdiv, '.');

			return $bcdiv;	
			
		} else if ($value>0.00001) {	
			
			$bcdiv = bcdiv($value, 1, 6);
			$bcdiv = rtrim($bcdiv, '0');
			$bcdiv = rtrim($bcdiv, '.');

			return $bcdiv;
			
		} else if ($value>0.000001) {	
			
			$bcdiv = bcdiv($value, 1, 7);
			$bcdiv = rtrim($bcdiv, '0');
			$bcdiv = rtrim($bcdiv, '.');
			
			return $bcdiv;		
			
		} else if ($value>0.0000001) {	
			
			$bcdiv = bcdiv($value, 1, 8);
			$bcdiv = rtrim($bcdiv, '0');
			$bcdiv = rtrim($bcdiv, '.');

			return $bcdiv;	
			
		} else if ($value>0.00000001) {	
			
			$bcdiv = bcdiv($value, 1, 9);
			$bcdiv = rtrim($bcdiv, '0');
			$bcdiv = rtrim($bcdiv, '.');

			return $bcdiv;
			
		} else if ($value>0.000000001) {	
			
			$bcdiv = bcdiv($value, 1, 10);
			$bcdiv = rtrim($bcdiv, '0');
			$bcdiv = rtrim($bcdiv, '.');

			return $bcdiv;	
			
		} else if ($value>0.0000000001) {	
			$bcdiv = bcdiv($value, 1, 11);
			$bcdiv = rtrim($bcdiv, '0');
			$bcdiv = rtrim($bcdiv, '.');
			
			
			
			return $bcdiv;	
		} else if ($value>0.00000000001) {	
			
			$bcdiv = bcdiv($value, 1, 12);
			$bcdiv = rtrim($bcdiv, '0');
			$bcdiv = rtrim($bcdiv, '.');
			
			return $bcdiv;
			
		} else {
			
			$bcdiv = bcdiv($value, 1, 2);
			$bcdiv = rtrim($bcdiv, '0');
			$bcdiv = rtrim($bcdiv, '.');

			return $bcdiv;
		}
	}
	
	/**
	 * getPoolInfo($pool_name='') 
	 */
	public static function getPoolInfo($pool_name='') 
	{
		if (empty($pool_name)) {
			return false;
		}
		
		//$pool_name = strtoupper($pool_name);
		
		$url = 'http://94.141.102.189:8000/pool_info?pool_name='.$pool_name;
		
		$response  = @file_get_contents($url, "r");
		if (empty($response) || !is_string($response)) {
			return false;
		}
		
		$array = @json_decode($response, true);
		
		if (empty($array) || !is_array($array)) {
			return false;
		}

		return $array;
	}
}
