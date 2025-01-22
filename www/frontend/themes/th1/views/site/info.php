<?php

use yii\helpers\Html;
use common\widgets\Alert;

$this->title = Yii::t('Frontend', 'Info');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs('
	jQuery(document).ready(function($) {
		jQuery(".btn-close").on("click", function(){
			window.location.href = "/";
		});		
	});
', yii\web\View::POS_END);
?>

<?= $this->render(
	'@app/themes/th1/views/site/elements/__header_2.php'
) ?>

<!-- Hero Start -->
<section class="content position-relative">
	<div class="site-info">
		<div class="bg-overlay bg-linear-gradient-2"></div>
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 p-0">
					<div class="d-flex flex-column min-vh-100 p-4">
						<!-- Start Logo -->
						<div class="text-center">
							<a href="/"><img src="/images/logo/logo-dark.png" alt=""></a>
						</div>
						<!-- End Logo -->

						<!-- Start Content -->
						<div class="title-heading text-center my-auto">
							<div class="form-signin px-4 py-5 bg-white rounded-md shadow-sm">
	
								<h5 class="mb-4"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>&nbsp;<?= Html::encode($this->title) ?></h5>
								
								<?=Alert::widget()?>
	
							</div>
						</div>
						<!-- End Content -->
					</div>
					<!-- Start Footer -->
					<?= $this->render(
						'@app/themes/th1/views/site/elements/__footer_2.php'
					) ?>
					<!-- End Footer -->
				</div><!--end col-->
			</div><!--end row-->
		</div><!--end container-->
	</div>
</section><!--end section-->
<!-- Hero End -->

