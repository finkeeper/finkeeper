<?php
use yii\bootstrap5\Html;
use frontend\assets\FinkeeperSuiAsset;

FinkeeperSuiAsset::register($this);

$this->registerJs('
	jQuery(document).ready(function($) {	
		var sui = new SUI();
		sui.getSUI({
			elementButton: "sui-exchange-connect-button-as164",
			elementForm: "sui-exchange-connect-manage-as164",
			id: log_id,
			sc: sc,
			connect: suiConnectedStatus,
		});	
	});
	
', yii\web\View::POS_END);
?>

<div class="connect-page" id="suiWallet" tabindex="-1" style="display:block">
	<div class="wrap-page">
		<!-- Start calc -->
		<div id="wrap-sui-form">
		
			<div class="app-navigation">
				<a href="/app" alt="<?=Yii::t('Api', 'Back link')?>"><i class="fas fa-arrow-left app-back"></i></a>
			</div>
			
			<div id="sui-exchange-connect-manage-as164"></div>

		</div>
		<!-- End calc -->
	</div>
</div>