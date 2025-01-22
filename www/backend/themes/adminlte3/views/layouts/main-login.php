<?php

use hail812\adminlte3\assets\AdminLteAsset;
use hail812\adminlte3\assets\PluginAsset;

AdminLteAsset::register($this);

$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700');
$this->registerCssFile('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css');

PluginAsset::register($this)->add(['fontawesome', 'icheck-bootstrap']);

$this->beginPage();
?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
	<head>
		<meta charset="<?= Yii::$app->charset ?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="apple-touch-icon" sizes="180x180" href="/images/favicons/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/images/favicons/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/images/favicons/favicon-16x16.png">
		<link rel="manifest" href="/images/favicons/site.webmanifest">
		<link rel="mask-icon" href="/images/favicons/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="msapplication-TileColor" content="#da532c"> 
		<meta name="theme-color" content="#ffffff">
		
		<title><?=Yii::t('Title', 'Admin')?> <?=strtoupper(Yii::$app->params['site'])?></title>

		<?php $this->registerCsrfMetaTags() ?>
		<?php $this->head() ?>
	</head>
	<body class="hold-transition login-page">
	<?php  $this->beginBody() ?>
	
	<div class="login-box">
		<div class="login-logo">
			<a href="<?=Yii::$app->homeUrl?>"><b><?=Yii::t('Title', 'Admin')?></b> <?=strtoupper(Yii::$app->params['site'])?></a>
		</div>
		<!-- /.login-logo -->

		<?= $content ?>
	</div>
	<!-- /.login-box -->

	<?php $this->endBody() ?>
	</body>
</html>
<?php $this->endPage() ?>