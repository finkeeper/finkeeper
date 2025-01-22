<?php

$this->title = Yii::t('Title', 'Privacy');
$this->params['breadcrumbs'][] = $this->title; 
?>

<?= $this->render(
	'@app/themes/th1/views/site/elements/__header_'.$model->id_header.'.php'
) ?>

<!-- Start -->
<div class="site-privacy">

	<?=$model->template?>

</div>	
<!-- End -->
		
<?= $this->render(
	'@app/themes/th1/views/site/elements/__footer_'.$model->id_footer.'.php'
) ?>