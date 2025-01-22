<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\widgets\Breadcrumbs;
use api\assets\AppAsset;
use api\models\LoadSettings;

AppAsset::register($this);

$this->beginPage(); 
$this->title = 'FinKeeper';
?>

	<!DOCTYPE html>
	<html lang="<?= Yii::$app->language ?>">
		<head>
			<meta charset="<?= Yii::$app->charset ?>">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			
			<link rel="apple-touch-icon" sizes="180x180" href="/images/logo/finkeeper/finkeeper_fav.png">
			<link rel="icon" type="image/png" sizes="32x32" href="/images/logo/finkeeper/finkeeper_fav.png">
			<link rel="icon" type="image/png" sizes="16x16" href="/images/logo/finkeeper/finkeeper_fav.png">
			<link rel="manifest" href="/images/logo/finkeeper/favicon/site.webmanifest">
			
			<?=LoadSettings::getGoogleTagManager('head')?>

			<?php $this->registerCsrfMetaTags() ?>
			<title><?= Html::encode($this->title) ?></title>
			<?php $this->head() ?>
		
			<?=LoadSettings::getYandexMetrica()?>
		</head>
		<body>		
		<?php $this->beginBody() ?>

			<?=LoadSettings::getGoogleTagManager('body')?>

			<?=$content ?>

		<?php $this->endBody() ?>
		</body>
	</html>
	
<?php $this->endPage() ?>
