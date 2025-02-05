<?php


use frontend\assets\FinkeeperAppAsset;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use frontend\models\LoadSettings;

FinkeeperAppAsset::register($this);
$this->beginPage(); 

?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
	<head>
		<meta charset="<?= Yii::$app->charset ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, minimal-ui">
	
		<link rel="icon" type="image/png" href="/images/favicons/favicon-96x96.png" sizes="96x96" />
		<link rel="icon" type="image/svg+xml" href="/images/favicons/favicon.svg" />
		<link rel="shortcut icon" href="/images/favicons/favicon.ico" />
		<link rel="apple-touch-icon" sizes="180x180" href="/images/favicons/apple-touch-icon.png" />
		<meta name="apple-mobile-web-app-title" content="FinKeeper" />
		<link rel="manifest" href="/images/favicons/site.webmanifest" />

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
