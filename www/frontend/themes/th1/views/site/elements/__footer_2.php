<?php

use yii\helpers\Url;
use yii\bootstrap5\Html;
?>

<!-- Footer Start -->
<footer class="">

	<div class="">
		<div class="container text-center">
			<div class="row align-items-center">
				<div class="col-sm-6"></div><!--end col-->
				<div class="col-sm-6 mt-4 mt-sm-0 pt-2 pt-sm-0">	  
					 <ul class="list-unstyled social-icon foot-social-icon mb-0 mt-4" style="text-align:right">
						<li class="list-inline-item lh-1"><a href="<?=Url::current(['lang'=>'en'])?>" class="rounded"><img src="/images/flags/en-EN.png"></a></li>
						<li class="list-inline-item lh-1"><a href="<?=Url::current(['lang'=>'ru'])?>" class="rounded"><img src="/images/flags/ru-RU.png"></a></li>
					</ul>
				</div><!--end col-->
			</div><!--end row-->
		</div><!--end container-->
	</div>

	<div class="container text-center">
		<small class="mb-0 text-light title-dark">© <?= date('Y') ?> <?= Html::encode(Yii::$app->name) ?></small>
	</div><!--end container-->
</footer><!--end footer-->
<!-- Footer End -->