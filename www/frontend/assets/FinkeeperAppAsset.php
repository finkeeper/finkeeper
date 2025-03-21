<?php

namespace frontend\assets;

use Yii;
use yii\web\AssetBundle; 

/**
 * Main frontend application asset bundle.
 */
class FinkeeperAppAsset extends AssetBundle
{
	public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
		'vendor/swiper/swiper.min.css',
		'css/style.css?999','https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;600&display=swap',
		'css/font-awesome5.min.css',
		'css/materialdesignicons.min.css',
		'css/scroll/perfect-scrollbar.min.css',
		'css/suikit/suikit.css?36999',	
		'css/aptos/aptos.css?19',
		'css/site.css?46676',
		//YII_ENV_DEV ? 'finkeeper/css/site.css?12555' : 'finkeeper/css/site.min.css?12666'
    ];
	
    public $js = [
		'vendor/jquery/jquery.validate.min.js',
		'vendor/swiper/swiper.min.js',
		'vendor/charts/Chart.min.js',
		'vendor/charts/chartjs-plugin-style.min.js',
		'js/custom-charts.js',
		'js/swiper-init.js',
		'js/jquery.custom.js',
		'js/header-scroll.js',
		'js/scroll/perfect-scrollbar.jquery.min.js',
		'/js/appkit/bundle.js?10008',
		'/js/aptos/bundle.js?10051',
		'/js/aptos/637.bundle.js',		
		'js/site.js?50236',
		//YII_ENV_DEV ? 'js/site.js?222' : 'finkeeper/js/site.min.js?222'
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
		
		$this->js[] = ['/js/bundle.js?22111', 'data-id'=>'bundle', 'data-lang'=>$lang];
		$this->js[] = ['/js/suikit/bundle.js?26555', 'data-id'=>'suikit', 'data-lang'=>$lang];
	}
}
