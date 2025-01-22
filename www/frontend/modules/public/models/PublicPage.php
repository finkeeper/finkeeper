<?php

namespace app\modules\public\models;

use Yii;
use yii\base\Model;
use yii\base\View;
use common\models\Clients;

use app\modules\certificate\models\CertificateWork;
use common\models\Certificate;
use frontend\components\SchoolToken;
use yii\helpers\Url;


/**
 * Signup form
 */
class PublicPage extends Model
{
	const SCENARIO_PAGE_CONTRACT = 'contract';
	const SCENARIO_PAGE_ADDRESS = 'address';
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['id', 'integer', 'integerOnly' => true, 'min' => 0],
            ['is_mainnet', 'integer', 'integerOnly' => true, 'min' => 0, 'max' => 1],
            ['deployed_to_mainnet', 'integer', 'integerOnly' => true, 'min' => 0, 'max'=>1],
            ['deployed_to_testnet', 'integer', 'integerOnly' => true, 'min' => 0, 'max'=>1],

			['identify_name', 'trim'],
            ['identify_name', 'required'],
			['identify_name', 'match', 'pattern' => '/^[a-zA-Z0-9\-\_]{3,255}$/i'],
			
			['name', 'trim'],
			['name', 'string', 'min' => 3, 'max' => 255], 
			
			['surname', 'trim'],
			['surname', 'string', 'min' => 3, 'max' => 255],
			
			['web_site', 'trim'],
			['web_site', 'string', 'min' => 3, 'max' => 255],
			
			['image', 'trim'],
			['image', 'string', 'min' => 3, 'max' => 255],
			
			['school_logo', 'trim'],
			['school_logo', 'string', 'min' => 3, 'max' => 255],
			
			['employee', 'trim'],
			['employee', 'string', 'min' => 3, 'max' => 255],
			
			['school_nft_address_testnet', 'trim'],
			['school_nft_address_testnet', 'string', 'max' => 255],

			['owner_nft_address_testnet', 'trim'],
			['owner_nft_address_testnet', 'string', 'max' => 255],
			
			['school_nft_address_mainnet', 'trim'],
			['school_nft_address_mainnet', 'string', 'max' => 255],

			['owner_nft_address_mainnet', 'trim'],
			['owner_nft_address_mainnet', 'string', 'max' => 255],

			
			[['password', 'confirm_password'], 'string', 'min' => 0, 'max' => 255],

			['confirm_password', 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => false, 'message' => Yii::t('Error', 'Passwords Not Match')],	
        ];
    }
	
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'name' => Yii::t('Frontend', 'Name'),
			'identify_name' => Yii::t('Frontend', 'Education organization'),
        ];
    }

	public function setAttributesContract() {
		$this->scenario = self::SCENARIO_PAGE_CONTRACT;
	}

	public function setAttributesAddress() {
		$this->scenario = self::SCENARIO_PAGE_ADDRESS;
	}



}
