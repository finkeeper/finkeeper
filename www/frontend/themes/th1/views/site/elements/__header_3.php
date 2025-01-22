<?php

// use yii\bootstrap5\Html;
use yii\helpers\Html;

use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use common\models\Clients;

if (!Yii::$app->user->isGuest) { 
	$client = Clients::findUserId();
	if (empty($client)) {
		$client = new Clients;
	}
		
	$this->registerCss('
		.auth-hidden {
			display:none; 
		}
	');
}

$this->registerCss('
	.big-text-overflow {
		overflow-wrap: break-word;
	}
');


$this->registerJsFile('/js/ethers.umd.min.js', ['position' => yii\web\View::POS_END]);

$this->registerJs('
	jQuery(document).ready(function($) {
		
		let loading = false;
		if (window.ethereum) {
			initLoad();	
		}
			
		$("#connectWalletCtfn").off("click");
		$("#connectWalletCtfn").on("click", async function() {

			if (loading) {
				return false;
			}

			if (window.ethereum) {

				loading = true;

				let networkIsSet = await setNetwork(true);
				if (networkIsSet) {
					await redirectTo();
				}

			} else {

				showModal("warning", "Metamask is not installed", "-");
			
			}

			loading = false;

		});
	});

	async function initLoad() {
		let connected = await isConnected();
		if (connected) {
			await printWalletData();
		}
	}

	async function changeNetworkMainnet() {
		try {
			await ethereum.request({
				method: \'wallet_switchEthereumChain\',
				params: [{ chainId: \'0x38\' }],
			});
		} catch (switchError) {
			if (switchError.code === 4902) {
				await window.ethereum.request({
					method: "wallet_addEthereumChain",
					params: [{
						chainId: "0x38", // decimal 56
						rpcUrls: ["https://bsc-dataseed.binance.org"],
						chainName: "Binance Smart Chain Mainnet",
						nativeCurrency: {
							name: "BNB",
							symbol: "BNB",
							decimals: 18
						},
						blockExplorerUrls: ["https://bscscan.com"]
					}]
				});
			} else {
				throw switchError;
			}
		}
	}

	async function changeNetworkTestnet() {
		try {
			await ethereum.request({
				method: \'wallet_switchEthereumChain\',
				params: [{ chainId: \'0x61\' }],
			});
		} catch (switchError) {
			if (switchError.code === 4902) {
				await window.ethereum.request({
					method: "wallet_addEthereumChain",
					params: [{
						chainId: "0x61", // decimal 97
						rpcUrls: ["https://data-seed-prebsc-1-s1.binance.org:8545"],
						chainName: "Binance Smart Chain Testnet",
						nativeCurrency: {
							name: "tBNB",
							symbol: "tBNB",
							decimals: 18
						},
						blockExplorerUrls: ["https://testnet.bscscan.com"]
					}]
				});
			} else {
				throw switchError;
			}
		}
	}

	async function setNetwork(isMainnet = false) {

		try {

			const provider = new ethers.providers.Web3Provider(window.ethereum); //id
			const listAccounts = await provider.send("eth_requestAccounts", []);
			const signer = await provider.getSigner(listAccounts[0]);
			
			const { chainId } = await provider.getNetwork();

			if (isMainnet === "0") {
				isMainnet = false;	
			} else if (isMainnet === "1") {
				isMainnet = true;
			} else {
				isMainnet = !!isMainnet;
			}
			
			if (isMainnet) {
				if (chainId != 56) {
					await changeNetworkMainnet();
					await printWalletData();
				}
			} else {
				if (chainId != 97) {
					await changeNetworkTestnet();
					await printWalletData();
				}
			}
			
			return true;

		} catch (error) {
			if (typeof(error) === "object") {
				error = JSON.stringify(error);
			}
			showModal("danger", "Error", error);
			console.log(error);
			return false;
		}
		
	}

	async function redirectTo() {

		const provider = new ethers.providers.Web3Provider(window.ethereum); //id
		const listAccounts = await provider.send("eth_requestAccounts", []);
		const signer = await provider.getSigner(listAccounts[0]);
		const signerAddress = await signer.getAddress();

		var url = "/public/address#searchblock";
		var form = \'<form action="\' + url + \'" method="post">\';
			form += \'<input type="text" name="SearchCertificate[user_nft_address]" value="\';
			form += signerAddress + \'" />\'
			
			form += \'' . Html::hiddenInput(\Yii::$app->getRequest()->csrfParam, \Yii::$app->getRequest()->getCsrfToken(), []) . '\';
			form += \'</form>\';
			
		form = $(form);
		$(\'body\').append(form);
		form.submit();
		// window.location.replace("/public/address");
	}


	async function isConnected() {
		
		const accounts = await ethereum.request({method: "eth_accounts"});       
		if (accounts.length) {
			return true;
		}

		return false;
	}

	async function connectedWallet() {
		
		const accounts = await ethereum.request({method: "eth_accounts"});       
		if (accounts.length) {
			return true;
		}

		return false;
	}

	async function printWalletData() {
		const provider = new ethers.providers.Web3Provider(window.ethereum); //id
		const listAccounts = await provider.send("eth_requestAccounts", []);
		const signer = await provider.getSigner(listAccounts[0]);
		const signerAddress = await signer.getAddress();

		const { chainId } = await provider.getNetwork();

		if (chainId == 97 || chainId == 56) {
			const balance = await provider.getBalance(signerAddress);
			const balanceInEth = ethers.utils.formatEther(balance);
			let symbol = "TBNB";
			if (chainId == 56) {
				symbol = "BNB";
			}
			$("#publicAddressBalanceCtfn").text(balanceInEth + " " + symbol);
		}
		
		$("#publicAddressCtfn").prop("title", signerAddress);
		$("#publicAddressCtfn").text(signerAddress.substring(0, 7) + "..." + signerAddress.substring(signerAddress.length-7, signerAddress.length));
		$("#profileBlock").show();
	}

	function showModal(className, title, body) {

		let modalElement = $("#cf-modal-load");
		let modalTitleElement = $(modalElement).find(".modal-title");
		let modalContentElement = $(modalElement).find(".modal-content");
		let modalBodyElement = $(modalElement).find(".modal-body"); 

		$(modalTitleElement).text("");
		$(modalTitleElement).removeClass();
		$(modalTitleElement).addClass("modal-title big-text-overflow");

		$(modalBodyElement).text("");
		$(modalBodyElement).removeClass();
		$(modalBodyElement).addClass("modal-body big-text-overflow");
		
		$(modalContentElement).removeClass();
		$(modalContentElement).addClass("modal-content");
		
		let alertClassName = "alert alert-light";
		
		if (className == "primary") {
			alertClassName = "alert alert-primary";
			$(modalTitleElement).addClass("text-white");
			$(modalBodyElement).addClass("text-white");
		} else if (className == "secondary") {
			alertClassName = "alert alert-secondary";
		} else if (className == "success") {
			alertClassName = "alert alert-success";
		} else if (className == "danger") {
			alertClassName = "alert alert-danger";
		} else if (className == "warning") {
			alertClassName = "alert alert-warning";
		} else if (className == "info") {
			alertClassName = "alert alert-info";
		} else if (className == "light") {
			alertClassName = "alert alert-light";
		} else if (className == "dark") {
			alertClassName = "alert alert-dark";
		}
		
		$(modalContentElement).addClass(alertClassName);

		$(modalTitleElement).text(title);
		$(modalBodyElement).text(body);

		$("#cf-modal-load").toggle();
	}

	function resetModal() {

		let modalElement = $("#cf-modal-load");
		let modalTitleElement = $(modalElement).find(".modal-title");
		let modalContentElement = $(modalElement).find(".modal-content");
		let modalBodyElement = $(modalElement).find(".modal-body"); 

		$(modalTitleElement).text("");
		$(modalTitleElement).removeClass();
		$(modalTitleElement).addClass("modal-title");

		$(modalBodyElement).text("");
		$(modalBodyElement).removeClass();
		$(modalBodyElement).addClass("modal-body");
		
		$(modalContentElement).removeClass();
		$(modalContentElement).addClass("modal-content");

	}

	$("#cf-modal-load").on("click", "[data-dismiss=\"modal\"]", function() {
		$("#cf-modal-load").toggle();
		resetModal();
	});

	
', yii\web\View::POS_END);


?>

<div id="cf-modal-load" class="modal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="btn close" data-dismiss="modal" aria-label="Close">
					<span class="h3" aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer visually-hidden">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><?=Yii::t('Frontend', 'Close')?></button>
				<button type="button" class="btn btn-primary"><?=Yii::t('Frontend', 'Save')?></button>
			</div>
		</div>
	</div>
</div>

<?php /*
<!-- Loader -->
<div id="preloader">
	<div id="status">
		<div class="spinner">
			<div class="double-bounce1"></div>
			<div class="double-bounce2"></div>
		</div>
	</div>
</div>
<!-- Loader -->
*/?>

<!-- Navbar STart -->
<header id="topnav" class="defaultscroll sticky">
	<div class="container">
		<!-- Logo Start-->
		<a class="logo" href="/">
			<span class="logo-light-mode">
				<img src="/images/logo/logo-dark.png" height="26" class="l-dark" alt="">
				<img src="/images/logo/logo-white.png" height="26" class="l-light" alt="">
			</span>
			<img src="/images/logo/logo-light.png" height="26" class="logo-dark-mode" alt="">
		</a>
		<!-- Logo end-->

		<!-- Mobile -->
		<div class="menu-extras">
			<div class="menu-item">
				<!-- Mobile menu toggle-->
				<a class="navbar-toggle" id="isToggle" onclick="toggleMenu()">
					<div class="lines">
						<span></span>
						<span></span>
						<span></span>
					</div>
				</a>
				<!-- End mobile menu toggle-->
			</div>
		</div>
		<!-- Mobile -->

		 <?php if (!Yii::$app->user->isGuest) { ?>

			<!--Login button Start-->
			<ul class="buy-button list-inline mb-0">
				<li id="connectWalletCtfnBlock" class="list-inline-item mb-0 me-1">
					<a id="connectWalletCtfn">
						<span class="btn-icon-dark"><span class="btn btn-icon btn-pills btn-primary"><i class="uil uil-wallet fs-6"></i></span></span>
						<span class="btn-icon-light"><span class="btn btn-icon btn-pills btn-light"><i class="uil uil-wallet fs-6"></i></span></span>
					</a>
				</li>
				
				<li class="list-inline-item mb-0"> 
					<div class="dropdown dropdown-primary">
						<button type="button" class="btn btn-pills dropdown-toggle p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							
							<?php if (empty($client->image)) { ?>
					
								<img src="/upload/default.jpg" class="rounded-pill avatar avatar-sm-sm" alt="">
								
							<?php } else { ?>
								
								<img src="/upload/client/<?=md5($client->id)?>/<?=$client->image?>" class="rounded-pill avatar avatar-sm-sm" alt="">
								
							<?php } ?>
		
						</button>
						<div class="dropdown-menu dd-menu dropdown-menu-end bg-white shadow border-0 mt-3 pb-3 pt-0 overflow-hidden rounded" style="min-width: 200px;">
							<div class="position-relative">
								<div class="pt-5 pb-3 bg-gradient-primary"></div>
								<div class="px-3">
									<div class="d-flex align-items-end mt-n4">
										
										<?php if (empty($client->image)) { ?>
					
											<img src="/upload/default.jpg" class="rounded-pill avatar avatar-md-sm img-thumbnail shadow-md" alt="">
											
										<?php } else { ?>
											
											<img src="/upload/client/<?=md5($client->id)?>/<?=$client->image?>" class="rounded-pill avatar avatar-md-sm img-thumbnail shadow-md" alt="">
											
										<?php } ?>
				
										<h6 class="text-dark fw-bold mb-0 ms-1"><?=$client->name?> <?=$client->surname?></h6>
									</div>
									<div class="mt-2">
										<small class="text-start text-dark d-block fw-bold"><?=Yii::t('Frontend', 'Wallet')?>:</small>
										<div class="d-flex justify-content-between align-items-center">
											<small id="publicAddressCtfn" class="text-muted"></small>
											<!-- <a href="javascript:void(0);" class="text-primary"><span class="uil uil-copy"></span></a> -->
										</div>
									</div>
									
									<div class="mt-2">
										<small class="text-dark"><?=Yii::t('Frontend', 'Balance')?>: <span id="publicAddressBalanceCtfn" class="text-primary fw-bold"></span></small>
									</div>
								</div>
							</div>
							<div class="mt-2">
								<a class="dropdown-item small fw-semibold text-dark d-flex align-items-center" href="/profile/view"><span class="mb-0 d-inline-block me-1"><i class="uil uil-user align-middle h6 mb-0 me-1"></i></span> <?=Yii::t('Profile', 'View profile')?></a>
								<div class="dropdown-divider border-top"></div>
								<a class="dropdown-item small fw-semibold text-dark d-flex align-items-center" href="/logout"><span class="mb-0 d-inline-block me-1"><i class="uil uil-sign-out-alt align-middle h6 mb-0 me-1"></i></span> <?=Yii::t('Menu', 'Logout')?></a>
							</div>
						</div>
					</div>
				</li>
				
			</ul>
			<!--Login button End-->
			<div id="navigation">
				<!-- Navigation Menu-->   
				<ul class="navigation-menu nav-left nav-light">
				
					<li class="has-submenu parent-parent-menu-item">
						<a href="javascript:void(0)"><?=Yii::t('Menu', 'Profile')?></a><span class="menu-arrow"></span>
						<ul class="submenu">
							<li><a href="/profile/view" class="sub-menu-item"><?=Yii::t('Profile', 'View profile')?></a></li>
							<li><a href="/profile/update" class="sub-menu-item"><?=Yii::t('Menu', 'Edit profile')?></a></li>
						</ul>
					</li>
					<li class="has-submenu parent-parent-menu-item">
						<a href="javascript:void(0)"><?=Yii::t('Menu', 'Search')?></a><span class="menu-arrow"></span>
						<ul class="submenu">
							<li><a href="/public/address" class="sub-menu-item"><?=Yii::t('Menu', 'Search by student address')?></a></li>
							<li><a href="/public/contract" class="sub-menu-item"><?=Yii::t('Menu', 'Verify NFT-certificate')?></a></li>
						</ul>
					</li>
					<li><a href="/logout" class="sub-menu-item"><?=Yii::t('Menu', 'Logout')?></a></li>
				</ul><!--end navigation menu-->
			</div><!--end navigation--> 
		 
		 <?php } else { ?>
			<ul class="buy-button list-inline mb-0">
				<li id="connectWalletCtfnBlock" class="list-inline-item mb-0 me-1">
					<a id="connectWalletCtfn">
						<span class="btn-icon-dark"><span class="btn btn-icon btn-pills btn-primary"><i class="uil uil-wallet fs-6"></i></span></span>
						<span class="btn-icon-light"><span class="btn btn-icon btn-pills btn-light"><i class="uil uil-wallet fs-6"></i></span></span>
					</a>
				</li>
				<li id="profileBlock" class="list-inline-item mb-0" style="display:none;"> 
					<div class="dropdown dropdown-primary">
						<button type="button" class="btn btn-pills dropdown-toggle p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<img src="/upload/default.jpg" class="rounded-pill avatar avatar-sm-sm" alt="">
						</button>
						<div class="dropdown-menu dd-menu dropdown-menu-end bg-white shadow border-0 mt-3 pb-3 pt-0 overflow-hidden rounded" style="min-width: 200px;">
							<div class="position-relative">
								<div class="pt-5 pb-3 bg-gradient-primary"></div>
								<div class="px-3">
									<div class="d-flex align-items-end mt-n4">
										<img src="/upload/default.jpg" class="rounded-pill avatar avatar-md-sm img-thumbnail shadow-md" alt="">
									</div>
									<div class="mt-2">
										<small class="text-start text-dark d-block fw-bold"><?=Yii::t('Frontend', 'Wallet')?>:</small>
										<div class="d-flex justify-content-between align-items-center">
											<small id="publicAddressCtfn" class="text-muted"></small>
										</div>
									</div>
									
									<div class="mt-2">
										<small class="text-dark"><?=Yii::t('Frontend', 'Balance')?>: <span id="publicAddressBalanceCtfn" class="text-primary fw-bold"></span></small>
									</div>
								</div>
							</div>
							<!-- <div class="mt-2">
								<a class="dropdown-item small fw-semibold text-dark d-flex align-items-center" href="/profile/view"><span class="mb-0 d-inline-block me-1"><i class="uil uil-user align-middle h6 mb-0 me-1"></i></span> <?=Yii::t('Menu', 'Profile view')?></a>
								<div class="dropdown-divider border-top"></div>
								<a class="dropdown-item small fw-semibold text-dark d-flex align-items-center" href="/logout"><span class="mb-0 d-inline-block me-1"><i class="uil uil-sign-out-alt align-middle h6 mb-0 me-1"></i></span> <?=Yii::t('Menu', 'Logout')?></a>
							</div> -->
						</div>
					</div>
				</li>
			</ul>
			<div id="navigation">
				<!-- Navigation Menu-->   
				<ul class="navigation-menu nav-left nav-light">
					<li class="has-submenu parent-parent-menu-item">
						<a href="javascript:void(0)"><?=Yii::t('Menu', 'Search')?></a><span class="menu-arrow"></span>
						<ul class="submenu">
							<li><a href="/public/address" class="sub-menu-item"><?=Yii::t('Menu', 'Search by student address')?></a></li>
							<li><a href="/public/contract" class="sub-menu-item"><?=Yii::t('Menu', 'Verify NFT-certificate')?></a></li>
						</ul>
					</li>
					<li><a href="/login" class="sub-menu-item"><?=Yii::t('Menu', 'Login')?></a></li>
					<li><a href="/signup" class="sub-menu-item"><?=Yii::t('Menu', 'Signup')?></a></li>
				</ul><!--end navigation menu-->
			</div><!--end navigation-->
		 <?php } ?>
	</div><!--end container-->
</header><!--end header-->
<!-- Navbar End -->