<?php
namespace api\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\Controller;
use yii\web\HttpException;
use common\models\Exchange;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\base\InvalidArgumentException;
use api\models\LoadPage;
use api\models\LoadClients;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @init
     */
	public function init() 
	{
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
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['error', 'index'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
		return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
	
	/**
     * {@inheritdoc}
     */
    public function actionIndex()
    {
		if (Yii::$app->language == 'ru-RU') {
			$model = LoadPage::getPage(6);
		} else {
			$model = LoadPage::getPage(7);
		}

		if (empty($model)) {
			throw new NotFoundHttpException();
		}
		
		//Yii::t('Api', 'Number of users').': '.
		$users = LoadClients::countUsers();
		$model->template = str_replace(
			[
				'{count_users}',
			],
			[
				$users,
			],
			$model->template
		);

		return $this->render('index', [
            'model' => $model
        ]);
	}
}
