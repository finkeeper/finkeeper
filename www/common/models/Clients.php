<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use nickcv\encrypter\behaviors\EncryptionBehavior;

/**
 * Clients model
 *
 * @property integer $id
 * @property string $login
 * @property string $hash
 * @property string $auth_key
 * @property string $password_reset_token
 * @property string $name
 * @property string $surname
 * @property string $patronumic
 * @property string $image
 * @property string $phone
 * @property integer $id_country
 * @property integer $zip_code
 * @property string $area
 * @property string $city
 * @property string $address
 * @property string $creation_date
 * @property integer $ban
 * @property integer $deleted
 * @property string $deleted_date
 * @property integer $active
 * @property string $verification_token
 * @property string $identify
 * @property string $ip_client
 * @property integer $points
 * @property integer $tg_chat_id
 * @property string $referral_token
 */
class Clients extends ActiveRecord implements IdentityInterface
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
	const STATUS_NOT_DELETED = 0;
	const STATUS_DELETED = 1;
	
	const MIN_PHONE = 11;
	const MAX_PHONE = 11;
	
	const LENGTH_SMS_CODE = 5;
	const TIME_SMS_CODE = 60;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%clients}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
				'class' => '\yii\behaviors\TimestampBehavior' ,
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => ['creation_date'],
					ActiveRecord::EVENT_BEFORE_DELETE => ['deleted_date'],
				] ,
				'value' => new \yii\db\Expression ('NOW()'),
			] ,
			/*
			'encryption' => [
				'class' => '\nickcv\encrypter\behaviors\EncryptionBehavior',
				'attributes' => [
					'email',
					'name',
					'surname',
					'patronumic',
					'phone',
					'area',
					'name_city',
					'name_address',
				],
			],
			*/
        ];
    }
	
	/**
     * @inheritdoc
     */
	public function getReferrals()
	{
		return $this->hasMany(Referrals::className(), ['id_client' => 'id']);
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['active', 'default', 'value' => self::STATUS_NOT_ACTIVE],
            ['active', 'in', 'range' => [self::STATUS_NOT_ACTIVE, self::STATUS_ACTIVE]],
			['deleted', 'default', 'value' => self::STATUS_NOT_DELETED],
            ['deleted', 'in', 'range' => [self::STATUS_NOT_DELETED, self::STATUS_DELETED]],
        ];
    }
	
	/**
	 * @beforeSave($insert)
	 */
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {

			if (!empty($this->ban)) {
				$this->ban_date = date('Y-m-d H:i:s');
			}
			
			if (!empty($this->deleted)) {
				$this->deleted_date = date('Y-m-d H:i:s');
			}
	 
			return true;
		}
		return false;
	}

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
		if (self::isPhone($username)) {
			$username = self::clearPhone($username);
		}
		
		$username = self::generateLogin($username);

		return static::findOne(['login' => $username, 'active' => self::STATUS_ACTIVE, 'deleted' => self::STATUS_NOT_DELETED]);
    }
	
	/**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByLogin($login)
    {
		if (Clients::isEmail($login)) {
			
			return static::findOne(['email' => $login, 'active' => self::STATUS_ACTIVE, 'deleted' => self::STATUS_NOT_DELETED]);
			
		} elseif (Clients::isPhone($login)) {
			
			$login = self::clearPhone($login);
			return static::findOne(['phone' => $login, 'active' => self::STATUS_ACTIVE, 'deleted' => self::STATUS_NOT_DELETED]);
		
		} else {
			
			return null;
			
		}	
    }
	
	/**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmailExists($email, $active=0)
    {
        if (empty($active)) {
		
			return static::findOne(['email' => $email, 'active' => self::STATUS_NOT_ACTIVE, 'deleted' => self::STATUS_NOT_DELETED]);
			
		} else {
			
			return static::findOne(['email' => $email, 'active' => self::STATUS_ACTIVE, 'deleted' => self::STATUS_NOT_DELETED]);
			
		}
    }
	
	/**
     * Finds user by phone
     *
     * @param string $phone
     * @return static|null
     */
    public static function findByPhoneExists($phone, $active=0)
    {
        $phone = self::clearPhone($phone);
		
		if (empty($active)) {
		
			return static::findOne(['phone' => $phone, 'active' => self::STATUS_NOT_ACTIVE, 'deleted' => self::STATUS_NOT_DELETED]);
			
		} else {
			
			return static::findOne(['phone' => $phone, 'active' => self::STATUS_ACTIVE, 'deleted' => self::STATUS_NOT_DELETED]);
			
		}
    }
	
	/**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByNewUsername($token)
    {
        return static::findOne(['verification_token' => $token, 'active' => self::STATUS_NOT_ACTIVE, 'deleted' => self::STATUS_NOT_DELETED]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'active' => self::STATUS_ACTIVE,
			'deleted' => self::STATUS_NOT_DELETED,
        ]);
    }
	
	/**
     * Finds user by tg token
     *
     * @param string $tg_auth_token
     */
    public static function findByTelegramToken($token='') 
    {
        return static::findOne([
            'tg_auth_token' => $token,
			'deleted' => self::STATUS_NOT_DELETED,
        ]);
    }
	
	/**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByRegistrationToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'verification_token' => $token,
            'active' => self::STATUS_NOT_ACTIVE,
			'deleted' => self::STATUS_NOT_DELETED,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
		return Yii::$app->security->validatePassword($password, $this->hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
	
	/**
     * Client "ip"
     */
    public function getClientIP()
    {
        $this->ip_client = Yii::$app->request->userIP;
    }
	
	
	/**
     * Generates "http token"
     */
    public function getHttpToken()
    {
		$this->http_token = Yii::$app->security->generateRandomString();
    }

	/**
     * Generates "identify"
     */
    public function getIdentify()
    {
        $this->identify = 'user';
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time()+60;
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
	
	/**
     * generateEmailVerificationToken()
     */
	public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
	
	/**
     * generateSMSToken
     */
    public function generateSMSToken()
    {
		$sms_token = '';
		for($i = 0; $i < self::LENGTH_SMS_CODE; $i++) {

			$sms_token .= mt_rand(0, 9);
  
		} 

		$this->sms_token = $sms_token;
    }
	
	/**
	 * findUserId
	 */
	public static function findUserId()
	{
		$user = static::findOne(['id' => Yii::$app->user->id, 'active' => self::STATUS_ACTIVE, 'deleted' => self::STATUS_NOT_DELETED]);
		if ($user!==null) {
			return $user;
		}
		
		return false;
	}
	
	/**
	 * findUsers
	 */
	public static function findUsers()
	{
		$users = static::find()->select([
			'login',
			'http_token'
		])->where([
			'active' => self::STATUS_ACTIVE, 
			'deleted' => self::STATUS_NOT_DELETED,
			'ban' => 0,
		])->all();

		if ($users!==null && !empty($users)) {
			return $users;
		}
		
		return false;
	}
	
	/**
	 * isPhone($phone)
	 */
	public static function clearPhone($phone)
	{
		$phone = (int) preg_replace('/[^0-9]/', '', $phone);
		$phone = preg_replace('/^[8]/', '7', $phone);

		return $phone;
	}
	
	/**
	 * isPhone($phone)
	 */
	public static function isPhone($phone)
	{
		if (self::isEmail($phone)) {
			return false;	
		}

		$number = (int) preg_replace('/[^0-9]/', '', $phone);
		if (empty($number) || strlen($number)<self::MIN_PHONE || strlen($number)>self::MAX_PHONE) {
			return false;
		}
	
		return true;
	}
	
	/**
	 * isPhone($phone)
	 */
	public static function isEmail($email)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return true;	
		}
		
		return false;
	}
	
	/**
	 * generateLogin($login)
	 */
	public static function generateLogin($login)
	{
		$time = time();
		return rtrim(strtr(base64_encode($login.$time), '+/', '-_'), '=');
	}
	
	/**
	 * isPhone($phone)
	 */
	public static function getData($verification_token)
	{
		$date = 0;
		$match = [];
		
		@preg_match_all('/[0-9]{8,}$/i', $verification_token, $match);
		if (!empty($match[0][0])) {
			$date = (int) $match[0][0];
		}

		return (int) $date + self::TIME_SMS_CODE;
	}
	
	/**
	 * coutUsers
	 */
	public static function coutUsers()
	{
		return (int) static::find()->where([
			'deleted' => self::STATUS_NOT_DELETED,
		])->count();
	}
}
