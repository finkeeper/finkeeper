<?php
namespace backend\modules\statistics\modules\google\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use common\models\Settings;
use yii\data\ActiveDataProvider;
use yii\base\InvalidParamException;
use backend\modules\statistics\modules\google\GoogleModule;

/**
 * Login form
 */
class Google extends Model
{
	public $source = 'google';
	public $code;
	public $deleted;
	public $deleted_date;
	public $creation_date;

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
		
			['deleted', 'integer'],
			['source', 'string', 'max' => 255],
			[['creation_date', 'deleted_date'], 'string', 'max' => 60],
			[['code'], 'string'],
        ];
    }
	
	/**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'code' => Yii::t('Backend', 'Code'),
			'source' => Yii::t('Backend', 'Source'),
			'deleted' => Yii::t('Backend', 'Deleted'),
			'creation_date' => Yii::t('Backend', 'Creation Date'),
			'deleted_date' => Yii::t('Backend', 'Deleted Date'),
        ];
    }

	 /**
     * update
     */
    public function update()
    {
		if (!$this->validate()) {
            return null;
        }
		
		$settings = self::findSettings();
		if (empty($settings)) {
			$settings = new Settings;
		}
		
		$settings->setAttributes($this->attributes, false);

		if ($settings->save()) {
			return true;
		}

        return false;
    }

	/**
	 * findPages()
	 */
	public static function findSettings()
	{
		return Settings::findSettings('google');
	}
}
