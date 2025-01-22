<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use common\widgets\Alert;
use yii\bootstrap5\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use app\modules\profile\ProfileModule;

$this->title = $title; 

$jsAdminFile = !empty($model->is_mainnet) ? "adminCtfnMainnet" : "adminCtfnTestnet";

$this->registerJsFile('/js/ethers.umd.min.js', ['position' => yii\web\View::POS_END]);

if (!empty($model->is_mainnet)) {
	$this->registerJsFile('/js/adminctfnpromainnet.js', ['position' => yii\web\View::POS_END]);
} else {
	$this->registerJsFile('/js/adminctfnprotestnet.js', ['position' => yii\web\View::POS_END]);
}

// die("testView2"); //

$this->registerJs('

	function str_rand() {
		var result = "";
		var words = "0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
		var max_position = words.length - 1;
		
		for( i = 0; i < 10; ++i ) {
			position = Math.floor ( Math.random() * max_position );
			result = result + words.substring(position, position + 1);
		}
		
		return result;
	}

	jQuery(document).ready(function($) {
		$("#generate-password").on("click", function() {
			$("#profile-password, #profile-confirm_password").attr("type", "text").val(str_rand());
			$("#show-password").find("i").removeClass("fa-eye").addClass("fa-eye-slash");
		});
		
		$("#show-password").on("click", function() {
			var type_input = $("#profile-password").attr("type");
			if (typeof type_input!=="undefined" && type_input!==undefined && type_input=="text") {
				$("#show-password").find("i").removeClass("fa-eye-slash").addClass("fa-eye");
				$("#profile-password, #profile-confirm_password").attr("type", "password")
			} else {
				$("#show-password").find("i").removeClass("fa-eye").addClass("fa-eye-slash");
				$("#profile-password, #profile-confirm_password").attr("type", "text");
			}
		});
		
		$("#pro-img").on("change", function(event) {
			var image = document.getElementById("profile-image");
			image.src = URL.createObjectURL(event.target.files[0]);
			
			uploadFile(event.target.files[0], 1);
		});
		
		createTokenButton();
	});

	

	let stateObj = {
		loading: false,
		reloadTokenBlock: false
	};

	function createTokenButton() {
		$("#createToken").off("click");
		$("#createToken").on("click", async function() {

			if (stateObj.loading) {
				return false;
			}
			stateObj.loading = true;
			
			$(this).removeClass("disabled");
			$(this).addClass("disabled");
			
			$("#createTokenSpinner").show();
			
			let result = await processCreateToken();
			
			$("#createTokenSpinner").hide();
			$(this).removeClass("disabled");
			stateObj.loading = false;
		});
	}
	
	async function processCreateToken() {
		let schoolName = "'.$model->identify_name.'";
		let is_mainnet = '.$model->is_mainnet.';
		try {

			if (await beforeProccess(is_mainnet) === true) {
				return await createSchoolToken(schoolName, schoolName.substring(0, 3));
			}

		} catch(error) {
			console.log("Error ", error);

			if (typeof error === "object") {
				error = JSON.stringify(error);
			}

			showModal("danger", "' . Yii::t('Frontend', 'Error') . '", error);
		}
		return false;
	}

	async function createSchoolToken(_schoolName, _schoolAbbr) {
		
		let adminObj = ' . $jsAdminFile . ';
		
		const provider = new ethers.providers.Web3Provider(window.ethereum); //id
		const listAccounts = await provider.send("eth_requestAccounts", []);
		const signer = await provider.getSigner(listAccounts[0]);
		const signer_address = await signer.getAddress();
		
		const {chainId} = await provider.getNetwork();
		
		let chainLabels = [];
		chainLabels[97] = "BNB Testnet";
		chainLabels[56] = "BNB Mainnet";
		
		if (chainId != adminObj.CHAIN_ID) {
			throw "'.Yii::t('Profile', 'Error! Change network to').' " + chainLabels[adminObj.CHAIN_ID] + ". (ChainID = " + adminObj.CHAIN_ID + ")";
		}

		const contract = await new ethers.Contract(adminObj.CONTRACT_ADDRESS, adminObj.CONTRACT_ABI, signer);

		_schoolAbbr = _schoolAbbr.toUpperCase();
		
		const tx = await contract.createSchool(_schoolName, _schoolAbbr, {value: ethers.utils.parseUnits(adminObj.DEPLOY_SCHOOL_PRICE)});

		const result = await tx.wait();
		console.log("transaction", result);
		
		const SchoolCreated = result.events[2];
		console.log("SchoolCreated", SchoolCreated.args);
		
		await SaveCreateSchoolData(SchoolCreated.args);
		
		// const returnTX = await contract.withdraw();
		// const resultTX = await returnTX.wait();
		// console.log("transaction return", resultTX);

		if (stateObj.reloadTokenBlock) {
			showModal("success", "' . Yii::t('Profile', 'Success') . '", "' . Yii::t('Profile', 'Token is created') . '");
			stateObj.reloadTokenBlock = false;
			await reloadTokenBlock();
			createTokenButton();
		}
	}

	async function beforeProccess(is_mainnet) {
		let networkIsSet = await setNetwork(is_mainnet);
		if (networkIsSet) {
			await printWalletData();
			return true;
		}
		return false;
	}

	////// ERROR HERE Url::toRoute(\'/profile/save\')

	async function SaveCreateSchoolData(SchoolCreated) {
		stateObj.reloadTokenBlock = false;
		await $.ajax({
			url: "' . Url::toRoute('/profile/save') . '",
			type: "post",
			data: {
				YII_CSRF_TOKEN: "' . Yii::$app->getRequest()->getCsrfToken() . '",
				createdBy: SchoolCreated.createdBy,
				schoolAddress: SchoolCreated.schoolAddress,
			},
			success: function(response) {
				try {
					let result = JSON.parse(response);
					if (result.error) {
						console.log(result.message);
						showModal("danger", "' . Yii::t('Profile', 'Error') . '", result.message);
					} else {
						stateObj.reloadTokenBlock = true;
					}
				} catch (error) {
					console.log(error);
					showModal("danger", "' . Yii::t('Profile', 'Error') . '", error);
				}
			}
		});
	}

	async function reloadTokenBlock() {
		
		$("#reloadTokenContent").fadeOut();
		$("#reloadTokenSpinner").fadeIn();

		await $.ajax({
			url: "' . Url::toRoute('/profile/reloadtoken') . '",
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
	.passwd-input {
		width: calc(100% - 46px);
		float:left;
	}
	.passwd-button {
		position:relative;
		width:43px;
		height:70px;
		padding:20px 0 0 0;
		float:right;
		overflow:hidden;
	}
	.passwd-generate-button,
	.passwd-show-button	{
		padding:0;
		margin:0;
	}
	.passwd-generate-button .fa,
	.passwd-show-button .fa	{
		font-size:12px;
	}
	.passwd-generate-button button,
	.passwd-show-button button {
		height:18px;
		width:19px;
		margin:0;
	}

	.big-text-overflow {
		overflow-wrap: break-word;
	}
');

?>



<?= $this->render(	
	'@app/themes/th1/views/site/elements/__header_3.php',
) ?>


<div id="none" class="modal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="btn close" data-dismiss="modal" aria-label="Close">
					<span class="h3" aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><?=Yii::t('Frontend', 'Close')?></button>
				<button type="button" class="btn btn-primary"><?=Yii::t('Frontend', 'Save')?></button>
			</div>
		</div>
	</div>
</div>


<!-- Start -->
<div class="content site-profile-edit">

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
				<div class="col-lg-9">
					<h5><?=Yii::t('Profile', 'You can create organization token, set preferred display name and manage other profile settings')?>.</h5>
				</div> 
			</div>

			<div class="row">
				<div class="col-lg-8 col-md-7 col-12 order-2 order-md-1 mt-4 pt-2">
				
					<?=Alert::widget()?>
					<div class="result-img-load"></div>

					<div class="rounded-md shadow">
						<div class="p-4 border-bottom">
							<h5 class="mb-0"><?=Yii::t('Frontend', 'Edit Form Profile')?>:</h5>
						</div>
			
						<div class="p-4">
						
							<?php $form = ActiveForm::begin([
								'id' => 'edit-profile-form',
								'class' => 'profile-edit',
							]); ?>
							
								<div class="row">
									<div class="col-12 mb-4">
										
										<?=$form->field($model, 'name', [
											'template' => '<label for="profile-name" class="form-label h6">'.Yii::t('Profile', 'Display Name (for profile page)').'</label>{input}{error}'
										])->textInput(['type'=>'text', 'placeholder'=>Yii::t('Profile', 'Will be displayed in the organization profile on CTFN.pro'), 'autocomplete' => 'off']) ?>
										
									</div><!--end col-->
									
									<div class="col-12 mb-4">
										<label for="profile-identify_name" class="form-label h6"><?=Yii::t('Profile', 'Education organization (Token name)')?></label> <i class="fa fa-asterisk text-danger"></i>
							
										<?=$form->field($model, 'identify_name', [
											'template' => '{input}{error}'
										])->textInput(['type'=>'text', 'placeholder'=>Yii::t('Profile', 'Only latin characters'), 'autocomplete' => 'off']) ?>
			
									</div><!--end col-->

									<div class="col-12 mb-4">

										<?=$form->field($model, 'employee', [
											'template' => '<label for="profile-employee" class="form-label h6">'.Yii::t('Profile', 'Description').'</label>{input}{error}'
										])->textInput(['type'=>'text', 'placeholder' => Yii::t('Profile', 'Description of education organization'), 'autocomplete' => 'off']) ?>
										
									</div><!--end col-->

									<div class="col-12 mb-4">
										
										<?=$form->field($model, 'web_site', [
											'template' => '<label for="profile-web_site" class="form-label h6">'.Yii::t('Frontend', 'Website').'</label>{input}{error}'
										])->textInput(['type'=>'url', 'placeholder'=>'https://example.com', 'autocomplete' => 'off']) ?>
		
									</div><!--end col-->
	
									<div class="col-12 mb-4">
										<label class="form-label h6"><?=Yii::t('Frontend', 'Email')?></label>
										<div class="form-control"><?=$model->email?></div>
									</div><!--end col-->
			
									<div class="col-12 mb-4">
										
										<label for="profile-web_site" class="form-label h6"><?=Yii::t('Form', 'Password')?></label>	
						
										<?= $form->field($model, 'password', [
											'template' => '<div class="input-group">{input}<button id="show-password" class="btn btn-info btn-outline-secondary" type="button" title="'.Yii::t('Frontend', 'Show password').'"><i class="fa fa-eye" aria-hidden="true"></i></button><button id="generate-password" class="btn btn-warning btn-outline-secondary" type="button" title="'.Yii::t('Frontend', 'Generate password').'"><i class="fa fa-key" aria-hidden="true"></i></button>{error}</div>'
										])->passwordInput(['placeholder'=>Yii::t('Form', 'Password'), 'autocomplete' => 'off', 'class' => 'form-control']) ?>

									</div><!--end col-->
										
									<div class="col-lg-12 mb-4">
										
										<?= $form->field($model, 'confirm_password', [
											'template' => '<label for="profile-web_site" class="form-label h6">'.Yii::t('Form', 'Password Confirm Form').'</label>{input}{error}'
										])->textInput(['placeholder'=>Yii::t('Form', 'Password Confirm Form'), 'autocomplete' => 'off', 'type'=>'password']) ?>
										
									</div><!--end col-->

									<div class="col-lg-12 mb-4">
										
										<?= $form->field($model, 'is_mainnet', [
											'template' => '<label for="profile-is_mainnet" class="form-label h6">'.Yii::t('Form', 'Mainnet').'</label>{input}{error}'
										])->checkBox() ?>
										
									</div><!--end col-->
								
								</div>
								<div class="row">		
				
									<div class="col-lg-12">
											
										<?= Html::submitButton(Yii::t('Form', 'Update Profile'), ['id'=>'submit-form', 'class' => 'btn btn-primary', 'name' => 'send']) ?>

									</div><!--end col-->
										
								</div>
	
							<?php ActiveForm::end(); ?>
							
						</div>	
					</div>
						
					
					<?php if (!empty($model->identify_name) && !$model->getErrors()) { ?>
						<div id="reloadTokenData">
							<div id="reloadTokenDataSpinner" class="spinner-grow text-primary" style="display:none;" role="status">
								<span class="visually-hidden"><?=Yii::t('Form', 'Loading')?>...</span>
							</div>
							<div id="reloadTokenContent">
								<?php echo $model->getSchoolTokenData(); ?>
							</div>
						</div>
					<?php } ?>
							
				</div><!--end col-->

				<div class="col-lg-4 col-md-5 col-12 order-1 order-md-2 mt-4 pt-2">
					<div class="card ms-lg-5">
						<div class="profile-pic">
							<input id="pro-img" name="profile-image" type="file" class="d-none" onchange="loadFile(event)" />
							<div class="position-relative d-inline-block">
								
								<?php if (empty($model->image)) { ?>
					
									<img src="/upload/default.jpg" class="avatar avatar-medium img-thumbnail rounded-pill shadow-sm" id="profile-image" alt="">
									
								<?php } else { ?>
									
									<img src="/upload/client/<?=md5($model->id)?>/<?=$model->image?>" class="avatar avatar-medium img-thumbnail rounded-pill shadow-sm" id="profile-image" alt="">
									
								<?php } ?>
		
								<label class="icons position-absolute bottom-0 end-0" for="pro-img"><span class="btn btn-icon btn-sm btn-pills btn-primary"><i data-feather="camera" class="icons"></i></span></label>
							</div>
						</div>

						<div class="mt-4">
							<p class="text-muted mb-0"><?=Yii::t('Frontend', 'We recommend an image of at least 400X400. GIF, PNG, JPEG work too.')?></p>
						</div>
					</div>
				</div><!--end col-->
			</div><!--end row-->
		</div><!--end container-->
	</section><!--end section-->
	<!-- End -->
			
</div>
<!-- End section -->
		
<?= $this->render(
	'@app/themes/th1/views/site/elements/__footer_1.php'
) ?>