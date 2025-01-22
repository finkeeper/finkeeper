<?php

namespace backend\modules\editors\modules\pages;

use Yii;
use yii\web\HttpException;

/**
 * service module definition class
 */
class PagesModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\editors\modules\pages\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {

		parent::init();

    }
}
