<?php

namespace backend\components;

require dirname(dirname(dirname(__FILE__))).'/vendor/phpmailer/phpmailer/src/Exception.php';
require dirname(dirname(dirname(__FILE__))).'/vendor/phpmailer/phpmailer/src/PHPMailer.php';

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
use phpmailer\phpmailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/** 
 * $to Адрес кому отправляем письмо
 * $to_name Имя кому отправляем письмо
 * $subject Заголовок письма
 * $message Тело письмо
 * $from Адрес от кого отправляем письмо
 * $from_name Имя от кого отправляем письмо
 * $reply_to Адрес на который слать ответы
 * $header Дополнительные заголовки
 * $attachment Вложения
 * $type Тип сообщения
 */
class SendMail
{
    public static function send($to, $to_name='', $subject, $message, $from='', $from_name='', $reply_to='', $header = [], $attachment = [])
	{	
		if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
			return false;
		}
		
		if (!filter_var($from, FILTER_VALIDATE_EMAIL)) {
			return false;
		}

		if (!filter_var($reply_to, FILTER_VALIDATE_EMAIL)) {
			return false;
		}

		$data = [
			'to'             => $to,
			'to_name'        => $to_name,
			'subject'        => $subject,
			'message'        => wordwrap($message, 500,"\r\n\t",true),
			'from'           => $from,
			'from_name'      => $from_name,
			'reply_to'       => $reply_to,
			'message_id'     => $header['MessageID'], 
			'custom_header'  => $header,
			'attachment'     => $attachment,
		];

		if(self::request($data)) {
			return true;
		} 
		
		return false;
	}

	/**
	 * request($data)
	 */
	public static function request($data)
	{	
		if (
			empty(Yii::$app->params['postman_login']) || 
			empty(Yii::$app->params['postman_passwd']) || 
			empty(Yii::$app->params['postman_domain'])
		) {
			return self::sendPHPMailer($data);
		}
		
		$send_url = 'https://'.Yii::$app->params['postman_domain'].'/send';
		$login = Yii::$app->params['postman_login'];
		$passwd = Yii::$app->params['postman_passwd'];
		$data64 = base64_encode(serialize($data));

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $send_url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);	
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_POSTREDIR, 3);	
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(['data'=>$data64]));
		curl_setopt($curl, CURLOPT_HTTPHEADER, [
			'Authorization: Basic ' . base64_encode($login . ':' . $passwd)
		]);
		
		$api_answer = curl_exec($curl);
		curl_close($curl);

		if (!empty($api_answer)) {
			$result = json_decode((string) $api_answer, true);
		
			if (empty($result) || empty($result['message'])) {
				
				error_log('Error: '.date('Y-m-d H:i:s')."\r\n No Answer\r\n".PHP_EOL, 3, dirname(dirname(__FILE__)).'/runtime/logs/sendlog.log');
				
				return self::sendPHPMailer($data);
				
			} elseif (empty($result['error'])) {
				
				return true;
				
			} else {
				
				error_log('Error: '.date('Y-m-d H:i:s')."\r\n".$result['message']."\r\n".PHP_EOL, 3, dirname(dirname(__FILE__)).'/runtime/logs/sendlog.log');
				
				return self::sendPHPMailer($data);
			}
		}
	}	
	
	/**
	 * sendPHPMailer($data=[])
	 */
	public static function sendPHPMailer($data=[])
	{
		if (empty($data) || !is_array($data)) {
			return false;
		}
		
		$PHPMailer = new PHPMailer(true);

		$PHPMailer->Priority = 3; 
		$PHPMailer->IsHTML(true); 
		$PHPMailer->Subject = $data['subject'];
		$PHPMailer->Body = $data['message'];
		$PHPMailer->AddAddress($data['to'], $data['to_name']);
		$PHPMailer->CharSet = 'utf-8'; 
		$PHPMailer->From = $data['from']; 
		$PHPMailer->FromName = $data['from_name'];
		$PHPMailer->Sender = 'nosender@'.Yii::$app->params['site'];		
		$PHPMailer->AddCustomHeader('Precedence: bulk'); 
		
		return $PHPMailer->Send();
	}
}