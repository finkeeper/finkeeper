<?php

namespace backend\modules\tools\modules\chatbot\controllers;

use Yii;
use common\models\Chatbot;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use backend\modules\tools\modules\chatbot\components\ChatbotController;


/**
 * Default controller for the `service` module
 */
class DefaultController extends ChatbotController
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
		
		$model = new Chatbot;
		
		return $this->render('view', [
			'model' => $model,
		]);
    }

}
