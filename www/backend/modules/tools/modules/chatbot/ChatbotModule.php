<?php

namespace backend\modules\tools\modules\chatbot;

use Yii;
use yii\web\HttpException;

/**
 * service module definition class
 */
class ChatbotModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\tools\modules\chatbot\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
		
		parent::init();

    }
}
