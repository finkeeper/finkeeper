<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\ChatbotConfig;
use yii\data\ActiveDataProvider;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;

/**
 * Chatbot model
 *
 * @property integer $id_crupto
 * @property integer $rank
 * @property string $slug
 * @property string $name
 * @property string $symbol
 * @property string $type
 * @property integer $nominal
 * @property integer $value
 * @property string $date_of_change
 * @property string $currency
 */
class Chatbot extends ActiveRecord
{
    const STATUS_NOT_DELETED = 0;
	const STATUS_DELETED = 1;
	
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%chatbot}}';
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
			[['id_bot', 'bot_type', 'show_menu'], 'integer'],
			[['creation_date', 'deleted_date'], 'string', 'max' => 60],
			[['bot_name', 'bot_url', 'bot_desription', 'bot_token'], 'string'],
			
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
		$query = static::find()->orderBy('id_bot');
		   
		return new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => Yii::$app->params['pagination'],
			],
			'sort' => [
				'defaultOrder' => [
					'id_bot' => SORT_DESC,
				]
			],
		]);
	}
	
	/**
	 * sanitizeQuery($str='')
	 */
	public static function sanitizeQuery($str='')
	{
		if (empty($str) || !is_string($str)) {
			return false;
		}
		
		$str = str_replace(
			[
				'/', 
				'?',
			], 
			[
				'', 
				'',
			]
		, $str);
		
		if (!preg_match('/^[0-9a-z]{1,}$/i', $str)) {
			return false;
		}
		
		$str = strtolower($str);
		
		$replace = [
			
		];
		
		if (!empty($replace[$str])) {
			$str = $replace[$str];
		}
		
		
		return $str;
	}
}
