<?php

namespace backend\modules\editors\modules\pages\controllers;

use Yii;

use yii\data\Pagination;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;


use backend\modules\editors\modules\pages\models\PageEditor;
use backend\modules\editors\modules\pages\components\PagesController;
/*

use Imagine\Gd;
use Imagine\Image\Box;
use yii\imagine\Image;

use yii\web\UploadedFile;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\components\GetFile;
*/
//



/**
 * Default controller for the `settings` module
 */
class DefaultController extends PagesController
{		
	/**
     * @init
     */
	public function init()
    {
		parent::init();
		
		$this->getView()->theme = Yii::createObject([
			'class' => '\yii\base\Theme',
			'basePath' => '@app/themes/adminlte3',
			'baseUrl' => '@app/themes/adminlte3/web',
			'pathMap' => ['@app/views' => '@app/themes/adminlte3/views'],
		]);
    }
	
	/**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
		throw new HttpException(403 , Yii::t('Error', '403'));
    }
	
	/**
     * actionView()
     */
    public function actionView()
    {
		if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
		
		$model = new PageEditor;

		return $this->render('view', [
			'model' => $model,
		]);
    }

	/**
     * actionUpdate()
     */
    public function actionUpdate($id)
    {
		if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
		
		$model = new PageEditor;
		$page = $this->loadModel($id);
		$model->setAttributes($page->attributes);

		if ($model->load(Yii::$app->request->post())) {
			
			if ($model->update()) {
				
				Yii::$app->session->setFlash('success', Yii::t('Backend', 'Save Success'));			
				return Yii::$app->response->redirect(['/editors/pages/update?id='.$model->id_page]);
				
			} else {
				
				$model->addError('Backend', Yii::t('Error', 'ErrorSave'));
				
			}
			
		}

		return $this->render('update', [
            'model' => $model,
        ]);
    }
	
	/**
     * actionUpdate()
     */
    public function actionUpload()
    {
		$results = [
			'src' => 'https://'.Yii::$app->params['site'].'/upload/noimage.png',
			'message' => Yii::t('Backend', 'Image Upload'),
		];
		
		//$results[''] = 
		
		//error_log(serialize($_FILES)."\r\n".PHP_EOL, 3, dirname(__FILE__).'/log.log');
		
		//dirname(dirname(getcwd()))

		return $results['src'];
		
		
		
		
		
		
		
	}
	
	/**
     * actionUpdate()
     */
    public function actionBrowse()
    {
		$results = [
			'src' => 'https://'.Yii::$app->params['site'].'/upload/noimage.png',
			'message' => Yii::t('Backend', 'Image Upload'),
		];
		
		//$results[''] = 
		
		//error_log(serialize($_FILES)."\r\n".PHP_EOL, 3, dirname(__FILE__).'/log.log');
		
		//dirname(dirname(getcwd()))

		return $results['src'];
		
		
		
		
		
		
		
	}
	
	/**
     * loadModel
     */
    public function loadModel($id)
    {
		$model = PageEditor::findPage($id);

		if ($model==null) {
			throw new HttpException(500 , Yii::t('Error', '500'));
		}
		
		return $model;
    }
}

