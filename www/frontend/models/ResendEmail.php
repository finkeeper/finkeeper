<?php


namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\base\View;
use common\models\Clients;
use frontend\components\SendMail;
use frontend\components\SendSMS;
use frontend\components\TagHandler;

class ResendEmail extends Model
{
    /**
     * @var string
     */
    public $login;
	public $verifyCode;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['login', 'trim'],
			['login', 'email'],
            ['login', 'required'],
			['login', 'validateLogin', 'skipOnEmpty'=> false],	
			[
				['verifyCode'], 
				\himiklab\yii2\recaptcha\ReCaptchaValidator3::className(),   
			],
        ];
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

			if (!Clients::findByEmailExists($this->login)) {
				$this->addError($attribute, Yii::t('Error', 'There is no user with this email address'));
				Yii::$app->session->setFlash('error', Yii::t('Error', 'There is no user with this email address'));
			}
			
		} else if (Clients::isPhone($this->login)) {

			// Временно отключаем номера телефонов (внести правки в source_message id 118)
			$this->addError($attribute, Yii::t('Error', 'Value is not an email or phone number'));
			Yii::$app->session->setFlash('error', Yii::t('Error', 'Value is not an email or phone number'));
			
			if (!Clients::findByPhoneExists($this->login)) {
				$this->addError($attribute, Yii::t('Error', 'There is no user with this phone number'));
				Yii::$app->session->setFlash('error', Yii::t('Error', 'There is no user with this phone number'));
			}
			
		} else {

			$this->addError($attribute, Yii::t('Error', 'Value is not an email or phone number'));
			Yii::$app->session->setFlash('error', Yii::t('Error', 'Value is not an email or phone number'));
			
		}
    }
	
	/**
     * Sends confirmation email to user
     *
     * @return bool whether the email was sent
     */
    public function sendEmail()
    {
        $clients = Clients::findOne([
            'email' => $this->login,
            'active' => Clients::STATUS_NOT_ACTIVE
        ]);

        if ($clients === null) {
            return false;
        }
		
		$clients->generateEmailVerificationToken();
		if (!$clients->save()) {
			return false;
		}
		
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
			$clients->name, 
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
     *
     * @return bool whether the email was sent
     */
    public function sendSMS()
    {
        $this->login = Clients::clearPhone($this->login);
		$clients = Clients::findOne([
            'phone' => $this->login,
            'active' => Clients::STATUS_NOT_ACTIVE
        ]);

        if ($clients === null) {
            return false;
        }

		$clients->generateEmailVerificationToken();
		$clients->generateSMSToken();
		if (!$clients->save()) {
			return false;
		}
		
		$message = 'Security Code';
		$subject = 'Security Code: '.$clients->sms_token;

		$result = SendSMS::send(
			$this->login, 
			$subject, 
			$message, 
			Yii::$app->params['from'], 
			Yii::$app->params['from_name']
		);
		
		if (!empty($result)) {
			return ['error'=>0, 'token'=>$clients->verification_token];
		}
		
		return ['error'=>1, 'message'=>Yii::t('Error', 'Error Send SMS')];	
    }
}
