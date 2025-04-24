<?php
use yii\bootstrap5\Html;
use frontend\assets\FinkeeperEthAsset;

FinkeeperEthAsset::register($this);

$this->registerJs('
	jQuery(document).ready(function($) {	
		var eth = new ETH();
		eth.getETH({
			elementButton: "eth-exchange-connect-button-as628",
			elementForm: "eth-exchange-connect-manage-as628",
			id: log_id,
			sc: sc,
			connect: ethConnectedStatus,
		});
	});
	
', yii\web\View::POS_END);
?>

<div class="connect-page" id="ethWallet" tabindex="-1" style="display:block">
	<div class="wrap-page">
		<!-- Start calc -->
		<div id="wrap-eth-form">
		
			<div class="app-navigation">
				<a href="/app" alt="<?=Yii::t('Api', 'Back link')?>"><i class="fas fa-arrow-left app-back"></i></a>
			</div>

			<div id="eth-exchange-connect-manage-as628"></div>

		</div>
		<!-- End calc -->
	</div>
</div>
