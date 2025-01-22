<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\base\View;
use common\models\Clients;
use frontend\components\SendMail;
use frontend\components\TagHandler;

/**
 * Login form
 */
class ConfirmForm extends Model
{
    private $_new_user;

	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [			
			
        ];
    }
	
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [		
			
        ];
    }

	/**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function confirm($token)
    {
        $clients = self::getNewUser($token);
		if (empty($clients)) {
            return null;
        }

		$clients->getHttpToken();
		$clients->active = 1;	
		
		if ($clients->save()) {
			if ($this->sendEmail($clients)) {
				return true;
			}
		}

		return false;
    }

	/**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($clients)
    {
        $subject = Yii::t('Email', 'Successful registration subject');
		$message = Yii::t('Email', 'Successful registration message');
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
    protected function getNewUser($token)
    {
        if ($this->_new_user === null) {
            $this->_new_user = Clients::findByNewUsername($token);
        }

        return $this->_new_user;
    }
}
