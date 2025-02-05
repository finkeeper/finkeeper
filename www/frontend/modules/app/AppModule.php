<?php

namespace frontend\modules\app;

use Yii;
use yii\web\HttpException;

/**
 * service module definition class
 */
class AppModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\modules\app\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
		  parent::init();
    }
}
