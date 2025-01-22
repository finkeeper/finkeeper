<?php
namespace backend\modules\tools\modules\chatgpt\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use common\models\Chatgpt;
use yii\data\ActiveDataProvider;
use yii\base\InvalidParamException;
use backend\modules\tools\modules\chatgpt\ChatgptModule;

/**
 * Login form
 */
class Gptchat extends Model
{
	public $direction;
	public $system;
	public $used;
	public $id;

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['used', 'id'], 'integer'],
			[['direction', 'system'], 'string'],
        ];
    }
	
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'direction' => Yii::t('Backend', 'Form Direction'),
			'system' => Yii::t('Backend', 'Form System'),
			'used' => Yii::t('Backend', 'Used Chatgpt'),
			'id' => Yii::t('Backend', 'ID'),
        ];
    }

	 /**
     * update
     */
    public function update($id=0)
    {
		if (!$this->validate()) {
            return null;
        }
		
		$gpt = self::findGptchat($id);
		if (empty($gpt)) {
			$gpt = new Chatgpt;
		}
		
		$gpt->setAttributes($this->attributes, false);

		if ($gpt->save()) {
			return true;
		}

        return false;
    }

	/**
	 * findPages()
	 */
	public static function findGptchat($id=0)
	{
		return Chatgpt::findOne(['id' => $id, 'deleted' => Chatgpt::STATUS_NOT_DELETED]);;
	}
}
