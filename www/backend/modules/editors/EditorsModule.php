<?php

namespace backend\modules\editors;

use Yii;
use yii\web\HttpException;

/**
 * service module definition class
 */
class EditorsModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\editors\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
		
		parent::init();

    }
}
