<?php

use yii\helpers\Html;
use hail812\adminlte3\assets\FontAwesomeAsset;
use hail812\adminlte3\assets\AdminLteAsset;

FontAwesomeAsset::register($this);
AdminLteAsset::register($this);
$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback');

$assetDir = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');

$publishedRes = Yii::$app->assetManager->publish('@vendor/hail812/yii2-adminlte3/src/web/js');
$this->registerJsFile($publishedRes[1].'/control_sidebar.js', ['depends' => '\hail812\adminlte3\assets\AdminLteAsset']);

$this->beginPage();
?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
	<head>
		<meta charset="<?= Yii::$app->charset ?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="icon" type="image/png" href="/images/favicons/favicon-96x96.png" sizes="96x96" />
		<link rel="icon" type="image/svg+xml" href="/images/favicons/favicon.svg" />
		<link rel="shortcut icon" href="/images/favicons/favicon.ico" />
		<link rel="apple-touch-icon" sizes="180x180" href="/images/favicons/apple-touch-icon.png" />
		<meta name="apple-mobile-web-app-title" content="Admin FinKeeper" />
		<link rel="manifest" href="/images/favicons/site.webmanifest" />		
		
		<?php $this->registerCsrfMetaTags() ?>
		<title><?= Html::encode($this->title) ?></title>
		<?php $this->head() ?>
	</head>

	<body class="hold-transition sidebar-mini">
	<?php $this->beginBody() ?>

		<div class="wrapper">
			<!-- Navbar -->
			<?= $this->render('navbar', ['assetDir' => $assetDir]) ?>
			<!-- /.navbar -->

			<!-- Main Sidebar Container -->
			<?= $this->render('sidebar', ['assetDir' => $assetDir]) ?>

			<!-- Content Wrapper. Contains page content -->
			<?= $this->render('content', ['content' => $content, 'assetDir' => $assetDir]) ?>
			<!-- /.content-wrapper -->

			<!-- Control Sidebar -->
			<?= $this->render('control-sidebar') ?>
			<!-- /.control-sidebar -->

			<!-- Main Footer -->
			<?= $this->render('footer') ?>
			
		</div>

	<?php $this->endBody() ?>
	</body>
</html>
<?php $this->endPage() ?>
