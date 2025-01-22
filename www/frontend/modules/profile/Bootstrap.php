<?php

namespace app\modules\profile;

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
				'<profile>/<action:\w+>' => '<profile>/default/<action>',
				'<profile>/<action:\w+>/<id:\d+>' => '<profile>/default/<action>',
            ]
        );
    }
}