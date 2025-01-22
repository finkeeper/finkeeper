<?php 

use common\widgets\Alert;

$this->title = Yii::t('Menu', 'CTFN — NFT-certificates for educational organizations'); 

?>

<?= $this->render(	
	'@app/themes/th1/views/site/elements/__header_'.$model->id_header.'.php',
) ?>


<!-- Start -->
<div class="content site-index">

	<?=$model->template?>
			
</div>
<!-- End section -->
		
<?= $this->render(
	'@app/themes/th1/views/site/elements/__footer_'.$model->id_footer.'.php'
) ?>
