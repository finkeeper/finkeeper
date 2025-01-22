<?php

namespace backend\modules\editors;

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
				'<editors>/<action:\w+>' => '<editors>/default/<action>',
				'<editors>/<action:\w+>/<id:\d+>' => '<editors>/default/<action>',
            ]
        );
    }
}