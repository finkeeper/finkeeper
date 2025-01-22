<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;

/**
 * Menu model
 *
 * @property integer $id_message
 * @property string $language
 * @property string $translation
 * @property int $deleted
 * @property string $deleted_date
 * @property string $creation_date
 */
class Message extends ActiveRecord
{
	const STATUS_NOT_DELETED = 0;
	const STATUS_DELETED = 1;
	
	public $category;
	public $message;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%message}}';
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
					ActiveRecord::EVENT_BEFORE_INSERT => ['date_create'],
					ActiveRecord::EVENT_BEFORE_DELETE => ['date_deleted'],
				] ,
				'value' => new \yii\db\Expression ('NOW()'),
			] ,
        ];
    }
	
	/**
     * @inheritdoc
     */
	public function getSourceMessage()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'id']);
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
}
