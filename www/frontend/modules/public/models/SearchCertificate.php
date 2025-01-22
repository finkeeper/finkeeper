<?php

namespace app\modules\public\models;

use Yii;
use yii\base\View;
use yii\base\Model;
use yii\helpers\Url;
use common\models\Clients;
use common\models\Certificate;

/**
 * Signup form
 */
class SearchCertificate extends Model
{
	public $user_nft_address;
	public $verifyCode;

	public $school_name;
	public $web_site;
	
	public $id;
	public $image;
	public $school_nft_address_testnet;
	public $school_nft_address_mainnet;
	public $identify_name;
	
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
			['user_nft_address', 'trim'],
			['user_nft_address', 'string', 'max' => 255],
        ];
    }
	
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'user_nft_address' => Yii::t('Frontend', 'Crypto wallet address'),
        ];
    }

	/**
	 * searchUserNftAddress()
	 */
	public function searchUserNftAddress()
	{
		if (empty($this->user_nft_address)) {
			return false;
		}
		
		Yii::$app->db;
		
		$certificates = Certificate::searchUserNftAddress($this->user_nft_address);
		if (empty($certificates)) {
			return false;
		}
		
		return $certificates;
	}

	/**
	 * searchUserSchools()
	 */
	public function searchUserSchools()
	{
		if (empty($this->user_nft_address)) {
			return false;
		}
		
		$schools = Certificate::searchUserSchools($this->user_nft_address);
		if (empty($schools)) {
			return false;
		}
		
		return $schools;
	}
	
	

}
