<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\base\View;
use common\models\Clients;
use frontend\components\SendMail;
use frontend\components\TagHandler;

/**
 * Password reset request form
 */
class PasswordResetConfirm extends Model
{
	public $password;
	public $confirm_password;
	public $reset_token_expire;
	public $verifyCode;

    private $_new_user;

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [			
			[['password', 'confirm_password'], 'required'],
			[['password', 'confirm_password'], 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
			//['password', 'match', 'pattern' => '/^[a-zA-Z0-9\@\#\%\&]{8,32}$/i'],
			['confirm_password', 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => false, 'message' => Yii::t('Error', 'Passwords Not Match')],
			[['password', 'confirm_password'], 'validatePassLevel', 'skipOnEmpty'=> false],	
			
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
    public function confirm($token)
    {
		if (!$this->validate()) {
            return null;
        }
        
        $clients = self::getUser($token);
		if (empty($clients)) {
            return null;
        }

		if (!empty($clients->sms_token)) {
			
			$date = $clients->getData($clients->password_reset_token);
			$sec = (int) $date - time();

			if ($this->security_code!=$clients->sms_token || $sec<=0) {
				
				$this->addError('security_code', Yii::t('Error', 'Security Code Not Correct'));
				return false;
				
			} else {

				$clients->setPassword($this->password);
				$clients->getHttpToken($this->password);
				$clients->generatePasswordResetToken();

				return $clients->save();
				
			}
			
		} else {
			
			$clients->setPassword($this->password);
			$clients->getHttpToken($this->password);
			$clients->generatePasswordResetToken();
		
			return $clients->save() && $this->sendEmail($clients);	
		}
    }
	
	/**
	 * validatePassLevel($attribute, $params)
	 */
	public function validatePassLevel($attribute, $params)
    {
        $len = strlen($this->password);
		if ($len<Yii::$app->params['user.passwordMinLength']) {
			$this->addError('password', Yii::t('Error', 'Passw Short', ['count' => Yii::$app->params['user.passwordMinLength']]));
			Yii::$app->session->setFlash('error', Yii::t('Error', 'Passw Short', ['count' => Yii::$app->params['user.passwordMinLength']]));
		}
		
		if (!preg_match('/[0-9]/', $this->password)) {
			$this->addError('password', Yii::t('Error', 'Passw Weak'));
			Yii::$app->session->setFlash('error', Yii::t('Error', 'Passw Weak'));
		}
		
		if (!preg_match('/[a-z]/', $this->password)) {
			$this->addError('password', Yii::t('Error', 'Passw Weak'));
			Yii::$app->session->setFlash('error', Yii::t('Error', 'Passw Weak'));
		}
		
		//if (!preg_match('/[A-Z]/', $this->password)) {
			//$this->addError('password', Yii::t('Error', 'Passw Weak'));
		//}
    }

    /**
     * Sends confirmation email to user
     *
     * @return bool whether the email was sent
     */
    public function sendEmail($clients)
    {
        $subject = Yii::t('Email', 'Password changed successfully subject');
		$message = Yii::t('Email', 'Password changed successfully message');
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
			$clients->email, 
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
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser($token)
    {
        if ($this->_new_user === null) {
            $this->_new_user = Clients::findByPasswordResetToken($token);
        }

        return $this->_new_user;
    }
}
