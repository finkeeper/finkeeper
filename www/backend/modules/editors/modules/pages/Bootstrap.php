<?php
namespace backend\modules\editors\modules\pages;

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
				'<editors>/<pages>/<action:\w+>' => '<editors>/<pages>/default/<action>',
				'<editors>/<pages>/<action:\w+>/<id:\d\w+>' => '<editors>/<pages>/default/<action>',
				'<editors>/<pages>/<action:\w+>/<_pjax:[\%\w]+>' => '<editors>/<pages>/default/<action>',
            ]
        );
    }
}