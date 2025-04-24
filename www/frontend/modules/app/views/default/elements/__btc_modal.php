<?php
use yii\bootstrap5\Html;
use frontend\assets\FinkeeperBtcAsset;

FinkeeperBtcAsset::register($this);

$this->registerJs('
	jQuery(document).ready(function($) {	
		var btc = new BTC();
		btc.getBTC({
			elementButton: "btc-wallet-connect-button-as1958",
			elementForm: "btc-wallet-connect-manage-as1959",
			id: log_id,
			sc: sc,
			connect: btcConnectedStatus,
		});
	});
	
', yii\web\View::POS_END);
?>


<div class="connect-page" id="btcWallet" tabindex="-1" style="display:block">
	<div class="wrap-page">
		<!-- Start calc -->
		<div id="wrap-btc-form">
		
			<div class="app-navigation">
				<a href="/app" alt="<?=Yii::t('Api', 'Back link')?>"><i class="fas fa-arrow-left app-back"></i></a>
			</div>
			
			<div id="btc-wallet-connect-manage-as1959"></div>

		</div>
		<!-- End calc -->
	</div>
</div>