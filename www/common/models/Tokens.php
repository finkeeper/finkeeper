<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;

/**
 * Tokens model
 *
 * @property integer $id_token
 * @property integer $service_type
 * type 1 => Tonconnect; identify1 => wallet address
 * type 2 => Bybit; identify1 => uid; identify2 => api_key; identify3 => apisecret
 * @property string $identify1
 * @property string $identify2
 * @property string $identify3
 * @property string $identify4
 * @property string $identify5
 * @property integer $id_client
 * @property integer $deleted
 * @property string $deleted_date
 * @property string $creation_date
 * @property integer $user_connect
 */
class Tokens extends ActiveRecord
{
    const STATUS_NOT_DELETED = 0;
	const STATUS_DELETED = 1;
	const STATUS_NOT_CONNECT = 0;
	const STATUS_CONNECT = 1;
	
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tokens}}';
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
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['id_token', 'service_type', 'id_client', 'deleted', 'user_connect'], 'integer'],
			[['creation_date', 'deleted_date'], 'string', 'max' => 60],
			[['identify1', 'identify2', 'identify3', 'identify4', 'identify5'], 'string'],
			
			['deleted', 'default', 'value' => self::STATUS_NOT_DELETED],
            ['deleted', 'in', 'range' => [self::STATUS_NOT_DELETED, self::STATUS_DELETED]],
			
			['user_connect', 'default', 'value' => self::STATUS_NOT_CONNECT],
            ['user_connect', 'in', 'range' => [self::STATUS_NOT_CONNECT, self::STATUS_CONNECT]],
        ];
    }
	
	/**
	 * @beforeSave($insert)
	 */
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			
			if ($insert) {
               
			   $this->creation_date = date('Y-m-d H:i:s');
			   
            } else {
			
				if (!empty($this->deleted)) {
					
					$this->deleted_date = date('Y-m-d H:i:s');
				}
			}
			
			return true;
		}
		return false;
	}
	
	/**
	 * search()
	 */
	public function search()
	{
		$query = static::find()->orderBy('id_token');
		   
		return new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => Yii::$app->params['pagination'],
			],
			'sort' => [
				'defaultOrder' => [
					'id_token' => SORT_DESC,
				]
			],
		]);
	}
}
