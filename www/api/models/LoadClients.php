<?php

namespace api\models;

use Yii;
use yii\base\Model;
use common\models\Clients;
use yii\web\HttpException;

/**
 * ContactForm is the model behind the contact form.
 */
class LoadClients extends Model
{
	
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['deleted'], 'integer'],
			['creation_date', 'deleted_date', 'string', 'min' => 60, 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'deleted' => 'deleted',
			'deleted_date' => 'deleted_date',
			'creation_date' => 'creation_date',
        ];
    }
	
	/**
	 * countUsers()
	 */
	public static function countUsers()
	{	
		return Clients::coutUsers();
	}	
}
