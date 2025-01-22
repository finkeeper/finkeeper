<?php

namespace backend\modules\tools\modules\currency\controllers;

use Yii;
use common\models\Exchange;
use common\models\CurrencyConfig;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use backend\modules\tools\modules\currency\components\CurrencyController;

/**
 * Default controller for the `service` module
 */
class DefaultController extends CurrencyController
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

		$model = new Exchange;
		
		return $this->render('view', [
			'model' => $model,
		]);
    }
	
	/**
     * actionCreate()
     */
    public function actionCreate()
    {
		if (Yii::$app->request->isAjax) {

			$content = Yii::$app->request->post('value');
			
			if (empty($content) || !is_string($content))  {
				
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Backend', 'Error 101')]));
			}
			
			if (!preg_match('/^[a-z \,]{1,}$/i', $content)) {
				
				exit(json_encode(['error'=>2, 'message'=>Yii::t('Backend', 'Error 102')]));
			}
	
			$array = @explode(',', $content);
			
			if (empty($array) || !is_array($array))  {
				
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Backend', 'Error 103')]));
			}
			
			$data_api = Exchange::getDataCurrency();
			if (empty($data_api) || !is_array($data_api))  {
				
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Backend', 'Error 104')]));
			}
		
			$save_data = [];

			foreach ($array as $symbol) {
	
				if (empty($symbol)) {
					continue;
				}
				
				$key = strtolower(trim($symbol));
				if (!empty($data_api[$key])) {
					$save_data[] = [
						'symbol' => strtoupper($data_api[$key]['symbol']),
						'name' => $data_api[$key]['name'],
					];
				}
			}

			if (empty($save_data) || !is_array($save_data))  {
				
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Backend', 'Error 105')]));
			}
			
			$res = CurrencyConfig::changeConfig($save_data);
			if (empty($res))  {
				
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Backend', 'Error 106')]));
			}
			
			exit(json_encode(['error'=>0, 'message'=>Yii::t('Backend', 'Success cryptocurrency')]));

			
		} else {
			
			throw new NotFoundHttpException();
			
		}	
	}
	
	/**
     * actionUpdate()
     */
    public function actionUpdate($id=0)
    {
		$modelExchange = Exchange::find()->where(['id_crupto' => $id])->one();
		if (empty($modelExchange)) {
			throw new NotFoundHttpException();
		}
	
		$modelCurrencyConfig = CurrencyConfig::find()->where(['id' => $modelExchange->id_config])->one();
		if (empty($modelCurrencyConfig)) {
			throw new NotFoundHttpException();
		}
		
		return $this->render('update', [
			'modelExchange' => $modelExchange,
			'modelCurrencyConfig' => $modelCurrencyConfig,
		]);
	}
	
	/**
     * actionDelete($id)
     */
    public function actionDelete($id)
    {
		$modelExchange = Exchange::find()->where(['id_crupto' => $id])->one();
		if (empty($modelExchange)) {
			return false;
		}
		
		$modelCurrencyConfig = CurrencyConfig::find()->where(['id' => $modelExchange->id_config])->one();
		if (empty($modelCurrencyConfig)) {
			return false;
		}
		
		$modelCurrencyConfig->deleted = 1;
		if ($modelCurrencyConfig->save()) {
			$modelExchange->delete();	
			
			Yii::$app->session->setFlash('success', Yii::t('Backend', 'Deleted Success'));			
				
			return Yii::$app->response->redirect(['/tools/currency/view']);
		}

		Yii::$app->session->setFlash('error', Yii::t('Backend', 'Deleted Error'));	

		return Yii::$app->response->redirect(['/tools/currency/view']);
	}
}
