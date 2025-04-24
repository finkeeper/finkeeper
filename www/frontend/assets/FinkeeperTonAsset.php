<?php

namespace frontend\assets;

use Yii;
use yii\web\AssetBundle; 

/**
 * Main frontend application asset bundle.
 */
class FinkeeperTonAsset extends AssetBundle
{
	public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
		
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
		
		$this->js[] = ['https://unpkg.com/@tonconnect/ui@latest/dist/tonconnect-ui.min.js', 'data-id'=>'api', 'data-lang'=>$lang];
	}
}
