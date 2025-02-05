<?php

namespace frontend\modules\app\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\HttpException;
use common\models\Exchange;
use yii\rest\ActiveController;
use frontend\modules\app\models\ApiChatbot;
use frontend\modules\app\components\TelegramApi;
use frontend\modules\app\components\BybitApi;
use frontend\modules\app\components\TonApi;
use frontend\modules\app\components\OKXApi;
use frontend\modules\app\components\SOLApi;
use frontend\modules\app\components\GPTApi2;
use frontend\modules\app\components\SUIApi;
use frontend\modules\app\components\AppController;

/**
 * Default controller for the `service` module
 */
class DefaultController extends AppController
{		
	public $defaultAction = 'index'; 
	public $accessUser = false;
	public $limitCoins = 10;
	
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
		if (!Yii::$app->user->isGuest) {           
			$this->accessUser = true;			
        }
		
		$currency = Exchange::getDefaultCurrency();
		$grafema = Exchange::getGrafemCurrency($currency);

		if (!empty($this->accessUser)) {
			
			$id_client = Yii::$app->user->getId();
			$used_gpt1 = ApiChatbot::getUsedGPTChat(1);
			$used_gpt2 = ApiChatbot::getUsedGPTChat(2);
			
			$log = ApiChatbot::getUserLog($id_client);
			$id = $log->id;
			
			ApiChatbot::addMenuButton($id);

			$exchange = Exchange::getData();	

			$targets = ApiChatbot::getTargets($id);

			$friends = ApiChatbot::getReferralsData($id);		
			$status = ApiChatbot::getStatusConnect($id);
			
			$sc = TelegramApi::tg()->generateUserToken($id);
			
			$lang = ApiChatbot::getSettingsLang($id);
			Yii::$app->language=strtolower($lang).'-'.strtoupper($lang);
		
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
			]);
			
		} else {
	
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
	 * actionGetbybitbalance
	 */
	public function actionGetbybitbalance()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$input = file_get_contents('php://input');
		$array = @json_decode($input, true);

		if (empty($array['type'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing type balance')]));			
		}
		
		if (empty($array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing token')]));	
		} 
		
		if (!TelegramApi::validateUser($array['log_id'], $array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Incorrect token')]));	
		}
		
		if ($array['type']==1) {
			
			if (empty($array['uid'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing Bybit UID')]));			
			}
			
			if (empty($array['apikey'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing Bybit API Key')]));			
			}
			
			if (empty($array['apisecret'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing Bybit API Secret')]));			
			}
			
			if (empty($array['log_id'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not User Chat ID')]));			
			}
			
			$bybit = new BybitApi;
			$bybit->api_key = $array['apikey'];
			$bybit->secret_key = $array['apisecret'];
			$bybit->uid = $array['uid'];
			
			$save_tokens = ApiChatbot::saveTokens(2, $array['log_id'], $array['uid'], $array['apikey'], $array['apisecret']);

			if (
				empty($save_tokens) || 
				!is_array($save_tokens) || 
				!empty($save_tokens['error'])
			) {
				error_log($save_tokens['message']."\r\n".PHP_EOL, 3, dirname(__FILE__).'/log.log');
			}
			
		} else if ($array['type']==2) {
			
			if (empty($array['log_id'])) {
				return false;			
			}
			
			$modelChatbotLog = ApiChatbot::getChatbotLog($array['log_id']);
			if (empty($modelChatbotLog)) {
				return false;		
			}
			
			$modelTokens = ApiChatbot::getBybitTokens($modelChatbotLog->id_client);
			if (empty($modelTokens)) {
				return false;		
			}
			
			$bybit = new BybitApi;
			$bybit->api_key = $modelTokens->identify2;
			$bybit->secret_key = $modelTokens->identify3;
			$bybit->uid = $modelTokens->identify1;
			
		} else {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Incorrect type balance')]));	
		}

		$currency = Exchange::getDefaultCurrency();
		$grafema = Exchange::getGrafemCurrency($currency);
		$summ = 0;
		$sum_active = 0;
		$sum_trade = 0;
		$error = [];
		
		$data =[
			'active' => [],
			'trade' => [],
		];
		
		$list_coins = [];

		$response = $bybit->getWalletBalance('FUND');
		if (empty($response['error'])) {
			
			if (
				!empty($response['data']['result']) && 
				!empty($response['data']['result']['balance'])
			) {
				$inc=1;
				$index=1;
				foreach ($response['data']['result']['balance'] as $val) {
			
					$value = 0;

					if ($inc>$this->limitCoins) {
						$inc=1;
						$index++;		
					}
		
					$list_coins[$index][] = strtoupper($val['coin']);
					$inc++;

					if (empty($val['walletBalance']) || empty($val['coin'])) {
						continue;
					}
	
					$price = ApiChatbot::getPrice($val['coin'], $currency, 1);
					if (empty($price['error']) && !empty($price['data'])) {
						$value = $price['data']*$val['walletBalance'];	
					}
	
					$img = '/images/cryptologo/default_coin.webp';
					$img_name = strtolower($val['coin']);
					$path = getcwd().'/images/cryptologo/'.$img_name.'.webp';

					if (file_exists($path)) {
						$img = '/images/cryptologo/'.$img_name.'.webp';
					}
			
					if (!empty($value)) {
						if (is_float($value)) {
							$value = number_format($value, 12, '.', '');
						} else if (is_int($value)) {
							$value = number_format($value, 12, '.', '');
						} else {
							$value = $value*1;
							$value = number_format($value, 12, '.', '');
						}
						
						$summ += $value;
						$sum_active += $value;
					}
				
						
					if (!empty($val['walletBalance'])) {
						if (is_float($val['walletBalance'])) {
							$val['walletBalance'] = number_format($val['walletBalance'], 12, '.', '');
						} else if (is_int($val['walletBalance'])) {
							$val['walletBalance'] = number_format($val['walletBalance'], 12, '.', '');
						} else {
							$val['walletBalance'] = $val['walletBalance']*1;
							$val['walletBalance'] = number_format($val['walletBalance'], 12, '.', '');
						}
					}
			
					$currency_value = Exchange::formatValue($value);
					$class = 'middle_value';
					if ($currency_value<1) {
						$class = 'small_value';
					}
				
					$data['active'][] = [
						'balance' => Exchange::formatValue($val['walletBalance']),
						'name' => $val['coin'],
						'currency' => $currency,
						'sort' => $value,
						'currency_value' => $currency_value,
						'img' => $img,
						'symbol' => $val['coin'],
						'symbolid' => strtolower($val['coin']),
						'grafema' => $grafema,
						'class' => $class,
						'apr' => '',
						'price' => $price['data'],
						'asset' => $bybit->uid,
					];	
				}
				
				usort($data['active'], [$this, 'cmp']);

			} else {
				$error[] = Yii::t('Error', 'Not response');
			}
			
		} else {
			$error[] = $response['message'];
		}
	
		$str_coins = '';
		if (!empty($list_coins) && is_array($list_coins)) {
			
			foreach ($list_coins as $coins) {

				$str_coins = implode(',', $coins);

				$response = $bybit->getWalletBalance('UNIFIED', $str_coins);

				if (empty($response['error'])) {
					
					if (
						!empty($response['data']['result']) && 
						!empty($response['data']['result']['balance'])
					) {
						
						foreach ($response['data']['result']['balance'] as $val) {
						
							$value = 0;
							
							if (empty($val['walletBalance']) || empty($val['coin'])) {
								continue;
							}
							
							$price = ApiChatbot::getPrice($val['coin'], $currency, 1);
							if (empty($price['error']) && !empty($price['data'])) {
								$value = $price['data']*$val['walletBalance'];	
							}
							
							$img = '/images/cryptologo/default_coin.webp';
							$img_name = strtolower($val['coin']);
							$path = getcwd().'/images/cryptologo/'.$img_name.'.webp';

							if (file_exists($path)) {
								$img = '/images/cryptologo/'.$img_name.'.webp';
							}

							if (!empty($value)) {
								if (is_float($value)) {
									$value = number_format($value, 12, '.', '');
								} else if (is_int($value)) {
									$value = number_format($value, 12, '.', '');
								} else {
									$value = $value*1;
									$value = number_format($value, 12, '.', '');
								}
								
								$summ += $value;
								$sum_trade += $value;
							}
							
							if (!empty($val['walletBalance'])) {
								if (is_float($val['walletBalance'])) {
									$val['walletBalance'] = number_format($val['walletBalance'], 12, '.', '');
								} else if (is_int($val['walletBalance'])) {
									$val['walletBalance'] = number_format($val['walletBalance'], 12, '.', '');
								} else {
									$val['walletBalance'] = $val['walletBalance']*1;
									$val['walletBalance'] = number_format($val['walletBalance'], 12, '.', '');
								}
							}
							
							$currency_value = Exchange::formatValue($value);
							$class = 'middle_value';
							if ($currency_value<1) {
								$class = 'small_value';
							}
					
							$data['trade'][] = [
								'balance' => Exchange::formatValue($val['walletBalance']),
								'name' => $val['coin'],
								'currency' => $currency,
								'sort' => $value,
								'currency_value' => $currency_value,
								'img' => $img,
								'symbol' => $val['coin'],
								'symbolid' => strtolower($val['coin']),
								'grafema' => $grafema,
								'class' => $class,
								'apr' => '',
								'price' => $price['data'],
								'asset' => $bybit->uid,
							];	
						}
						
						usort($data['trade'], [$this, 'cmp']);
						
					} else {
						$error[] = Yii::t('Error', 'Not response');
					}
					
				} else {
					$error[] = $response['message'];
				}
			}
		}

		$status_connect = 0;
		if (ApiChatbot::saveStatusConnect(2, $array['log_id'], 1)) {
			$status_connect = 1;
		}

		if ($array['type']==1 && !empty($save_tokens['change_token'])) {
			if (!ApiChatbot::sendMessageConnectedTon($array['log_id'], 3)) {
			
			}
		}
		
		$err_st = 0;
		if (!empty($error)) {
			$err_st = 1;
		}

		exit(json_encode([
			'error'=>$err_st, 
			'data'=>$data,
			'summ' => Exchange::formatValue($summ),
			'sum_active' => Exchange::formatValue($sum_active),
			'sum_trade' => Exchange::formatValue($sum_trade),
			'grafema' => $grafema,
			'connect' => $status_connect,
			'message' => $error,
			'address' => $bybit->uid,
		]));
	}
	
	/** 
	 * actionGetokxbalance
	 */
	public function actionGetokxbalance()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$input = file_get_contents('php://input');
		$array = @json_decode($input, true);

		if (empty($array['type'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing type balance')]));			
		}
		
		if (empty($array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing token')]));	
		} 
		
		if (!TelegramApi::validateUser($array['log_id'], $array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Incorrect token')]));	
		}
		
		if ($array['type']==1) {
			
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
			
			if (empty($array['log_id'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not User Chat ID')]));			
			}
			
			$okx = new OKXApi;
			$okx->api_key = $array['apikey'];
			$okx->secret_key = $array['apisecret'];
			$okx->password = $array['password'];
			$okx->uid = $array['uid'];
			
			$save_tokens = ApiChatbot::saveTokens(3, $array['log_id'], $array['password'], $array['apikey'], $array['apisecret'], $array['uid']);

			if (
				empty($save_tokens) || 
				!is_array($save_tokens) || 
				!empty($save_tokens['error'])
			) {
				error_log($save_tokens['message']."\r\n".PHP_EOL, 3, dirname(__FILE__).'/log.log');
			}
			
		} else if ($array['type']==2) {	

			if (empty($array['log_id'])) {
				return false;			
			}
	
			$modelChatbotLog = ApiChatbot::getChatbotLog($array['log_id']);
			if (empty($modelChatbotLog)) {
				return false;		
			}
			
			$modelTokens = ApiChatbot::getOkxTokens($modelChatbotLog->id_client);
			if (empty($modelTokens)) {
				return false;		
			}

			$okx = new OKXApi;
			$okx->api_key = $modelTokens->identify2;
			$okx->secret_key = $modelTokens->identify3;
			$okx->password = $modelTokens->identify1;
			$okx->uid = $modelTokens->identify4;

		} else {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Incorrect type balance')]));	
		}
		
		$currency = Exchange::getDefaultCurrency();
		$grafema = Exchange::getGrafemCurrency($currency);
		$summ = 0;
		$sum_active = 0;
		$sum_trade = 0;
		$error = [];
		
		$data =[
			'active' => [],
			'trade' => [],
		];
		
		$response = $okx->getWalletBalance('FUND');
		if (empty($response['error'])) {
			
			if (!empty($response['data'])) {
				
				foreach ($response['data'] as $val) {
			
					$value = 0;

					if (empty($val['availBal']) || empty($val['ccy'])) {
						continue;
					}
					
					$price = ApiChatbot::getPrice($val['ccy'], $currency, 3);
					if (empty($price['error']) && !empty($price['data'])) {
						$value = $price['data']*$val['availBal'];	
					}
					
					$img = '/images/cryptologo/default_coin.webp';
					$img_name = strtolower($val['ccy']);
					$path = getcwd().'/images/cryptologo/'.$img_name.'.webp';

					if (file_exists($path)) {
						$img = '/images/cryptologo/'.$img_name.'.webp';
					}
					
					if (!empty($value)) {
						if (is_float($value)) {
							$value = number_format($value, 12, '.', '');
						} else if (is_int($value)) {
							$value = number_format($value, 12, '.', '');
						} else {
							$value = $value*1;
							$value = number_format($value, 12, '.', '');
						}
						
						$summ += $value;
						$sum_active += $value;
					}
						
					if (!empty($val['availBal'])) {
						if (is_float($val['availBal'])) {
							$val['availBal'] = number_format($val['availBal'], 12, '.', '');
						} else if (is_int($val['availBal'])) {
							$val['availBal'] = number_format($val['availBal'], 12, '.', '');
						} else {
							$val['availBal'] = $val['availBal']*1;
							$val['availBal'] = number_format($val['availBal'], 12, '.', '');
						}
					}
					
					$currency_value = Exchange::formatValue($value);
					$class = 'middle_value';
					if ($currency_value<1) {
						$class = 'small_value';
					}
			
					$data['active'][] = [
						'balance' => Exchange::formatValue($val['availBal']),
						'name' => $val['ccy'],
						'currency' => $currency,
						'sort' => $value,
						'currency_value' => $currency_value,
						'img' => $img,
						'symbol' => $val['ccy'],
						'symbolid' => strtolower($val['ccy']),
						'grafema' => $grafema,
						'class' => $class,
						'apr' => '',
						'price' => $price['data'],
						'asset' => $okx->uid,
					];		
				}

				usort($data['active'], [$this, 'cmp']);

			} else {
				$error[] = Yii::t('Error', 'Not response');
			}

		} else {
			exit(json_encode(['error'=>1, 'message'=>$response['message']]));
		}
		
		$response = $okx->getWalletBalance('UNIFIED');
		if (empty($response['error'])) {
			
			if (!empty($response['data'])) {
				
				foreach ($response['data'] as $val) {
				
					if (!empty($val['details']) && is_array($val['details'])) {
					
						foreach ($val['details'] as $asset) {					
			
							$value = 0;

							if (empty($asset['availBal']) || empty($asset['ccy'])) {
								continue;
							}

							$price = ApiChatbot::getPrice($asset['ccy'], $currency, 3);
							if (empty($price['error']) && !empty($price['data'])) {
								$value = $price['data']*$asset['availBal'];	
							}
					
							$img = '/images/cryptologo/default_coin.webp';
							$img_name = strtolower($asset['ccy']);
							$path = getcwd().'/images/cryptologo/'.$img_name.'.webp';

							if (file_exists($path)) {
								$img = '/images/cryptologo/'.$img_name.'.webp';
							}
					
							if (!empty($value)) {
								if (is_float($value)) {
									$value = number_format($value, 12, '.', '');
								} else if (is_int($value)) {
									$value = number_format($value, 12, '.', '');
								} else {
									$value = $value*1;
									$value = number_format($value, 12, '.', '');
								}
								
								$summ += $value;
								$sum_active += $value;
							}
								
							if (!empty($asset['availBal'])) {
								if (is_float($asset['availBal'])) {
									$asset['availBal'] = number_format($asset['availBal'], 12, '.', '');
								} else if (is_int($asset['availBal'])) {
									$asset['availBal'] = number_format($asset['availBal'], 12, '.', '');
								} else {
									$asset['availBal'] = $asset['availBal']*1;
									$asset['availBal'] = number_format($asset['availBal'], 12, '.', '');
								}
							}
							
							$currency_value = Exchange::formatValue($value);
							$class = 'middle_value';
							if ($currency_value<1) {
								$class = 'small_value';
							}
			
							$data['trade'][] = [
								'balance' => Exchange::formatValue($asset['availBal']),
								'name' => $asset['ccy'],
								'currency' => $currency,
								'sort' => $value,
								'currency_value' => $currency_value,
								'img' => $img,
								'symbol' => $asset['ccy'],
								'symbolid' => strtolower($asset['ccy']),
								'grafema' => $grafema,
								'class' => $class,
								'apr' => '',
								'price' => $price['data'],
								'asset' => $okx->uid,
							];		
						}
					}
				}
				
				usort($data['trade'], [$this, 'cmp']);
				
			} else {
				$error[] = Yii::t('Error', 'Not response');
			}
		
		} else {
			exit(json_encode(['error'=>1, 'message'=>$response['message']]));
		}
		
		$status_connect = 0;
		if (ApiChatbot::saveStatusConnect(3, $array['log_id'], 1)) {
			$status_connect = 1;
		}

		if ($array['type']==1 && !empty($save_tokens['change_token'])) {
			if (!ApiChatbot::sendMessageConnectedTon($array['log_id'], 5)) {
			
			}
		}		
		
		exit(json_encode([
			'error'=>0, 
			'data'=>$data,
			'summ' => Exchange::formatValue($summ),
			'sum_active' => Exchange::formatValue($sum_active),
			'sum_trade' => Exchange::formatValue($sum_trade),
			'grafema' => $grafema,
			'connect' => $status_connect,
			'address' => $okx->uid,
		]));
	}

	/** 
	 * actionGetsolbalance
	 */
	public function actionGetsolbalance()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$input = file_get_contents('php://input');
		$array = @json_decode($input, true);

		if (empty($array['type'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing type balance')]));			
		}
		
		if (empty($array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing token')]));	
		} 
		
		if (!TelegramApi::validateUser($array['log_id'], $array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Incorrect token')]));	
		}

		if ($array['type']==1) {
			
			if (empty($array['address'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing SOL Address Wallet')]));			
			}

			$sol = new SOLApi;
			$sol->address = $array['address'];
			
			$save_tokens = ApiChatbot::saveTokens(4, $array['log_id'], $array['address']);

			if (
				empty($save_tokens) || 
				!is_array($save_tokens) || 
				!empty($save_tokens['error'])
			) {
				error_log($save_tokens['message']."\r\n".PHP_EOL, 3, dirname(__FILE__).'/log.log');
			}
			
		} else if ($array['type']==2) {	

			if (empty($array['log_id'])) {
				return false;			
			}
			
			$modelChatbotLog = ApiChatbot::getChatbotLog($array['log_id']);
			if (empty($modelChatbotLog)) {
				return false;		
			}
			
			$modelTokens = ApiChatbot::getSolTokens($modelChatbotLog->id_client);
			if (empty($modelTokens)) {
				return false;		
			}

			$sol = new SOLApi;
			$sol->address = $modelTokens->identify1;

		} else {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Incorrect type balance')]));	
		}

		$currency = Exchange::getDefaultCurrency();
		$grafema = Exchange::getGrafemCurrency($currency);
		$summ = 0;
		$sum_active = 0;
		$sum_trade = 0;
		$error = [];
		
		$data =[];

		$response = $sol->getWalletBalance();
		if (empty($response['error'])) {
			
			if (!empty($response['data'])) {
				
				foreach ($response['data'] as $val) {
			
					$value = 0;

					if (empty($val['balance']) || empty($val['symbolid'])) {
						continue;
					}
					
					$price = ApiChatbot::getPrice($val['symbolid'], $currency, 3);
					if (empty($price['error']) && !empty($price['data'])) {
						$value = $price['data']*$val['balance'];	
					}
					
					if (empty($val['image'])) {
						$img = '/images/cryptologo/default_coin.webp';
						$img_name = strtolower($val['symbolid']);
						$path = getcwd().'/images/cryptologo/'.$img_name.'.webp';
					} else {
						$img = $val['image'];
					}

					if (file_exists($path)) {
						$img = '/images/cryptologo/'.$img_name.'.webp';
					}
					
					if (!empty($value)) {
						if (is_float($value)) {
							$value = number_format($value, 12, '.', '');
						} else if (is_int($value)) {
							$value = number_format($value, 12, '.', '');
						} else {
							$value = $value*1;
							$value = number_format($value, 12, '.', '');
						}
						
						$summ += $value;
						$sum_active += $value;
					}
						
					if (!empty($val['balance'])) {
						if (is_float($val['balance'])) {
							$val['balance'] = number_format($val['balance'], 12, '.', '');
						} else if (is_int($val['balance'])) {
							$val['balance'] = number_format($val['balance'], 12, '.', '');
						} else {
							$val['balance'] = $val['balance']*1;
							$val['balance'] = number_format($val['balance'], 12, '.', '');
						}
					}
					
					$currency_value = Exchange::formatValue($value);
					$class = 'middle_value';
					if ($currency_value<1) {
						$class = 'small_value';
					}
			
					$data[] = [
						'balance' => Exchange::formatValue($val['balance']),
						'name' => $val['name'],
						'currency' => $currency,
						'sort' => $value,
						'currency_value' => $currency_value,
						'img' => $img,
						'symbol' => $val['symbol'],
						'symbolid' => strtolower($val['symbolid']),
						'grafema' => $grafema,
						'class' => $class,
						'apr' => '',
						'price' => $price['data'],
						'asset' => $sol->address,
					];		
				}

				usort($data, [$this, 'cmp']);

			} 

		} else {
			$error[] = $response['message'];
		}
		
		$response = $sol->getTokenBalance();
		if (empty($response['error'])) {
			
			if (!empty($response['data'])) {
				
				foreach ($response['data'] as $val) {
			
					$value = 0;

					if (empty($val['balance']) || empty($val['symbolid'])) {
						continue;
					}

					if (!empty($val['price'])) {
						$price['data'] = $val['price'];
						$value = $val['price']*$val['balance'];
					} else {
						$price = ApiChatbot::getPrice($val['symbolid'], $currency, 3);
						if (empty($price['error']) && !empty($price['data'])) {
							$value = $price['data']*$val['balance'];	
						}
					}

					if (empty($val['img'])) {
						$img = '/images/cryptologo/default_coin.webp';
						$img_name = strtolower($val['symbolid']);
						$path = getcwd().'/images/cryptologo/'.$img_name.'.webp';
						
						if (file_exists($path)) {
							$img = '/images/cryptologo/'.$img_name.'.webp';
						}
						
					} else {
						$img = $val['img'];
					}

					if (!empty($value)) {
						if (is_float($value)) {
							$value = number_format($value, 12, '.', '');
						} else if (is_int($value)) {
							$value = number_format($value, 12, '.', '');
						} else {
							$value = $value*1;
							$value = number_format($value, 12, '.', '');
						}
						
						$summ += $value;
						$sum_active += $value;
					}
						
					if (!empty($val['balance'])) {
						if (is_float($val['balance'])) {
							$val['balance'] = number_format($val['balance'], 12, '.', '');
						} else if (is_int($val['balance'])) {
							$val['balance'] = number_format($val['balance'], 12, '.', '');
						} else {
							$val['balance'] = $val['balance']*1;
							$val['balance'] = number_format($val['balance'], 12, '.', '');
						}
					}
					
					$currency_value = Exchange::formatValue($value);
					$class = 'middle_value';
					if ($currency_value<1) {
						$class = 'small_value';
					}
			
					$data[] = [
						'balance' => Exchange::formatValue($val['balance']),
						'name' => $val['name'],
						'currency' => $currency,
						'sort' => $value,
						'currency_value' => $currency_value,
						'img' => $img,
						'symbol' => $val['symbol'],
						'symbolid' => strtolower($val['symbolid']),
						'grafema' => $grafema,
						'class' => $class,
						'apr' => '',
						'price' => $price['data'],
						'asset' => $sol->address,
					];		
				}

				usort($data, [$this, 'cmp']);

			} 

		} else {
			$error[] = $response['message'];
		}
		
		if (!empty($error)) {
			exit(json_encode([
				'error'=>1, 
				'message' => implode('; ', $error),
			]));
		}


		$status_connect = 0;
		if (ApiChatbot::saveStatusConnect(4, $array['log_id'], 1)) {
			$status_connect = 1;
		}

		if ($array['type']==1 && !empty($save_tokens['change_token'])) {
			if (!ApiChatbot::sendMessageConnectedTon($array['log_id'], 7)) {
			
			}
		}		

		exit(json_encode([
			'error'=>0, 
			'data'=>$data,
			'summ' => Exchange::formatValue($summ),
			'sum_active' => Exchange::formatValue($sum_active),
			'sum_trade' => Exchange::formatValue($sum_trade),
			'grafema' => $grafema,
			'connect' => $status_connect,
			'address' => $sol->address,
		]));
	}
	
	/** 
	 * actionGetsuibalance
	 */
	public function actionGetsuibalance()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$input = file_get_contents('php://input');
		$array = @json_decode($input, true);

		if (empty($array['type'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing type balance')]));			
		}
		
		if (empty($array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing token')]));	
		} 
		
		if (!TelegramApi::validateUser($array['log_id'], $array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Incorrect token')]));	
		}

		if ($array['type']==1) {
			
			if (empty($array['address'])) {
				exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing SUI Address Wallet')]));			
			}

			$sui = new SUIApi;
			$sui->address = $array['address'];
			
			$save_tokens = ApiChatbot::saveTokens(5, $array['log_id'], $array['address']);

			if (
				empty($save_tokens) || 
				!is_array($save_tokens) || 
				!empty($save_tokens['error'])
			) {
				error_log($save_tokens['message']."\r\n".PHP_EOL, 3, dirname(__FILE__).'/log.log');
			}

		} else if ($array['type']==2) {	

			if (empty($array['log_id'])) {
				return false;			
			}
			
			$modelChatbotLog = ApiChatbot::getChatbotLog($array['log_id']);
			if (empty($modelChatbotLog)) {
				return false;		
			}
			
			$modelTokens = ApiChatbot::getSuiTokens($modelChatbotLog->id_client);
			if (empty($modelTokens)) {
				return false;		
			}

			$sui = new SUIApi;
			$sui->address = $modelTokens->identify1;

		} else {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Incorrect type balance')]));	
		}

		$currency = Exchange::getDefaultCurrency();
		$grafema = Exchange::getGrafemCurrency($currency);
		$summ = 0;
		$sum_active = 0;
		$sum_trade = 0;
		$error = [];
		
		$data = [];
		
		$response = $sui->getWalletBalance();
		if (empty($response['error'])) {
			
			if (!empty($response['data']) && !empty($response['data'][0])) {
				
				foreach ($response['data'][0] as $val) {
			
					$value = 0;

					if (empty($val['balance']) || empty($val['symbolid'])) {
						continue;
					}
					
					if (empty($val['price'])) {
						$price = ApiChatbot::getPrice($val['symbolid'], $currency, 3);
						if (empty($price['error']) && !empty($price['data'])) {
							$value = $price['data']*$val['balance'];	
						}
					} else {
						$price['data'] = $val['price'];
						$value = $val['price']*$val['balance'];
					}
		
					if (empty($val['image'])) {
						$img = '/images/cryptologo/default_coin.webp';
						$img_name = strtolower($val['symbolid']);
						$path = getcwd().'/images/cryptologo/'.$img_name.'.webp';
					} else {
						$img = $val['image'];
					}

					if (file_exists($path)) {
						$img = '/images/cryptologo/'.$img_name.'.webp';
					}
					
					if (!empty($value)) {
						if (is_float($value)) {
							$value = number_format($value, 12, '.', '');
						} else if (is_int($value)) {
							$value = number_format($value, 12, '.', '');
						} else {
							$value = $value*1;
							$value = number_format($value, 12, '.', '');
						}
						
						$summ += $value;
						$sum_active += $value;
					}
						
					if (!empty($val['balance'])) {
						if (is_float($val['balance'])) {
							$val['balance'] = number_format($val['balance'], 12, '.', '');
						} else if (is_int($val['balance'])) {
							$val['balance'] = number_format($val['balance'], 12, '.', '');
						} else {
							$val['balance'] = $val['balance']*1;
							$val['balance'] = number_format($val['balance'], 12, '.', '');
						}
					}
					
					$currency_value = Exchange::formatValue($value);
					$class = 'middle_value';
					if ($currency_value<1) {
						$class = 'small_value';
					}
			
					$data[] = [
						'balance' => Exchange::formatValue($val['balance']),
						'name' => $val['name'],
						'currency' => $currency,
						'sort' => $value,
						'currency_value' => $currency_value,
						'img' => $img,
						'symbol' => $val['symbol'],
						'symbolid' => strtolower($val['symbolid']),
						'grafema' => $grafema,
						'class' => $class,
						'apr' => '',
						'price' => $price['data'],
						'asset' => $sui->address,
					];		
				}

				usort($data, [$this, 'cmp']);

			} 

		} else {
			$error[] = $response['message'];
		}
		
		if (!empty($error)) {
			exit(json_encode([
				'error'=>1, 
				'message' => implode('; ', $error),
			]));
		}

		$status_connect = 0;
		if (ApiChatbot::saveStatusConnect(5, $array['log_id'], 1)) {
			$status_connect = 1;
		}

		if ($array['type']==1 && !empty($save_tokens['change_token'])) {
			if (!ApiChatbot::sendMessageConnectedTon($array['log_id'], 9)) {
			
			}
		}	
		
		$address = SUIApi::pstatic()->getAddressParse($sui->address);
	
		exit(json_encode([
			'error'=>0, 
			'data'=>$data,
			'summ' => Exchange::formatValue($summ),
			'sum_active' => Exchange::formatValue($sum_active),
			'sum_trade' => Exchange::formatValue($sum_trade),
			'grafema' => $grafema,
			'connect' => $status_connect,
			'address' => $address,
		]));
	}

	/** 
	 * https://api.bank.ctfn.pro/v3/datas/getaddress
	 */
	public function actionGetaddress()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$input = file_get_contents('php://input');
		$array = @json_decode($input, true);
	
		if (empty($array['address']) || empty($array['log_id'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not Address')]));
		}
		
		if (empty($array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing token')]));	
		} 
		
		if (!TelegramApi::validateUser($array['log_id'], $array['sc'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Incorrect token')]));	
		}

		$save_tokens = ApiChatbot::saveTokens(1, $array['log_id'], $array['address']);
		if (
			empty($save_tokens) || 
			!is_array($save_tokens) || 
			!empty($save_tokens['error'])
		) {
			error_log($save_tokens['message']."\r\n".PHP_EOL, 3, dirname(__FILE__).'/log.log');
		}

		$data = [];
		$summ = 0;
		$currency = Exchange::getDefaultCurrency();
		$grafema = Exchange::getGrafemCurrency($currency);
		$pool1 = Exchange::getPoolInfo('TON/USDT');
		$ton_apr = '';
		if (!empty($pool1) && !empty($pool1['pool_info']) && !empty($pool1['pool_info']['apr'])) {
			$ton_apr = $pool1['pool_info']['apr'];
		}

		$ton = TonApi::pstatic()->getTonBalance($array['address']);
		if (empty($ton['error'])) {

			if (empty($ton['empty'])) {

				$data[0] = $ton['data'];

				$ton_price = ApiChatbot::getPrice('ton', $currency, 2);	
				if (empty($ton_price['error'])) {
					$value = $ton_price['data']*$data[0]['balance'];
					$class = 'middle_value';
					if ($value<1) {
						$class = 'small_value';
					}
					$summ += $value;
					$data[0]['sort'] = $value;
					$data[0]['currency_value'] = Exchange::formatValue($value);
					$data[0]['class'] = $class;
					$data[0]['apr'] = $ton_apr;
					$data[0]['price'] = $ton_price['data'];
					$data[0]['asset'] = $array['address'];
				}
			}
		}
		
		$pool2 = Exchange::getPoolInfo('AquaUSD/USDT');
		$usdt_apr = '';
		if (!empty($pool2) && !empty($pool2['pool_info']) && !empty($pool2['pool_info']['apr'])) {
			$usdt_apr = $pool2['pool_info']['apr'];
		}
		
		$jettons = TonApi::pstatic()->getJettonsBalance($array['address'], $currency);
		if (empty($jettons['error'])) {
			
			if (empty($jettons['empty'])) {
				foreach ($jettons['data'] as $jetton) {
					
					if ($jetton['symbolid']=='usdt') {
						$jetton['apr'] = $usdt_apr;
					} else {
						$jetton['apr'] = '';
					}					
					
					$jetton['asset'] = $array['address'];					
					$summ += $jetton['sort'];
					$data[] = $jetton;
				}
				
				usort($data, [$this, 'cmp']);
			}
		}
		
		$status_connect = 0;
		if (ApiChatbot::saveStatusConnect(1, $array['log_id'], 1)) {
			$status_connect = 1;
		}
		
		if (!empty($save_tokens['change_token'])) {
			if (!ApiChatbot::sendMessageConnectedTon($array['log_id'], 1)) {
				
			}	
		}
		
		$address = TonApi::pstatic()->getAddressParse($array['address']);

		exit(json_encode([
			'error'=>0, 
			'data'=>$data,
			'summ' => Exchange::formatValue($summ),
			'grafema' => $grafema,
			'connect' => $status_connect,
			'address' => $address,
		]));
	}
	
	/**
	 * actionTonconnected()
	 */
	public function actionTonconnected()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
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
		
		$data = [];
		$summ = 0;
		$currency = Exchange::getDefaultCurrency();
		$grafema = Exchange::getGrafemCurrency($currency);
		$tokens = ApiChatbot::getTokens($array['log_id']);
		if (empty($tokens) || empty($tokens['ton']) || empty($tokens['ton']['address'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not Address')]));
		}

		$pool1 = Exchange::getPoolInfo('TON/USDT');
		$ton_apr = '';
		if (!empty($pool1) && !empty($pool1['pool_info']) && !empty($pool1['pool_info']['apr'])) {
			$ton_apr = $pool1['pool_info']['apr'];
		}
			
		$ton = TonApi::pstatic()->getTonBalance($tokens['ton']['address']);
		if (empty($ton['error'])) {

			if (empty($ton['empty'])) {

				$data[0] = $ton['data'];
				
				$ton_price = ApiChatbot::getPrice('ton', $currency, 2);
				if (empty($ton_price['error'])) {
					$value = $ton_price['data']*$data[0]['balance'];
					$class = 'middle_value';
					if ($value<1) {
						$class = 'small_value';
					}
					$summ += $value;
					$data[0]['sort'] = $value;
					$data[0]['currency_value'] = Exchange::formatValue($value);
					$data[0]['class'] = $class;
					$data[0]['apr'] = $ton_apr;
					$data[0]['price'] = $ton_price['data'];
					$data[0]['asset'] = $tokens['ton']['address'];
				}
			}
		}
		
		$pool2 = Exchange::getPoolInfo('AquaUSD/USDT');
		$usdt_apr = '';
		if (!empty($pool2) && !empty($pool2['pool_info']) && !empty($pool2['pool_info']['apr'])) {
			$usdt_apr = $pool2['pool_info']['apr'];
		}
		
		$jettons = TonApi::pstatic()->getJettonsBalance($tokens['ton']['address'], $currency);
		if (empty($jettons['error'])) {
			
			if (empty($jettons['empty'])) {
				foreach ($jettons['data'] as $jetton) {
					
					if ($jetton['symbolid']=='usdt') {
						$jetton['apr'] = $usdt_apr;
					} else {
						$jetton['apr'] = '';
					}	
					
					$jetton['asset'] = $tokens['ton']['address'];
					$summ += $jetton['sort'];
					$data[] = $jetton;
				}
				
				usort($data, [$this, 'cmp']);
			}
		}
		
		$address = TonApi::pstatic()->getAddressParse($tokens['ton']['address']);

		exit(json_encode([
			'error'=>0, 
			'data'=>$data,
			'summ' => Exchange::formatValue($summ),
			'grafema' => $grafema,
			'connect' => 1,
			'address' => $address,
		]));	
	}
	
	/**
	 * actionTondisconnect()
	 */
	public function actionTondisconnect()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$input = file_get_contents('php://input');
		$array = @json_decode($input, true);
	
		if (empty($array['log_id'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not Address')]));
		}
		
		$status_connect = 0;
		if (ApiChatbot::saveStatusConnect(1, $array['log_id'], 0)) {
			$status_connect = 0;
		}
		
		//if (!ApiChatbot::sendMessageConnectedTon($array['log_id'], 2)) {
			
		//}
		
		exit(json_encode([
			'error'=>0, 
			'connect' => $status_connect,
		]));
	}
	
	/**
	 * actionBybitdisconnect()
	 */
	public function actionBybitdisconnect()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$input = file_get_contents('php://input');
		$array = @json_decode($input, true);
	
		if (empty($array['log_id'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not Address')]));
		}

		$status_connect = 0;
		if (ApiChatbot::saveStatusConnect(2, $array['log_id'], 0)) {
			$status_connect = 0;
		}
		
		//if (!ApiChatbot::sendMessageConnectedTon($array['log_id'], 4)) {
			
		//}
		
		exit(json_encode([
			'error'=>0, 
			'connect' => $status_connect,
		]));
	}
	
	/**
	 * actionOkxdisconnect()
	 */
	public function actionOkxdisconnect()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$input = file_get_contents('php://input');
		$array = @json_decode($input, true);
	
		if (empty($array['log_id'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not Address')]));
		}

		$status_connect = 0;
		if (ApiChatbot::saveStatusConnect(3, $array['log_id'], 0)) {
			$status_connect = 0;
		}
		
		//if (!ApiChatbot::sendMessageConnectedTon($array['log_id'], 4)) {
			
		//}
		
		exit(json_encode([
			'error'=>0, 
			'connect' => $status_connect,
		]));
	}
	
	/**
	 * actionOkxdisconnect()
	 */
	public function actionSoldisconnect()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$input = file_get_contents('php://input');
		$array = @json_decode($input, true);
	
		if (empty($array['log_id'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not Address')]));
		}

		$status_connect = 0;
		if (ApiChatbot::saveStatusConnect(4, $array['log_id'], 0)) {
			$status_connect = 0;
		}
		
		//if (!ApiChatbot::sendMessageConnectedTon($array['log_id'], 8)) {
			
		//}
		
		exit(json_encode([
			'error'=>0, 
			'connect' => $status_connect,
		]));
	}
	
	/**
	 * actionSuidisconnect()
	 */
	public function actionSuidisconnect()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$input = file_get_contents('php://input');
		$array = @json_decode($input, true);
	
		if (empty($array['log_id'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Not Address')]));
		}

		$status_connect = 0;
		if (ApiChatbot::saveStatusConnect(5, $array['log_id'], 0)) {
			$status_connect = 0;
		}
		
		//if (!ApiChatbot::sendMessageConnectedTon($array['log_id'], 8)) {
			
		//}
		
		exit(json_encode([
			'error'=>0, 
			'connect' => $status_connect,
		]));
	}
	
	/**
	 * actionAddtarget()
	 */
	public function actionAddtarget()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
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

		exit(json_encode(['error'=>0, 'message'=>$save_targets, 'targets'=>$targets]));	
	}
	
	/**
	 * actionAlassistant()
	 */
	public function actionAlassistant()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

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
		
		if (empty($array['data'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing Question')]));	
		} 
		
		if (empty($array['type'])) {
			exit(json_encode(['error'=>1, 'message'=>Yii::t('Error', 'Missing Type')]));	
		}

		$answer = GPTApi2::pstatic()->getQuestion(json_encode($array['data']), $array['log_id'], $array['type']);
		if (empty($answer['error'])) {
			ApiChatbot::sendMessageToChat($array['log_id'], $answer);
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * cmp($a=[], $b=[])
	 */
	public function cmp($a=[], $b=[])
	{	
		return ($a['sort'] < $b['sort']);
	}
}
