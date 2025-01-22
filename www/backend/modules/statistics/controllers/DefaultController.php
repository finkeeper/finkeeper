<?php

namespace backend\modules\statistics\controllers;

use Yii;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use app\modules\statistics\components\StatisticsController;

/**
 * Default controller for the `service` module
 */
class DefaultController extends StatisticsController
{		
	/**
     * @init
     */
	public function init()
    {
		parent::init();
		throw new HttpException(404 , Yii::t('Error', '404'));
    }
	
	/**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        throw new HttpException(404 , Yii::t('Error', '404'));
    }
}
