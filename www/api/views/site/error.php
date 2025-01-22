<?php
use yii\helpers\Html;

$statusCode = '500?';
if (!empty($exception) && !empty($exception->statusCode)) {
	$statusCode = $exception->statusCode;
}

if (!empty($_POST)) {

	header('HTTP/1.1 '.$statusCode); 
	header("Content-Type: text/xml");
	header("Expires: Thu, 19 Feb 1998 13:24:18 GMT");
	header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
	header("Cache-Control: no-cache, must-revalidate");
	header("Cache-Control: post-check=0,pre-check=0");
	header("Cache-Control: max-age=0");
	header("Pragma: no-cache");
	
	echo '<?xml version="1.0" encoding="UTF-8" ?>';
	echo '<Error>';
		echo '<title>'.$name.'</title>';
		echo '<StatusCode>'.$statusCode.'</StatusCode>';
		echo '<Message>'.$message.'</Message>';
	echo '</Error>';
	exit;
	
} else {

	$this->title = $name;
	header('HTTP/1.1 '.$statusCode);
	?>

	<div id="notfound">
		<div class="notfound">
			<div class="notfound-404"></div>
			<?php if ($statusCode==520) : ?>
				
				<h2>Oops!</h2>
				
			<?php else : ?>
				
				<h1><?=$statusCode?></h1>
				
			<?php endif; ?>
			<h4><?=nl2br(Html::encode($message))?></h4>
			<p>&nbsp;</p>
			<p><?=Yii::t('Frontend', 'Error Message Footer')?>:&nbsp;<a href="mailto:<?=Yii::$app->params['adminEmail']?>"><?=Yii::$app->params['adminEmail']?></a></p>
			
		</div>
	</div>
	
<?php 
}