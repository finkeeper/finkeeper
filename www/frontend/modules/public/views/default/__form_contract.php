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

$this->registerJsFile('/js/schoolToken.js', ['position' => yii\web\View::POS_END]);

$this->registerJs('
	jQuery(document).ready(function($) {
		SearchButton();
	});

	let stateObj = {
		loading: false,
		reloadTokenBlock: false,
		ctfnProData: {},
		knownError: false,
	};

	async function beforeProccess(is_mainnet) {
		let networkIsSet = await setNetwork(is_mainnet);
		if (networkIsSet) {
			await printWalletData();
			return true;
		}
		return false;
	}

	function SearchButton() {
		$("#searchToken").off("click");
		$("#searchToken").on("click", async function() {

			$("#certificateNotFound").fadeOut();
			$("#certificateInfo").fadeOut();

			renderReset();

			stateObj.ctfnProData = {};
			stateObj.knownError = false;

			let contractAddress = $("#viewcontract-search_contract").val().trim();
			let tokenId = parseInt($("#viewcontract-search_token_id").val().trim());
			let is_mainnet = $("#viewcontract-is_mainnet").prop("checked");

			console.log("contractAddress", contractAddress);
			console.log("tokenId", tokenId);
			console.log("is_mainnet", is_mainnet);

			if (contractAddress.length == 0 || contractAddress == undefined) {
				showModal("danger", "' . Yii::t('Frontend', 'Error') . '", "' . Yii::t('Frontend', 'Wrong contract address') . '");
				return false;
			}

			if (!ethers.utils.isAddress(contractAddress)) {
				showModal("danger", "' . Yii::t('Frontend', 'Error') . '", "' . Yii::t('Frontend', 'Wrong contract address') . '");
				return false;
			}

			if (isNaN(tokenId) || tokenId == undefined) {
				showModal("danger", "' . Yii::t('Frontend', 'Error') . '", "' . Yii::t('Frontend', 'Wrong token Id') . '");
				return false;
			}

			if (isNaN(is_mainnet) || is_mainnet == undefined) {
				showModal("danger", "' . Yii::t('Frontend', 'Error') . '", "' . Yii::t('Frontend', 'Reload page') . '");
				return false;
			}

			if (stateObj.loading) {
				return false;
			}

			stateObj.loading = true;
			
			$(this).removeClass("disabled");
			$(this).addClass("disabled");
			
			$("#searchTokenSpinner").show();



			let result = await processSearchToken(contractAddress, tokenId, is_mainnet);

			if (result) {
				
				renderData(stateObj.ctfnProData);
				
			} else {

				$("#certificateInfo").fadeOut();
				$("#certificateNotFound").fadeIn();

				let element = document.getElementById("certificateNotFound");
				element.scrollIntoView({ behavior: "smooth"});

			}
			
			$("#searchTokenSpinner").hide();
			
			$(this).removeClass("disabled");
			stateObj.loading = false;
		});
	}
	
	async function processSearchToken(contractAddress, tokenId, is_mainnet) {
		
		let paramsObj = {};

		try {

			if (await beforeProccess(is_mainnet) === false) {
				return false;
			}

			let metaURI = await searchToken(contractAddress, tokenId, is_mainnet);
			console.log("metaURI", metaURI);

			let ownerAddress = await getOwnerOf(contractAddress, tokenId, is_mainnet);
			console.log("ownerAddress", ownerAddress);
			
			paramsObj.contractAddress = contractAddress;
			paramsObj.tokenId = tokenId;
			paramsObj.ownerAddress = ownerAddress;
			paramsObj.is_mainnet = is_mainnet;
			
			stateObj.ctfnProData = await getCtfnProData(metaURI, paramsObj);
			
			return true;

		} catch(error) {
			
			console.log(error);

			if (stateObj.knownError) {
				showModal("danger", "' . Yii::t('Frontend', 'Error') . '", error);
			} else {
				return false;
			}
		}
		
		return false;
	}

	function renderData(ctfnProData) {
		console.log("renderData", ctfnProData);

		if (ctfnProData.meta.error || ctfnProData.params.error || !ctfnProData.meta.data || !ctfnProData.params.data) {
			
			$("#certificateInfo").fadeOut();
			$("#certificateNotFound").fadeIn();

			return false;
		}

		let doLinks = false;
		let doLinksToTokenId = false;

		if ("image" in ctfnProData.meta.data && ctfnProData.meta.data.image.length > 0) {
			$("#certificateImg").prop("src", ctfnProData.meta.data.image);
			$("#certificateImgBlock").show();
		} else {
			$("#certificateImgBlock404").show();
		}

		if ("is_mainnet" in ctfnProData.params.data) {
			if (ctfnProData.params.data.is_mainnet) {
				$("#certificateTokenIdLabel").text("'.Yii::t('Frontend', 'Mainnet Token Id').'");
				$("#certificateContractLabel").text("'.Yii::t('Frontend', 'Mainnet contract address').'");
				$("#certificateTabLabel").text("'.Yii::t('Frontend', 'Mainnet Details').'");
			} else {
				$("#certificateTokenIdLabel").text("'.Yii::t('Frontend', 'Testnet Token Id').'");
				$("#certificateContractLabel").text("'.Yii::t('Frontend', 'Testnet contract address').'");
				$("#certificateTabLabel").text("'.Yii::t('Frontend', 'Testnet Details').'");
			}
			doLinks = true;
		}

		if ("ctfn" in ctfnProData.params.data) {
			$("#ctfnBlock").show();
			$("#certificateCourse").text(ctfnProData.params.data.ctfn.course);
			$("#certificateSchool").text(ctfnProData.params.data.ctfn.schoolName);
			$("#certificateName").parent().hide();
		} else {
			if ("name" in ctfnProData.meta.data) {
				$("#ctfnBlock").show();
				$("#certificateName").text(ctfnProData.meta.data.name);
				$("#certificateCourse").parent().hide();
				$("#certificateSchool").parent().hide();
			}
		}

		if ("contractAddress" in ctfnProData.params.data) { /// LINK
			$("#certificateContractBlock").show();
						
			if (doLinks) {
				let html = getHtmlLinkBscscan(ctfnProData.params.data.is_mainnet, false, ctfnProData.params.data.contractAddress);
				$("#certificateContract").html(html);
			} else {
				$("#certificateContract").text(ctfnProData.params.data.contractAddress);
			}
			
			doLinksToTokenId = true;
		}

		if ("tokenId" in ctfnProData.params.data) { 
			$("#certificateTokenIdBlock").show();

			if (doLinks && doLinksToTokenId) {
				
				let html = getHtmlLinkBscscan(ctfnProData.params.data.is_mainnet, false, ctfnProData.params.data.contractAddress, ctfnProData.params.data.tokenId);
				$("#certificateTokenId").html(html);

			} else {
				$("#certificateTokenId").text(ctfnProData.params.data.tokenId);
			}
		}

		if ("description" in ctfnProData.meta.data && ctfnProData.meta.data.description.length > 0) {
			$("#certificateDescriptionBlock").show();
			$("#certificateDescription").text(ctfnProData.meta.data.description);
		}

		if ("ownerAddress" in ctfnProData.params.data && ctfnProData.params.data.ownerAddress.length > 0) { 
			$("#certificateOwnerBlock").show();
			if (doLinks) {
				let html = getHtmlLinkBscscan(ctfnProData.params.data.is_mainnet, true, ctfnProData.params.data.ownerAddress);
				$("#certificateOwner").html(html);
			} else {
				$("#certificateOwner").text(ctfnProData.params.data.ownerAddress);
			}
		}

		$("#certificateNotFound").fadeOut();
		$("#certificateInfo").fadeIn();
		
		let element = document.getElementById("certificateInfo");
		element.scrollIntoView({ behavior: "smooth"});

	}


	function getHtmlLinkBscscan(is_mainnet, to_owner = false, address, token_id = null) {
		let baseMainnetContract = "'.SchoolToken::MAINNET_BSCSCAN_ADDRESS.'";
		let baseTestnetContract = "'.SchoolToken::TESTNET_BSCSCAN_ADDRESS.'";
		let baseMainnetOwner = "'.SchoolToken::MAINNET_BSCSCAN_OWNER_ADDRESS.'";
		let baseTestnetOwner = "'.SchoolToken::TESTNET_BSCSCAN_OWNER_ADDRESS.'";

		if (is_mainnet) {
			if (to_owner) {
				// ссылка на owner в mainnet
				return "<a target =\'_blank\' href =\'" + baseMainnetOwner + address + "\'>" + address + "</a>";
			} else {
				if (token_id !== null) {
					// ссылка на токен id в mainnet
					return "<a target =\'_blank\' href =\'" + baseMainnetContract + address + "?a=" + token_id + "\'>" + token_id + "</a>";
				} else {
					// ссылка на контракт токена в mainnet
					return "<a target =\'_blank\' href =\'" + baseMainnetContract + address + "\'>" + address + "</a>";
				}
			}
		} else {
			if (to_owner) {
				// ссылка на owner в testnet
				return "<a target =\'_blank\' href =\'" + baseTestnetOwner + address + "\'>" + address + "</a>";
			} else {
				if (token_id !== null) {
					// ссылка на токен id в testnet
					return "<a target =\'_blank\' href =\'" + baseTestnetContract + address + "?a=" + token_id + "\'>" + token_id + "</a>";
				} else {
					// ссылка на контракт токена в testnet
					return "<a target =\'_blank\' href =\'" + baseTestnetContract + address + "\'>" + address + "</a>";
				}
			}
		}
	
	}

	function renderReset() {
		$("#certificateImgBlock").hide();
		$("#certificateImgBlock404").hide();
		$("#certificateImg").prop("src", "");
		$("#ctfnBlock").hide();
		$("#certificateName").text("");
		$("#certificateCourse").text("");
		$("#certificateSchool").text("");
		$("#certificateTokenIdBlock").hide();
		$("#certificateTokenIdLabel").text("");
		$("#certificateTokenId").text("");
		$("#certificateTokenId").html("");
		$("#certificateContractBlock").hide();
		$("#certificateContractLabel").text("");
		$("#certificateTabLabel").text("");
		$("#certificateContract").text("");
		$("#certificateContract").html("");
		$("#certificateDescriptionBlock").hide();
		$("#certificateDescription").text("");
		$("#certificateOwnerBlock").hide();
		$("#certificateOwner").text("");
		$("#certificateOwner").html("");
		$(".network_type").text("");


		$("#certificateName").parent().show();
		$("#certificateCourse").parent().show();
		$("#certificateSchool").parent().show();
	}

	async function getOwnerOf(contractAddress, tokenId, is_mainnet) {
		
		const provider = new ethers.providers.Web3Provider(window.ethereum); //id
		const listAccounts = await provider.send("eth_requestAccounts", []);
		const signer = await provider.getSigner(listAccounts[0]);
		const signer_address = await signer.getAddress();
		
		const {chainId} = await provider.getNetwork();

		let chainLabels = [];
		chainLabels[97] = "BNB Testnet";
		chainLabels[56] = "BNB Mainnet";
		
		let BNBChainId = 97;
		if (is_mainnet) {
			BNBChainId = 56;
		}

		if (chainId != BNBChainId) {
			stateObj.knownError = true;
			throw "'.Yii::t('Frontend', 'Error! Change network to').' " + chainLabels[BNBChainId] + ". (ChainID = " + BNBChainId + ")";
		}

		const contract = await new ethers.Contract(contractAddress, schoolToken.CONTRACT_ABI, signer);
		return await contract.ownerOf(tokenId);
	}

	async function searchToken(contractAddress, tokenId, is_mainnet) {
		
		const provider = new ethers.providers.Web3Provider(window.ethereum); //id
		const listAccounts = await provider.send("eth_requestAccounts", []);
		const signer = await provider.getSigner(listAccounts[0]);
		const signer_address = await signer.getAddress();
		
		const {chainId} = await provider.getNetwork();

		let chainLabels = [];
		chainLabels[97] = "BNB Testnet";
		chainLabels[56] = "BNB Mainnet";
		
		let BNBChainId = 97;
		if (is_mainnet) {
			BNBChainId = 56;
		}

		if (chainId != BNBChainId) {
			stateObj.knownError = true;
			throw "'.Yii::t('Frontend', 'Error! Change network to').' " + chainLabels[BNBChainId] + ". (ChainID = " + BNBChainId + ")";
		}

		const contract = await new ethers.Contract(contractAddress, schoolToken.CONTRACT_ABI, signer);
		return await contract.tokenURI(tokenId);
	}

	async function getMetaData(metaURI) {
		let response = await fetch(metaURI, {
			method: "GET",
			headers: {
				"Accept": "application/json",
			},
		});
		return await response.json();
	}


	async function getMintPrice() {
		const provider = new ethers.providers.Web3Provider(window.ethereum); //id
		const listAccounts = await provider.send("eth_requestAccounts", []);
		const signer = await provider.getSigner(listAccounts[0]);
		const signer_address = await signer.getAddress();
		
		const contract = await new ethers.Contract(adminObj.CONTRACT_ADDRESS, adminObj.CONTRACT_ABI, signer);
		return await contract.safeMintPrice();
	}

	async function getCtfnProData(metaURI, paramsObj) {

		return await $.ajax({
			url: "' . Url::toRoute('/public/getmetadata') . '",
			type: "post",
			data: {
				YII_CSRF_TOKEN: "' . Yii::$app->getRequest()->getCsrfToken() . '",
				metaURI: metaURI,
				params: JSON.stringify(paramsObj),
			},
		});
	}


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
	.display-table {
		display: table;
	}
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

<section class="section py-5">
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
								'id' => 'contract-search',
								'class' => 'contract-search',
							]); ?>

								<div class="row">
											
									<div class="col-12 mb-4">
											
										<?=$form->field($model, 'search_contract', [
											'template' => '<label for="viewcontract-search_contract" class="form-label fw-bold">'.Yii::t('Frontend', 'Education organization token address in BNB chain').' <i class="fa fa-asterisk text-danger"></i></label>{input}{error}'
										])->textInput(['type'=>'text', 'placeholder'=>Yii::t('Frontend', 'Token address'), 'autocomplete' => 'off']) ?>
										
									</div><!--end col-->

									<div class="col-12 mb-4">
											
										<?=$form->field($model, 'search_token_id', [
											'template' => '<label for="viewcontract-search_token_id" class="form-label fw-bold">'.Yii::t('Frontend', 'Token Id').' <i class="fa fa-asterisk text-danger"></i></label>{input}{error}'
										])->textInput(['type'=>'text', 'placeholder'=>Yii::t('Frontend', 'Token Id'), 'autocomplete' => 'off']) ?>
										
									</div><!--end col-->

									<div class="col-12 mb-4">
											
										<?=$form->field($model, 'is_mainnet', [
											'template' => '<label for="viewcontract-is_mainnet" class="form-label fw-bold">'.Yii::t('Frontend', 'Mainnet').' <i class="fa fa-asterisk text-danger"></i></label>{input}{error}'
										])->checkBox() ?>
										
									</div><!--end col-->	
									
								</div>

								<div class="col-lg-12">
									<button type="button" id="searchToken" class="btn btn-primary">
										<span id="searchTokenSpinner" class="spinner-grow spinner-grow-sm me-2" style="display:none;" role="status" aria-hidden="true"></span>
											<?php echo(Yii::t('Frontend', 'Search')) ?>
									</button>
								</div><!--end col-->
										
					
							<?php ActiveForm::end(); ?>
						</div><!--end col-->
					</div><!--end row-->
				</div>
			</div><!--end col-->
		</div><!--end row-->
	</div><!--end container-->
</section>



<section id="certificateInfo" class="pb-5 pt-3 display-table w-100 mb-5" style="display:none">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<div id="certificateImgBlock" class="sticky-bar">
					<img id="certificateImg" src="" class="img-fluid rounded-md shadow" alt="">
				</div>
				<div id="certificateImgBlock404" class="sticky-bar">
					<div class="card text-center border">
						
						<div class="card-body">
							<h5 class="card-title"><?php echo(Yii::t('Frontend', 'Image not found'))?></h5>
							<p class="card-text"><?php echo(Yii::t('Frontend', 'Metadata key: "image" not found or incorrect'))?>.</p>
						</div>
						
					</div>
				</div>
			</div>

			<div class="col-md-6 mt-4 pt-2 mt-sm-0 pt-sm-0">
				<div class="ms-lg-4">
					<div id="ctfnBlock" class="title-heading">
						<h4 class="h3 fw-bold "><?php echo(Yii::t('Frontend', 'Course') . ': ')?><span id="certificateCourse"></span></h4>
						<h4 class="h3 fw-bold "><span id="certificateName"></span></h4>
						<h6 class="fw-bold mb-0"><?php echo(Yii::t('Frontend', 'Education organization') . ': ') ?><span id="certificateSchool"></span></h6>
					</div>

					<div class="row mt-2 pt-2">
						<div class="col-12">
							<ul class="nav nav-tabs border-bottom" id="myTab" role="tablist">
								<li class="nav-item" role="presentation">
									<button class="nav-link active" id="detail-tab" data-bs-toggle="tab" data-bs-target="#detailItem" type="button" role="tab" aria-controls="detailItemMainnet" aria-selected="true"><span id="certificateTabLabel"></span></button>
								</li>
							</ul>

							<div class="tab-content mt-4 pt-2" id="myTabContent">
								<div class="tab-pane fade show active" id="detailItem" role="tabpanel" aria-labelledby="detail-tab">
									<div class="row">
										<div id="certificateTokenIdBlock" class="col-12">
											<h6 id="certificateTokenIdLabel"></h6>
											<h4 id="certificateTokenId" class="mb-0"></h4>
										</div>
										<div id="certificateContractBlock" class="col-12 mt-4 pt-2" style="word-break:break-all;overflow-wrap:break-word;">
											<h6 id="certificateContractLabel"></h6>	
											<h5 id="certificateContract" class="mb-0"></h5>
										</div>
										<div id="certificateDescriptionBlock" class="col-12 mt-4 pt-2">
											<p id="certificateDescription" class="text-muted mb-0"></p>
										</div>
										<div id="certificateOwnerBlock" class="col-12 mt-4 pt-2" style="word-break:break-all;overflow-wrap:break-word;">
											<h6><?php echo(Yii::t('Frontend', 'Owner'))?></h6>
											<div class="creators creator-primary d-flex align-items-center">
												<div class="ms-3">
													<h6 class="mb-0"><span class="text-dark name" id="certificateOwner" style="cursor:pointer"></span></h6>
												</div>
											</div>
										</div>
									</div>
								</div>
						</div>
					</div>
				</div>
			</div><!--end col-->
		</div><!--end row-->
	</div><!--end container-->

	<!--end container-->
</section>
<section>
	<div id="certificateNotFound" class="container mb-5" style="display:none">
		<div class="row justify-content-center">
			<div class="col-12 mx-auto">
				<div  class="row justify-content-center">
					<div class="col-12">
						<div class="container my-5">
							<div class="row justify-content-center">
								<div class="col-12">
									<div class="section-title text-center">
										<h6 class="text-muted fw-normal mb-3"><?php echo(Yii::t('Frontend', 'Certificate not found'))?></h6>
										<h4 class="title mb-4"><?php echo(Yii::t('Frontend', 'Try different token id or token address'))?></h4>
									</div>
								</div><!--end col-->
							</div><!--end row-->
						</div>
					</div>
				</div>
			</div>
		</div><!--end row-->
	</div><!--end container-->
</section>


