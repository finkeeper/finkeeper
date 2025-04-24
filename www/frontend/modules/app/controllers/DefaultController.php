<?php

namespace frontend\modules\app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use common\models\Exchange;
use common\models\Networks;
use yii\rest\ActiveController;
use frontend\modules\app\models\ApiChatbot;
use frontend\modules\app\components\TelegramApi;
use frontend\modules\app\components\BybitApi;
use frontend\modules\app\components\TonApi;
use frontend\modules\app\components\OKXApi;
use frontend\modules\app\components\SOLApi;
use frontend\modules\app\components\GPTApi2;
use frontend\modules\app\components\AiagentApi;
use frontend\modules\app\components\SUIApi;
use frontend\modules\app\components\SUIApi2;
use frontend\modules\app\components\APTApi;
use frontend\modules\app\components\ETHApi;
use frontend\modules\app\components\BTCApi;
use frontend\modules\app\components\WalletApi;
use frontend\modules\app\components\AppController;
use frontend\modules\app\components\LaunchpoolsApi;
use frontend\modules\app\components\AptospoolsApi;
use frontend\modules\app\components\GoogleApi;

/**
 * Default controller for the `service` module
 */
class DefaultController extends AppController
{		
	public $defaultAction = 'index'; 
	public $accessUser = false;
	
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
		
		$this->layout = '@app/themes/th1/views/layouts/main_finkeeper.php';
		
		$id = 0;
		$exist_lang = Yii::$app->params['supported_lang'];
		$lang = '';
		
		if (isset($_GET['id']) && !empty($_GET['id'])) {
			if (isset($_GET['sc']) && !empty($_GET['sc'])) {
				if (TelegramApi::validateUser($_GET['id'], $_GET['sc'])) {
					$id = (int) $_GET['id'];
					if (
						isset($_GET['lang']) && 
						!empty($_GET['lang']) && 
						in_array(strtolower($_GET['lang']), $exist_lang)
					) {
						$lang = strtolower($_GET['lang']);
						ApiChatbot::setSettingsLang($id, $lang);
						unset($_GET['lang']);
					}
				}
			}
		}
		
		
		
		ApiChatbot::getUserLang($id, $lang);
	
		parent::init();
    }
	
	/**
     * Renders the index view for the module
     * @return string
     */


	public function actionIndex($sc='') 
	{
		session_write_close();
		
		if (!Yii::$app->user->isGuest) {           
			$this->accessUser = true;			
        }
		
		$currency = Exchange::getDefaultCurrency();
		$grafema = Exchange::getGrafemCurrency($currency);

		if (!empty($this->accessUser)) {
			
			$id_client = Yii::$app->user->getId();
			$used_gpt1 = []; //ApiChatbot::getUsedGPTChat(1);
			$used_gpt2 = []; //ApiChatbot::getUsedGPTChat(2);
			
			$userpic = '';
			$name = Yii::t('Api', 'You');
			$client = ApiChatbot::findClient($id_client);
			if (!empty($client)) {
				if (!empty($client->userpic)) {
					$userpic = $client->userpic;
				}
				
				if (!empty($client->name)) {
					$name = $client->name;
				}
			}
			
			$log = ApiChatbot::getUserLog($id_client);
			$id = $log->id;
			
			ApiChatbot::addMenuButton($id);

			$exchange = Exchange::getData();	

			$targets = ApiChatbot::getTargets($id);

			$friends = ApiChatbot::getReferralsData($id);		
			$status = ApiChatbot::getStatusConnect($id);
			$wallet = ApiChatbot::getWallet($id_client);
			
			$sc = TelegramApi::tg()->generateUserToken($id);
			
			$lang = ApiChatbot::getSettingsLang($id);
			Yii::$app->language=strtolower($lang).'-'.strtoupper($lang);
			
			session_start();
		
			return $this->render('converter', [
				'id' => $id,
				'sc' => $sc,
				'exchange' => $exchange,
				'friends' => $friends,
				'default_currency' => $currency,
				'grafema' => $grafema,
				'status' => $status,
				'targets' => $targets,
				'id_client' => $id_client,
				'used_gpt1' => $used_gpt1,
				'used_gpt2' => $used_gpt2,
				'username' => $name,
				'userpic' => $userpic,
				'wallet' => $wallet,
			]);
			
		} else {
			
			session_start();
	
			return $this->render('auth', [
				'id' => 0,
				'sc' => '',
				'default_currency' => $currency,
				'grafema' => $grafema,
				'exchange' => [],
				'friends' => [],
				'status' => [],
				'targets' => [],
				'id_client' => 0,
				'used_gpt1' => [],
				'used_gpt2' => [],
			]);
		}
	}
	
	/** 
	 * actionServiceAuth()
	 */
	public function actionServiceauth()
	{
		if (Yii::$app->request->isPost) {
		
			session_write_close();
			
			Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			
			if (!Yii::$app->user->isGuest) {           
				return Yii::$app->response->redirect(['/app']);		
			}
			
			$input = file_get_contents('php://input');
			$array = @json_decode($input, true);
			
			if (empty($array['token'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing token')]));			
			}

			$data = (array) GoogleApi::pstatic()->parseJWT($array['token']);
			if (!empty($data['error'])) {
				exit(json_encode($data));
			}
			
			$client_id = GoogleApi::pstatic()->saveData($data);
			if (!empty($client_id)) {
				
				$hash = Yii::$app->security->generateRandomString();
				
				if (!ApiChatbot::saveEmailToken($client_id, $hash)) {
					exit(json_encode([
						'error' => 1,
						'message' => Yii::t('Error', 'Not save user data'),
					]));
				}
	
				exit(json_encode(['error' => 0, 'message' => 'Success', 'token' => $hash]));
			}

			exit(json_encode([
				'error' => 1, 
				'message' => Yii::t('Error', 'Not save user data'),
			]));
		
		} else {
			
			throw new NotFoundHttpException();
		}
	}
	
	/** 
	 * actionConnect($id=0)
	 */
	public function actionConnect($id=0)
	{
		session_write_close();
	
		if (Yii::$app->user->isGuest) {           
			return Yii::$app->response->redirect(['/login']);			
        }
		
		if (empty($id)) {
			exit(json_encode([ 
				'error' => 1,
				'status' => '404',
				'message' => 'Page not found',
			]));
		}
		
		$network = Networks::findNetwork($id);
		if (empty($network)) {
			exit(json_encode([ 
				'error' => 1,
				'status' => '404',
				'message' => 'Page not found',
			]));
		}
		
		$id_client = Yii::$app->user->getId();

		$log = ApiChatbot::getUserLog($id_client);

		$status = ApiChatbot::getStatusConnect($log->id);
		
		$wallet = [
			'sui' => [
				'address' => '',
				'balance' => 0,
				'price' => 0,
				'navi' => 0,
				'rewards' => 0,
			],
		];
			
		$sc = TelegramApi::tg()->generateUserToken($log->id);
			
		$lang = ApiChatbot::getSettingsLang($log->id);
		Yii::$app->language=strtolower($lang).'-'.strtoupper($lang);
		
		session_start();

		return $this->render('connect', [
			'log_id' => $log->id,
			'sc' => $sc,
			'status' => $status,
			'network' => $network,
			'lang' => $lang,
			'wallet' => $wallet,
        ]);
	}
	
	/** 
	 * https://api.bank.ctfn.pro/v3/datas/stakingcalc
	 */
	public function actionStakingcalc($id=0)
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;

		return $this->render('stakingcalc', [
           'id' => $id,
        ]);
	}

	/** 
	 * actionGetbybitbalance
	 */
	public function actionGetbybitbalance()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		session_write_close();
		
		$input = file_get_contents('php://input');
		$array = @json_decode($input, true);
		
		if (empty($array['type'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing type balance')]));			
		}
		
		if (empty($array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing token')]));	
		} 
		
		if (empty($array['log_id'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not User Chat ID')]));			
		}
		
		if (!TelegramApi::validateUser($array['log_id'], $array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Incorrect token')]));	
		}
		
		$modelChatbotLog = ApiChatbot::getChatbotLog($array['log_id']);
		if (empty($modelChatbotLog)) {
			return false;		
		}
		
		$lang = ApiChatbot::getSettingsLang($modelChatbotLog->id);
		Yii::$app->language = strtolower($lang).'-'.strtoupper($lang);

		if ($array['type']==2) {
			
			$exname = !empty($array['exname']) ? $array['exname'] : "";
			
			if (empty($array['uid'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing Bybit UID')]));			
			}
			
			if (empty($array['apikey'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing Bybit API Key')]));			
			}
			
			if (empty($array['apisecret'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing Bybit API Secret')]));			
			}
	
			$bybit = new BybitApi;
			$bybit->api_key = $array['apikey'];
			$bybit->secret_key = $array['apisecret'];
			$bybit->uid = $array['uid'];

			$save_tokens = ApiChatbot::saveTokens(2, $array['log_id'], $array['uid'], $array['apikey'], $array['apisecret'], '', '', $exname);
	
			if (
				empty($save_tokens) || 
				!is_array($save_tokens) || 
				!empty($save_tokens['error'])
			) {
				error_log($save_tokens['message']."\r\n".PHP_EOL, 3, dirname(__FILE__).'/log.log');
			}
		} else if ($array['type']==3) {

			if (empty($array['id'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing Bybit Account ID')]));
			}
			
			if (!ApiChatbot::saveStatusConnect(2, $array['log_id'], 0, $array['id'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not save status connect')]));
			}
		}

		$arrayTokens = ApiChatbot::getBybitTokens($modelChatbotLog->id_client);
		if (empty($arrayTokens) || !is_array($arrayTokens)) {
			return false;		
		}

		$currency = Exchange::getDefaultCurrency();
		$grafema = Exchange::getGrafemCurrency($currency);
		$data = [];
		$summ = 0;
		$sum_active = 0;
		$sum_trade = 0;
		$status_connect = 0;

		foreach ($arrayTokens as $modelTokens) {
			
			if (empty($modelTokens->user_connect)) {
				continue;
			}
		
			$exname = $modelTokens->exname;
			
			$bybit = new BybitApi;
			$bybit->api_key = $modelTokens->identify2;
			$bybit->secret_key = $modelTokens->identify3;
			$bybit->uid = $modelTokens->identify1;

			$data[$modelTokens->identify1] =[
				'active' => [],
				'trade' => [],
				'asset' => $modelTokens->identify1,
				'connectname' => $exname,
				'status_connect' => 0,
			];
		
			$list_coins = [];
		
			$response = $bybit->getWalletBalance();
			if (empty($response['error'])) {
			
				if (!empty($response['data']['active'])) {
					foreach ($response['data']['active'] as $key=>$val) {
			
						$summ += $val['currency_value'];
						$sum_active += $val['currency_value'];
						$data[$modelTokens->identify1]['active'][] = $val;
					}
				
					usort($data[$modelTokens->identify1]['active'], [$this, 'cmp']);
					$data[$modelTokens->identify1]['status_connect'] = 1;
					$status_connect = 1;

				}
				
				if (!empty($response['data']['trade'])) {
					foreach ($response['data']['trade'] as $key=>$val) {
			
						$summ += $val['currency_value'];
						$sum_active += $val['currency_value'];
						$data[$modelTokens->identify1]['trade'][] = $val;
					}
				
					usort($data[$modelTokens->identify1]['trade'], [$this, 'cmp']);
					$data[$modelTokens->identify1]['status_connect'] = 1;
					$status_connect = 1;

				}
			
			}

			$data[$modelTokens->identify1]['error'] = $response['errors'];
			
			if ($array['type']==2 && empty($data[$modelTokens->identify1]['status_connect'])) {

				if (ApiChatbot::saveStatusConnect(2, $array['log_id'], 1, $modelTokens->id_token)) {
						
				}

				if (!empty($save_tokens['change_token'])) {
					if (!ApiChatbot::sendMessageConnectedTon($array['log_id'], 3, $modelTokens->id_token)) {
					
					}
				}
			}
		}
		
		session_start();

		exit(json_encode([
			'error'=>0, 
			'connect' => $status_connect,
			'data'=>$data,
			'summ' => Exchange::formatValue($summ),
			'sum_active' => Exchange::formatValue($sum_active),
			'sum_trade' => Exchange::formatValue($sum_trade),
			'grafema' => $grafema,
		]));
	}
	
	/** 
	 * actionGetokxbalance
	 */
	public function actionGetokxbalance()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		session_write_close();
		
		$input = file_get_contents('php://input');
		$array = @json_decode($input, true);

		if (empty($array['type'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing type balance')]));			
		}
		
		if (empty($array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing token')]));	
		} 
		
		if (empty($array['log_id'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not User Chat ID')]));			
		}

		if (!TelegramApi::validateUser($array['log_id'], $array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Incorrect token')]));	
		}
		
		$modelChatbotLog = ApiChatbot::getChatbotLog($array['log_id']);
		if (empty($modelChatbotLog)) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing user data')]));		
		}
		
		$lang = ApiChatbot::getSettingsLang($modelChatbotLog->id);
		Yii::$app->language = strtolower($lang).'-'.strtoupper($lang);
		
		if ($array['type']==2) {
			
			$exname = !empty($array['exname']) ? $array['exname'] : "";
			
			if (empty($array['password'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing OKX Password')]));			
			}
			
			if (empty($array['apikey'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing OKX API Key')]));			
			}
			
			if (empty($array['apisecret'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing OKX API Secret')]));			
			}
			
			if (empty($array['uid'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing OKX UID')]));			
			}
			
			$okx = new OKXApi;
			$okx->api_key = $array['apikey'];
			$okx->secret_key = $array['apisecret'];
			$okx->password = $array['password'];
			$okx->uid = $array['uid'];
			
			$save_tokens = ApiChatbot::saveTokens(3, $array['log_id'], $array['uid'], $array['apikey'], $array['apisecret'], $array['password'], '', $exname);
	
			if (
				empty($save_tokens) || 
				!is_array($save_tokens) || 
				!empty($save_tokens['error'])
			) {
				error_log($save_tokens['message']."\r\n".PHP_EOL, 3, dirname(__FILE__).'/log.log');
			}
			
		} else if ($array['type']==3) {	
		
			if (empty($array['id'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing OKX Account ID')]));
			}
			
			if (!ApiChatbot::saveStatusConnect(3, $array['log_id'], 0, $array['id'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not save status connect')]));
			}
		}
		
		$arrayTokens = ApiChatbot::getOkxTokens($modelChatbotLog->id_client);
		if (empty($arrayTokens)) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing token')]));	
		}

		$currency = Exchange::getDefaultCurrency();
		$grafema = Exchange::getGrafemCurrency($currency);
		$data = [];
		$summ = 0;
		$sum_active = 0;
		$sum_trade = 0;
		$status_connect = 0;
	
		foreach ($arrayTokens as $modelTokens) {
		
			if (empty($modelTokens->user_connect)) {
				continue;
			}
		
			$exname = $modelTokens->exname;
			
			$okx = new OKXApi;
			$okx->api_key = $modelTokens->identify2;
			$okx->secret_key = $modelTokens->identify3;
			$okx->password = $modelTokens->identify4;
			$okx->uid = $modelTokens->identify1;
		
			$error = [];
		
			$data[$modelTokens->identify1] =[
				'active' => [],
				'trade' => [],
				'asset' => $modelTokens->identify1,
				'connectname' => $exname,
				'status_connect' => 0,
			];
						
			$response = $okx->getWalletBalance();
			if (empty($response['error'])) {
			
				if (!empty($response['data']['active'])) {
					foreach ($response['data']['active'] as $key=>$val) {
			
						$summ += $val['currency_value'];
						$sum_active += $val['currency_value'];
						$data[$modelTokens->identify1]['active'][] = $val;
					}
				
					usort($data[$modelTokens->identify1]['active'], [$this, 'cmp']);
					$data[$modelTokens->identify1]['status_connect'] = 1;
					$status_connect = 1;

				}
				
				if (!empty($response['data']['trade'])) {
					foreach ($response['data']['trade'] as $key=>$val) {
			
						$summ += $val['currency_value'];
						$sum_active += $val['currency_value'];
						$data[$modelTokens->identify1]['trade'][] = $val;
					}
				
					usort($data[$modelTokens->identify1]['trade'], [$this, 'cmp']);
					$data[$modelTokens->identify1]['status_connect'] = 1;
					$status_connect = 1;

				}
			
			}

			$data[$modelTokens->identify1]['error'] = $response['errors'];
			
			if ($array['type']==3 && empty($data[$modelTokens->identify1]['status_connect'])) {

				if (ApiChatbot::saveStatusConnect(3, $array['log_id'], 1, $modelTokens->id_token)) {
						
				}

				if (!empty($save_tokens['change_token'])) {
					if (!ApiChatbot::sendMessageConnectedTon($array['log_id'], 5, $modelTokens->id_token)) {
					
					}
				}
			}
		}	
		
		session_start();
		
		exit(json_encode([
			'error'=>0,
			'connect' => $status_connect,			
			'data'=>$data,
			'summ' => Exchange::formatValue($summ),
			'sum_active' => Exchange::formatValue($sum_active),
			'sum_trade' => Exchange::formatValue($sum_trade),
			'grafema' => $grafema,
		]));
	}

	/** 
	 * actionGetsolbalance
	 */
	public function actionGetsolbalance()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		session_write_close();
		
		$input = file_get_contents('php://input');
		$array = @json_decode($input, true);
		$error = [];
			
		if (empty($array['type'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing type balance')]));			
		}
		
		if (empty($array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing token')]));	
		} 
		
		if (empty($array['log_id'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not User Chat ID')]));			
		}

		if (!TelegramApi::validateUser($array['log_id'], $array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Incorrect token')]));	
		}
		
		$modelChatbotLog = ApiChatbot::getChatbotLog($array['log_id']);
		if (empty($modelChatbotLog)) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing user data')]));		
		}
		
		$lang = ApiChatbot::getSettingsLang($modelChatbotLog->id);
		Yii::$app->language = strtolower($lang).'-'.strtoupper($lang);	

		if ($array['type']==2) {
			
			$exname = !empty($array['exname']) ? $array['exname'] : "";
			
			if (empty($array['address'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing SOL Address Wallet')]));			
			}

			$sol = new SOLApi;
			$sol->address = $array['address'];
			
			$save_tokens = ApiChatbot::saveTokens(4, $array['log_id'], $array['address'], '', '', '', '', $exname);

			if (
				empty($save_tokens) || 
				!is_array($save_tokens) || 
				!empty($save_tokens['error'])
			) {
				error_log($save_tokens['message']."\r\n".PHP_EOL, 3, dirname(__FILE__).'/log.log');
			}
			
		} else if ($array['type']==3) {	

			if (empty($array['id'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing SOL Account ID')]));
			}
			
			if (!ApiChatbot::saveStatusConnect(3, $array['log_id'], 0, $array['id'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not save status connect')]));
			}
		}
		
		$arrayTokens = ApiChatbot::getSolTokens($modelChatbotLog->id_client);
		if (empty($arrayTokens)) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing token')]));	
		}

		$currency = Exchange::getDefaultCurrency();
		$grafema = Exchange::getGrafemCurrency($currency);
		$data = [];
		$summ = 0;
		$sum_active = 0;
		$sum_trade = 0;
		$status_connect = 0;

		foreach ($arrayTokens as $modelTokens) {
		
			if (empty($modelTokens->user_connect)) {
				continue;
			}

			$exname = $modelTokens->exname;
			
			$sol = new SOLApi;
			$sol->address = $modelTokens->identify1;
		
			$data[$modelTokens->identify1] =[
				'active' => [],
				'asset' => $modelTokens->identify1,
				'connectname' => $exname,
				'status_connect' => 0,
			];
	
			$response = $sol->getWalletBalance();
			if (empty($response['error'])) {
				
				if (!empty($response['data'])) {
					
					foreach ($response['data'] as $val) {
		
						$summ += $val['currency_value'];
						$sum_active += $val['currency_value'];
						$data[$modelTokens->identify1]['active'][] = $val;	
					}

					usort($data[$modelTokens->identify1]['active'], [$this, 'cmp']);
					$data[$modelTokens->identify1]['status_connect'] = 1;
					$status_connect = 1;
					
				}
				
			} else {
				$error[] = $response['message'];
			}
	
			$data[$modelTokens->identify1]['error'] = $error;
			
			if ($array['type']==4 && empty($data[$modelTokens->identify1]['status_connect'])) {

				if (ApiChatbot::saveStatusConnect(3, $array['log_id'], 1, $modelTokens->id_token)) {
						
				}

				if (!empty($save_tokens['change_token'])) {
					if (!ApiChatbot::sendMessageConnectedTon($array['log_id'], 7, $modelTokens->id_token)) {
					
					}
				}
			}	
		}
		
		session_start();

		exit(json_encode([
			'error'=>0,
			'connect' => $status_connect,			
			'data'=>$data,
			'summ' => Exchange::formatValue($summ),
			'sum_active' => Exchange::formatValue($sum_active),
			'sum_trade' => Exchange::formatValue($sum_trade),
			'grafema' => $grafema,
		]));
	}
	
	/** 
	 * actionGetsuibalance
	 */
	public function actionGetsuibalance()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		session_write_close();
		
		$input = file_get_contents('php://input');
		$array = @json_decode($input, true);
		$error = [];
			
		if (empty($array['type'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing type balance')]));			
		}
		
		if (empty($array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing token')]));	
		} 
		
		if (empty($array['log_id'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not User Chat ID')]));			
		}

		if (!TelegramApi::validateUser($array['log_id'], $array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Incorrect token')]));	
		}
		
		$modelChatbotLog = ApiChatbot::getChatbotLog($array['log_id']);
		if (empty($modelChatbotLog)) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing user data')]));		
		}
		
		$lang = ApiChatbot::getSettingsLang($modelChatbotLog->id);
		Yii::$app->language = strtolower($lang).'-'.strtoupper($lang);	

		if ($array['type']==2) {
			
			$exname = !empty($array['exname']) ? $array['exname'] : "";
			
			if (empty($array['address'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing SUI Address Wallet')]));			
			}

			$sui2 = new SUIApi2;
			$sui2->address = $array['address'];
			
			$save_tokens = ApiChatbot::saveTokens(5, $array['log_id'], $array['address'], '', '', '', '', $exname);

			if (
				empty($save_tokens) || 
				!is_array($save_tokens) || 
				!empty($save_tokens['error'])
			) {
				error_log($save_tokens['message']."\r\n".PHP_EOL, 3, dirname(__FILE__).'/log.log');
			}
			
		} else if ($array['type']==3) {	

			if (empty($array['id'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing SUI Account ID')]));
			}
			
			if (!ApiChatbot::saveStatusConnect(3, $array['log_id'], 0, $array['id'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not save status connect')]));
			}
		}
		
		$arrayTokens = ApiChatbot::getSuiTokens($modelChatbotLog->id_client);
		if (empty($arrayTokens)) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing token')]));	
		}

		$currency = Exchange::getDefaultCurrency();
		$grafema = Exchange::getGrafemCurrency($currency);
		$data = [];
		$summ = 0;
		$sum_active = 0;
		$sum_trade = 0;
		$status_connect = 0;

		foreach ($arrayTokens as $modelTokens) {
		
			if (empty($modelTokens->user_connect)) {
				continue;
			}

			$exname = $modelTokens->exname;

			$sui2 = new SUIApi2;
			$sui2->address = $modelTokens->identify1;
		
			$data[$modelTokens->identify1] =[
				'active' => [],
				'asset' => $modelTokens->identify1,
				'connectname' => $exname,
				'status_connect' => 0,
			];

			$response = $sui2->getWalletBalance();
			if (empty($response['error'])) {		
				if (!empty($response['data'])) {
			
					foreach ($response['data'] as $val) {
				
						$summ += $val['currency_value'];
						$sum_active += $val['currency_value'];
						$data[$modelTokens->identify1]['active'][] = $val;							
					}

					usort($data[$modelTokens->identify1]['active'], [$this, 'cmp']);
					$data[$modelTokens->identify1]['status_connect'] = 1;
					$status_connect = 1;
				}
			
			} else {
				$error[] = $response['message'];
			}	

			$data[$modelTokens->identify1]['error'] = $error;

			if ($array['type']==5 && empty($data[$modelTokens->identify1]['status_connect'])) {

				if (ApiChatbot::saveStatusConnect(3, $array['log_id'], 1, $modelTokens->id_token)) {
						
				}

				if (!empty($save_tokens['change_token'])) {
					if (!ApiChatbot::sendMessageConnectedTon($array['log_id'], 9, $modelTokens->id_token)) {
					
					}
				}
			}	
		}
		
		session_start();

		exit(json_encode([
			'error'=>0,
			'connect' => $status_connect,			
			'data'=>$data,
			'summ' => Exchange::formatValue($summ),
			'sum_active' => Exchange::formatValue($sum_active),
			'sum_trade' => Exchange::formatValue($sum_trade),
			'grafema' => $grafema,
		]));
	}
	
	/** 
	 * actionGetsuibalance
	 */
	public function actionGetaptbalance()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		session_write_close();
		
		$input = file_get_contents('php://input');
		$array = @json_decode($input, true);
		$error = [];
			
		if (empty($array['type'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing type balance')]));			
		}
		
		if (empty($array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing token')]));	
		} 
		
		if (empty($array['log_id'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not User Chat ID')]));			
		}

		if (!TelegramApi::validateUser($array['log_id'], $array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Incorrect token')]));	
		}
		
		$modelChatbotLog = ApiChatbot::getChatbotLog($array['log_id']);
		if (empty($modelChatbotLog)) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing user data')]));		
		}
		
		$lang = ApiChatbot::getSettingsLang($modelChatbotLog->id);
		Yii::$app->language = strtolower($lang).'-'.strtoupper($lang);
		
		if ($array['type']==2) {
			
			$exname = !empty($array['exname']) ? $array['exname'] : "";
			
			if (empty($array['address'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing APTOS Address Wallet')]));			
			}

			$apt = new APTApi;
			$apt->address = $array['address'];
			
			$save_tokens = ApiChatbot::saveTokens(7, $array['log_id'], $array['address'], '', '', '', '', $exname);

			if (
				empty($save_tokens) || 
				!is_array($save_tokens) || 
				!empty($save_tokens['error'])
			) {
				error_log($save_tokens['message']."\r\n".PHP_EOL, 3, dirname(__FILE__).'/log.log');
			}
			
		} else if ($array['type']==3) {	

			if (empty($array['id'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing APTOS Account ID')]));
			}
			
			if (!ApiChatbot::saveStatusConnect(3, $array['log_id'], 0, $array['id'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not save status connect')]));
			}
		}
		
		$arrayTokens = ApiChatbot::getAptTokens($modelChatbotLog->id_client);
		if (empty($arrayTokens)) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing token')]));	
		}

		$currency = Exchange::getDefaultCurrency();
		$grafema = Exchange::getGrafemCurrency($currency);
		$data = [];
		$summ = 0;
		$sum_active = 0;
		$sum_trade = 0;
		$status_connect = 0;

		foreach ($arrayTokens as $modelTokens) {
		
			if (empty($modelTokens->user_connect)) {
				continue;
			}

			$exname = $modelTokens->exname;

			$apt = new APTApi;
			$apt->address = $modelTokens->identify1;
		
			$data[$modelTokens->identify1] =[
				'active' => [],
				'asset' => $modelTokens->identify1,
				'connectname' => $exname,
				'status_connect' => 0,
			];
		
			$response = $apt->getWalletBalance();
			if (empty($response['error'])) {		
				if (!empty($response['data'])) {
		
					foreach ($response['data'] as $val) {
		
						$summ += $val['currency_value'];
						$sum_active += $val['currency_value'];
						$data[$modelTokens->identify1]['active'][] = $val;		
					}

					usort($data[$modelTokens->identify1]['active'], [$this, 'cmp']);
					$data[$modelTokens->identify1]['status_connect'] = 1;
					$status_connect = 1;
					
				}
				
			} else {
				$error[] = $response['message'];
			}
			
			$data[$modelTokens->identify1]['error'] = $error;

			if ($array['type']==7 && empty($data[$modelTokens->identify1]['status_connect'])) {

				if (ApiChatbot::saveStatusConnect(3, $array['log_id'], 1, $modelTokens->id_token)) {
						
				}

				if (!empty($save_tokens['change_token'])) {
					if (!ApiChatbot::sendMessageConnectedTon($array['log_id'], 11, $modelTokens->id_token)) {
					
					}
				}
			}
		}
		
		session_start();
		
		exit(json_encode([
			'error'=>0,
			'connect' => $status_connect,			
			'data'=>$data,
			'summ' => Exchange::formatValue($summ),
			'sum_active' => Exchange::formatValue($sum_active),
			'sum_trade' => Exchange::formatValue($sum_trade),
			'grafema' => $grafema,
		]));
	}
	
	/** 
	 * actionGetethbalance
	 */
	public function actionGetethbalance()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		session_write_close();
		
		$input = file_get_contents('php://input');
		$array = @json_decode($input, true);
		$error = [];
			
		if (empty($array['type'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing type balance')]));			
		}
		
		if (empty($array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing token')]));	
		} 
		
		if (empty($array['log_id'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not User Chat ID')]));			
		}

		if (!TelegramApi::validateUser($array['log_id'], $array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Incorrect token')]));	
		}
		
		$modelChatbotLog = ApiChatbot::getChatbotLog($array['log_id']);
		if (empty($modelChatbotLog)) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing user data')]));		
		}
		
		$lang = ApiChatbot::getSettingsLang($modelChatbotLog->id);
		Yii::$app->language = strtolower($lang).'-'.strtoupper($lang);

		if ($array['type']==2) {
			
			$exname = !empty($array['exname']) ? $array['exname'] : "";
			
			if (empty($array['address'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing ETH Address Wallet')]));			
			}

			$eth = new ETHApi;
			$eth->address = $array['address'];
			
			$save_tokens = ApiChatbot::saveTokens(8, $array['log_id'], $array['address'], '', '', '', '', $exname);

			if (
				empty($save_tokens) || 
				!is_array($save_tokens) || 
				!empty($save_tokens['error'])
			) {
				error_log($save_tokens['message']."\r\n".PHP_EOL, 3, dirname(__FILE__).'/log.log');
			}
			
		} else if ($array['type']==3) {	

			if (empty($array['id'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing ETH Account ID')]));
			}
			
			if (!ApiChatbot::saveStatusConnect(3, $array['log_id'], 0, $array['id'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not save status connect')]));
			}
		}
		
		$arrayTokens = ApiChatbot::getEthTokens($modelChatbotLog->id_client);
		if (empty($arrayTokens)) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing token')]));	
		}

		$currency = Exchange::getDefaultCurrency();
		$grafema = Exchange::getGrafemCurrency($currency);
		$data = [];
		$summ = 0;
		$sum_active = 0;
		$sum_trade = 0;
		$status_connect = 0;

		foreach ($arrayTokens as $modelTokens) {
		
			if (empty($modelTokens->user_connect)) {
				continue;
			}

			$exname = $modelTokens->exname;

			$eth = new ETHApi;
			$eth->address = $modelTokens->identify1;
		
			$data[$modelTokens->identify1] =[
				'active' => [],
				'asset' => $modelTokens->identify1,
				'connectname' => $exname,
				'status_connect' => 0,
			];
		
			$response = $eth->getWalletBalance();
			if (empty($response['error'])) {		
				if (!empty($response['data'])) {
		
					foreach ($response['data'] as $val) {
		
						$summ += $val['currency_value'];
						$sum_active += $val['currency_value'];
						$data[$modelTokens->identify1]['active'][] = $val;					
					}

					usort($data[$modelTokens->identify1]['active'], [$this, 'cmp']);
					$data[$modelTokens->identify1]['status_connect'] = 1;
					$status_connect = 1;
					
				}
				
			} else {
				$error[] = $response['message'];
			}

			$data[$modelTokens->identify1]['error'] = $error;

			if ($array['type']==8 && empty($data[$modelTokens->identify1]['status_connect'])) {

				if (ApiChatbot::saveStatusConnect(3, $array['log_id'], 1, $modelTokens->id_token)) {
						
				}

				if (!empty($save_tokens['change_token'])) {
					if (!ApiChatbot::sendMessageConnectedTon($array['log_id'], 13, $modelTokens->id_token)) {
					
					}
				}
			}
		}
		
		session_start();
		
		exit(json_encode([
			'error'=>0,
			'connect' => $status_connect,			
			'data'=>$data,
			'summ' => Exchange::formatValue($summ),
			'sum_active' => Exchange::formatValue($sum_active),
			'sum_trade' => Exchange::formatValue($sum_trade),
			'grafema' => $grafema,
		]));
	}
	
	/** 
	 * actionGetbtcbalance
	 */
	public function actionGetbtcbalance()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		session_write_close();
		
		$input = file_get_contents('php://input');
		$array = @json_decode($input, true);
		$error = [];

		if (empty($array['type'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing type balance')]));			
		}
		
		if (empty($array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing token')]));	
		} 
		
		if (empty($array['log_id'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not User Chat ID')]));			
		}

		if (!TelegramApi::validateUser($array['log_id'], $array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Incorrect token')]));	
		}
		
		$modelChatbotLog = ApiChatbot::getChatbotLog($array['log_id']);
		if (empty($modelChatbotLog)) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing user data')]));		
		}

		$lang = ApiChatbot::getSettingsLang($modelChatbotLog->id);
		Yii::$app->language = strtolower($lang).'-'.strtoupper($lang);

		if ($array['type']==2) {
			
			$exname = !empty($array['exname']) ? $array['exname'] : "";
			
			if (empty($array['address'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing BTC Address Wallet')]));			
			}

			$btc = new BTCApi;
			$btc->address = $array['address'];
			
			$save_tokens = ApiChatbot::saveTokens(9, $array['log_id'], $array['address'], '', '', '', '', $exname);

			if (
				empty($save_tokens) || 
				!is_array($save_tokens) || 
				!empty($save_tokens['error'])
			) {
				error_log($save_tokens['message']."\r\n".PHP_EOL, 3, dirname(__FILE__).'/log.log');
			}
			
		} else if ($array['type']==3) {	

			if (empty($array['id'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing BTC Account ID')]));
			}
			
			if (!ApiChatbot::saveStatusConnect(3, $array['log_id'], 0, $array['id'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not save status connect')]));
			}
		}
		
		$arrayTokens = ApiChatbot::getBtcTokens($modelChatbotLog->id_client);
		if (empty($arrayTokens)) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing token')]));	
		}

		$currency = Exchange::getDefaultCurrency();
		$grafema = Exchange::getGrafemCurrency($currency);
		$data = [];
		$summ = 0;
		$sum_active = 0;
		$sum_trade = 0;
		$status_connect = 0;

		foreach ($arrayTokens as $modelTokens) {
		
			if (empty($modelTokens->user_connect)) {
				continue;
			}

			$exname = $modelTokens->exname; 

			$btc = new BTCApi;
			$btc->address = $modelTokens->identify1;
		
			$data[$modelTokens->identify1] =[
				'active' => [],
				'asset' => $modelTokens->identify1,
				'connectname' => $exname,
				'status_connect' => 0,
			];
		
			$response = $btc->getWalletBalance();
			if (empty($response['error'])) {		
				if (!empty($response['data'])) {
		
					foreach ($response['data'] as $val) {
		
						$summ += $val['currency_value'];
						$sum_active += $val['currency_value'];
						$data[$modelTokens->identify1]['active'][] = $val;					
					}

					usort($data[$modelTokens->identify1]['active'], [$this, 'cmp']);
					$data[$modelTokens->identify1]['status_connect'] = 1;
					$status_connect = 1;
					
				}
				
			} else {
				$error[] = $response['message'];
			}

			$data[$modelTokens->identify1]['error'] = $error;

			if ($array['type']==9 && empty($data[$modelTokens->identify1]['status_connect'])) {

				if (ApiChatbot::saveStatusConnect(3, $array['log_id'], 1, $modelTokens->id_token)) {
						
				}

				if (!empty($save_tokens['change_token'])) {
					if (!ApiChatbot::sendMessageConnectedTon($array['log_id'], 15, $modelTokens->id_token)) {
					
					}
				}
			}
		}
		
		session_start();
		
		exit(json_encode([
			'error'=>0,
			'connect' => $status_connect,			
			'data'=>$data,
			'summ' => Exchange::formatValue($summ),
			'sum_active' => Exchange::formatValue($sum_active),
			'sum_trade' => Exchange::formatValue($sum_trade),
			'grafema' => $grafema,
		]));
	}

	/** 
	 * actionGettonbalance
	 */
	public function actionGettonbalance()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		session_write_close();
		
		$input = file_get_contents('php://input');
		$array = @json_decode($input, true);
		$error = [];
			
		if (empty($array['type'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing type balance')]));			
		}
		
		if (empty($array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing token')]));	
		} 
		
		if (empty($array['log_id'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not User Chat ID')]));			
		}

		if (!TelegramApi::validateUser($array['log_id'], $array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Incorrect token')]));	
		}
		
		$modelChatbotLog = ApiChatbot::getChatbotLog($array['log_id']);
		if (empty($modelChatbotLog)) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing user data')]));		
		}
		
		$lang = ApiChatbot::getSettingsLang($modelChatbotLog->id);
		Yii::$app->language = strtolower($lang).'-'.strtoupper($lang);
	
		if ($array['type']==2) {
			
			$exname = !empty($array['exname']) ? $array['exname'] : "";
			
			if (empty($array['address'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing ETH Address Wallet')]));			
			}

			$ton = new TonApi;
			$raw_address = $ton->getAddressParse($array['address'], 1);
			if (!empty($raw_address['error'])) {
				exit(json_encode(['error'=>1, 'message'=>$raw_address['message']]));	
			}
			
			$ton->address = $raw_address;

			$save_tokens = ApiChatbot::saveTokens(1, $array['log_id'], $raw_address, '', '', '', '', $exname);

			if (
				empty($save_tokens) || 
				!is_array($save_tokens) || 
				!empty($save_tokens['error'])
			) {
				error_log($save_tokens['message']."\r\n".PHP_EOL, 3, dirname(__FILE__).'/log.log');
			}
	
		} else if ($array['type']==3) {	

			if (empty($array['id'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing ETH Account ID')]));
			}
			
			$array['id'] = str_replace('finkeeper_', '0:', $array['id']);
			
			if (!ApiChatbot::saveStatusConnect(3, $array['log_id'], 0, $array['id'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not save status connect')]));
			}
		}

		$arrayTokens = ApiChatbot::getTonTokens($modelChatbotLog->id_client);
		if (empty($arrayTokens)) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing token')]));	
		}

		$currency = Exchange::getDefaultCurrency();
		$grafema = Exchange::getGrafemCurrency($currency);
		$data = [];
		$summ = 0;
		$sum_active = 0;
		$sum_trade = 0;
		$status_connect = 0;
	
		foreach ($arrayTokens as $modelTokens) {

			if (empty($modelTokens->user_connect)) {
				continue;
			}
	
			$exname = $modelTokens->exname;

			$ton = new TonApi;
			$ton->address = $modelTokens->identify1;		
			$data[$modelTokens->identify1] =[
				'active' => [],
				'asset' => $modelTokens->identify1,
				'assetP2PKH' => $ton->getAddressParse($modelTokens->identify1),
				'connectname' => $exname,
				'status_connect' => 0,
			];

			$response = $ton->getWalletBalance();
			if (empty($response['error'])) {		
				if (!empty($response['data'])) {
		
					foreach ($response['data'] as $val) {
		
						$summ += $val['currency_value'];
						$sum_active += $val['currency_value'];
						$data[$modelTokens->identify1]['active'][] = $val;					
					}

					usort($data[$modelTokens->identify1]['active'], [$this, 'cmp']);
					$data[$modelTokens->identify1]['status_connect'] = 1;
					$status_connect = 1;
					
				}
				
			} else {
				$error[] = $response['message'];
			}

			$data[$modelTokens->identify1]['error'] = $error;

			if ($array['type']==1 && empty($data[$modelTokens->identify1]['status_connect'])) {

				if (ApiChatbot::saveStatusConnect(3, $array['log_id'], 1, $modelTokens->id_token)) {
						
				}

				if (!empty($save_tokens['change_token'])) {
					if (!ApiChatbot::sendMessageConnectedTon($array['log_id'], 1, $modelTokens->id_token)) {
					
					}
				}
			}
		}
		
		$address = '';
		$id = '';
		if (!empty($ton->address)) {
			$address = TonApi::pstatic()->getAddressParse($ton->address);
			$id = $ton->address;
		}
		
		session_start();

		exit(json_encode([
			'error'=>0,
			'connect' => $status_connect,			
			'data'=>$data,
			'summ' => Exchange::formatValue($summ),
			'sum_active' => Exchange::formatValue($sum_active),
			'sum_trade' => Exchange::formatValue($sum_trade),
			'grafema' => $grafema,
			'address' => $address,
			'id' => $id,
		]));
	}
	
	/**
	 * actionAddtarget()
	 */
	public function actionAddtarget()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		session_write_close();
		
		$input = file_get_contents('php://input');
		$array = @json_decode($input, true);

		if (empty($array['symbol'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing symbol')]));				
		} 

		if (empty($array['price'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing price')]));		
		}
		
		if (empty($array['coins'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing coins')]));		
		}
		
		if (empty($array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing token')]));	
		} 
		
		if (empty($array['multiply'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing multiply')]));	
		} 
		
		if (empty($array['description'])) {
			$array['description'] = '';	
		} 
		
		if (empty($array['current_price'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing price')]));	
		}
		
		if (!TelegramApi::validateUser($array['log_id'], $array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Incorrect token')]));	
		}
		
		$modelChatbotLog = ApiChatbot::getChatbotLog($array['log_id']);
		if (empty($modelChatbotLog)) {
			return false;		
		}
		
		$save_targets = ApiChatbot::saveTargets(
			$array['log_id'], 
			$array['symbol'], 
			$array['price'], 
			$array['coins'],
			$array['description'],
			$array['current_price'],
			$array['multiply']
		);
		
		if (empty($save_targets) || !is_array($save_targets)) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Server not responding')]));	
		}
		
		if(!empty($save_targets['error'])) {
			exit(json_encode(['error'=>1, 'message'=>$save_targets['message']]));	
		}
		
		$save_targets['symbol'] = $array['symbol'];
		$save_targets['price'] = $array['price'];

		$targets = ApiChatbot::getTargets($array['log_id']);
		
		session_start();

		exit(json_encode(['error'=>0, 'message'=>$save_targets, 'targets'=>$targets]));	
	}
	
	/**
	 * actionAlassistant()
	 */
	public function actionAlassistant()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		session_write_close();

		$input = file_get_contents('php://input');
		$array = @json_decode($input, true);
		
		$apr = '';

		if (empty($array['log_id'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not ID')]));
		}
	
		if (empty($array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing token')]));	
		} 
		
		if (!TelegramApi::validateUser($array['log_id'], $array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Incorrect token')]));	
		}
		
		if (empty($array['type'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing Type')]));	
		}
		
		if (empty($array['data'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing Question')]));	
		}

		$apr = 0;
		if (!empty($array['coin'])) {
			$apr = WalletApi::pstatic()->getAPR($array['coin']);			
		}

		if ($array['type']==3) {

			$portfolio = [
				"ton" => [0 => ["active" => []]],
				"bybit" => [0 => ["active" =>[], "trading"=>[]]],
				"okx" => [0 => ["active" =>[], "trading"=>[]]],
				"sol" => [0 => ["active" => []]],
				"sui" => [0 => ["active" => []]],
			];
			
			if (!empty($array['portfolio'])) {
				$portfolio = $array['portfolio'];
			}
			
			$portfolio = json_encode($portfolio);
		
			$answer = AiagentApi::pstatic()->getQuestion($array['data'], $array['coin'], $portfolio);
			$answer['apr'] = $apr;
			exit(json_encode($answer));	

		} else if ($array['type']==4) {

			$answer = WalletApi::pstatic()->createWallet($array['log_id']);
			exit(json_encode($answer));
			
		} else if ($array['type']==5) {

			$answer = WalletApi::pstatic()->sendButtonProcess($array['data'], $array['log_id']);
			exit(json_encode($answer));

		} else if ($array['type']==8) {
			
			$id_client = Yii::$app->user->getId();
			$wallet = ApiChatbot::getWallet($id_client);
			exit(json_encode($wallet));
			
		} else if ($array['type']==9) {

			
			//print_r($array);
			
			/*
			$portfolio = [
				"ton" => [0 => ["active" => []]],
				"bybit" => [0 => ["active" =>[], "trading"=>[]]],
				"okx" => [0 => ["active" =>[], "trading"=>[]]],
				"sol" => [0 => ["active" => []]],
				"sui" => [0 => ["active" => []]],
			];
			
			if (!empty($array['portfolio'])) {
				$portfolio = $array['portfolio'];
			}
			
			$portfolio = json_encode($portfolio);
			
			$answer = AiagentApi::pstatic()->getQuestion($array['data'], $portfolio);
			*/
			
		} else if ($array['type']==10) {
			
			$answer = SUIApi2::pstatic()->transactionStatusSui($array['data']);
			exit(json_encode($answer));
			
		} else if ($array['type']==11) {

			$id_client = Yii::$app->user->getId();
			$digest = WalletApi::pstatic()->getClaimallNaviRewards($id_client);
			if (!empty($digest['error'])) {
				exit(json_encode($digest));
			}
			
			$wallet = ApiChatbot::getWallet($id_client);
			$wallet['digest'] = $digest;
			exit(json_encode($wallet));

		} else {
		
			$answer = GPTApi2::pstatic()->getQuestion(json_encode($array['data']), $array['log_id'], $array['type']);
			if (empty($answer['error'])) {
				ApiChatbot::sendMessageToChat($array['log_id'], $answer);
				exit(json_encode(['error'=>0, 'message'=>Yii::t('Api', 'Successful Send')]));	
			}
			
			exit(json_encode($answer));
		}
	}
	
	/**
	 * actionLaunchpools()
	 */
	public function actionLaunchpools()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		session_write_close();

		$input = file_get_contents('php://input');
		$array = @json_decode($input, true);

		if (empty($array['log_id'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not ID')]));
		}
	
		if (empty($array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing token')]));	
		} 
		
		if (!TelegramApi::validateUser($array['log_id'], $array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Incorrect token')]));	
		}
		
		$pools = LaunchpoolsApi::pstatic()->getPools();
		if (empty($pools)) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Api', 'Not Pools')]));	
		}
		
		exit(json_encode($pools));
	}
	
	/**
	 * actionAptospools()
	 */
	public function actionAptospools()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		session_write_close();

		$input = file_get_contents('php://input');
		$array = @json_decode($input, true);

		if (empty($array['log_id'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not ID')]));
		}
	
		if (empty($array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing token')]));	
		} 
		
		if (!TelegramApi::validateUser($array['log_id'], $array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Incorrect token')]));	
		}
		
		if (empty($array['token'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing token')]));	
		} 
		
		$pools = [];
		
		$apt = new AptospoolsApi;
		$apt->token = $array['token'];
		
		$pools = $apt->getPools();
		if (!empty($pools['error'])) {
			exit(json_encode(['error'=>1, 'message'=>$pools['message']]));	
		}
		
		exit(json_encode($pools['data']));
	}
	
	/**
	 * cmp($a=[], $b=[])
	 */
	public function cmp($a=[], $b=[])
	{	
		return ($a['sort'] < $b['sort']);
	}
}
