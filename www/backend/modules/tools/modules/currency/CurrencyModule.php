<?php

namespace backend\modules\tools\modules\currency;

use Yii;
use yii\web\HttpException;

/**
 * service module definition class
 */
class CurrencyModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\tools\modules\currency\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
		
		parent::init();

    }
}
