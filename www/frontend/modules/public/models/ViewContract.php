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
class ViewContract extends Model
{
	public $is_mainnet = 1;
	public $search_contract;
	public $search_token_id;
	public $verifyCode;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['search_token_id', 'integer', 'integerOnly' => true, 'min' => 0, 'skipOnEmpty' => false],
            ['is_mainnet', 'integer', 'integerOnly' => true, 'min' => 0, 'max' => 1],
			
			['search_contract', 'trim'],
			['search_contract', 'string', 'min'=>26, 'max' => 255, 'skipOnEmpty' => false],
        ];
    }
	
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'search_token_id' => Yii::t('Frontend', 'Token Id'),
			'is_mainnet' => Yii::t('Frontend', 'Mainnet'),
			'search_contract' => Yii::t('Contract', 'Address of education organization contract'),
        ];
    }

	public static function getMetaData($uri = '') {
		if (empty($uri)) {
			return ['error' => 1, 'message' => 'Wrong URI'];
		}

		$result = SchoolToken::getMetaData($uri);

		if (!empty($result['error'])) {
			return $result;
		}

		if (!is_array($result['data']) || empty($result['data'])) {
			return $result;
		}

		foreach($result['data'] as $key => &$entity) {

			if ($key == 'image') {
				if (!self::isImage($entity)) {
					$entity = '';
				}
				continue;
			}

			if (is_numeric($entity)) {
				$entity = (float) $entity;
			} else if (is_string($entity)) {
				$entity = SchoolToken::clearText($entity);
			}
		}

		return ['error' => 0, 'message' => 'meta clear', 'data' => $result['data']];
	}

	public function clearMetaParams($jsonString = '') {
		if (empty($jsonString)) {
			return ['error' => 1, 'message' => 'Wrong json'];
		}
		
		$params = json_decode($jsonString, true);
		
		if (empty($params)) {
			return ['error' => 1, 'message' => 'Wrong params json'];
		}
		
		$ctfnProData = [];
		
		foreach($params as $key => &$param) {
			if (is_numeric($param)) {
				$param = (float) $param;
			} else if (is_string($param)) {
				$param = SchoolToken::clearText($param);
			}
		}

		$model = new CertificateWork;
		
		if (!empty($params['is_mainnet'])) {
			// mainnet

			$certificate = Certificate::find()->where(
				'minted_on_mainnet = 1 AND minted_by_contract_mainnet = :minted_by_contract_mainnet AND id_nft_token_mainnet = :id_nft_token_mainnet', 
				[
					'minted_by_contract_mainnet'=> $params['contractAddress'],
					'id_nft_token_mainnet'=> $params['tokenId'],
				]
			)->one();

			if (!empty($certificate)) {
				$model->setAttributes($certificate->attributes, false);
				$ctfnProData['schoolName'] = $model->getSchoolName();
				$ctfnProData['course'] = $model->course;
			}

		} else {
			//testnet

			$certificate = Certificate::find()->where(
				'minted_on_testnet = 1 AND minted_by_contract_testnet = :minted_by_contract_testnet AND id_nft_token_testnet = :id_nft_token_testnet', 
				[
					'minted_by_contract_testnet'=> $params['contractAddress'],
					'id_nft_token_testnet'=> $params['tokenId'],
				]
			)->one();

			if (!empty($certificate)) {
				$model->setAttributes($certificate->attributes, false);
				$ctfnProData['schoolName'] = $model->getSchoolName();
				$ctfnProData['course'] = $model->course;
			}
		}

		if (!empty($ctfnProData)) {
			$params['ctfn'] = $ctfnProData;
		}

		return ['error' => 0, 'message' => 'params clear', 'data' => $params];
	}
	


	public static function isImage($url) {
	  	$params = array('http' => array(
			'method' => 'HEAD'
		));
		$ctx = stream_context_create($params);
		$fp = @fopen($url, 'rb', false, $ctx);
	   	if (!$fp) {
		   	return false;  // Problem with url
	    }
  
		$meta = stream_get_meta_data($fp);
		if ($meta === false) {
			fclose($fp);
			return false;  // Problem reading data from url
		}
  
		$wrapper_data = $meta["wrapper_data"];
		if (is_array($wrapper_data)) {
			foreach(array_keys($wrapper_data) as $hh){
				// strlen("Content-Type: image") == 19
				if (substr($wrapper_data[$hh], 0, 19) == "Content-Type: image") { 
					fclose($fp);
					return true;
				}
			}
		}
  
		fclose($fp);
		return false;
	}

}
