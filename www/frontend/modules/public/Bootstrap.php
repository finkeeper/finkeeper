<?php

namespace app\modules\public;

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
				'<public>/<action:\w+>' => '<public>/default/<action>',
				'<public>/<action:\w+>/<id:\d+>' => '<public>/default/<action>',
            ]
        );
    }
}