<?php

namespace frontend\assets;

use Yii;
use yii\web\AssetBundle; 

/**
 * Main frontend application asset bundle.
 */
class FinkeeperSuiAsset extends AssetBundle
{
	public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
		'css/suikit/suikit.css?1',
    ];
	
    public $js = [
		
    ];
	
    public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap5\BootstrapPluginAsset',
    ];
	
	/**
     * @init
     */
	public function init()
    {
		$lang='en';
		if (Yii::$app->language=='ru-RU') {
			$lang='ru';			
		}
		
		$this->js[] = ['/js/suikit/bundle.js?1', 'data-id'=>'suikit', 'data-lang'=>$lang];
	}
}
