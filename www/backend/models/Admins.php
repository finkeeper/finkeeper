<?php
namespace backend\models;

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
 * @property integer $role
 * @property string $login
 * @property string $password_hash
 * @property string $auth_key
 * @property string $password_reset_token
 * @property string $name
 * @property string $surname
 * @property string $patronumic
 * @property string $image
 * @property string $date_create 
 * @property string $email
 * @property string $phone
 * @property integer $id_country
 * @property integer $zip_code
 * @property string $area
 * @property string $city
 * @property string $address
 * @property varchar $ip_client
 * @property integer $deleted
 * @property string $date_deleted
 * @property integer $ban
 * @property string $date_ban
 * @property integer $active
 * @property string $comment
 
 */
class Admins extends ActiveRecord implements IdentityInterface
{
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
	const STATUS_NOT_DELETED = 0;
	const STATUS_DELETED = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admins}}';
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
	 
			$this->ip_client = $_SERVER['REMOTE_ADDR'];
			
			if (!empty($this->ban)) {
				$this->date_ban = date('Y-m-d H:i:s');
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
        $username = self::generateLogin($username);
		
		return static::findOne(['login' => $username, 'active' => self::STATUS_ACTIVE, 'deleted' => self::STATUS_NOT_DELETED]);
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
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
	
	/**
	 * getRole
	 */
	public static function findUserId()
	{
		$user = static::findOne(['id' => Yii::$app->user->id, 'active' => self::STATUS_ACTIVE, 'deleted' => self::STATUS_NOT_DELETED]);
		if ($user!==null && !empty($user->role)) {
			return $user;
		}
		
		return false;
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
		return rtrim(strtr(base64_encode($login), '+/', '-_'), '=');
	}
	
	/**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByLogin($login)
    {
		if (self::isEmail($login)) {
			
			return static::findOne(['email' => $login, 'active' => self::STATUS_ACTIVE, 'deleted' => self::STATUS_NOT_DELETED]);
			
		} elseif (self::isPhone($login)) {
			
			$login = self::clearPhone($login);
			return static::findOne(['phone' => $login, 'active' => self::STATUS_ACTIVE, 'deleted' => self::STATUS_NOT_DELETED]);
		
		} else {
			
			return null;
			
		}	
    }
}
