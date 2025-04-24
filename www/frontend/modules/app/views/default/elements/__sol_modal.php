<?php
use yii\bootstrap5\Html;
use frontend\assets\FinkeeperSolAsset;

FinkeeperSolAsset::register($this);

$this->registerJs('
	jQuery(document).ready(function($) {	
		var sol = new SOL();
		sol.getSOL({
			elementButton: "sol-exchange-connect-button-as864",
			elementForm: "sol-exchange-connect-manage-as864",
			id: log_id,
			sc: sc,
			connect: solConnectedStatus,
		});	
	});
	
', yii\web\View::POS_END);
?>

<div class="connect-page" id="solWallet" tabindex="-1" style="display:block">
	<div class="wrap-page">
		<!-- Start calc -->
		<div id="wrap-sol-form">

			<div class="app-navigation">
				<a href="/app" alt="<?=Yii::t('Api', 'Back link')?>"><i class="fas fa-arrow-left app-back"></i></a>
			</div>

			<div id="sol-exchange-connect-manage-as864"></div>

		</div>
		<!-- End calc -->
	</div>
</div>