<?php

namespace app\modules\profile\models;

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
class Profile extends Model
{
	public $id;
	public $email;
	public $web_site;
	public $identify_name;
	public $name;
	public $surname;
	public $password;
	public $confirm_password;
	public $image;
	public $school_logo;
	public $employee;
	public $is_mainnet;
	public $owner_nft_address_testnet;
	public $school_nft_address_testnet;
	public $owner_nft_address_mainnet;
	public $school_nft_address_mainnet;
	public $deployed_to_mainnet;
	public $deployed_to_testnet;


	// public $owner_nft_address;
	// public $school_nft_address;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['id', 'integer', 'integerOnly' => true, 'min' => 0],
            ['is_mainnet', 'integer', 'integerOnly' => true, 'min' => 0, 'max'=>1],
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
			'employee' => Yii::t('Frontend', 'Description'),
			'web_site' => Yii::t('Frontend', 'Website'),
			'email' => Yii::t('Frontend', 'Email'),
			'password' => Yii::t('Form', 'Password'),
			'confirm_password' => Yii::t('Form', 'Password Confirm Form'),
			'image' => Yii::t('Form', 'Image'),
			'school_logo' => Yii::t('Form', 'Logo'),
			'is_mainnet' => Yii::t('Frontend', 'Mainnet'),
			'school_nft_address_testnet' => Yii::t('Frontend', 'Token address'),
			'owner_nft_address_testnet' => Yii::t('Frontend', 'Owner address'),
			'deployed_to_mainnet' => Yii::t('Frontend', 'Deployed to Mainnet'),
			'deployed_to_testnet' => Yii::t('Frontend', 'Deployed to Testnet'),
        ];
    }
	
	/**
	 * validatePassLevel($attribute, $params)
	 */
	public function validatePassLevel($attribute, $params)
    {
		$this->validatePassword();
    }

    /**
     * update
     */
    public function update()
    {
		if (!$this->validate()) {
			return false;
		}

		if (!empty($this->password)) {

			if ($this->password!=$this->confirm_password) {
				$this->addError('password', Yii::t('Error', 'Passwords Not Match'));
				Yii::$app->session->setFlash('error', Yii::t('Error', 'Passwords Not Match'));
				return false;
			}

			if (!$this->validatePassword()) {
				return false;
			}
		}
		
		$client = self::findClient();
		$client->setAttributes($this->attributes, false);

		$client->setPassword($this->password);
		$client->getHttpToken($this->password);
		$client->generatePasswordResetToken();

		if ($client->save()) {
			return true;
		}
		
		

        return false;
    }
	
	/**
	 * findClient()
	 */
	public static function findClient()
	{
		return Clients::findUserId();
	}
	
	/**
	 * validatePassLevel($attribute, $params)
	 */
	public function validatePassword()
    {
        $len = strlen($this->password);
		if ($len<Yii::$app->params['user.passwordMinLength']) {
			$this->addError('password', Yii::t('Error', 'Passw Short', ['count' => Yii::$app->params['user.passwordMinLength']]));
			Yii::$app->session->setFlash('error', Yii::t('Error', 'Passw Short', ['count' => Yii::$app->params['user.passwordMinLength']]));
			return false;
		}
		
		if (!preg_match('/[0-9]/', $this->password)) {
			$this->addError('password', Yii::t('Error', 'Passw Weak'));
			Yii::$app->session->setFlash('error', Yii::t('Error', 'Passw Weak'));
			return false;
		}
		
		if (!preg_match('/[a-z]/', $this->password)) {
			$this->addError('password', Yii::t('Error', 'Passw Weak'));
			Yii::$app->session->setFlash('error', Yii::t('Error', 'Passw Weak'));
			return false;
		}
		
		return true;
    }

	public function getTokenData($type = '') {

		$school_nft_address = ''; 

		if ($type == CertificateWork::MAINNET) {
			$title = Yii::t("Frontend", "Token on BNB Mainnet");
			$href = SchoolToken::MAINNET_BSCSCAN_ADDRESS . $this->school_nft_address_mainnet;
			$school_nft_address = $this->school_nft_address_mainnet;
		} else if ($type == CertificateWork::TESTNET) {
			$title = Yii::t("Frontend", "Token on BNB Testnet");
			$href = SchoolToken::TESTNET_BSCSCAN_ADDRESS . $this->school_nft_address_testnet;
			$school_nft_address = $this->school_nft_address_testnet;
		} else {
			return $this->getDefaultSchoolTokenData();
		}

		$html = '
			<div class="rounded-md shadow mt-4">
				<div class="p-4 border-bottom">
					<h5 class="mb-0">' . $title . '</h5>
				</div>
				<div class="p-4">
					<div class="">
						<div class="input-group">
							<input type="text" value="' . $school_nft_address . '" disabled class="form-control"/>
							<a href="' . $href . '" target="_blank" id="bsccan" class="btn btn-info btn-outline-secondary" type="button" title="' . Yii::t('Frontend', 'Token address') . '">
								<i class="fa fa-eye" aria-hidden="true"></i>
							</a>
						</div>
					</div><!--end col-->
				</div>
			</div>
		';
		return $html;
	}

	public function getDefaultSchoolTokenData() {

		$html = '';
		$title = Yii::t("Frontend", "Create token on BNB Testnet");
		$leadText = sprintf(Yii::t('Frontend', 'To issue a certificate, you need to create a token for your organization. To create a token, click the "Create token" button. Price: %s TBNB + gas fee'), '0.01');
		$buttonText = Yii::t('Frontend', 'Create token');
		
		if ($this->is_mainnet) {
			$title = Yii::t("Frontend", "Create token on BNB Mainnet");
			$leadText = sprintf(Yii::t('Frontend', 'To issue a certificate, you need to create a token for your organization. To create a token, click the "Create token" button. Price: %s BNB + gas fee'), '0.01');
		}

		$html = '
			<div class="rounded-md shadow mt-4">
				<div class="p-4 border-bottom">
					<h5 class="mb-0">' . $title . '</h5>
				</div>

				<div class="p-4">
					<h6 class="mb-0">' . $leadText . '</h6>
					<div class="mt-4">
						<button id="createToken" class="btn btn-primary">
							<span id="createTokenSpinner" class="spinner-grow spinner-grow-sm me-2" style="display:none;" role="status" aria-hidden="true"></span>
							' . $buttonText . '
						</button>
					</div><!--end col-->
				</div>
			</div>
		';

		return $html;
	}

	public function getSchoolTokenData() {
		if (!empty($this->deployed_to_mainnet) && !empty($this->is_mainnet)) {
			return $this->getTokenData(CertificateWork::MAINNET);
		}
		if (!empty($this->deployed_to_testnet) && empty($this->is_mainnet)) {
			return $this->getTokenData(CertificateWork::TESTNET);
		}
		return $this->getDefaultSchoolTokenData();
	}

	public static function renderCertificates() {

		$modelProfile = self::findClient();

		if (empty($modelProfile)) {
			print("
				<div>
				".Yii::t('Frontend', 'There is no profile')."
				</div>
			");
			return;
		}

		$modelCertificates = Certificate::find()->where('id_client = :id_client AND deleted = 0', ['id_client'=> $modelProfile->id])->orderBy('id_certificate DESC')->all();

		if (empty($modelCertificates)) {
			print("
				<div>
					".Yii::t('Frontend', 'There is no certificates')."
				</div>
			");
			return;
		}

		$html = '';
		foreach($modelCertificates as $model) {

			$href_to_token_mainnet = '<a href="javascript:void(0)" class="icon cert-icon cert-icon-disable" title="'.Yii::t('Frontend', 'Mainnet').'" data-bs-toggle="tooltip" data-bs-placement="top">M<i class="fa fa-wifi"></i></a>';
			
			$href_to_token_testnet = '<a href="javascript:void(0)" class="icon cert-icon cert-icon-disable" title="'.Yii::t('Frontend', 'Testnet').'" data-bs-toggle="tooltip" data-bs-placement="top">T<i class="fa fa-wifi"></i></a>';
			
			$hash = Certificate::getHashCertificate($model->id_client, $model->id_certificate);

			if (!empty($model->minted_on_mainnet)) {
				$href_to_token_mainnet = '<a href="'.SchoolToken::MAINNET_BSCSCAN_ADDRESS . $model->minted_by_contract_mainnet . '?a=' . $model->id_nft_token_mainnet.'" class="icon cert-icon cert-icon" title="'.Yii::t('Frontend', 'Mainnet').'" data-bs-toggle="tooltip" data-bs-placement="top" target="_blank">M<i class="fa fa-wifi"></i></a>';
			}
		
			if (!empty($model->minted_on_testnet)) {
				$href_to_token_testnet = '<a href="'.SchoolToken::TESTNET_BSCSCAN_ADDRESS . $model->minted_by_contract_testnet . '?a=' . $model->id_nft_token_testnet.'" class="icon cert-icon cert-icon" title="'.Yii::t('Frontend', 'Testnet').'" data-bs-toggle="tooltip" data-bs-placement="top" target="_blank">T<i class="fa fa-wifi"></i></a>';
			}
			
			$name = trim($model->name.' '.$model->surname);
			$course = trim($model->course);
			$date = date('d.m.Y', strtotime($model->creation_date));

			$html .= '
			<div class="col">
				<div class="card nft-items nft-primary rounded-md shadow overflow-hidden mb-1 p-3">
					<div class="d-flex justify-content-between">
						<div class="img-group">
							<a href="/certificate?id='.$model->id_certificate.'&hash='.$hash.'" class="user-avatar" title="'.Yii::t('Frontend', 'Certificate PNG').'" data-bs-toggle="tooltip" data-bs-placement="top" target="_blank">
								<i class="fa fa-file-image-o avatar avatar-sm-sm cert-icon" aria-hidden="true"></i>
							</a>
							<!--<a href="/certificate?id='.$model->id_certificate.'&hash='.$hash.'&type=pdf" class="user-avatar ms-n3" title="'.Yii::t('Frontend', 'Certificate PDF').'" data-bs-toggle="tooltip" data-bs-placement="top" target="_blank">
								<i class="fa fa-file-pdf-o avatar avatar-sm-sm cert-icon" aria-hidden="true"></i>
							</a>-->
							<a href="/certificate/update?id='.$model->id_certificate.'" class="user-avatar ms-n3" title="'.Yii::t('Frontend', 'Edit certificate').'" data-bs-toggle="tooltip" data-bs-placement="top" target="_blank">
								<i class="fa fa-pencil-square-o avatar avatar-sm-sm cert-icon" aria-hidden="true"></i>
							</a>
						</div>					
						<span>
							'.$href_to_token_mainnet.'
							'.$href_to_token_testnet.'
						</span>
					</div> 
					<div class="nft-image rounded-md position-relative overflow-hidden cert-block-text">
						<a class="img-link" href="/certificate?id='.$model->id_certificate.'&hash='.$hash.'" target="_blank"><img src="/upload/certificate/nf_certificate.png" class="img-fluid" alt="">
							<div class="position-absolute cert-pos pos-1">
								<div class="text-center">№ '.$model->id_certificate.'</div>
							</div>
							<div class="position-absolute cert-pos pos-2">
								<div class="text-center">'.$name.'</div>
							</div>
							<div class="position-absolute cert-pos pos-4">
								<div class="text-center">'.$course.'</div>
							</div>
							<div class="position-absolute pos-5">
								'.$date.'
							</div>
						</a>
					</div>
					<div class="card-body content position-relative p-0">
						<div class="justify-content-between mt-2">
							<div class="text-dark small">'.$name.'</div>
							<div class="text-dark small">'.$course.'</div>
						</div>
					</div>
				</div>
			</div>';
		}
		
		echo $html;
	}
}
