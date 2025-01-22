<?php

namespace app\modules\certificate\controllers;

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

use app\modules\certificate\models\CertificateWork;
use app\modules\profile\models\Profile;
use common\models\Certificate;
use common\models\Clients;

use app\modules\certificate\components\CertificateController;
use frontend\components\SchoolToken;

/**
 * Default controller for the `service` module
 */
class DefaultController extends CertificateController
{		
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
			Yii::$app->response->cookies->add(new \yii\web\Cookie([
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
		
		$model = new CertificateWork;

		$title = Yii::t('Menu', 'View certificate | CTFN — NFT-certificates for educational organizations');
		$bigTitle =  Yii::t('Frontend', 'View certificate');
		$smallTitle = Yii::t('Frontend', 'View your certificate');

		return $this->render('view', [
			'model' => $model,
			'title' => $title,
			'bigTitle' => $bigTitle,
			'smallTitle' => $smallTitle,
		]);
    }
	
	/**
     * actionCreate()
     */
    public function actionCreate()
    {
		if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

		$model = new CertificateWork;
		
		if ($model->load(Yii::$app->request->post())) {
			
			if ($model->create()) {
				
				Yii::$app->session->setFlash('success', Yii::t('Frontend', 'Create Certificate Success'));			
				return Yii::$app->response->redirect(['/certificate/update?id='.$model->id_certificate]);
				
			} else {
				
				$model->addError('Frontend', Yii::t('Error', 'Create Certificate Error'));
				
			}
		}
		
		$title = Yii::t('Menu', 'Create certificate | CTFN — NFT-certificates for educational organizations');
		$bigTitle =  Yii::t('Frontend', 'Create certificate');
		$smallTitle = Yii::t('Frontend', 'Create your certificate');

		return $this->render('create', [
            'model' => $model,
			'title' => $title,
			'bigTitle' => $bigTitle,
			'smallTitle' => $smallTitle,
        ]);
    }
	
	/**
     * actionUpdate($id)
     */
    public function actionUpdate($id)
    {
		if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

		$model = new CertificateWork;

		$certificate = CertificateWork::findClientCertificate($id);
		
		if (is_null($certificate)) {
			throw new HttpException(500 , Yii::t('Error', '500'));
		}

		$model->setAttributes($certificate->attributes, false);
		
		if ($model->load(Yii::$app->request->post())) {
			
			if ($model->update()) {
				
				Yii::$app->session->setFlash('success', Yii::t('Frontend', 'Update Certificate Success'));			
				return Yii::$app->response->redirect(['/certificate/update?id='.$model->id_certificate]);
				
			} else {
				
				$model->addError('Frontend', Yii::t('Error', 'Update Certificate Error'));
				
			}
		}

		$title = Yii::t('Menu', 'Update certificate | CTFN — NFT-certificates for educational organizations');
		$bigTitle =  Yii::t('Frontend', 'Update certificate');
		$smallTitle = Yii::t('Frontend', 'Update your certificate');
		
		return $this->render('update', [
            'model' => $model,
			'title' => $title,
			'bigTitle' => $bigTitle,
			'smallTitle' => $smallTitle,
        ]);
    }
	
	/**
     * loadModel
     */
    public function loadModel($id)
    {
		$model = CertificateWork::findCertificate($id);

		if ($model==null) {
			throw new HttpException(500 , Yii::t('Error', '500'));
		}
		
		return $model;
    }


	public function actionSavemint($id) {
		$id = (int) $id;

		if (Yii::$app->user->isGuest) {
			throw new HttpException(404 , Yii::t('Error', '404'));
		}

		$certificate = CertificateWork::findClientCertificate($id);

		if (is_null($certificate)) {
			exit(json_encode(['error' => 1, 'message' => 'Certificate is not found']));
		}
		
		$model = new CertificateWork;
		$model->setAttributes($certificate->attributes, false);
		

		$model->processSaveMint();
		exit;
	}

	public function actionReloadtoken($id) {
		if (Yii::$app->user->isGuest) {
            throw new HttpException(404 , Yii::t('Error', '404'));
        }
		$model = new CertificateWork;
		$certificate = CertificateWork::findClientCertificate($id);
		if (is_null($certificate)) {
			throw new HttpException(404 , Yii::t('Error', '404'));
		}
		$model->setAttributes($certificate->attributes, false);
		exit($model->getRenderData());
	}
}
