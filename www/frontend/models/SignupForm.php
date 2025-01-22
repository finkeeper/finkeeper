<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\base\View;
use common\models\Clients;
use frontend\components\SendMail;
use frontend\components\SendSMS;
use frontend\components\TagHandler;

/**
 * Signup form
 */
class SignupForm extends Model
{
	public $login;
	public $password;
    public $rememberMe = true;
	public $verifyCode;
	public $agree_personal;
	public $email;
	public $phone;
	public $name;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['login', 'trim'],
            ['login', 'required'],
			['login', 'email'], 
			['login', 'validateLogin', 'skipOnEmpty'=> false],
			//['login', 'email'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
			//['password', 'match', 'pattern' => '/^[a-zA-Z0-9]{8,32}$/i'],
			
			['name', 'string', 'min' => 3, 'max' => 60],
			//['name', 'required'],

            [
				['agree_personal'], 
				'required', 
				'requiredValue' => 1, 
				'message' => Yii::t('Error', 'Required Agree Personal')
			],
			
			[
				 ['verifyCode'], 
				 \himiklab\yii2\recaptcha\ReCaptchaValidator3::className(), 
			 ],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
		
		$clients = new Clients();
		$clients->generateAuthKey();
		$clients->generateEmailVerificationToken();
		$clients->getClientIP();
		$clients->getHttpToken($this->password);
		$clients->setPassword($this->password);
		$clients->getIdentify();
		$clients->name = $this->name;

        if (Clients::isEmail($this->login)) {

			$clients->login = $clients->generateLogin($this->login);
			$clients->email = $this->login;
			if ($clients->save() && $this->sendEmail($clients)) {
				return ['email' => true, 'token' => $clients->verification_token]; 
			}
			
			return false;
			
		} elseif (Clients::isPhone($this->login)) {

			$clients->generateSMSToken();
			$this->login = Clients::clearPhone($this->login);
			$clients->login = $clients->generateLogin($this->login);
			$clients->phone = $this->login;
			if ($clients->save() && $this->sendSMS($clients)) {
				return ['phone'=>true, 'token' => $clients->verification_token]; 
			}
			
			return false;
		}
    }
	
	/**
     * Validates the Autor.
     * This method serves as the inline validation for Autor.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateLogin($attribute, $params)
    {
		if (Clients::isEmail($this->login)) {

			if (!self::isUniqueEmail($this->login)) {
				$this->addError('login', Yii::t('Error', 'This email address has already been taken'));
				Yii::$app->session->setFlash('error', Yii::t('Error', 'This email address has already been taken'));
			}
			
		} elseif (Clients::isPhone($this->login)) {

			// Временно отключаем номера телефонов (внести правки в source_message id 118)
			$this->addError('login', Yii::t('Error', 'Value is not an email or phone number'));
			Yii::$app->session->setFlash('error', Yii::t('Error', 'Value is not an email or phone number'));
			
			if (!self::isUniquePhone($this->login)) {
				$this->addError('login', Yii::t('Error', 'This phone number has already been taken'));
				Yii::$app->session->setFlash('error', Yii::t('Error', 'This phone number has already been taken'));
			}
			
		} else {

			$this->addError('login', Yii::t('Error', 'Value is not an email or phone number'));
			Yii::$app->session->setFlash('error', Yii::t('Error', 'Value is not an email or phone number'));

		}
    }

   /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($clients)
    {
		$subject = Yii::t('Email', 'Registration in the service subject');
		$message = Yii::t('Email', 'Registration in the service message');
		if (empty($subject) || empty($message)) {
			return false;
		}

		$subject = TagHandler::replaceEmailTemplateAuth($clients, $subject);
		$message = TagHandler::replaceEmailTemplateAuth($clients, $message);

		$header['X-Mailru-Msgtype'] = 'letter-ctfn.pro';
		$header['List-id'] = 'ctfn.pro';
		$header['Feedback-ID'] = $clients->id.':letter:ctfn.pro';
		$header['MessageID'] = ['ctfn.pro',0,1,$clients->id,Yii::$app->params['platform'],0,0];

		return SendMail::send(
			$this->login, 
			$this->name, 
			$subject, 
			$message, 
			Yii::$app->params['senderEmail'], 
			Yii::$app->params['senderName'], 
			Yii::$app->params['adminEmail'],  
			$header
		);
    }
	
	/**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendSMS($clients)
    {
        $message = 'Security Code';
		$subject = 'Security Code: '.$clients->sms_token;

		return SendSMS::send(
			$this->phone, 
			$subject, 
			$message, 
			Yii::$app->params['from'], 
			Yii::$app->params['from_name']
		);
    }
	
	/**
     * isUniqueEmail($email)
     */
    protected function isUniqueEmail($email)
    {
		if (Clients::findByEmailExists($email, 1)) {
			return false;
		}
		
		if (Clients::findByEmailExists($email)) {
			return false;
		}

		return true;
	}
	
	/**
     * isUniquePhone($phone)
     */
    protected function isUniquePhone($phone)
    {
		$phone = Clients::clearPhone($phone);
		if (Clients::findByPhoneExists($phone, 1)) {
			return false;
		}
		
		if (Clients::findByPhoneExists($phone)) {
			return false;
		}

		return true;
	}
}
