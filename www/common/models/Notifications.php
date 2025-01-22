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
 * @property integer $id
 * @property integer $id_client
 * @property integer $type
 * @property integer $sent 
 * @property string $creation_date
 * @property string $sent_date
 * @property string $sent_to_date
 * @property integer $recipients
 * @property integer $deleted
 * @property string $title
 * @property string $message
 * @property string $sender
 */
class Notifications extends ActiveRecord
{
    const STATUS_NOT_DELETED = 0;
	const STATUS_DELETED = 1;
	
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notifications}}';
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
			[['id', 'id_client', 'deleted', 'sent', 'type', 'recipients', 'status', 'id_type'], 'integer'],
			[['creation_date', 'sent_date', 'sent_to_date'], 'string', 'max' => 60],
			[['sender'], 'string', 'max' => 255],
			[['message', 'title'], 'string'],
			
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
			
			if ($insert) {
               
			   $this->creation_date = date('Y-m-d H:i:s');
			   
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
		$query = static::find()->orderBy('id');
		   
		return new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => Yii::$app->params['pagination'],
			],
			'sort' => [
				'defaultOrder' => [
					'id' => SORT_DESC,
				]
			],
		]);
	}
}
