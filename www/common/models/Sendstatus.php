<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\base\NotSupportedException;

/**
 * Userdata model
 *
 * @property integer $id
 * @property integer $type
 * @property string $key
 * @property string $value
 */
class Sendstatus extends ActiveRecord
{
    const STATUS_NOT_DELETED = 0;
	const STATUS_DELETED = 1;
	
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%send_status}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['id_message', 'id_client', 'deleted', 'status', 'func'], 'integer'],
			[['amount', 'type'], 'string', 'max' => 255],
			[['address'], 'string'],
			
			['deleted', 'default', 'value' => self::STATUS_NOT_DELETED],
            ['deleted', 'in', 'range' => [self::STATUS_NOT_DELETED, self::STATUS_DELETED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findSendStatus($id=0)
    {
		if (empty($id)) {
			return false;
		}
		
		$id_client = Yii::$app->user->getId();
		if (empty($id_client)) {
			return false;
		}
		
		return static::findOne(['id_client' => $id_client, 'id' => $id, 'status' => 0, 'deleted' => self::STATUS_NOT_DELETED]);
    }
	
	/**
     * @inheritdoc
     */
    public static function createSendStatus($data=[])
    {
		if (empty($data['function']) || empty($data['amount'])) {
			return false;
		}

		$func = (int) $data['function'];
		if (empty($func)) {
			return false;
		}

		if ($func==1 && empty($data['address'])) {
			return false;
		}

		$coin = '';
		if (!empty($data['coin'])) {
			$coin = $data['coin'];
		}
		
		$address = '';
		if (!empty($data['address'])) {
			$address = $data['address'];
		}
		
		$id_client = Yii::$app->user->getId();
		if (empty($id_client)) {
			return false;
		}
		
		$amount = (string) $data['amount'];
			
		$model = new Sendstatus;
		$model->id_client = $id_client;
		$model->func = $func;
		$model->address = $address;
		$model->status = 0;
		$model->amount = $amount;
		$model->type = $coin;

		if (!$model->save()) {
			return false;
		}

		return $model->id;
    }
}
