<?php

namespace backend\modules\tools\modules\chatgpt;

use Yii;
use yii\web\HttpException;

/**
 * service module definition class
 */
class ChatgptModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\tools\modules\chatgpt\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
		
		parent::init();

    }
}
