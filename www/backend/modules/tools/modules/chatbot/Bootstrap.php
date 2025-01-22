<?php

namespace backend\modules\tools\modules\chatbot;

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
				'<tools>/<chatbot>/<action:\w+>' => '<tools>/<chatbot>/default/<action>',
				'<tools>/<chatbot>/<action:\w+>/<id:\d\w+>' => '<tools>/<chatbot>/default/<action>',
				'<tools>/<chatbot>/<action:\w+>/<_pjax:[\%\w]+>' => '<tools>/<chatbot>/default/<action>',
            ]
        );
    }
}