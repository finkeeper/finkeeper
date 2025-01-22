<?php

namespace backend\modules\statistics\modules\google;

use Yii;
use yii\web\HttpException;

/**
 * service module definition class
 */
class GoogleModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\statistics\modules\google\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {

		parent::init();

    }
}
