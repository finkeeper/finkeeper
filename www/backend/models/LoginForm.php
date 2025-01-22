<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\base\View;
use backend\models\Admins;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $login;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['login', 'trim'],
            ['login', 'required'],
			['login', 'email'],
			//['login', 'validateLogin', 'skipOnEmpty'=> false],
			
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
           
			['password', 'required'],
            ['password', 'validatePassword'],
			//['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
			//['password', 'match', 'pattern' => '/^[a-zA-Z0-9\@\#\%\&]{8,32}$/i'],
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
			//Yii::$app->session->setFlash('error', Yii::t('Error', 'BanUsername'));
		}

		if (!$this->hasErrors()) {
            $user = $this->getUser();			
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('Error', 'Incorrect Passwd Username'));
				//Yii::$app->session->setFlash('error', Yii::t('Error', 'Incorrect Passwd Username'));
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
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Admins::findByUsername($this->login);
        }

        return $this->_user;
    }
}
