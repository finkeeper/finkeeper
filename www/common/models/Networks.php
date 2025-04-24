<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;

/**
 * Menu model
 *
 * @property integer $id
 * @property string $token
 * @property string $name
 * @property string $address
 * @property int $deleted
 * @property string $deleted_date
 * @property string $creation_date
 */
class Networks extends ActiveRecord
{
	const STATUS_NOT_DELETED = 0;
	const STATUS_DELETED = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%networks}}';
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
			['deleted', 'default', 'value' => self::STATUS_NOT_DELETED],
            ['deleted', 'in', 'range' => [self::STATUS_NOT_DELETED, self::STATUS_DELETED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findNetwork($id=0, $type=0)
    {
        if (empty($type)) {
			
			return static::findOne(['id' => $id, 'deleted' => self::STATUS_NOT_DELETED]);
			
		} else {
			
			return static::findOne(['id' => $id, 'type' => $type, 'deleted' => self::STATUS_NOT_DELETED]);
		}
    }

    /**
     * @inheritdoc
     */
    public static function findNetworkAll($type=0)
    {
        if (empty($type)) {
			
			return static::find()
				->where(['deleted'=>self::STATUS_NOT_DELETED])
				->orderBy('id')
				->all();
			
		} else {	
			
			return static::find()
				->where(['type' => $type, 'deleted'=>self::STATUS_NOT_DELETED])
				->orderBy('id')
				->all();
		}
    }
	
	/**
     * @inheritdoc
     */
    public static function findNetworksProvider($type=0)
    {
        if (empty($type)) {
			
			return static::find()
				->where(['deleted'=>self::STATUS_NOT_DELETED])
				->orderBy('id');
			
		} else {
		
			return static::find()
				->where(['type' => $type, 'deleted'=>self::STATUS_NOT_DELETED])
				->orderBy('id');
		}
    }
}
