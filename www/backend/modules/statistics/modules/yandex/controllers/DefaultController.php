<?php

namespace backend\modules\statistics\modules\yandex\controllers;

use Yii;

use yii\data\Pagination;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use backend\modules\statistics\modules\yandex\models\Yandex;
use backend\modules\statistics\modules\yandex\components\YandexController;

/**
 * Default controller for the `settings` module
 */
class DefaultController extends YandexController
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
     * actionUpdate()
     */
    public function actionUpdate()
    {
		if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
		
		$model = new Yandex;
		$settings = $this->loadModel();
		$model->setAttributes($settings->attributes);

		if ($model->load(Yii::$app->request->post())) {
			
			if ($model->update()) {
				
				Yii::$app->session->setFlash('success', Yii::t('Backend', 'Save Success'));			
				return Yii::$app->response->redirect(['/statistics/yandex/update']);
				
			} else {
				
				$model->addError('Backend', Yii::t('Error', 'ErrorSave'));
				
			}
			
		}

		return $this->render('update', [
            'model' => $model,
        ]);
    }
	
	/**
     * loadModel
     */
    public function loadModel()
    {
		$model = Yandex::findSettings();

		if ($model==null) {
			return new Yandex;
		}
		
		return $model;
    }
}

