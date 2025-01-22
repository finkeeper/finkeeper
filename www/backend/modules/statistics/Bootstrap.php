<?php

namespace backend\modules\statistics;

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
				'<statistics>/<action:\w+>' => '<statistics>/default/<action>',
				'<statistics>/<action:\w+>/<id:\d+>' => '<statistics>/default/<action>',
            ]
        );
    }
}