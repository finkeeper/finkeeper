<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use common\widgets\Alert;
use common\models\Clients;
use yii\bootstrap5\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use app\modules\certificate\CertificateModule;
use frontend\components\SchoolToken;

$client = Clients::findUserId();

$chainId = SchoolToken::BNB_TESTNET_CHAIN_ID;
$contractAddress = $client->school_nft_address_testnet;
$jsAdminObj = SchoolToken::JS_ADMIN_OBJECT_TESTNET;


$this->registerJsFile('/js/ethers.umd.min.js', ['position' => yii\web\View::POS_END]);
$this->registerJsFile('/js/schoolToken.js', ['position' => yii\web\View::POS_END]);

if (!empty($client->is_mainnet)) {
	$this->registerJsFile('/js/adminctfnpromainnet.js', ['position' => yii\web\View::POS_END]);
	$jsAdminObj = SchoolToken::JS_ADMIN_OBJECT_MAINNET;
	$chainId = SchoolToken::BNB_MAINNET_CHAIN_ID;
	$contractAddress = $client->school_nft_address_mainnet;
} else {
	$this->registerJsFile('/js/adminctfnprotestnet.js', ['position' => yii\web\View::POS_END]);
}

$this->registerJs('
	jQuery(document).ready(function($) {
		createTokenButton();
	});

	let stateObj = {
		loading: false,
		reloadTokenBlock: false
	};

	function createTokenButton() {
		$("#mintToken").off("click");
		$("#mintToken").on("click", async function() {

			if (stateObj.loading) {
				return false;
			}
			stateObj.loading = true;
			
			$(this).removeClass("disabled");
			$(this).addClass("disabled");
			
			$("#mintTokenSpinner").show();
			
			let result = await processMintToken();
			
			$("#mintTokenSpinner").hide();
			$(this).removeClass("disabled");
			stateObj.loading = false;
		});
	}
	
	async function processMintToken() {
		let studentAddress = "'.$model->user_nft_address.'";
		let newTokenUri = "'.$model->getCertificateMetaUrl().'";
		let is_mainnet = "'.$client->is_mainnet.'";
		try {

			if (await beforeProccess(is_mainnet) === true) {
				return await mintToken(studentAddress, newTokenUri);
			}
			
		} catch(error) {
			console.log(error);

			if (typeof error === "object") {
				error = JSON.stringify(error);
			}

			showModal("danger", "' . Yii::t('Frontend', 'Error') . '", error);
		}
		return false;
	}

	async function beforeProccess(is_mainnet) {
		console.log(is_mainnet);
		let networkIsSet = await setNetwork(is_mainnet);
		if (networkIsSet) {
			await printWalletData();
			return true;
		}
		return false;
	}

	async function mintToken(studentAddress, newTokenUri) {
		
		const provider = new ethers.providers.Web3Provider(window.ethereum); //id
		const listAccounts = await provider.send("eth_requestAccounts", []);
		const signer = await provider.getSigner(listAccounts[0]);
		const signer_address = await signer.getAddress();
		
		const {chainId} = await provider.getNetwork();

		const adminObj = ' . $jsAdminObj . ';
		const contractAddress = "'. $contractAddress .'";

		let chainLabels = [];
		chainLabels[97] = "BNB Testnet";
		chainLabels[56] = "BNB Mainnet";
		
		if (chainId != adminObj.CHAIN_ID) {
			throw "'.Yii::t('Frontend', 'Error! Change network to').' " + chainLabels[adminObj.CHAIN_ID] + ". (ChainID = " + adminObj.CHAIN_ID + ")";
		}
		
		if (!ethers.utils.isAddress(studentAddress)) {
			throw "'.Yii::t('Frontend', 'Error! User address is invalid').'";
		}

		const contract = await new ethers.Contract(contractAddress, schoolToken.CONTRACT_ABI, signer);
		
		const price = await getMintPrice();
		const tx = await contract.safeMint(studentAddress, newTokenUri, {value: price, gasLimit: adminObj.BASE_GAS_LIMIT});

		let result = await tx.wait();
		console.log("transaction", result);
		
		const [Transfer] = result.events;
		console.log("Transfer", Transfer);

		await SaveMintData(result.from, Transfer.args);	

		if (stateObj.reloadTokenBlock) {
			showModal("success", "' . Yii::t('Frontend', 'Success') . '", "' . Yii::t('Frontend', 'Token is minted') . '");
			stateObj.reloadTokenBlock = false;
			await reloadTokenBlock();
			createTokenButton();
		}

	}

	async function SaveMintData(mintedby, Transfer) {
		stateObj.reloadTokenBlock = false;

		let tokenId = parseInt(Transfer[2]._hex);
		console.log("tokenId", tokenId);

		await $.ajax({
			url: "' . Url::to(['/certificate/savemint', 'id' => $model->id_certificate]) . '",
			type: "post",
			data: {
				YII_CSRF_TOKEN: "' . Yii::$app->getRequest()->getCsrfToken() . '",
				mintedby: mintedby,
				tokenId: tokenId,
			},
			success: function(response) {
				try {
					let result = JSON.parse(response);
					if (result.error) {
						console.log(result.message);
						showModal("danger", "' . Yii::t('Frontend', 'Error') . '", result.message);
					} else {
						stateObj.reloadTokenBlock = true;
					}
				} catch (error) {
					console.log(error);
					showModal("danger", "' . Yii::t('Frontend', 'Error') . '", error);
				}
			}
		});
	}


	async function getMintPrice() {
		const adminObj = '. $jsAdminObj .';
		const provider = new ethers.providers.Web3Provider(window.ethereum); //id
		const listAccounts = await provider.send("eth_requestAccounts", []);
		const signer = await provider.getSigner(listAccounts[0]);
		const signer_address = await signer.getAddress();
		
		const contract = await new ethers.Contract(adminObj.CONTRACT_ADDRESS, adminObj.CONTRACT_ABI, signer);
		return await contract.safeMintPrice();
	}

	async function reloadTokenBlock() {
		
		$("#reloadTokenContent").fadeOut();
		$("#reloadTokenSpinner").fadeIn();

		await $.ajax({
			url: "' . Url::to(['/certificate/reloadtoken', 'id' => $model->id_certificate]) . '",
			type: "post",
			data: {
				YII_CSRF_TOKEN: "' . Yii::$app->getRequest()->getCsrfToken() . '"
			},
			success: function(response) {
				$("#reloadTokenContent").html(response);
			}
		});

		$("#reloadTokenSpinner").fadeOut();
		$("#reloadTokenContent").fadeIn();
	}


', yii\web\View::POS_END);


$this->registerCss('
	.big-text-overflow {
		overflow-wrap: break-word;
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

<!-- Start -->
<section class="section">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-4 order-2 order-md-1 mt-4 pt-2 mt-sm-0 pt-sm-0">
				<div class="card creators creator-primary rounded-md shadow overflow-hidden sticky-bar">
					<div class="py-5" style="background: url('/upload/school_logo_default.jpg');"></div>
					
					<?php //if (empty($client->school_logo)) { ?>

						<!-- <div class="py-5" style="background: url('/upload/school_logo_default.jpg');"></div> -->
					
					<?php //} else { ?>
					
						<!-- <div class="py-5" style="background: url('/upload/client/<?php //echo(md5($client->id))?>/<?php //echo($client->school_logo)?>');"></div> -->
					
					<?php //} ?>
					
					
					<div class="position-relative mt-n5">
						
						<?php if (empty($client->image)) { ?>
					
							<img src="/upload/default.jpg" class="avatar avatar-md-md rounded-pill shadow-sm bg-light img-thumbnail mx-auto d-block" id="client-image" alt="">
							
						<?php } else { ?>
							
							<img src="/upload/client/<?=md5($client->id)?>/<?=$client->image?>" class="avatar avatar-md-md rounded-pill shadow-sm bg-light img-thumbnail mx-auto d-block" id="client-image" alt="">
							
						<?php } ?>
				
						<div class="content text-center pt-2 p-4">
							<h6 class="mb-0"><?=$client->name?> <?=$client->surname?></h6>
							<small class="text-muted"><?=$client->identify_name?></small>

							<ul class="list-unstyled mb-0 mt-3" id="navmenu-nav">
								<li class="px-0">
									<a href="/profile/view" class="d-flex align-items-center text-dark">
										<span class="fs-6 mb-0"><i class="uil uil-user"></i></span>
										<small class="d-block fw-semibold mb-0 ms-2"><?=Yii::t('Menu', 'Profile View')?></small>
									</a>
								</li>
								<li class="px-0 mt-2">
									<a href="/logout" class="d-flex align-items-center text-dark">
										<span class="fs-6 mb-0"><i class="uil uil-sign-in-alt"></i></span>
										<small class="d-block fw-semibold mb-0 ms-2"><?=Yii::t('Menu', 'Logout')?></small>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div><!--end col-->

			<div class="col-lg-9 col-md-8 order-1 order-md-2">
			
				<?=Alert::widget()?>
				<?php if (empty($model->isNewRecord) && !empty($model->user_nft_address)) : ?>
					<div class="pb-4">
						<div id="reloadTokenData">
							<div id="reloadTokenDataSpinner" class="spinner-grow text-primary" style="display:none;" role="status">
								<span class="visually-hidden"><?=Yii::t('Form', 'Loading')?>...</span>
							</div>
							<div id="reloadTokenContent">
								<?php echo($model->getRenderData()); ?>
							</div>
						</div>
					</div>
				<?php else: ?>
					<div class="alert alert-info" role="alert">
						<?php echo(Yii::t('Certificate', 'Enter the student certificate data and click "Save students data" to go to the NFT-certificate minting')) ?>
					</div>
				<?php endif; ?>

				<div class="result-img-load"></div>
			
				<div class="card rounded-md shadow p-4">		
					<div class="">
					
						<?php $form = ActiveForm::begin([
							'id' => 'certificate-form',
							'class' => 'certificate-edit',
						]); ?>

							<div class="row">
										
								<div class="col-12 mb-4">
										
									<?=$form->field($model, 'user_nft_address', [
										'template' => '<label for="certificatework-user_nft_address" class="form-label fw-bold">'.Yii::t('Certificate', 'Crypto wallet address').' <i class="fa fa-asterisk text-danger"></i></label>{input}{error}'
									])->textInput(['type'=>'text', 'placeholder'=>Yii::t('Certificate', "Student's crypto wallet address in BNB chain"), 'autocomplete' => 'off']) ?>
									
								</div><!--end col-->		
										
								<div class="col-12 mb-4">
										
									<?=$form->field($model, 'name', [
										'template' => '<label for="certificatework-name" class="form-label fw-bold">'.Yii::t('Frontend', 'User Name').'</label>{input}{error}'
									])->textInput(['type'=>'text', 'placeholder'=>Yii::t('Certificate', "Student's Name"), 'autocomplete' => 'off']) ?>
									
								</div><!--end col-->	

								<div class="col-12 mb-4">
										
									<?=$form->field($model, 'surname', [
										'template' => '<label for="certificatework-surname" class="form-label fw-bold">'.Yii::t('Frontend', 'Surname').'</label>{input}{error}'
									])->textInput(['type'=>'text', 'placeholder'=>Yii::t('Certificate', "Student's Surname"), 'autocomplete' => 'off']) ?>
									
								</div><!--end col-->		
										
								<div class="col-12 mb-4">
										
									<?=$form->field($model, 'course', [
										'template' => '<label for="certificatework-course" class="form-label fw-bold">'.Yii::t('Frontend', 'Course').'</label>{input}{error}'
									])->textInput(['type'=>'text', 'placeholder'=>Yii::t('Frontend', 'Course'), 'autocomplete' => 'off']) ?>
									
								</div><!--end col-->
				
								<div class="col-12 mb-4">
										
									<?=$form->field($model, 'number', [
										'template' => '<label for="certificatework-number" class="form-label fw-bold">'.Yii::t('Frontend', 'Number').'</label>{input}{error}'
									])->textInput(['type'=>'text', 'placeholder'=>Yii::t('Certificate', 'Identifier of student in your LMS system'), 'autocomplete' => 'off']) ?>
									
								</div><!--end col-->		

								<div class="col-lg-12">
									<?= Html::submitButton(Yii::t('Form', 'Save student data'), ['id'=>'submit-form', 'class' => 'btn btn-primary', 'name' => 'send']) ?>
								</div><!--end col-->
										
							</div>
				
						<?php ActiveForm::end(); ?>
						
					</div>	
				</div>
			</div><!--end col-->
		</div><!--end row-->
	</div><!--end container-->
</section><!--end section-->
<!-- End -->


