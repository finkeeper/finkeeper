<?php

use Yii;
use yii\helpers\Url; // alt str_replace('/default', '', Yii::$app->controller->route)
use yii\bootstrap5\Html;
?>

<!-- Footer Start -->
<footer class="bg-footer">

	<div class="footer-bar">
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
	
	<div class="footer-bar">
		<div class="container text-center">
			<div class="row align-items-center">
				<div class="col-sm-6">
					<div class="text-sm-start">
						<p class="mb-0">© <?= date('Y') ?> <?= Html::encode(Yii::$app->name) ?></p>
					</div>
				</div><!--end col-->

				<!--<div class="col-sm-6 mt-4 mt-sm-0 pt-2 pt-sm-0">
					<ul class="list-unstyled footer-list text-sm-end mb-0">
						<li class="list-inline-item mb-0"><a href="/privacy" class="text-foot me-2"><?=Yii::t('Menu', 'Privacy')?></a></li>
						<li class="list-inline-item mb-0"><a href="/terms" class="text-foot me-2"><?=Yii::t('Menu', 'Terms')?></a></li>
						<li class="list-inline-item mb-0"><a href="/help" class="text-foot me-2"><?=Yii::t('Menu', 'Help')?></a></li>
					</ul>
				</div>--><!--end col-->
			</div><!--end row-->
		</div><!--end container-->
	</div>
	<p></p>
</footer><!--end footer-->
<!-- Footer End -->

<!-- Back to top -->
<a href="#" onclick="topFunction()" id="back-to-top" class="back-to-top rounded-pill fs-5"><i data-feather="arrow-up" class="fea icon-sm icons align-middle"></i></a>
<!-- Back to top -->

<!-- Wallet Modal -->
<div class="modal fade" id="modal-metamask" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-sm">                    
		<div class="modal-content justify-content-center border-0 shadow-md rounded-md position-relative">
			<div class="position-absolute top-0 start-100 translate-middle z-index-1">
				<button type="button" class="btn btn-icon btn-pills btn-sm btn-light btn-close opacity-10" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4"></i></button>
			</div>

			<div class="modal-body p-4 text-center">
				<img src="/images/wallet/MetaMask_Fox.svg" class="avatar avatar-md-md rounded-circle shadow-sm " alt="">

				<div class="content mt-4">
					<h5 class="text-danger mb-4"><?=Yii::t('Frontend', 'Error')?>!</h5>

					<P class="text-muted"><?=Yii::t('Frontend', 'Please Download MetaMask and create your profile and wallet in MetaMask. Please click and check the details')?>,</P>

					<a href="https://metamask.io/" class="btn btn-link primary text-primary fw-bold" target="_blank"><?=Yii::t('Frontend', 'MetaMask')?></a>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Wallet Modal -->
