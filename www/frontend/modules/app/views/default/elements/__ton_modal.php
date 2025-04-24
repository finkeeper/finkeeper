<?php
use yii\bootstrap5\Html;
use frontend\assets\FinkeeperTonAsset;

FinkeeperTonAsset::register($this);

$this->registerJs('
	jQuery(document).ready(function($) {	
		var ton = new TON();
		ton.getTON({
			elementButton: "ton-exchange-connect-button-as218",
			elementForm: "ton-exchange-connect-manage-as219",
			id: log_id,
			sc: sc,
			connect: tonConnectedStatus,
		});
	});
	
', yii\web\View::POS_END);
?>


<div class="connect-page" id="tonWallet" tabindex="-1" style="display:block">
	<div class="wrap-page">
		<!-- Start calc -->
		<div id="wrap-ton-form">
		
			<div class="app-navigation">
				<a href="/app" alt="<?=Yii::t('Api', 'Back link')?>"><i class="fas fa-arrow-left app-back"></i></a>
			</div>
			
			<div id="ton-exchange-connect-manage-as219"></div>
		</div>
	</div>
</div>