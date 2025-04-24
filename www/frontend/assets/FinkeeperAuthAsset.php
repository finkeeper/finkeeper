<?php

namespace frontend\assets;

use Yii;
use yii\web\AssetBundle; 

/**
 * Main frontend application asset bundle.
 */
class FinkeeperAuthAsset extends AssetBundle
{
	public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
		'vendor/swiper/swiper.min.css',
		'css/style.css?999','https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;600&display=swap',
		'css/font-awesome5.min.css',
		'css/materialdesignicons.min.css',
		'css/site.css?2',
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
		
		$this->js[] = ['https://accounts.google.com/gsi/client', 'async' => true, 'defer' => true, 'data-id'=>'auth', 'data-lang'=>$lang];
	}
}
