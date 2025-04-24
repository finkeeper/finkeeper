<?php

namespace frontend\assets;

use Yii;
use yii\web\AssetBundle; 

/**
 * Main frontend application asset bundle.
 */
class FinkeeperSolAsset extends AssetBundle
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
     * @init'
     */
	public function init()
    {
		$lang='en';
		if (Yii::$app->language=='ru-RU') {
			$lang='ru';			
		}
		
		$this->js[] = ['/js/appkit/sol/bundle_sol.js?2', 'data-id'=>'bundle', 'data-lang'=>$lang];
	}
}
