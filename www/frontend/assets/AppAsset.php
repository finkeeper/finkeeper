<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
	
    public $css = [
        'css/tiny-slider.css',
		'css/materialdesignicons.min.css',
		'css/unicons.min.css',
		'css/font-awesome5.min.css',
		'https://unicons.iconscout.com/release/v4.0.0/css/line.css',
		'css/style.min.css',
		'css/colors/default.css',
		YII_ENV_DEV ? 'css/site.css' : 'css/site.min.css'
    ];
	
	public $js = [
		'https://kit.fontawesome.com/78b3f3c94b.js',
		'js/tiny-slider.js',
		'js/shuffle.min.js',
		'js/feather.min.js',
		'js/plugins.init.js',
		'js/app.js',
		YII_ENV_DEV ? 'js/site.js' : 'js/site.min.js'
    ];
	
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapPluginAsset',
    ];
}
