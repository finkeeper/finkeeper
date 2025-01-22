<?php

namespace api\assets;

use yii\web\AssetBundle; 

/**
 * Main frontend application asset bundle.
 */
class CtfnAppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'ctfn/css/tiny-slider.css',
		'ctfn/css/materialdesignicons.min.css',
		'ctfn/css/unicons.min.css',
		'ctfn/css/font-awesome5.min.css',
		'https://unicons.iconscout.com/release/v4.0.0/css/line.css',
		'ctfn/css/style.min.css',
		'ctfn/css/colors/default.css',
		YII_ENV_DEV ? 'ctfn/css/site.css' : 'ctfn/css/site.min.css'
    ];
	
    public $js = [
		'https://kit.fontawesome.com/78b3f3c94b.js',
		'ctfn/js/tiny-slider.js',
		'ctfn/js/shuffle.min.js',
		'ctfn/js/feather.min.js',
		'ctfn/js/plugins.init.js',
		'ctfn/js/app.js',
		YII_ENV_DEV ? 'ctfn/js/site.js' : 'ctfn/js/site.min.js'
    ];
	
    public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap5\BootstrapPluginAsset',
    ];
}
