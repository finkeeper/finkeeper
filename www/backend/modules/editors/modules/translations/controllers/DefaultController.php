<?php

namespace backend\modules\editors\modules\translations\controllers;

use Yii;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use backend\components\SettingsView;
use backend\modules\editors\modules\translations\models\Translations;
use backend\modules\editors\modules\translations\components\TranslationsController;

/**
 * Default controller for the `service` module
 */
class DefaultController extends TranslationsController
{		
	/**
     * @init
     */
	public function init()
    {
		$this->getView()->theme = SettingsView::getTheme();
		SettingsView::langSwitcher();
		
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
		
		$model = new Translations;
		if (Yii::$app->request->get()) {
			$model->load(Yii::$app->request->get());
		}

		return $this->render('view', [
           'model' => $model,
        ]);	
    }
	
	/**
     * actionMessages($id)
     */
    public function actionMessages($category='', $language='')
    {
		if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
		
		$model = new Translations;
		$model->category = $category;
		$model->language = $language;
		
		if (Yii::$app->request->get()) {
			$model->load(Yii::$app->request->get());
		}

		return $this->render('messages', [
           'model' => $model,
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
		
		$model = new Translations;
		$model->id = $id;
		$model->loadModel();

		if ($model->load(Yii::$app->request->post())) {
		
			if ($model->save()) {
				
				Yii::$app->session->setFlash('success', Yii::t('EditorsTranslations', 'Save Success'));			
				return Yii::$app->response->redirect(['/editors/translations/update', 'id'=>$model->id]);
				
			} else {
				
				$model->addError('Backend', Yii::t('EditorsTranslations', 'ErrorSave'));
				
			}
		}

		return $this->render('update', [
           'model' => $model,
        ]);	
	}
	
	/**
     * actionCreate($id)
     */
    public function actionCreate($category='')
    {
		if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
		
		$model = new Translations;
		$model->category = $category;
		
		if ($model->load(Yii::$app->request->post())) {
		
			if ($model->save()) {
				
				Yii::$app->session->setFlash('success', Yii::t('EditorsTranslations', 'Save Success'));			
				return Yii::$app->response->redirect(['/editors/translations/update', 'id'=>$model->id]);
				
			} else {
				
				$model->addError('Backend', Yii::t('EditorsTranslations', 'ErrorSave'));
				
			}
		}
		
		return $this->render('create', [
           'model' => $model,
        ]);	
	}
	
	/**
     * actionCreate($id)
     */
    public function actionDelete($id)
    {
		if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
		
		$model = new Translations;
		$model->id = $id;
		$model->deleted = 1;
		$model->deleted_date = date('Y-m-d H:i:s');
		
		if ($model->deleted()) {
				
			Yii::$app->session->setFlash('success', Yii::t('EditorsTranslations', 'Save Deleted'));			
			return Yii::$app->response->redirect(['/editors/translations/view']);
				
		} else {
				
			if (!Yii::$app->session->hasFlash('error')) {
				Yii::$app->session->setFlash('error', Yii::t('EditorsTranslations', 'Error Deleted'));
			}
			
			return Yii::$app->response->redirect(['/editors/translations/update', 'id'=>$model->id]);	
				
		}
	}
}
