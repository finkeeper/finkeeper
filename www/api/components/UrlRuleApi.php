<?php

namespace api\components;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
use yii\web\Controller;
use yii\web\UrlRuleInterface;
use yii\base\BaseObject;
use yii\helpers\Url;

class UrlRuleApi extends BaseObject implements UrlRuleInterface 
{
    public $name;
 
    public function init()
	{
        if ($this->name === null) {
            $this->name = __CLASS__;
        }
    }
	
	public function createUrl($manager, $route, $params) 
	{
		$url = Url::to();
		if (preg_match('/(debug|error)/', Yii::$app->controller->route)) {
            
			return false;
			
        } else {
			
			if($this->checkParams($url)){
				throw new HttpException(404 , Yii::t('Error', '404'));
			}
	
		}

		return false;  
    }

    public function parseRequest($manager, $request) 
	{
        return false;  
    }
	
	/*
	 * private checkParams
	 */
	private function checkParams($url) 
	{
		$pattern = '/(\?id=)/i';
		if (preg_match($pattern, $url)) {
			return false;
		} 
		
		$pattern = '/(index.php|\?)/i';
		if (preg_match($pattern, $url)) {
			return true;
		} 
		
		return false;
	}
}