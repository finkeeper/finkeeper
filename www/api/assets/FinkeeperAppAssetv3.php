<?php

namespace api\assets;

use Yii;
use yii\web\AssetBundle; 

/**
 * Main frontend application asset bundle.
 */
class FinkeeperAppAssetv3 extends AssetBundle
{
	public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'finkeeper/vendor/swiper/swiper.min.css',
		'finkeeper/css/style.css?999','https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;600&display=swap',
		'finkeeper/css/font-awesome5.min.css',
		'finkeeper/css/materialdesignicons.min.css',
		'finkeeper/css/site-v3.css?48333',
		//YII_ENV_DEV ? 'finkeeper/css/site.css?12555' : 'finkeeper/css/site.min.css?12666'
    ];
	
    public $js = [
		//'finkeeper/vendor/jquery/jquery-3.5.1.min.js',
		'finkeeper/vendor/jquery/jquery.validate.min.js',
		'finkeeper/vendor/swiper/swiper.min.js',
		'finkeeper/vendor/charts/Chart.min.js',
		'finkeeper/vendor/charts/chartjs-plugin-style.min.js',
		'finkeeper/js/custom-charts.js',
		'finkeeper/js/swiper-init.js',
		'finkeeper/js/jquery.custom.js',
		'finkeeper/js/header-scroll.js',
		//'finkeeper/js/telegram-web-app.js',
		'https://telegram.org/js/telegram-web-app.js',
		'/finkeeper/js/appkit/dist/bundle.js?14555',
		'/finkeeper/js/appkit/dist/vendors-node_modules_reown_appkit-scaffold-ui_dist_esm_exports_w3m-modal_js.bundle.js',
		'/finkeeper/js/appkit/dist/vendors-node_modules_solflare-wallet_metamask-sdk_lib_esm_index_js.bundle.js',
		'/finkeeper/js/appkit/dist/vendors-node_modules_solflare-wallet_sdk_lib_esm_index_js.bundle.js',
		'finkeeper/js/site-v3.js?61000',
		//YII_ENV_DEV ? 'finkeeper/js/site.js?222' : 'finkeeper/js/site.min.js?222'
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
		
		$this->js[] = ['/finkeeper/js/bundle.js?13999', 'data-id'=>'bundle', 'data-lang'=>$lang];
	}
}
