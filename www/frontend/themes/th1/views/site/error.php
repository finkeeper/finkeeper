<?php

use yii\helpers\Html;

$this->title = $name;
?>

<?= $this->render(
	'@app/themes/th1/views/site/elements/__header_2.php'
) ?>

<!-- Hero Start -->
<section class="content position-relative bg-soft-primary">
	<div class="site-error">
	
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
							<img src="/images/bg/error.png" class="img-fluid" alt="">
							<h1 class="heading sub-heading mb-3 mt-5 text-dark"><?= nl2br(Html::encode($name)) ?></h1>
							<p class="text-muted"><?= nl2br(Html::encode($message)) ?></p>
						</div>
						<!-- End Content -->

                        <!-- Footer Start -->
						<footer class="">
							<div class="container text-center">
								<small class="mb-0 text-dark title-dark">© <?= date('Y') ?> <?= Html::encode(Yii::$app->name) ?></small>
							</div><!--end container-->
						</footer><!--end footer-->
						<!-- Footer End -->
                    </div>
				</div><!--end col-->
			</div><!--end row-->
		</div><!--end container-->
	
	</div>
</div>