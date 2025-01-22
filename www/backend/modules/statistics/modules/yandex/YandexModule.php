<?php

namespace backend\modules\statistics\modules\yandex;

use Yii;
use yii\web\HttpException;

/**
 * service module definition class
 */
class YandexModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\statistics\modules\yandex\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {

		parent::init();

    }
}
