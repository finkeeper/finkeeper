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
		'css/site.css?36555',
		//YII_ENV_DEV ? 'finkeeper/css/site.css?12555' : 'finkeeper/css/site.min.css?12666'
    ];
	
    public $js = [
		//'finkeeper/vendor/jquery/jquery-3.5.1.min.js',
		'vendor/jquery/jquery.validate.min.js',
		'vendor/swiper/swiper.min.js',
		'vendor/charts/Chart.min.js',
		'vendor/charts/chartjs-plugin-style.min.js',
		'js/custom-charts.js',
		'js/swiper-init.js',
		'js/jquery.custom.js',
		'js/header-scroll.js',
		//'js/telegram-web-app.js',
		//'https://telegram.org/js/telegram-web-app.js',
		'/js/appkit/dist/bundle.js?14888',
		'/js/appkit/dist/vendors-node_modules_reown_appkit-scaffold-ui_dist_esm_exports_w3m-modal_js.bundle.js',
		'/js/appkit/dist/vendors-node_modules_solflare-wallet_metamask-sdk_lib_esm_index_js.bundle.js',
		'/js/appkit/dist/vendors-node_modules_solflare-wallet_sdk_lib_esm_index_js.bundle.js',
		'js/site.js?40333',
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
		
		$this->js[] = ['/js/bundle.js?13777', 'data-id'=>'bundle', 'data-lang'=>$lang];
	}
}
