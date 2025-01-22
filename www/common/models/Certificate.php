<?php
namespace common\models;

use Yii;
use common\models\Clients;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use nickcv\encrypter\behaviors\EncryptionBehavior;


/**
 * Clients model
 *
 * @property integer $id_certificate
 * @property integer $id_client
 * @property integer $number
 * @property integer $deleted
 * @property integer $id_nft_token 
 * @property integer $nft_deleted
 * @property integer $nft_requested
 * @property integer $nft_sent
 * @property integer $hide_persona
 * @property varchar $name
 * @property varchar $surname
 * @property varchar $patronumic
 * @property varchar $course
 * @property varchar $address_nft_contract
 * @property varchar $user_nft_address 
 * @property datetime $creation_date
 * @property datetime $nft_request_date
 * @property datetime $nft_generation_date
 */
class Certificate extends ActiveRecord
{
	const STATUS_NOT_DELETED = 0;
	const STATUS_DELETED = 1;
	
	public $school_name;
	public $web_site;
	
	public $id;
	public $image;
	public $school_nft_address_testnet;
	public $school_nft_address_mainnet;
	public $identify_name;

	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%certificate}}';
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
					ActiveRecord::EVENT_BEFORE_INSERT => ['creation_date'],
				] ,
				'value' => new \yii\db\Expression ('NOW()'),
			] ,
			/*
			'encryption' => [
				'class' => '\nickcv\encrypter\behaviors\EncryptionBehavior',
				'attributes' => [
					'name',
					'surname',
					'patronumic',
				],
			],
			*/
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			['deleted', 'default', 'value' => self::STATUS_NOT_DELETED],
            ['deleted', 'in', 'range' => [self::STATUS_NOT_DELETED, self::STATUS_DELETED]],
        ];
    }
	
	/**
	 * @beforeSave($insert)
	 */
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {

			$this->id_client = (int) Yii::$app->user->identity->getId();
			if (empty($this->id_client)) {
				return false;
			}

			return true;
		}
		
		return false;
	}
	
	/**
	 * findUserId
	 */
	public static function findCertificate($id)
	{	
		$id = (int) $id;
		$certificate = static::findOne(['id_certificate' => $id, 'deleted' => self::STATUS_NOT_DELETED]);
		if (!empty($certificate)) {
			return $certificate;
		}
		
		return false;
	}
	
	/**
	 * searchUserNftAddress
	 */
	public static function searchUserNftAddress($user_nft_address)
	{
		$sql = '
			SELECT
				t1.id_certificate,
				t1.id_client,
				t1.name,
				t1.surname,
				t1.number,
				t1.course,
				t1.creation_date,
				t1.minted_on_mainnet,
				t1.minted_on_testnet,
				t1.minted_by_contract_mainnet,
				t1.minted_by_contract_testnet,
				t1.id_nft_token_mainnet,
				t1.id_nft_token_testnet,
				t2.name as school_name,
				t2.web_site
			FROM {{%certificate}} t1
			INNER JOIN {{%clients}} t2
				ON t1.id_client = t2.id
			WHERE t1.deleted=:deleted
			AND t2.deleted=:deleted
			AND (t1.minted_on_testnet=:active OR t1.minted_on_mainnet=:active)
			AND t1.user_nft_address=:user_nft_address
		';

		$certificates = self::findBySql($sql, [
			':deleted' => self::STATUS_NOT_DELETED,
			':active' => 1,
			':user_nft_address' => $user_nft_address,
		])->all();

		if (!empty($certificates)) {
			return $certificates;
		}
		
		return false;
	}

	/**
	 * searchUserSchools
	 */
	public static function searchUserSchools($user_nft_address)
	{
		$sql = '
			SELECT
				t2.id,
				t2.name as school_name,
				t2.image,
				t2.web_site,
				t2.school_nft_address_testnet,
				t2.school_nft_address_mainnet,
				t2.identify_name
			FROM {{%certificate}} t1
			INNER JOIN {{%clients}} t2
				ON t1.id_client = t2.id
			WHERE
				t1.user_nft_address = :user_nft_address
				AND ( t1.minted_on_mainnet = 1 OR t1.minted_on_testnet = 1)
				AND ( t2.deployed_to_mainnet = 1 OR t2.deployed_to_testnet = 1)
				AND t2.deleted = :deleted
				AND t2.active = :active
			GROUP BY t2.id
			ORDER BY t1.creation_date DESC
		';

		$schools = self::findBySql($sql, [
			':deleted' => self::STATUS_NOT_DELETED,
			':active' => 1,
			':user_nft_address' => $user_nft_address,
		])->all();

		if (!empty($schools)) {
			return $schools;
		}
		
		return false;
	}
	
	/**
	 * findUsers
	 */
	public static function findCertificates()
	{
		$id_client = (int) Yii::$app->user->identity->getId();
		if (empty($id_client)) {
			return false;
		}

		$certificates = static::find()->where([
			'deleted' => self::STATUS_NOT_DELETED,
			'id_client' => $id_client,
		])->all();

		if (!empty($certificates)) {
			return $certificates;
		}
		
		return false;
	}
	
	/**
	 * generateImageCertificate($id_certificate)
	 */
	 public static function generateImageCertificate($id_certificate)
	 {
        $certif = Certificate::findCertificate($id_certificate);
		if (empty($certif) || empty($certif)) {
			return false;
		}

		$path = getcwd().'/upload/certificate/';
		
		$maket = $path.'default_maket.png';
		$font = $path.'MagistralTT.ttf'; 
		$font_size = 80;

		$im = imageCreateFromPng($maket);
		$color = imagecolorallocate($im, '0', '0', '0');
		//#333333 - rgb 51 51 51
		
		$settings = self::getSettingsDefaultCertificate();
		
		$search = self::textSearch();
		$replace = self::textReplace($certif->name, $certif->surname, $certif->course, $certif->creation_date, $certif->id_certificate); 
		
		foreach ($settings as $key => $value) {
			// Текст с подстановкой значений
			$text = $value['text'] ? str_replace($search, $replace, $value['text']) : '';
			// Длина строки
			//$length = iconv_strlen($text,'UTF-8');
			// Верхнее смещение текстового блока
			$padding_top = $value['axis_y'] ? $value['axis_y'] : 0;
			// Левое смещение текстового блока
			$padding_left = $value['axis_x'] ? $value['axis_x'] : 0;
			// Высота текстового блока
			$height = $value['height'] ? $value['height'] : 0;
			// Ширина тестового блока
			$original_width = $value['width'] ? $value['width'] : 0;
			// Горизонтальные отступы в текстовом блоке
			$indent = ceil(($original_width/100)*5);
			// Ширина текстового блока с учетом отступов
			$width = ceil($original_width - ($indent*2));
			// Размер шрифта с учетом примерной ширины текстовой строки
			//$font_size = ceil(($width / $max_length) * 1.4);
			// Вычисляем центр блока
			$textbox = imagettfbbox ($font_size, 0, $font, $text);
			// Центр блока 
			$center = round(($width - ($textbox[2] - $textbox[0]))/2);
			// Левая координата с учетом отступа и центровки текста в блоке
			$new_padding_left = $padding_left + $indent + $center;
			// Накладываем текст на макет сертификата
			imagefttext($im, $font_size, 0, $new_padding_left, $padding_top, $color, $font, $text);
		}  
		
		return $im;
	 }
	
	/**
	 * getHashCertificate($id_client=0, $id_certificate=0)
	 */
	 public static function getHashCertificate($id_client=0, $id_certificate=0)
	 {
        return md5(md5($id_client.Yii::$app->params['cert_salt']).md5($id_certificate));
	 }
	 
	 /**
	  * validateHashCertificate($id_client=0, $hash='')
	  */
	 public static function validateHashCertificate($id_certificate=0, $hash='')
	 {
        if (empty($id_certificate)) {
			return false;
		}
		
		$certificate = self::findCertificate($id_certificate);
		if (empty($certificate)) {
			return false;
		}

		$generate_hash = md5(md5($certificate->id_client.Yii::$app->params['cert_salt']).md5($certificate->id_certificate));
		if ($generate_hash!=$hash) {
			return false;
		}
		
		return true;
	 }

	/**
	 * getSettingsDefaultCertificate()
	 */
	public static function getSettingsDefaultCertificate()
	{
		$settings = [  
			1 => [
				'text' => '{date_dd}.{date_mm}.{date_yyyy}',
				'axis_y' => '1903.4028387652',
				'axis_x' => '2292.7828436002',
				'width' => '779.3138007461',
				'height' => '110.7869057812',
			],
			2 => [
				'text' => '{name} {surname}',
				'axis_y' => '1184.2665618395',
				'axis_x' => '426.35843850675',
				'width' => '2641.0203431417',
				'height' => '140.2245432894',
			],
			3 => [
				'text' => '{course}',
				'axis_y' => '1465.9711441374',
				'axis_x' => '426.35843850675',
				'width' => '2643.9056524477',
				'height' => '140.2245432894',
			],
			4 => [
				'text' => '№ {number}',
				'axis_y' => '920',
				'axis_x' => '1402.2844478983',
				'width' => '675.63872503003',
				'height' => '110.90387749709',
			],	
		];
		
		return $settings;
	}

	/**
	 * getReplace
	 */
	public static function textReplace($name='', $surname='', $course='', $date='', $number=false)
	{
		if (!empty($date)) {
			$un = strtotime($date);
			$d1 = date('d', $un);
			$d2 = date('d', $un+86400);
			$m = date('m', $un);
			$y = date('Y', $un);
		} else {
			$d1 = date('d');
			$d2 = date('d', strtotime('+1 day'));
			$m = date('m');
			$y = date('Y');
		}

		return [
			$name, 
			$surname,  
			$course, 
			$number,
			$d1,
			$d2,
			$m, 
			$y
		];
	}
	
	/**
	 * getSearch
	 */
	public static function textSearch()
	{
		return [
			'{name}', 
			'{surname}',  
			'{course}', 
			'{number}',
			'{date_dd}', 
			'{date_dd_plus}',
			'{date_mm}', 
			'{date_yyyy}'
		];
	}
}