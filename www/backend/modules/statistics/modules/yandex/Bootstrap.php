<?php
namespace backend\modules\statistics\modules\yandex;

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
				'<statistics>/<yandex>/<action:\w+>' => '<statistics>/<yandex>/default/<action>',
				'<statistics>/<yandex>/<action:\w+>/<id:\d\w+>' => '<statistics>/<yandex>/default/<action>',
				'<statistics>/<yandex>/<action:\w+>/<_pjax:[\%\w]+>' => '<statistics>/<yandex>/default/<action>',
            ]
        );
    }
}