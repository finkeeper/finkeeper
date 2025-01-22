<?php

namespace app\modules\certificate;

use yii\base\BootstrapInterface;
 
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules(
            [
				'<certificate>/<action:\w+>' => '<certificate>/default/<action>',
				'<certificate>/<action:\w+>/<id:\d+>' => '<certificate>/default/<action>',
            ]
        );
    }
}