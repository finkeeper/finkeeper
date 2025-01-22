<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use common\widgets\Alert;
use yii\bootstrap5\ActiveForm;
use common\models\Certificate;
use mihaildev\ckeditor\CKEditor;
use frontend\components\SchoolToken;
use app\modules\certificate\CertificateModule;


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

	:target {
		padding-top: 56px;
    	margin-top: -56px;
	}
');
?>

<!-- Start Home -->
<section class="bg-half-170 d-table w-100" style="background: url('/images/bg/01.jpg') bottom;">
	<div class="bg-overlay bg-gradient-overlay-2"></div>
	<div class="container">
		<div class="row mt-5 justify-content-center">
			<div class="col-12">
				<div class="title-heading text-center">
					<h5 class="heading fw-semibold sub-heading text-white title-dark"><?=$bigTitle?></h5>
					<p class="text-white-50 para-desc mx-auto mb-0"><?=$smallTitle?></p>
				</div>
			</div><!--end col-->
		</div><!--end row-->

	</div><!--end container-->
</section><!--end section-->
<div class="position-relative">            
	<div class="shape overflow-hidden text-white">
		<svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="currentColor"></path>
		</svg>
	</div>
</div>
<!-- End Home --> 

<section class="section py-5">
	
	<?=Alert::widget()?>

	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-7 col-12">
				<div class="rounded p-4 shadow">
					<div class="row">
						<div class="col-12">
							<?=Alert::widget()?>
						</div>
						<div class="col-12">
						<?php $form = ActiveForm::begin([
								'id' => 'address-search',
								'class' => 'address-search',
								'action'=> ['/public/address','#'=>'searchblock']
							]); ?>

								<div class="row">
											
									<div class="col-12 mb-4">
											
										<?=$form->field($model, 'user_nft_address', [
											'template' => '<label for="publicpage-search_address" class="form-label fw-bold">'.Yii::t('Frontend', 'Crypto wallet address').' <i class="fa fa-asterisk text-danger"></i></label>{input}{error}'
										])->textInput(['type'=>'text', 'placeholder'=>Yii::t('Frontend', "Student's crypto wallet address in BNB chain"), 'autocomplete' => 'off']) ?>

									</div><!--end col-->	
									
								</div>

								<div class="col-lg-12">
											
									<?= Html::submitButton(Yii::t('Form', 'Search'), ['id'=>'submit-form', 'class' => 'btn btn-primary', 'name' => 'send']) ?>

								</div><!--end col-->
										
					
							<?php ActiveForm::end(); ?>
						</div><!--end col-->
					</div><!--end row-->
				</div>
			</div><!--end col-->
		</div><!--end row-->
	</div><!--end container-->
	
	<?php
		$count = 0;
		$certificates = $model->searchUserNftAddress();
		$count = !is_bool($certificates) ? count($certificates) : $count;
		$tabTitle = $count . ' ' . Yii::t('Frontend', 'certificates found!');
		
		$schools = $model->searchUserSchools();
		$countSchools = 0;
		$countSchools = !is_bool($schools) ? count($schools) : $countSchools;
		$tabSchoolTitle = $countSchools . ' ' . Yii::t('Frontend', 'education organizations found!');
	?>

	<?php //die(print_r($certificates, true))?>

	<div class="container mt-5">
		<div class="row">

			<div id="searchblock" class="col-12 pt-3"></div>

			<div class="col-12">
				<ul class="nav nav-tabs border-bottom" id="vwTab" role="tablist">
					<?php if (!empty($certificates) && is_array($certificates)) : ?>
						<li class="nav-item" role="presentation">
							<button class="nav-link active" id="cert-tab" data-bs-toggle="tab" data-bs-target="#cert-item" type="button" role="tab" aria-controls="cert-item" aria-selected="true"><?php echo($tabTitle)?></button>
						</li>
					<?php endif ?>
					<?php if (!empty($schools) && is_array($schools)) : ?>
						<li class="nav-item" role="presentation">
							<button class="nav-link <?php if (empty($certificates) || !is_array($certificates)) { echo("active"); } ?>" id="schools-tab" data-bs-toggle="tab" data-bs-target="#schools-item" type="button" role="tab" aria-controls="schools-item" aria-selected="true"><?php echo($tabSchoolTitle)?></button>
						</li>
					<?php endif ?>
				</ul>

				 <div class="tab-content mt-4 pt-2" id="vwTabContent">
					<div class="tab-pane fade show active" id="cert-item" role="tabpanel" aria-labelledby="cert-tab">
						
						
						
							<?php if (empty($certificates) || !is_array($certificates)) { ?>
		
		
								<?php if (!empty($model->user_nft_address)) { ?>
								
									<div class="container py-5">
										<div class="row justify-content-center">
											<div class="col-12">
												<div class="section-title text-center">
													<h4 class="title mb-4"><?php echo(Yii::t('Frontend', 'Certificates not found'))?>.<br><?php echo(Yii::t('Frontend', 'Try another address'))?></h4>
												</div>
											</div><!--end col-->
										</div><!--end row-->
									</div><!--end container-->
									
								<?php } ?>
		
							<?php } else { ?>
							
							<div class="row row-cols-xl-4 row-cols-lg-3 row-cols-sm-2 row-cols-1 g-4">
								
								<?php foreach ($certificates as $certificate) { ?>
								
									<?php
									$href_to_token_mainnet = '<a href="javascript:void(0)" class="icon cert-icon cert-icon-disable" title="'.Yii::t('Frontend', 'Mainnet').'" data-bs-toggle="tooltip" data-bs-placement="top">M<i class="fa fa-wifi"></i></a>';
									
									$href_to_token_testnet = '<a href="javascript:void(0)" class="icon cert-icon cert-icon-disable" title="'.Yii::t('Frontend', 'Testnet').'" data-bs-toggle="tooltip" data-bs-placement="top">T<i class="fa fa-wifi"></i></a>';
									
									$hash = Certificate::getHashCertificate($certificate['id_client'], $certificate['id_certificate']);

									if (!empty($certificate['minted_on_mainnet'])) {
										$href_to_token_mainnet = '<a href="'.SchoolToken::MAINNET_BSCSCAN_ADDRESS . $certificate['minted_by_contract_mainnet'] . '?a=' . $certificate['id_nft_token_mainnet'].'" class="icon cert-icon cert-icon" title="'.Yii::t('Frontend', 'Mainnet').'" data-bs-toggle="tooltip" data-bs-placement="top" target="_blank">M<i class="fa fa-wifi"></i></a>';
									}
								
									if (!empty($certificate['minted_on_testnet'])) {
										$href_to_token_testnet = '<a href="'.SchoolToken::TESTNET_BSCSCAN_ADDRESS . $certificate['minted_by_contract_testnet'] . '?a=' . $certificate['id_nft_token_testnet'].'" class="icon cert-icon cert-icon" title="'.Yii::t('Frontend', 'Testnet').'" data-bs-toggle="tooltip" data-bs-placement="top" target="_blank">T<i class="fa fa-wifi"></i></a>';
									}
									
									$name = trim($certificate['name'].' '.$certificate['surname']);
									$course = trim($certificate['course']);
									$date = date('d.m.Y', strtotime($certificate['creation_date']));
									$school = trim($certificate['school_name']);
									$web_site = trim($certificate['web_site']);

									if (!empty($school) && !empty($web_site)) {
										$school = "<a target='_blank' href='" . $web_site . "'>" .Yii::t('Frontend', 'Verified by') . ': ' . $school . "</a>";
									}

									?>
									<div class="col">
										<div class="card nft-items nft-primary rounded-md shadow overflow-hidden mb-1 p-3">
											<div class="d-flex justify-content-between">
												<div class="img-group">
													<a href="/certificate?id=<?=$certificate['id_certificate']?>&hash=<?=$hash?>" class="user-avatar" title="<?=Yii::t('Frontend', 'Certificate PNG')?>" data-bs-toggle="tooltip" data-bs-placement="top" target="_blank">
														<i class="fa fa-file-image-o avatar avatar-sm-sm cert-icon" aria-hidden="true"></i>
													</a>
													
													<?php if (!Yii::$app->user->isGuest) { ?>
														
														<a href="/certificate/update?id=<?=$certificate['id_certificate']?>" class="user-avatar ms-n3" title="<?=Yii::t('Frontend', 'Edit certificate')?>" data-bs-toggle="tooltip" data-bs-placement="top" target="_blank">
															<i class="fa fa-pencil-square-o avatar avatar-sm-sm cert-icon" aria-hidden="true"></i>
														</a>
														
													<?php } ?>
													
												</div>					
												<span>
													<?=$href_to_token_mainnet?>
													<?=$href_to_token_testnet?>
												</span>
											</div> 
											<div class="mx-auto nft-image rounded-md position-relative overflow-hidden cert-block-text">
												<a class="img-link" href="/public/viewaddress?id=<?=$certificate['id_certificate']?>&hash=<?=$hash?>" target="_blank"><img src="/upload/certificate/nf_certificate.png" class="img-fluid" alt="">
													<div class="position-absolute cert-pos pos-1">
														<div class="text-center">№ <?=$certificate['id_certificate']?></div>
													</div>
													<div class="position-absolute cert-pos pos-2">
														<div class="text-center"><?=$name?></div>
													</div>
													<div class="position-absolute cert-pos pos-4">
														<div class="text-center"><?=$course?></div>
													</div>
													<div class="position-absolute pos-5">
														<?=$date?>
													</div>
												</a>
											</div>
											<div class="card-body content position-relative p-0">
												<div class="justify-content-between mt-2">
													<div class="text-dark small"><?=$name?></div>
													<div class="text-dark small"><?=$school?></div>
													<div class="text-dark small"><?=$course?></div>
												</div>
											</div>
										</div>
									</div>

								<?php } ?>
								
							</div>
								
							<?php } ?>
						
					</div>
					<div class="tab-pane fade show" id="schools-item" role="tabpanel" aria-labelledby="schools-item">
						
						
					
							<?php if (!empty($schools) && is_array($schools)) { ?>
							
								<div class="row row-cols-xl-4 row-cols-lg-3 row-cols-sm-2 row-cols-1 g-4">
									
									<?php foreach ($schools as $school) { ?>
									
										<?php

										$href_to_school_token_mainnet = '<a href="javascript:void(0)" class="icon cert-icon cert-icon-disable" title="'.Yii::t('Frontend', 'Mainnet').'" data-bs-toggle="tooltip" data-bs-placement="top">M<i class="fa fa-wifi"></i></a>';
										
										$href_to_school_token_testnet = '<a href="javascript:void(0)" class="icon cert-icon cert-icon-disable" title="'.Yii::t('Frontend', 'Testnet').'" data-bs-toggle="tooltip" data-bs-placement="top">T<i class="fa fa-wifi"></i></a>';

										if (!empty($school->school_nft_address_mainnet)) {
											$href_to_school_token_mainnet = '<a href="'.SchoolToken::MAINNET_BSCSCAN_ADDRESS . $school->school_nft_address_mainnet . '" class="icon cert-icon cert-icon" title="'.Yii::t('Frontend', 'Mainnet').'" data-bs-toggle="tooltip" data-bs-placement="top" target="_blank">M<i class="fa fa-wifi"></i></a>';
										}
									
										if (!empty($school->school_nft_address_testnet)) {
											$href_to_school_token_testnet = '<a href="'.SchoolToken::TESTNET_BSCSCAN_ADDRESS . $school->school_nft_address_testnet . '" class="icon cert-icon cert-icon" title="'.Yii::t('Frontend', 'Testnet').'" data-bs-toggle="tooltip" data-bs-placement="top" target="_blank">T<i class="fa fa-wifi"></i></a>';
										}
										
										$school_name = trim($school->school_name);
										$school_web_site = trim($school->web_site);

										if (empty($school_name)) {
											$school_name = $school->identify_name;
										}

										if (!empty($school_web_site)) {
											$school_name = "<a target='_blank' href='" . $school_web_site . "'>" .Yii::t('Frontend', 'Verified by') . ': ' . $school_name . "</a>";
										}

										$image_src = "/upload/default.jpg";
										
										if (!empty($school->image)) {
											$image_src = "/upload/client/" . md5($school->id) . '/' . $school->image;
										}

										?>
										<div class="col">
											<div class="card nft-items nft-primary rounded-md shadow overflow-hidden mb-1 p-3">
												<div class="d-flex justify-content-end">					
													<span>
														<?=$href_to_school_token_mainnet?>
														<?=$href_to_school_token_testnet?>
													</span>
												</div> 
												<div class="mx-auto nft-image rounded-md position-relative overflow-hidden cert-block-text" style="max-height: 170px;">
													<img src="<?=$image_src?>" class="img-fluid" alt="">
												</div>
												<div class="card-body content position-relative p-0">
													<div class="justify-content-between mt-2">
														<div class="text-dark small"><?=$school_name?></div>
														<div class="text-dark small"><?=$school_web_site?></div>
													</div>
												</div>
											</div>
										</div>

									<?php } ?>
									
								</div>
								
							<?php } ?>
						
					</div>
				</div>
				
			</div>
		</div>
	</div>	
</section>


