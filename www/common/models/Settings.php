<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;

/**
 * Menu model
 *
 * @property integer $id_settings
 * @property string $source
 * @property string $code
 * @property int $deleted
 * @property string $deleted_date
 * @property string $creation_date
 */
class Settings extends ActiveRecord
{
	const STATUS_NOT_DELETED = 0;
	const STATUS_DELETED = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%settings}}';
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
    public static function findSettings($source)
    {
        return static::findOne(['source' => $source, 'deleted' => self::STATUS_NOT_DELETED]);
    }

    /**
     * @inheritdoc
     */
    public static function findPageAll()
    {
        return static::find()
			->where(['deleted'=>self::STATUS_NOT_DELETED])
			->orderBy('id_settings')
			->all();
    }
	
	/**
     * @inheritdoc
     */
    public static function findPagesProvider()
    {
        return static::find()
			->where(['deleted'=>self::STATUS_NOT_DELETED])
			->orderBy('id_settings');
    }
}
