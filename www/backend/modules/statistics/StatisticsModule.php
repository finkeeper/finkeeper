<?php

namespace backend\modules\statistics;

use Yii;
use yii\web\HttpException;

/**
 * service module definition class
 */
class StatisticsModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\statistics\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
		
		parent::init();

    }
}
