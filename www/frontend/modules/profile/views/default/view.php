<?php

use app\modules\profile\models\Profile;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use common\widgets\Alert;
use yii\bootstrap5\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use app\modules\profile\ProfileModule;

$this->title = $title; 

$this->registerJs('
	jQuery(document).ready(function($) {
		
		$("#pro-img").on("change", function(event) {
			var image = document.getElementById("profile-image");
			image.src = URL.createObjectURL(event.target.files[0]);
			
			uploadFile(event.target.files[0], 1);
		});

		$("#pro-banner").on("change", function(event) {

			var image = document.getElementById("profile-banner");
			image.src = URL.createObjectURL(event.target.files[0]);
			
			uploadFile(event.target.files[0], 2);
		});
		
		var tooltipTriggerList = [].slice.call(document.querySelectorAll("[data-bs-toggle=\"tooltip\"]"))
			var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
			return new bootstrap.Tooltip(tooltipTriggerEl)
		})
	});
', yii\web\View::POS_END);

$this->registerCss('
	.cert-icon {
		font-size:22px;
	}
	.cert-icon-disable {
		color:#ccc;
	}
	.cert-block-text {
		width:236px;
		font-size:8px;
		color:#333333;
	}
	.cert-block-text .img-fluid {
		width:236px;
	}
	.cert-block-text .cert-pos {
		left:10px;
		right:10px;
		color:#333333;
	}
	.cert-block-text .pos-1 {
		top:52px;
	}
	.cert-block-text .pos-2 {
		top:70px;
	}
	.cert-block-text .pos-3 {
		top:80px;
	}
	.cert-block-text .pos-4 {
		top:89px;
	}
	.cert-block-text .pos-5 {
		right:34px;
		top:119px;
		color:#333333;
	}
');
?>

<?= $this->render(	
	'@app/themes/th1/views/site/elements/__header_1.php',
) ?>

<div class="content site-profile-view">
	 <!-- Start Home -->
	<section class="bg-creator-profile">
		<div class="container">
			<div class="profile-banner">
				<input id="pro-banner" name="profile-banner" type="file" class="d-none" />
				<div class="position-relative d-inline-block">

					<img src="/upload/school_logo_default.jpg" class="rounded-md shadow-sm img-fluid" id="profile-banner" alt="">
					
					<?php //if (empty($model->school_logo)) { ?>
					
						<!-- <img src="/upload/school_logo_default.jpg" class="rounded-md shadow-sm img-fluid" id="profile-banner" alt=""> -->
					
					<?php //} else { ?>
					
						<!-- <img src="/upload/client/<?php //echo(md5($model->id))?>/<?php //echo($model->school_logo)?>" class="rounded-md shadow-sm img-fluid" id="profile-banner" alt=""> -->
					
					<?php //} ?>

					<!-- <label class="icons position-absolute bottom-0 end-0" for="pro-banner"><span class="btn btn-icon btn-sm btn-pills btn-primary"><i data-feather="camera" class="icons"></i></span></label> -->
				</div>
			</div>

			<div class="row justify-content-center">
				<div class="col">
					<div class="text-center mt-n80">
						<div class="profile-pic">
							<input id="pro-img" name="profile-image" type="file" class="d-none" />
							<div class="position-relative d-inline-block">
								
								<?php if (empty($model->image)) { ?>
					
									<img src="/upload/default.jpg" class="avatar avatar-medium img-thumbnail rounded-pill shadow-sm" id="profile-image" alt="">
								
								<?php } else { ?>
								
									<img src="/upload/client/<?=md5($model->id)?>/<?=$model->image?>" class="avatar avatar-medium img-thumbnail rounded-pill shadow-sm" id="profile-image" alt="">
								
								<?php } ?>
		
								<label class="icons position-absolute bottom-0 end-0" for="pro-img"><span class="btn btn-icon btn-sm btn-pills btn-primary"><i data-feather="camera" class="icons"></i></span></label>
							</div>
						</div>

						<div class="content mt-3">
							<h5 class="mb-3"><?=$model->name?></h5> 
							<!--<small class="text-muted px-2 py-1 rounded-lg shadow">bhcedeh5sdijuj-husac4saiu <a href="javascript:void(0)" class="text-primary h5 ms-1"><i class="uil uil-copy"></i></a></small>-->

							<h6 class="mt-3 mb-0"><?=$model->employee?></h6>

							<div class="mt-4">
								
								<a href="/profile/update" class="btn btn-pills btn-outline-primary mx-1"><?=Yii::t('Menu', 'Profile Edit')?></a>
								
								<a href="/certificate/create" class="btn btn-pills btn-outline-primary mx-1"><?=Yii::t('Menu', 'Create Certificate')?></a>
							</div>

						</div>
					</div>
				</div>
			</div>
			<p>&nbsp;</p>
			<?=Alert::widget()?>
			<div class="result-img-load"></div>			
		</div>
		
		 <div class="container mt-60">
			<div class="row">
				<div class="col-12">
					<ul class="nav nav-tabs border-bottom" id="vwTab" role="tablist">
						<li class="nav-item" role="presentation">
							<button class="nav-link active" id="cert-tab" data-bs-toggle="tab" data-bs-target="#cert-item" type="button" role="tab" aria-controls="cert-item" aria-selected="true"><?=Yii::t('Frontend', 'Certificates')?></button>
						</li>
					</ul>
					
					 <div class="tab-content mt-4 pt-2" id="vwTabContent">
						<div class="tab-pane fade show active" id="cert-item" role="tabpanel" aria-labelledby="cert-tab">
							<div class="row row-cols-xl-4 row-cols-lg-3 row-cols-sm-2 row-cols-1 g-4">

								<?php Profile::renderCertificates(); ?>

							</div>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</section><!--end section-->
	<!-- End Home -->
</div>

<?= $this->render(
	'@app/themes/th1/views/site/elements/__footer_1.php'
) ?>