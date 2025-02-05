<?php

namespace frontend\modules\public\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class ModuleAsset extends AssetBundle
{
    public $sourcePath = '@web'; 
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        YII_ENV_DEV ? 'css/site.css' : 'css/site.min.css'
    ];
    public $js = [
        
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset', 
    ];

    
}
