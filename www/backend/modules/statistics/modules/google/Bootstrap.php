<?php
namespace backend\modules\statistics\modules\google;

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
				'<statistics>/<google>/<action:\w+>' => '<statistics>/<google>/default/<action>',
				'<statistics>/<google>/<action:\w+>/<id:\d\w+>' => '<statistics>/<google>/default/<action>',
				'<statistics>/<google>/<action:\w+>/<_pjax:[\%\w]+>' => '<statistics>/<google>/default/<action>',
            ]
        );
    }
}