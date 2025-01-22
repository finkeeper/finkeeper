<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\base\View;
use yii\helpers\Html;
use common\models\Clients;
use frontend\components\SendMail;
use frontend\components\SendSMS;
use frontend\components\TagHandler;

/**
 * Password reset request form
 */
class PasswordReset extends Model
{
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
			
			if (Clients::findByEmailExists($this->login)) {
				
				$message = Yii::t('Error', 'The account registered for this email is not activated').'<div class="warning-link">'.Html::a(Yii::t('Error', 'Request reactivation'), ['/resendemail', 'login'=>$this->login]).'</div>';
				
				$this->addError($attribute, $message);
				Yii::$app->session->setFlash('error', $message);		
				
			} else if (!Clients::findByEmailExists($this->login, 1)) {
				
				$this->addError($attribute, Yii::t('Error', 'There is no user with this email address'));
				Yii::$app->session->setFlash('error', Yii::t('Error', 'There is no user with this email address'));
			}
			
		} elseif (Clients::isPhone($this->login)) {

			// Временно отключаем номера телефонов (внести правки в source_message id 118)
			$this->addError($attribute, Yii::t('Error', 'Value is not an email or phone number'));
			Yii::$app->session->setFlash('error', Yii::t('Error', 'Value is not an email or phone number'));
			
			if (Clients::findByPhoneExists($this->login)) {
				
				$message = Yii::t('Error', 'The account registered to this number is not activated').'<div class="warning-link">'.Html::a(Yii::t('Error', 'Request reactivation'), ['/resendemail', 'login'=>$this->login]).'</div>';

				$this->addError($attribute, $message);
				Yii::$app->session->setFlash('error', $message);
				
			} else if (!Clients::findByPhoneExists($this->login, 1)) {
				
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
            'active' => Clients::STATUS_ACTIVE
        ]);

        if ($clients === null) {
            return false;
        }
		
		$clients->generatePasswordResetToken();
		if (!$clients->save()) {
			return false;
		}

		$subject = Yii::t('Email', 'Password reset subject');
		$message = Yii::t('Email', 'Password reset message');
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
     * Sends confirmation phone to user
     *
     * @return bool whether the phone was sent
     */
    public function sendSMS()
    {
        $this->login = Clients::clearPhone($this->login);
		$clients = Clients::findOne([
            'phone' => $this->login,
            'active' => Clients::STATUS_ACTIVE
        ]);

        if ($clients === null) {
            return false;
        }

		$clients->generatePasswordResetToken();
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
			return ['error'=>0, 'token'=>$clients->password_reset_token];
		}
		
		return ['error'=>1, 'message'=>Yii::t('Error', 'Error Send SMS')];	
    }
}
