<?php

namespace backend\components;

use Yii;
use yii\web\HttpException;

/** 
 * Settings view
 */
class SettingsView
{
	/**
	 * getTheme()
	 */
	public static function getTheme()
	 {
		 return Yii::createObject([
			'class' => '\yii\base\Theme',
			'basePath' => '@app/themes/adminlte3',
			'baseUrl' => '@app/themes/adminlte3/web',
			'pathMap' => ['@app/views' => '@app/themes/adminlte3/views'],
		]);
	 }
	 
	/**
	 * langSwitcher()
	 */
	 public static function langSwitcher()
	 {
		 if (isset($_GET['lang']) && !empty($_GET['lang'])) {
			
			Yii::$app->language = strtolower($_GET['lang']).'-'.strtoupper($_GET['lang']);
			Yii::$app->response->cookies->add(new \yii\web\Cookie([
				'name' => 'lang',
				'value' => $_GET['lang'],
				'expire' => time() + (365 * 24 * 60),
			]));
			
			Yii::$app->session->set('lang', $_GET['lang']); 
			
		} else {
			
			$lang = Yii::$app->session->get('lang'); 
			if (!empty($lang)) {
				Yii::$app->language = strtolower($lang).'-'.strtoupper($lang);
			}
		} 
	 }
	
}