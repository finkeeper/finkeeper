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
 * @property integer $id_cryptorank
 * @property string $id_coingecko
 * @property integer $deleted
 * @property integer $deleted_date
 */
class ExchangeConfig extends ActiveRecord
{
	const STATUS_NOT_DELETED = 0;
	const STATUS_DELETED = 1;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%exchange_config}}';
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
			[['id_cryptorank', 'deleted'], 'integer'],
			[['deleted_date', 'symbol'], 'string', 'max' => 60],
			[['id_coingecko', 'logo', 'name'], 'string'],
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

		foreach ($save_data as $value) {

			$model = ExchangeConfig::find()->where([
				'symbol'=>strtoupper($value['symbol']),
			])->one();


			if (!empty($model)) {

				$model->deleted = 0;

			} else {
				
				$model = new ExchangeConfig;
				
				$model->symbol = strtoupper($value['symbol']);
				$model->name = $value['name'];
				$model->logo = '/images/cryptologo/'.strtolower($value['symbol']).'.webp';
				
			}

			if (!$model->validate() || !$model->save()) {
				return false;
			}
		}
		
		$path = dirname(dirname(__FILE__)).'/cron/launch_exchange.php';
		
		exec(escapeshellcmd('/usr/bin/php -f '.$path));
		
		return true;
	}
}
