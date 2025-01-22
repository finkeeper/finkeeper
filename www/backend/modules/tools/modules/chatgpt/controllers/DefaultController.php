<?php

namespace backend\modules\tools\modules\chatgpt\controllers;

use Yii;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use backend\modules\tools\modules\chatgpt\models\Gptchat;
use backend\modules\tools\modules\chatgpt\components\ChatgptController;


/**
 * Default controller for the `service` module
 */
class DefaultController extends ChatgptController
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
    public function actionUpdate() 
    {
		if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
		
		$model1 = new Gptchat;
		$gpt1 = $this->loadModel(1);
		$model1->setAttributes($gpt1->attributes);
		
		$model2 = new Gptchat;
		$gpt2 = $this->loadModel(2);
		$model2->setAttributes($gpt2->attributes);
		
		if (Yii::$app->request->post()) {
			
			$id = (int) Yii::$app->request->post('Gptchat')['id'];
			if ($id==1) {
				
				if ($model1->load(Yii::$app->request->post())) {	

					if ($model1->update(1)) {
						
						Yii::$app->session->setFlash('success', Yii::t('Backend', 'Save Success'));			
						return Yii::$app->response->redirect(['/tools/chatgpt/update']);
						
					} else {
						
						$model1->addError('Backend', Yii::t('Error', 'ErrorSave'));
						
					}
				}
				
			} else if ($id==2) {
				
				if ($model2->load(Yii::$app->request->post())) {	

					if ($model2->update(2)) {
						
						Yii::$app->session->setFlash('success', Yii::t('Backend', 'Save Success'));			
						return Yii::$app->response->redirect(['/tools/chatgpt/update#nav-active']);
						
					} else {
						
						$model2->addError('Backend', Yii::t('Error', 'ErrorSave'));
						
					}
				}
			}
		}

		return $this->render('update', [
			'model1' => $model1,
			'model2' => $model2,
		]);
    }
	
	/**
     * loadModel
     */
    public function loadModel($id=0)
    {
		$model = Gptchat::findGptchat($id);

		if (empty($model)) {
			return new Gptchat;
		}
		
		return $model;
    }

}
