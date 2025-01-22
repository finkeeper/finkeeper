<?php

namespace app\modules\profile;

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
    public $controllerNamespace = 'app\modules\profile\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
		
		parent::init();

    }
}
