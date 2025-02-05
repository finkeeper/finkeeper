<?php

namespace frontend\modules\app;

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
				'app/' => 'app/default/index',
				'<app>/<action:\w+>' => '<app>/default/<action>',
				'<app>/<action:\w+>' => '<app>/default/<action>',
				'<app>/<action:\w+>/<id:\d+>' => '<app>/default/<action>',
            ]
        );
    }
}