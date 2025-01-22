<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\ChatbotConfig;
use yii\data\ActiveDataProvider;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;

/**
 * Chatgpt model
 *
 * @property integer $id
 * @property string $direction
 * @property string $system
 * @property string $creation_date
 * @property string $deleted_date
 * @property integer $used
 */
class Chatgpt extends ActiveRecord
{
    const STATUS_NOT_DELETED = 0;
	const STATUS_DELETED = 1;
	
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%chatgpt}}';
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
			[['id', 'used'], 'integer'],
			[['creation_date', 'deleted_date'], 'string', 'max' => 60],
			[['direction', 'system'], 'string'],
			
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
	
	/**
	 * findUserId
	 */
	public static function findData($id=0)
	{	
		$id = (int) $id;
		
		$data = [
			'direction' => '',
			'system' => '',
			'used' => 0,
		];
		
		if (empty($id)) {
			return $data;
		}

		$direction = static::findOne(['id' => $id, 'deleted' => self::STATUS_NOT_DELETED]);
		if (!empty($direction) && !empty($direction->used)) {
			
			if (!empty($direction->direction)) {
				$data['direction'] = $direction->direction;
			}
			
			if (!empty($direction->system)) {
				$data['system'] = $direction->system;
			}
			
			if (!empty($direction->used)) {
				$data['used'] = $direction->used;
			}
		}
		
		return $data;
	}
}
