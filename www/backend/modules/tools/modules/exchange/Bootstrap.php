<?php

namespace backend\modules\tools\modules\exchange;

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
				'<tools>/<exchange>/<action:\w+>' => '<tools>/<exchange>/default/<action>',
				'<tools>/<exchange>/<action:\w+>/<id:\d\w+>' => '<tools>/<exchange>/default/<action>',
				'<tools>/<exchange>/<action:\w+>/<_pjax:[\%\w]+>' => '<tools>/<exchange>/default/<action>',
            ]
        );
    }
}