<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use common\widgets\Alert;
use common\models\Clients;
use yii\bootstrap5\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use app\modules\certificate\CertificateModule;
use app\modules\certificate\models\CertificateWork;
use frontend\components\SchoolToken;


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
		color:#7b7b7b;
	}
	.cert-block-text .img-fluid {
		width:236px;
	}
	.cert-block-text .cert-pos {
		left:10px;
		right:10px;
		color:#7b7b7b;
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
		color:#7b7b7b;
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
<!-- End -->
<?php
$href_to_token_id_mainnet = '';
$href_to_token_id_testnet = '';
$href_to_token_mainnet = '';
$href_to_token_testnet = '';
$href_to_owner_mainnet = '';
$href_to_owner_testnet = '';

if (!empty($model->minted_on_mainnet)) {
	$href_to_token_id_mainnet = '<a target="_blank" href="'.SchoolToken::MAINNET_BSCSCAN_ADDRESS . $model->minted_by_contract_mainnet . '?a=' . $model->id_nft_token_mainnet . '">' . $model->id_nft_token_mainnet . '</a>';
	$href_to_token_mainnet = '<a target="_blank" href="'.SchoolToken::MAINNET_BSCSCAN_ADDRESS . $model->minted_by_contract_mainnet . '">' . $model->minted_by_contract_mainnet . '</a>';
}

if (!empty($model->minted_on_testnet)) {
	$href_to_token_id_testnet = '<a target="_blank" href="'.SchoolToken::TESTNET_BSCSCAN_ADDRESS . $model->minted_by_contract_testnet . '?a=' . $model->id_nft_token_testnet . '">' . $model->id_nft_token_testnet . '</a>';
	$href_to_token_testnet = '<a target="_blank" href="'.SchoolToken::TESTNET_BSCSCAN_ADDRESS . $model->minted_by_contract_testnet . '">' . $model->minted_by_contract_testnet . '</a>';
}

if (!empty($model->user_nft_address)) {
	$href_to_owner_mainnet = '<a target="_blank" href="'.SchoolToken::MAINNET_BSCSCAN_OWNER_ADDRESS . $model->user_nft_address . '">' . $model->user_nft_address . '</a>';
	$href_to_owner_testnet = '<a target="_blank" href="'.SchoolToken::TESTNET_BSCSCAN_OWNER_ADDRESS . $model->user_nft_address . '">' . $model->user_nft_address . '</a>';
}

?>
<?php $metaMainnet = $model->getMetaData(CertificateWork::MAINNET); ?>
<?php $metaTestnet = $model->getMetaData(CertificateWork::TESTNET); ?>

<section class="bg-item-detail d-table w-100">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<div class="sticky-bar">
					<img src="<?php echo($model->getCertificateImageUrl()) ?>" class="img-fluid rounded-md shadow" alt="">
				</div>
			</div>

			<div class="col-md-6 mt-4 pt-2 mt-sm-0 pt-sm-0">
				<div class="ms-lg-4">
					<div class="title-heading">
						<h4 class="h3 fw-bold "><?php echo(Yii::t('Frontend', 'Course') . ': ' . $model->course)?></h4>
						<h6 class="fw-bold mb-0"><?php echo(Yii::t('Frontend', 'Education organization') . ': ' .$model->getSchoolName()) ?></h6>
					</div>

					<div class="row mt-2 pt-2">
						<div class="col-12">
							<ul class="nav nav-tabs border-bottom" id="myTab" role="tablist">
								<?php if (!empty($model->minted_on_mainnet)) : ?>
									<li class="nav-item" role="presentation">
										<button class="nav-link active" id="detail-tab" data-bs-toggle="tab" data-bs-target="#detailItemMainnet" type="button" role="tab" aria-controls="detailItemMainnet" aria-selected="true"><?php echo(Yii::t('Frontend', 'Mainnet Details'))?></button>
									</li>
								<?php endif; ?>
								<?php if (!empty($model->minted_on_testnet)) : ?>
									<li class="nav-item" role="presentation">
										<button class="nav-link <?php echo(empty($model->minted_on_mainnet) ? 'active' : '')?>" id="detail-tab" data-bs-toggle="tab" data-bs-target="#detailItemTestnet" type="button" role="tab" aria-controls="detailItemTestnet" aria-selected="true"><?php echo(Yii::t('Frontend', 'Testnet Details'))?></button>
									</li>
								<?php endif; ?>
							</ul>

							<div class="tab-content mt-4 pt-2" id="myTabContent">
								<?php if (!empty($model->minted_on_mainnet)) : ?>
									<div class="tab-pane fade show active" id="detailItemMainnet" role="tabpanel" aria-labelledby="detail-tab">
										<div class="row">
											<div class="col-12">
												<h6><?php echo(Yii::t('Frontend', 'Mainnet Token Id'))?></h6>
												<h4 class="mb-0"><?php echo($href_to_token_id_mainnet)?></h4>
											</div>
											<div class="col-12 mt-4 pt-2" style="word-break:break-all;overflow-wrap:break-word;">
												<h6><?php echo(Yii::t('Frontend', 'Mainnet contract address'))?></h6>
												<h5 class="mb-0"><?php echo($href_to_token_mainnet)?></h5>
											</div>
											<div class="col-12 mt-4 pt-2">
												<p class="text-muted mb-0"><?php echo $metaMainnet['description'] ?></p>
											</div>
											<div class="col-12 mt-4 pt-2" style="word-break:break-all;overflow-wrap:break-word;">
												<h6><?php echo(Yii::t('Frontend', 'Owner'))?></h6>
												<div class="creators creator-primary d-flex align-items-center">
													<div class="ms-3">
														<h6 class="mb-0"><?php echo($href_to_owner_mainnet) ?></h6>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php endif; ?>

								<?php if (!empty($model->minted_on_testnet)) : ?>
									<div class="tab-pane fade show <?php echo(empty($model->minted_on_mainnet) ? 'active' : '')?>" id="detailItemTestnet" role="tabpanel" aria-labelledby="detail-tab">	
										<div class="row">
											<div class="col-12">
												<h6><?php echo(Yii::t('Frontend', 'Testnet Token Id'))?></h6>
												<h4 class="mb-0"><?php echo($href_to_token_id_testnet)?></h4>
											</div>
											<div class="col-12 mt-4 pt-2" style="word-break:break-all;overflow-wrap:break-word;">
												<h6><?php echo(Yii::t('Frontend', 'Testnet contract address'))?></h6>
												<h5 class="mb-0"><?php echo($href_to_token_testnet)?></h5>
											</div>
											<div class="col-12 mt-4 pt-2">
												<p class="text-muted mb-0"><?php echo $metaTestnet['description'] ?></p>
											</div>
											<div class="col-12 mt-4 pt-2" style="word-break:break-all;overflow-wrap:break-word;">
												<h6><?php echo(Yii::t('Frontend', 'Owner'))?></h6>
												<div class="creators creator-primary d-flex align-items-center">
													<div class="ms-3">
														<h6 class="mb-0"><?php echo($href_to_owner_testnet) ?></h6>
													</div>
												</div>
											</div>
										</div>
									</div>
								<?php endif; ?>
						</div>
					</div>
				</div>
			</div><!--end col-->
		</div><!--end row-->
	</div><!--end container-->

	<!--end container-->
</section>


