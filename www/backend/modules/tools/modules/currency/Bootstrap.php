<?php

namespace backend\modules\tools\modules\currency;

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
				'<tools>/<currency>/<action:\w+>' => '<tools>/<currency>/default/<action>',
				'<tools>/<currency>/<action:\w+>/<id:\d\w+>' => '<tools>/<currency>/default/<action>',
				'<tools>/<currency>/<action:\w+>/<_pjax:[\%\w]+>' => '<tools>/<currency>/default/<action>',
            ]
        );
    }
}