<?php

namespace frontend\assets;

use Yii;
use yii\web\AssetBundle; 

/**
 * Main frontend application asset bundle.
 */
class FinkeeperAptAsset extends AssetBundle
{
	public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
		'css/aptos/aptos.css?1',
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
		
		$this->js[] = ['/js/aptos/bundle.js?1', 'data-id'=>'bundle', 'data-lang'=>$lang];
	}
}
