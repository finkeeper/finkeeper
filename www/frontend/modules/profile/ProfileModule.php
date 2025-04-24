<?php

namespace frontend\modules\profile;

use Yii;
use yii\web\HttpException;

/**
 * service module definition class
 */
class ProfileModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\modules\profile\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
		
		parent::init();

    }
}
