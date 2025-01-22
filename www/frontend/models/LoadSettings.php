<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use common\models\Settings;
use yii\data\ActiveDataProvider;
use yii\base\InvalidParamException;
use backend\modules\statistics\modules\yandex\YandexModule;

/**
 * Login form
 */
class LoadSettings extends Model
{
	public $source;
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
	 * findSettings()
	 */
	public static function findSettings($source)
	{
		return Settings::findSettings($source);
	}
	
	/**
	 * getYandexMetrica()
	 */
	public static function getYandexMetrica()
	{
		$model = Settings::findSettings('yandex');
		if (!empty($model)) {
			return $model->code;
		}
		
		return '';
	}
	
	/**
	 * getYandexMetrica()
	 */
	public static function getGoogleTagManager($type)
	{
		$model = Settings::findSettings('google_'.$type);
		if (!empty($model)) {
			return $model->code;
		}
		
		return '';
	}
}
