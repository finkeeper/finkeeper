<?php
use yii\bootstrap5\Html;
use frontend\assets\FinkeeperJoinAsset;

FinkeeperJoinAsset::register($this);

$this->registerJs('
	jQuery(document).ready(function($) {	
		var okx = new OKX();
		okx.getOKX({
			elementButton: "okx-exchange-connect-button-as858",
			elementForm: "okx-exchange-connect-manage-as858",
			id: log_id,
			sc: sc,
			connect: okxConnectedStatus,
		});	
	});
	
', yii\web\View::POS_END);
?>


<div class="connect-page" id="okxWallet" tabindex="-1" style="display:block">
	<div class="wrap-page">
		<!-- Start calc -->
		<div id="wrap-okx-form">
		
			<div class="app-navigation">
				<a href="/app" alt="<?=Yii::t('Api', 'Back link')?>"><i class="fas fa-arrow-left app-back"></i></a>
			</div>
			
			<div id="okx-exchange-connect-manage-as858"></div>
		</div>
	</div>
</div>