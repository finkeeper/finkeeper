<?php


use frontend\assets\AppAsset;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use frontend\models\LoadSettings;

AppAsset::register($this);
$this->beginPage(); 

?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
	<head>
		<meta charset="<?= Yii::$app->charset ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
		<link rel="apple-touch-icon" sizes="180x180" href="/images/favicons/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/images/favicons/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/images/favicons/favicon-16x16.png">
		<link rel="manifest" href="/images/favicons/site.webmanifest">
		<link rel="mask-icon" href="/images/favicons/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="msapplication-TileColor" content="#da532c">
		<meta name="theme-color" content="#ffffff">
		
		<?=LoadSettings::getGoogleTagManager('head')?>

		<?php $this->registerCsrfMetaTags() ?>
		<title><?= Html::encode($this->title) ?></title>
		<?php $this->head() ?>
		
		<?=LoadSettings::getYandexMetrica()?>
	</head>
	<body>
	<?php $this->beginBody() ?>
	
		<?=LoadSettings::getGoogleTagManager('body')?>

		<?= $content ?>

	<?php $this->endBody() ?>
	</body>
</html>
<?php $this->endPage();
