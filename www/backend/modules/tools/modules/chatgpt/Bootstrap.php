<?php

namespace backend\modules\tools\modules\chatgpt;

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
				'<tools>/<chatgpt>/<action:\w+>' => '<tools>/<chatgpt>/default/<action>',
				'<tools>/<chatgpt>/<action:\w+>/<id:\d\w+>' => '<tools>/<chatgpt>/default/<action>',
				'<tools>/<chatgpt>/<action:\w+>/<_pjax:[\%\w]+>' => '<tools>/<chatgpt>/default/<action>',
            ]
        );
    }
}