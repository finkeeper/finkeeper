<?php

namespace app\modules\certificate;

use Yii;
use yii\web\HttpException;

/**
 * service module definition class
 */
class CertificateModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\certificate\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
		
		parent::init();

    }
}
