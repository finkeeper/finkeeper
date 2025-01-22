<?php

namespace backend\modules\editors\modules\translations;

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
				'<editors>/<translations>/<action:\w+>' => '<editors>/<translations>/default/<action>',
				'<editors>/<translations>/<action:\w+>/<id:\d\w+>' => '<editors>/<translations>/default/<action>',
				'<editors>/<translations>/<action:\w+>/<_pjax:[\%\w]+>' => '<editors>/<translations>/default/<action>',
            ]
        );
    }
}