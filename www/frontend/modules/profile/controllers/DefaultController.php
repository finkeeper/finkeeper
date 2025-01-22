<?php

namespace app\modules\profile\controllers;

use Yii;
use Imagine\Image\Box;
use yii\imagine\Image;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Imagine\Image\BoxInterface;
use yii\web\NotFoundHttpException;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use app\modules\profile\models\Profile;
use app\modules\profile\components\ProfileController;
use yii\web\Cookie;

use frontend\components\SchoolToken;

use frontend\components\TranslateHelper;


/**
 * Default controller for the `service` module
 */
class DefaultController extends ProfileController
{		
	public $image_width = 400;
	public $image_height = 400;
	public $banner_width = 600;
	public $banner_height = 300;
	
	/**
     * @init
     */
	public function init()
    {
		$this->getView()->theme = Yii::createObject([
			'class' => '\yii\base\Theme',
			'basePath' => '@app/themes/th1',
			'baseUrl' => '@app/themes/th1/web',
			'pathMap' => ['@app/views' => '@app/themes/th1/views'],
		]);
		
		if (isset($_GET['lang']) && !empty($_GET['lang'])) {
			
			Yii::$app->language = strtolower($_GET['lang']).'-'.strtoupper($_GET['lang']);
			Yii::$app->response->cookies->add(new Cookie([
				'name' => 'lang',
				'value' => $_GET['lang'],
				'expire' => time() + (365 * 24 * 60),
			]));
			
			Yii::$app->session->set('lang', $_GET['lang']); 
			
		} else {
			
			$lang = Yii::$app->session->get('lang'); 
			if (!empty($lang)) {
				Yii::$app->language = strtolower($lang).'-'.strtoupper($lang);
			}
		}

		parent::init();
    }
	
	/**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        throw new HttpException(404 , Yii::t('Error', '404'));
    }	
	/**
     * actionView()
     */
    public function actionView()
    {
		if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
		
		$model = new Profile;
		$client = $this->loadModel();
		$model->setAttributes($client->attributes, false);

		$title = Yii::t('Menu', 'View profile | CTFN — NFT-certificates for educational organizations');
		$bigTitle =  Yii::t('Profile', 'View profile');
		$smallTitle = Yii::t('Profile', 'View your profile');

		return $this->render('view', [
			'model' => $model,
			'title' => $title,
			'bigTitle' => $bigTitle,
			'smallTitle' => $smallTitle,
		]);
    }
	
	/**
     * Renders the index view for the module
     * @return string
     */
    public function actionUpdate()
    {
		if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

		$model = new Profile;
		$client = $this->loadModel();
		$model->setAttributes($client->attributes, false);
		
		if ($model->load(Yii::$app->request->post())) {
			
			if ($model->update()) {
				
				Yii::$app->session->setFlash('success', Yii::t('Frontend', 'Update Profile Success'));			
				return Yii::$app->response->redirect(['/profile/update']);
				
			} else {
				
				$model->addError('Frontend', Yii::t('Error', 'Update Profile Error'));
				
			}
			
		}

		$title = Yii::t('Menu', 'Edit profile | CTFN — NFT-certificates for educational organizations');
		$bigTitle =  Yii::t('Profile', 'Edit profile');
		$smallTitle = Yii::t('Profile', 'Edit your profile');
		
		return $this->render('update', [
			'model' => $model,
			'title' => $title,
			'bigTitle' => $bigTitle,
			'smallTitle' => $smallTitle,
		]);
    }


	public function actionSave()
    {
		if (Yii::$app->user->isGuest) {
            throw new HttpException(404 , Yii::t('Error', '404'));
        }

		$model = new Profile;
		$client = $this->loadModel();
		$model->setAttributes($client->attributes, false);
		
		$owner_nft_address = SchoolToken::clearText($_POST['createdBy'] ?? '');
		$school_nft_address = SchoolToken::clearText($_POST['schoolAddress'] ?? '');

		if (empty($owner_nft_address) || empty($school_nft_address)) {
			exit(json_encode(['error' => 1, 'message' => 'Wrong address']));
		}

		if ($model->is_mainnet) {
			$model->owner_nft_address_mainnet = $owner_nft_address;
			$model->school_nft_address_mainnet = $school_nft_address;
			$model->deployed_to_mainnet = 1;
		} else {
			$model->owner_nft_address_testnet = $owner_nft_address;
			$model->school_nft_address_testnet = $school_nft_address;
			$model->deployed_to_testnet = 1;
		}

		if ($model->update()) {
			exit(json_encode(['error' => 0, 'message' => 'Saved']));
		} 
		
		exit(json_encode(['error' => 1, 'message' => 'Save failed']));
    }

	public function actionReloadtoken() {
		if (Yii::$app->user->isGuest) {
            throw new HttpException(404 , Yii::t('Error', '404'));
        }
		$model = new Profile;
		$client = $this->loadModel();
		$model->setAttributes($client->attributes, false);
		exit($model->getSchoolTokenData());
	}
	
	/**
     * Renders the index view for the module
     * @return string
     */
    public function actionUpload()
    {
		if (Yii::$app->request->isAjax) {
		
			if (empty($_FILES) || empty($_FILES['file'])) {
				exit(json_encode([
					'error'=>1, 
					'message'=>Yii::t('Error', 'Server Not File'),
				]));
			}
			
			$file = $_FILES['file'];

			if (!preg_match('/image\/(png|gif|jpg|jpeg)/i', $file['type'])) {
				exit(json_encode([
					'error'=>1, 
					'message'=>Yii::t('Error', 'Format Incorrect'),
				]));
			}

			$client = $this->loadModel();
			if (empty($client)) {
				exit(json_encode([
					'error'=>1, 
					'message'=>Yii::t('Error', 'Missing Client'),
				]));
			}
			
			$type = str_replace('image/', '', $file['type']);
			$path = getcwd().'/upload/client/'.md5($client->id).'/';
			if (!is_dir($path)) {
				mkdir($path, 0755);
			}
			
			if (!is_dir($path)) {
				if (!mkdir($path, 0755)) {
					exit(json_encode([
						'error'=>1, 
						'message'=>Yii::t('Error', 'Failed to create personal folder in boot directory'),
					]));
				}
			}

			if(Yii::$app->request->post('type')==1) {
				
				$client->image = 'ip_'.time().'.'.$type;
				
				$imagine = Image::getImagine();
				$imagine = $imagine->open($file['tmp_name']);
				$sizes = getimagesize($file['tmp_name']);

				if ($sizes[0]<$this->image_width || $sizes[1]<$this->image_height) {
					exit(json_encode([
						'error'=>1, 
						'message'=>Yii::t('Error', 'Format Incorrect'),
					]));
				}
				
				$height = round($sizes[1]*$this->image_width/$sizes[0]);        
				$imagine->resize(new Box($this->image_width, $height))
					->save($path.$client->image, ['quality' => 90]);
				
				if (!$client->save()) {
					exit(json_encode([
						'error'=>1, 
						'message'=>Yii::t('Error', 'Failed to save image'),
					]));
				}
				
				exit(json_encode([
					'error'=>0, 
					'message'=>Yii::t('Frontend', 'Image uploaded successfully'),
				]));
				
				
			} else if (Yii::$app->request->post('type')==2) {
				
				$client->school_logo = 'bp_'.time().'.'.$type;
				
				$imagine = Image::getImagine();
				$imagine = $imagine->open($file['tmp_name']);
				$sizes = getimagesize($file['tmp_name']);

				if ($sizes[0]<$this->banner_width || $sizes[1]<$this->banner_height) {
					exit(json_encode([
						'error'=>1, 
						'message'=>Yii::t('Error', 'Format Banner Incorrect'),
					]));
				}
   
				Image::crop($file['tmp_name'], $this->banner_width, $this->banner_height)
					->save($path.$client->school_logo, ['quality' => 90]);
				
				if (!$client->save()) {
					exit(json_encode([
						'error'=>1, 
						'message'=>Yii::t('Error', 'Failed to save image'),
					]));
				}
				
				exit(json_encode([
					'error'=>0, 
					'message'=>Yii::t('Frontend', 'Image uploaded successfully'),
				]));
				
			} else {
				
				throw new HttpException(404 , Yii::t('Error', '404'));
			}

		} else {
			throw new HttpException(404 , Yii::t('Error', '404'));
		}
	}

	/**
     * loadModel
     */
    public function loadModel()
    {
		$model = Profile::findClient();

		if ($model==null) {
			throw new HttpException(500 , Yii::t('Error', '500'));
		}
		
		return $model;
    }
}
