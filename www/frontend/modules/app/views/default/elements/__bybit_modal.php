<?php
use yii\bootstrap5\Html;
use frontend\assets\FinkeeperJoinAsset;

FinkeeperJoinAsset::register($this);

$this->registerJs('
	jQuery(document).ready(function($) {	
		var bybit = new Bybit();
		bybit.getBybit({
			elementButton: "bybit-exchange-connect-button-as854",
			elementForm: "bybit-exchange-connect-manage-as854",
			id: log_id,
			sc: sc,
			connect: bybitConnectedStatus,
		});
	});
	
', yii\web\View::POS_END);
?>


<div class="connect-page" id="bybitWallet" tabindex="-1" style="display:block">
	<div class="wrap-page">
		<!-- Start calc -->
		<div id="wrap-bybit-form">
		
			<div class="app-navigation">
				<a href="/app" alt="<?=Yii::t('Api', 'Back link')?>"><i class="fas fa-arrow-left app-back"></i></a>
			</div>
			
			<div id="bybit-exchange-connect-manage-as854"></div>

		</div>
		<!-- End calc -->
	</div>
</div>