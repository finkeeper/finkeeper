<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\cron\DB;
use yii\data\ActiveDataProvider;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;

/**
 * ExchangeConfig model
 *
 * @property integer $id
 * @property string $symbol
 * @property integer $deleted
 * @property integer $deleted_date
 */
class CurrencyConfig extends ActiveRecord
{
	const STATUS_NOT_DELETED = 0;
	const STATUS_DELETED = 1;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%currency_config}}';
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
			[['deleted', 'used_val'], 'integer'],
			[['deleted_date', 'symbol'], 'string', 'max' => 60],
			[['logo', 'name', 'name_en', 'value', 'value_currency'], 'string'],
        ];
    }
	
	/**
	 * @beforeSave($insert)
	 */
	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			
			

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
					'id' => SORT_ASC,
				]
			],
		]);
	}

	/**
	 * getConfigCoingecko()
	 */
	public static function getConfig()
	{
        return static::find()
			->where(['deleted'=>self::STATUS_NOT_DELETED])
			->orderBy('id')
			->all();
	}
	
	/**
	 * changeConfig($save_data)
	 */
	public static function changeConfig($save_data=[])
	{
		if (empty($save_data) || !is_array($save_data))  {
			return false;
		}
		
		$base_path = dirname(dirname(dirname(__FILE__)));

		foreach ($save_data as $value) {

			$model = CurrencyConfig::find()->where([
				'symbol'=>strtoupper($value['symbol']),
			])->one();


			if (!empty($model)) {

				$model->deleted = 0;

			} else {
				
				$model = new CurrencyConfig;
				
				$model->symbol = strtoupper($value['symbol']);
				$logo = '/images/svg/flags/'.strtolower(mb_substr($value['symbol'], 0, -1)).'.svg';
				
				if (file_exists($base_path.'/api/web'.$logo)) {
					$model->logo = $logo;					
				}
				
				if (!empty($value['name'])) {
					$model->name = $value['name'];
				}
			}

			if (!$model->validate() || !$model->save()) {
				return false;
			}
		}

		$path = $base_path.'/common/cron/launch_exchange.php';
		
		exec(escapeshellcmd('/usr/bin/php -f '.$path));
		
		return true;
	}
}
