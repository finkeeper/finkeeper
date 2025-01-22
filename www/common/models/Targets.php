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
 * @property integer $id_target
 * @property integer $id_client
 * @property string $symbol
 * @property string $price
 * @property string $coins
 * @property string $creation_date
 * @property string $deleted_date
 * @property integer $deleted
 * @property string $discription
 */
class Targets extends ActiveRecord
{
    const STATUS_NOT_DELETED = 0;
	const STATUS_DELETED = 1;
	
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%targets}}';
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
			[['id_target', 'id_client', 'deleted', 'notification_sent', 'notification_count'], 'integer'],
			[['creation_date', 'deleted_date'], 'string', 'max' => 60],
			[['symbol', 'price', 'coins', 'current_price'], 'string', 'max' => 255],
			[['description'], 'string'],
			
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
		$query = static::find()->orderBy('id_target');
		   
		return new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => Yii::$app->params['pagination'],
			],
			'sort' => [
				'defaultOrder' => [
					'id_target' => SORT_DESC,
				]
			],
		]);
	}
}
