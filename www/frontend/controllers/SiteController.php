<?php

namespace frontend\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\HttpException;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use common\models\Clients;
use frontend\models\LoginForm;
use frontend\models\ResendEmail;
use frontend\models\PasswordResetConfirm;
use frontend\models\PasswordReset;
use frontend\models\SignupForm;
use frontend\models\ConfirmForm;
use frontend\models\LoadPage;
use frontend\models\LoadClients;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
					[
                        'actions' => [
							'index',
							'signup', 
							'login', 
							'terms',
							'privacy',
							'help',
							'error', 
							'captcha', 
							'confirm', 
							'resendemail', 
							'passwordreset', 
							'passwordresetconfirm',
							'formvalidation',
							'loginsecure',
						],
                        'allow' => true,
						'roles' => ['?'],
                    ],
                    [
                        'actions' => [
							'logoutapp', 
							'index', 
							'error',
							'terms',
							'privacy',
							'help',
						],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'logout' => ['post'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
	
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
     * Displays homepage.
     *
     * @return mixed
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

    /**
     * Displays privacy page.
     *
     * @return mixed
     */
    public function actionPrivacy()
    {
		throw new NotFoundHttpException();
		
		$model = LoadPage::getPage(2);
		
		if (empty($model)) {
			throw new NotFoundHttpException();
		}
		
		return $this->render('privacy', [
            'model' => $model,
        ]);
    }

    /**
     * Displays terms page.
     *
     * @return mixed
     */
    public function actionTerms()
    {
		throw new NotFoundHttpException();
		
		$model = LoadPage::getPage(3);
		
		if (empty($model)) {
			throw new NotFoundHttpException();
		}
		
        return $this->render('terms', [
            'model' => $model,
        ]);
    }
	
	/**
     * Displays help page.
     *
     * @return mixed
     */
    public function actionHelp()
    {
		throw new NotFoundHttpException();
		
		$model = LoadPage::getPage(4);
		
		if (empty($model)) {
			throw new NotFoundHttpException();
		}
		
		return $this->render('help', [
			'model' => $model,
        ]);
    }
	
	/**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        throw new NotFoundHttpException();
		
		if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }
	
	/**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLoginsecure($sc='')
    {
		if (!Yii::$app->user->isGuest) {
			return $this->redirect('/app');
		}
		if (empty($sc)) {
			throw new NotFoundHttpException();
		}

		$model = new LoginForm();
		if (!$model->loginsecure($sc)) {
            new HttpException(401 , Yii::t('Error', 'Unauthorized'));
        }
		
		return $this->redirect('/app');
	}
	
    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogoutapp()
    {
		Yii::$app->user->logout();

        return $this->redirect('/app');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        throw new NotFoundHttpException();
		
		if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
		
		$model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->redirect(['info']);
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }
	
	/**
     * Confirms user.
     *
     * @return mixed
     */
    public function actionConfirm($token)
    {
		throw new NotFoundHttpException();
		
		if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

		if (empty($token) || !is_string($token)) {
			throw new NotFoundHttpException();
		}
		
		$model = new ConfirmForm();
		$clients = Clients::findByRegistrationToken($token);
		
		if (empty($clients->email)) {
			throw new NotFoundHttpException();
		}
		
		$result = [];
        if ($model->confirm($token)) {

			if (Yii::$app->getUser()->login($clients)) { 

				return $this->redirect(['/profile/update']);
					
			} else {

				$result = ['error'=>0, 'message' => Yii::t('Frontend', 'Confirm Registration')];
			
			}

		} else {

			$result = ['error'=>0, 'message' => Yii::t('Error', 'Error Registration')];
		
		}

        return $this->render('confirm', [
            'result' => $result,
        ]);
    }
	
	/**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionPasswordreset()
    {
		throw new NotFoundHttpException();
		
		if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
		
		$model = new PasswordReset();

		if ($model->load(Yii::$app->request->post()) && $model->validate()) {

			if (Clients::isEmail($model->login)) {

				if ($model->sendEmail()) {
					
					Yii::$app->session->setFlash('success', Yii::t('Frontend', 'Success Reset Password Email'));
					return $this->redirect(['info']);
					
				} 
			
			} elseif (Clients::isPhone($model->login)) {

				$result = $model->sendSMS();
				if (empty($result['error'])) {
					
					return $this->redirect(['passwordresetconfirm', 'token'=>$result['token']]);
					
				}
			
			}

            Yii::$app->session->setFlash('error', Yii::t('Error', 'Error Reset Password Email'));
			return $this->redirect(['resendemail']);
			
        } else {
		
			if (empty($errors['login'])) {
				
				$model = new PasswordReset();
				$model->load(Yii::$app->request->post());
				
			} else  if (!empty($errors['login'])) {
				
				return $this->redirect(['passwordreset']);
				
			}			
		}
		
		if (!empty($_GET['token'])) {
			$model->login = Encrypt::dsCrypt(base64_decode($_GET['token']), 1); 
		}

		return $this->render('password_reset', [
			'model' => $model,
		]);  
    }
	
	/**
     * Resets password. 
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionPasswordresetconfirm($token)
    {		
		throw new NotFoundHttpException();
		
		if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

		if (empty($token) || !is_string($token)) {
			throw new NotFoundHttpException();
		}

		$model = new PasswordResetConfirm();
		$clients = Clients::findByPasswordResetToken($token);
		if (empty($clients) || empty($clients->password_reset_token)) {
			throw new NotFoundHttpException();
		}

		$model->reset_token_expire = Clients::getData($clients->password_reset_token);

        if ($model->load(Yii::$app->request->post())) {

			if ($model->confirm($token)) {
				
				if (Yii::$app->getUser()->login($clients)) {
					
					return $this->redirect(['/profile/update']);
					
				} else {

					Yii::$app->session->setFlash('success', Yii::t('Frontend', 'New password saved'));
					return $this->redirect(['info']);
				}

			} else {

				//if (!Yii::$app->session->hasFlash('error')) {
					//Yii::$app->session->setFlash('error', Yii::t('Error', 'Error Password Reset'));
				//}
				
				return $this->redirect(['passwordresetconfirm', 'token'=>$token]);
			}
			
        } 

        return $this->render('password_reset_confirm', [
            'model' => $model, 
        ]);
    }
	
	/**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendemail($login='')
    {
		throw new NotFoundHttpException();
		
		if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
		
		$model = new ResendEmail();
		
		if (Yii::$app->request->post()) {
			
			if ($model->load(Yii::$app->request->post()) && $model->validate()) {

				if (Clients::isEmail($model->login)) {

					if ($model->sendEmail()) {
						
						Yii::$app->session->setFlash('success', Yii::t('Frontend', 'Success Resend Email'));
						return $this->redirect(['info']);
						
					} 
				
				} else if (Clients::isPhone($model->login)) {

					$result = $model->sendSMS();
					if (empty($result['error'])) {
						
						Yii::$app->session->setFlash('success', Yii::t('Frontend', 'Success Resend Email'));
						return $this->redirect(['confirm', 'token'=>$result['token']]);
						
					}
				
				}

				Yii::$app->session->setFlash('error', Yii::t('Error', 'Error Resend Email'));
				return $this->redirect(['resendemail']);
				
			}

		} else if (!empty($login)) { 
			
			$model->login = $login;
			
		}
		
        return $this->render('resend_email', [
            'model' => $model,
        ]);
    }
	
	/**
	 * actionFormValidation() 
	 */
	public function actionFormvalidation() 
	{
		throw new NotFoundHttpException();
		
		$post = [];
		if (Yii::$app->request->isAjax) {

			Yii::$app->response->format = Response::FORMAT_JSON;
			$post = Yii::$app->request->post();
			
			if (!empty($post['SignupForm'])) {
				
				$model = new SignupForm;
				if($model->load(Yii::$app->request->post())) {
					return ActiveForm::validate($model);
				}
				
			} else if (!empty($post['ConfirmForm'])) {
				
				$model = new ConfirmForm;
				if($model->load(Yii::$app->request->post())) {
					return ActiveForm::validate($model);
				}
				
			} else if (!empty($post['PasswordReset'])) {
				
				$model = new PasswordReset;
				if($model->load(Yii::$app->request->post())) {
					return ActiveForm::validate($model);
				}
				
			} else if (!empty($post['LoginForm'])) { 
				
				$model = new LoginForm;
				if($model->load(Yii::$app->request->post())) {
					return ActiveForm::validate($model);
				}

			} else if (!empty($post['PasswordResetConfirm'])) {
				
				$model = new PasswordResetConfirm;
				if($model->load(Yii::$app->request->post())) {
					return ActiveForm::validate($model);
				}

			} else if (!empty($post['ResendEmail'])) {
				
				$model = new ResendEmail;
				if($model->load(Yii::$app->request->post())) {
					return ActiveForm::validate($model);
				}

			}
		}

        throw new BadRequestHttpException('Bad request!');
	}
}
