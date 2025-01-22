<?php

namespace backend\modules\tools;

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
				'<tools>/<action:\w+>' => '<tools>/default/<action>',
				'<tools>/<action:\w+>/<id:\d+>' => '<tools>/default/<action>',
            ]
        );
    }
}