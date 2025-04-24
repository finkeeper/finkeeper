<?php

if(
	$_SERVER['REMOTE_ADDR'] == '178.67.194.237' || 
	$_SERVER['REMOTE_ADDR'] == '5.228.83.198'
) {
	defined('YII_DEBUG') or define('YII_DEBUG',true);
	defined('YII_ENV') or define('YII_ENV', 'dev');
} else {
	defined('YII_DEBUG') or define('YII_DEBUG',false);
	defined('YII_ENV') or define('YII_ENV', 'prod');
}

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../common/config/main-local.php'),
    require(__DIR__ . '/../config/main.php'),
    require(__DIR__ . '/../config/main-local.php')
);

(new yii\web\Application($config))->run();     