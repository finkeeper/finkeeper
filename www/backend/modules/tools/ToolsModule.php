<?php

namespace backend\modules\tools;

use Yii;
use yii\web\HttpException;

/**
 * service module definition class
 */
class ToolsModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\tools\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
		
		parent::init();

    }
}
