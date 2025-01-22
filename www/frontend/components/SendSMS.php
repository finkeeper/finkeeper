<?php

namespace frontend\components;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
use phpmailer\phpmailer\PHPMailer;
use phpmailer\phpmailer\Exception;

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
class SendSMS
{
    public static function send($to, $subject, $message, $from='', $from_name='')
	{	
		return true;
	}

	/**
	 * request($data)
	 */
	private function request($data)
	{	
		
	}	
}