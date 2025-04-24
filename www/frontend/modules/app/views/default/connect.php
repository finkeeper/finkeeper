<?php

use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap5\ActiveForm;
use common\widgets\Alert;
use common\models\Exchange;
use yii\helpers\Url;

$this->title = Yii::t('Title', 'FinKeeper');
$this->params['breadcrumbs'][] = $this->title;

$page_url = '/app?id='.$log_id.'&sc='.$sc;

$lang = 'en';
if (Yii::$app->language=='ru-RU') {
	$lang = 'ru';
}

echo Yii::$app->view->render('elements/__config',[
	'id' => $log_id,
	'sc' => $sc,
	'currency' => [],
	'targets' => [],
	'status' => $status,
	'page_url' => $page_url,
	'username' => '',
	'userpic' => '',
	'wallet' => $wallet,
	'lang' => $lang,
	'grafema' => '',
]);

require dirname(__FILE__).'/elements/__script/__app.php';

require dirname(__FILE__).'/elements/__script/__'.$network['name'].'_wallet.php';

echo Yii::$app->view->render('elements/__'.$network['name'].'_modal', [
	'id' => $log_id,
	'sc' => $sc,
	'status' => $status,
]);


