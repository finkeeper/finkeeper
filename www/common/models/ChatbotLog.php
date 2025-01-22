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
 * @property integer id
 * @property string error
 * @property integer error_code
 * @property string error_message
 * @property integer message_id
 * @property integer update_id
 * @property string api_date
 * @property string creation_date
 * @property string text
 * @property string callback_data
 * @property integer from_id
 * @property integer from_is_bot
 * @property string from_first_name
 * @property string from_last_name
 * @property string from_username
 * @property string from_language_code
 * @property integer chat_id
 * @property string chat_first_name
 * @property string chat_last_name
 * @property string chat_username
 * @property string chat_type
 * @property string type
 * @property string request
 * @property integer bot_id
 * @property string wallet_address
 * @property integer id_client
 */
class ChatbotLog extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%chatbot_log}}';
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
	public function getReferrals()
	{
		return $this->hasMany(Referrals::className(), ['id_client' => 'id_client']);
	}

    /**
     * @inheritdoc
     */
	/*
    public function rules()
    {
        return [
			[['id', 'error_code', 'message_id', 'update_id', 'from_id', 'chat_id', 'type', 'error', 'from_is_bot', 'bot_id', 'id_client'], 'integer'],
			[['creation_date', 'api_date'], 'string', 'max' => 60],
			[['callback_data', 'from_first_name', 'from_last_name', 'from_username', 'from_language_code', 'chat_first_name', 'chat_last_name', 'chat_username', 'chat_type'], 'string', 'max' => 255],
			[['error_message', 'text', 'request', 'wallet_address'], 'string'],
        ];
    }
	*/
	
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
