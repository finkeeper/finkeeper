<?php

namespace app\modules\public;

use Yii;
use yii\web\HttpException;

/**
 * service module definition class
 */
class PublicModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\public\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
		  parent::init();
    }
}
