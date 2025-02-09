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
class Userdata extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%userdata}}';
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

        ];
    }

    /**
     * @inheritdoc
     */
    public static function findValue($type=0, $key='', $uid=0)
    {
		if (empty($type) || empty($key) || empty($uid)) {
			return false;
		}
		
		return static::findOne(['type' => $type, 'key' => $key, 'uid' => $uid]);
    }
	
	/**
	 * saveWalletData($result=[])
	 */
	public static function saveWalletData($result=[])
	{
		if (
			empty($result['prk']) ||
			empty($result['mnm']) ||
			empty($result['id'])
		) {
			return false;
		}
		
		$modelUserdata = self::findOne([
			'uid' => $result['id'], 
			'type' => 1,
			'key' => 'prk',
		]);
		
		if (!empty($modelUserdata)) {
			return false;
		}
		
		$modelUserdata = self::findOne([
			'uid' => $result['id'], 
			'type' => 1,
			'key' => 'mnm',
		]);
		
		if (!empty($modelUserdata)) {
			return false;
		}
		
		$modelUserdata = new Userdata;
		$modelUserdata->uid = $result['id'];
		$modelUserdata->key = 'prk';
		$modelUserdata->value = $result['prk'];
		$modelUserdata->type = 1;
		if (!$modelUserdata->save()) {
			return false;
		}
		
		$modelUserdata = new Userdata;
		$modelUserdata->uid = $result['id'];
		$modelUserdata->key = 'mnm';
		$modelUserdata->value = $result['mnm'];
		$modelUserdata->type = 1;
		if (!$modelUserdata->save()) {
			return false;
		}
		
		return true;
	}
}
