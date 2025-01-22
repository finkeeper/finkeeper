<?php

namespace frontend\components;

use Yii;
use yii\web\HttpException;

/** 
 * Замена тегов, регулярные выражения
 */
class TagHandler
{	
	public static function replaceEmailTemplateAuth($model, $message, $test = false)
	{
		$search = [
			'{name}', 
			'{from_name}', 
			'{site}',
			'{url_reset_password}',
			'{from}',
			'{email}',
			'{url_confirm_registration}'
		];		

		if (empty($test)) {

			$name        				= !empty($model->name) ? ', '.$model->name : '!';
			$token_reset 				= !empty($model->password_reset_token) ? $model->password_reset_token : '';
			$from_name   				= !empty(Yii::$app->params['from_name']) ? Yii::$app->params['from_name'] : '';
			$from       		    	= !empty(Yii::$app->params['from']) ? Yii::$app->params['from'] : '';
			$site        				= !empty(Yii::$app->params['site']) ? Yii::$app->params['site'] : '';
			$email           			= !empty($model->email) ? $model->email : '';
			$token_verification			= !empty($model->verification_token) ? $model->verification_token : '';
			
			$url_confirm_registration 	= 'https://'.$site.'/confirm?token='.$token_verification;
			$url_reset_password 	    = 'https://'.$site.'/passwordresetconfirm?token='.$token_reset;

			$replace = [
				$name, 
				$from_name, 
				$site,
				$url_reset_password, 
				$from, 
				$email,
				$url_confirm_registration,
			];
			
		} else {
			 
			$replace = [
				'USER NAME', 
				'FROM NAME', 
				'SITE',
				'URL PASSWORD RESET', 
				'FROM', 
				'EMAIL',
				'URL CONFIRM REGISTRATION',
			];
		}

		return str_replace($search, $replace, $message);
	}
}