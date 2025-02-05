<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\base\View;
use common\models\Clients;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $login;
    public $password;
    public $rememberMe = true;
	public $verifyCode;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
             ['login', 'trim'],
			 ['login', 'email'], 
             ['login', 'required'],
			
             // rememberMe must be a boolean value
             ['rememberMe', 'boolean'],
           
			 ['password', 'required'],
             ['password', 'validatePassword'],
			
			 [
				 ['verifyCode'], 
				 \himiklab\yii2\recaptcha\ReCaptchaValidator3::className(), 
			 ],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
     public function validatePassword($attribute, $params)
    {
		if (!empty($this->getUser()->ban)) {
			$this->addError($attribute, Yii::t('Error', 'BanUsername'));
			Yii::$app->session->setFlash('error', Yii::t('Error', 'BanUsername'));
		}

		if (!$this->hasErrors()) {
            $user = $this->getUser();			
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('Error', 'Incorrect Passwd Username'));
				Yii::$app->session->setFlash('error', Yii::t('Error', 'Incorrect Passwd Username'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        
        return false;
    }
	
	/**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function loginsecure($token='')
    {
        if(empty($token)) {
			return false;
		}
		
		$client = Clients::findByTelegramToken($token);
        if(empty($client)) {
			return false;
		}
		
		$client->tg_auth_token = '';
		if (!$client->save(false)) {
			return false;
		}

		return Yii::$app->user->login($client, 3600 * 24 * 30);
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Clients::findByUsername($this->login);
        }

        return $this->_user;
    }
}
