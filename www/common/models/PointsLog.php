<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;

/**
 * PointsLog model
 *
 * @property integer $id_point
 * @property integer $point_type
 * @property integer $id_client
 * @property integer $point
 * @property integer $deleted
 * @property string $deleted_date
 * @property string $creation_date
 * @property integer $id_log
 */
class PointsLog extends ActiveRecord
{
	const STATUS_NOT_DELETED = 0;
	const STATUS_DELETED = 1;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%points_log}}';
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
			[['id_point', 'point_type', 'id_client', 'point', 'deleted', 'id_log'], 'integer'],
			[['creation_date', 'deleted_date'], 'string', 'max' => 60],
			
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
		$query = static::find()->orderBy('id_point');
		   
		return new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => Yii::$app->params['pagination'],
			],
			'sort' => [
				'defaultOrder' => [
					'id_point' => SORT_DESC,
				]
			],
		]);
	}
}
