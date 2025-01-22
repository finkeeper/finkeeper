<?php

namespace app\modules\certificate\models;

use app\modules\profile\models\Profile;
use Yii;
use yii\base\Model;
use yii\base\View;
use common\models\Certificate;
use common\models\Clients;
use yii\helpers\Url;

use frontend\components\SchoolToken;

/**
 * Signup form
 */
class CertificateWork extends Model
{
	
	public $id_certificate;
	public $id_client;
	public $creation_date;
	public $name;
	public $surname;
	public $number;
	public $course;
	public $user_nft_address;
	public $id_nft_token_mainnet;
	public $id_nft_token_testnet;
	public $minted_on_mainnet;
	public $minted_on_testnet;
	public $minted_by_contract_mainnet;
	public $minted_by_contract_testnet;
	public $minted_by_address_mainnet;
	public $minted_by_address_testnet;
	public $date_minted_on_mainnet;
	public $date_minted_on_testnet;
	public $burned_on_mainnet;
	public $burned_on_testnet;
	public $date_burned_on_mainnet;
	public $date_burned_on_testnet; 

	const MAINNET = 'mainnet';
	const TESTNET = 'testnet';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            
			['user_nft_address', 'required'],
			
			[['id_nft_token_testnet', 'id_nft_token_mainnet', 'minted_on_mainnet', 'minted_on_testnet', 'burned_on_mainnet', 'burned_on_testnet', 'number'], 'default', 'value' => 0],

			[['id_certificate', 'id_client', 'id_nft_token_testnet',  'id_nft_token_mainnet','number'], 'integer', 'integerOnly' => true, 'min' => 0],
            
			[['name', 'surname', 'user_nft_address', 'course'], 'string', 'min' => 3, 'max' => 255], 
			
			[['minted_on_mainnet', 'minted_on_testnet', 'burned_on_mainnet', 'burned_on_testnet'], 'integer', 'integerOnly' => true, 'min' => 0, 'max'=>1], 
			
			[['minted_by_contract_mainnet', 'minted_by_contract_testnet', 'minted_by_address_mainnet', 'minted_by_address_testnet'], 'string', 'min' => 0, 'max' => 255, 'skipOnEmpty'=>true],
			
			[['date_minted_on_mainnet', 'date_minted_on_testnet', 'date_burned_on_mainnet', 'date_burned_on_testnet'], 'default', 'value' => '0000-00-00 00:00:00'], 
			[['date_minted_on_mainnet', 'date_minted_on_testnet', 'date_burned_on_mainnet', 'date_burned_on_testnet'], 'safe'],
        ];
    }
	
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'name' => Yii::t('Frontend', 'Name'),
			'surname' => Yii::t('Frontend', 'Surname'),
			'course' => Yii::t('Frontend', 'Course'),
			'number' => Yii::t('Frontend', 'Number'),
			'user_nft_address' => Yii::t('Frontend', 'User nft address'),

			'minted_on_mainnet' => Yii::t('Frontend', 'Minted on Mainnet'), 
			'minted_on_testnet' => Yii::t('Frontend', 'Minted on Testnet'), 
			'minted_by_contract_mainnet' => Yii::t('Frontend', 'Minted by contract on Mainnet'), 
			'minted_by_contract_testnet' => Yii::t('Frontend', 'Minted by contract on Testnet'), 
			'minted_by_address_mainnet' => Yii::t('Frontend', 'Minted by address on Mainnet'), 
			'minted_by_address_testnet' => Yii::t('Frontend', 'Minted by address on Testnet'), 
			'date_minted_on_mainnet' => Yii::t('Frontend', 'Date minted on Mainnet'), 
			'date_minted_on_testnet' => Yii::t('Frontend', 'Date minted on Testnet'), 
			'burned_on_mainnet' => Yii::t('Frontend', 'Burned on Mainnet'), 
			'burned_on_testnet' => Yii::t('Frontend', 'Burned on Testnet'), 
			'date_burned_on_mainnet' => Yii::t('Frontend', 'Date burned on Mainnet'), 
			'date_burned_on_testnet' => Yii::t('Frontend', 'Date burned on Testnet'),
        ];
    }
	
	/**
     * update
     */
    public function create()
    {
		if (!$this->validate()) {
			return false;
		}

		$certificate = new Certificate;
		$certificate->setAttributes($this->attributes, false);

		if ($certificate->save()) {
			$this->id_certificate = $certificate->id_certificate;
			return true;
		}

        return false;
    }

    /**
     * update
     */
    public function update()
    {
		if (!$this->validate()) {
			return false;
		}

		$certificate = self::findCertificate($this->id_certificate);
		$certificate->setAttributes($this->attributes, false);

		if ($certificate->save()) {
			return true;
		}

        return false;
    }
	
	/**
	 * findClient()
	 */
	public static function findCertificate($id)
	{
		return Certificate::findCertificate($id);
	}

	public static function findClientCertificate($id)
	{
		$id = (int) $id;
		$modelProfile = Profile::findClient();
		
		if (empty($modelProfile)) {
			$id_client = Yii::$app->user->identity->id;
		} else {
			$id_client = $modelProfile->id;
		}

		return  Certificate::find()->where(
			'id_certificate = :id_certificate AND id_client = :id_client AND deleted = 0',
			[
				'id_certificate'=> $id,
				'id_client' => $id_client
			]
		)->one();
	}
		

	public function getRenderCertificateData($type = '', $modelProfile) {

		if (empty($type)) {
			return $this->getRenderNoTokenData($modelProfile);
		}

		if (!empty($modelProfile->is_mainnet) && !empty($this->minted_on_mainnet)) {
			return $this->getRenderDataForMintedCertificate(self::MAINNET, $modelProfile);
		}
		
		if (!empty($modelProfile->is_mainnet) && empty($this->minted_on_mainnet)) {
			return $this->getRenderDataForMinting(self::MAINNET, $modelProfile);
		}
		
		if (empty($modelProfile->is_mainnet) && !empty($this->minted_on_testnet)) {
			return $this->getRenderDataForMintedCertificate(self::TESTNET, $modelProfile);
		}

		if (empty($modelProfile->is_mainnet) && empty($this->minted_on_testnet)) {
			return $this->getRenderDataForMinting(self::TESTNET, $modelProfile);
		}

		return $this->getRenderNoTokenData($modelProfile);
	}

	public function getRenderNoTokenData($modelProfile) {

		$html = '';
		$title = Yii::t("Frontend", "Mint certificate on BNB Testnet");
		$text = Yii::t('Frontend', 'You need to deploy token first from profile page!');
		
		if (!empty($modelProfile->is_mainnet)) {
			$title = Yii::t("Frontend", "Mint certificate on BNB Mainnet");
		}
		

		$html = '
			<div class="rounded-md shadow">
				<div class="p-4 border-bottom">
					<h5 class="mb-2">' . $title . '</h5>
				</div>

				<div class="p-4">
					<div class="mt-4">
						<div class="alert alert-warning">
							' . $text . '
						</div>
					</div><!--end col-->
				</div>
			</div>
		';

		return $html;
	}

	public function getRenderData() {
		$modelProfile = Profile::findClient();

		if (!empty($modelProfile->deployed_to_mainnet) && !empty($modelProfile->is_mainnet)) {
			return $this->getRenderCertificateData(self::MAINNET, $modelProfile);
		}
		if (!empty($modelProfile->deployed_to_testnet) && empty($modelProfile->is_mainnet)) {
			return $this->getRenderCertificateData(self::TESTNET, $modelProfile);
		}
		return $this->getRenderNoTokenData($modelProfile);
	}


	public function getRenderDataForMintedCertificate($type, $modelProfile) {

		$school_nft_address = ''; 
		$owner_address = Yii::t('Frontend', 'Owner address is %s'); 

		if ($type == self::MAINNET) {
			
			$id_nft_token = $this->id_nft_token_mainnet;
			$title = Yii::t("Frontend", "Token on BNB Mainnet");
			$href_to_contract = SchoolToken::MAINNET_BSCSCAN_ADDRESS . $modelProfile->school_nft_address_mainnet;
			$href_to_token = $href_to_contract . '?a=' . $id_nft_token;
			$school_nft_address = $modelProfile->school_nft_address_mainnet;
			$owner_address = sprintf($owner_address, $modelProfile->owner_nft_address_mainnet);
			
			
		} else if ($type == self::TESTNET) {
			
			$id_nft_token = $this->id_nft_token_testnet;
			$title = Yii::t("Frontend", "Token on BNB Testnet");
			$href_to_contract = SchoolToken::TESTNET_BSCSCAN_ADDRESS . $modelProfile->school_nft_address_testnet;
			$href_to_token = $href_to_contract . '?a=' . $id_nft_token;
			$school_nft_address = $modelProfile->school_nft_address_testnet;
			$owner_address = sprintf($owner_address, $modelProfile->owner_nft_address_testnet);
		
		} else {
			return $this->getRenderNoTokenData($modelProfile);
		}

		$html = '
			<div class="rounded-md shadow">
				<div class="p-4 border-bottom">
					<h5 class="mb-2">' . $title . '</h5>
					<p class="mb-0">' . $owner_address . '</p>
				</div>
				<div class="p-4">
					<div class="">
						<div class="input-group">
							<span class="input-group-text">'.Yii::t('Frontend', 'Token').'</span>
							<input type="text" value="' . $school_nft_address . '" disabled class="form-control bg-white""/>
							<a href="' . $href_to_contract . '" target="_blank" id="bsccan" class="btn btn-info btn-outline-secondary" type="button" title="' . Yii::t('Frontend', 'Token address') . '">
								<i class="fa fa-eye" aria-hidden="true"></i>
							</a>
							<div class="mx-2 middle" style="width:25px;"></div>
							<span class="input-group-text">'.Yii::t('Frontend', 'Token Id').'</span>
							<input type="text" value="' . $id_nft_token . '" disabled class="form-control bg-white"" style="max-width:80px;"/>
							<a href="' . $href_to_token . '" target="_blank" id="bsccan" class="btn btn-info btn-outline-secondary" type="button" title="' . Yii::t('Frontend', 'Token address') . '">
								<i class="fa fa-eye" aria-hidden="true"></i>
							</a>
						</div>
					</div><!--end col-->
				</div>
			</div>
		';
		return $html;
		

	}

	public function getRenderDataForMinting($type, $modelProfile) {

		$school_nft_address = ''; 
		$owner_address = Yii::t('Frontend', 'Owner address is %s'); 
		$buttonText = Yii::t("Frontend", "Mint certificate"); 

		if ($type == self::MAINNET) {
			$title = Yii::t("Frontend", "Mint certificate on BNB Mainnet");
			$leadText = Yii::t("Frontend", "Mint certificate for your student. Mint price is 0.0003 BNB + gas fee");
			$school_nft_address = $modelProfile->school_nft_address_mainnet;
			$owner_address = sprintf($owner_address, $modelProfile->owner_nft_address_mainnet);
		}
		
		if ($type == self::TESTNET) {
			$title = Yii::t("Frontend", "Mint certificate on BNB Testnet");
			$leadText = Yii::t("Frontend", "Mint certificate for your student. Mint price is 0.0003 TBNB + gas fee");
			$school_nft_address = $modelProfile->school_nft_address_testnet;
			$owner_address = sprintf($owner_address, $modelProfile->owner_nft_address_testnet);
		}

		$html = '
			<div class="rounded-md shadow">
				<div class="p-4 border-bottom">
					<h5 class="mb-2">' . $title . '</h5>
					<p class="mb-0">' . $owner_address . '</p>
				</div>

				<div class="p-4">
					<h6 class="mb-0">' . $leadText . '</h6>
					<div class="mt-4">
						<button id="mintToken" class="btn btn-primary" data-address="'. $school_nft_address .'">
							<span id="mintTokenSpinner" class="spinner-grow spinner-grow-sm me-2" style="display:none;" role="status" aria-hidden="true"></span>
							' . $buttonText . '
						</button>
					</div><!--end col-->
				</div>
			</div>
		';

		return $html;
	}

	public function getCertificateMetaUrl() {
		$modelProfile = Profile::findClient();
		
		if (empty($modelProfile)) {
			return '';
		}

		if (!empty($modelProfile->is_mainnet)) {
			$type = self::MAINNET;
		} else {
			$type = self::TESTNET;
		}

		return Url::to(['/public/meta', 'id' => $this->id_certificate, 'hash' => Certificate::getHashCertificate($modelProfile->id, $this->id_certificate), 'type' => $type], 'https');
	}
	
	public function getCertificateImageUrl() {
		return Url::to(['/certificate', 'id' => $this->id_certificate, 'hash'=>Certificate::getHashCertificate($this->id_client, $this->id_certificate)], 'https');
	}

	public function getMetaData($type = '') {
		
		if (!empty($type == self::MAINNET) && !empty($this->minted_on_mainnet)) {
			$date = $this->date_minted_on_mainnet;
			$id_nft_token = $this->id_nft_token_mainnet;
		} else if ($type == self::TESTNET && !empty($this->minted_on_testnet)) {
			$date = $this->date_minted_on_testnet;
			$id_nft_token = $this->id_nft_token_testnet;
		} else {
			return [];
		}

		$description = sprintf(Yii::t('Frontend', 'Certificate ID = %s. Created at %s'), $this->id_certificate, $date); 

		return [
            "description" => $description, 
            "image" => $this->getCertificateImageUrl(), 
            "name" => $this->course,
            "tokenId" => $id_nft_token,
            "certificateId" => $this->id_certificate,
        ];
	}

	public function getSchoolName() {
		$modelProfile = Clients::find()->where('id = :id', ['id'=>$this->id_client])->one();
		if (empty($modelProfile)) {
			return '';
		}
		return $modelProfile->identify_name;
	}

	public function processSaveMint() {
		
		$modelProfile = Profile::findClient();

		$minted_by_address = SchoolToken::clearText($_POST['mintedby'] ?? '');
		$id_nft_token = (int) SchoolToken::clearText($_POST['tokenId'] ?? '');		

		if (empty($minted_by_address)) {
			exit(json_encode(['error' => 1, 'message' => 'Wrong minted_by_address']));
		}
		
		if (!empty($modelProfile->is_mainnet)) {

			$this->id_nft_token_mainnet = $id_nft_token;
			
			if (!empty($this->minted_on_mainnet)) {
				exit(json_encode(['error' => 1, 'message' => 'Already minted on mainnet']));
			}
			
			$this->minted_on_mainnet = 1;
			$this->date_minted_on_mainnet = date("Y-m-d H:i:s");
			$this->minted_by_address_mainnet = $minted_by_address;
			$this->minted_by_contract_mainnet = $modelProfile->school_nft_address_mainnet;
			
		} else {

			$this->id_nft_token_testnet = $id_nft_token;
			
			if (!empty($this->minted_on_testnet)) {
				exit(json_encode(['error' => 1, 'message' => 'Already minted on testnet']));
			}

			$this->minted_on_testnet = 1;
			$this->date_minted_on_testnet = date("Y-m-d H:i:s");
			$this->minted_by_address_testnet = $minted_by_address;
			$this->minted_by_contract_testnet = $modelProfile->school_nft_address_testnet;

		}
		
		if ($this->update()) {
			exit(json_encode(['error' => 0, 'message' => 'Saved']));
		} 
		
		exit(json_encode(['error' => 1, 'message' => 'Save failed']));
	}
}
