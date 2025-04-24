<?php
use yii\bootstrap5\Html;
use frontend\assets\FinkeeperAptAsset;

FinkeeperAptAsset::register($this);

$this->registerJs('
	jQuery(document).ready(function($) {	
		var apt = new APT();
		apt.getAPT({
			elementButton: "apt-exchange-connect-button-as316",
			elementForm: "apt-exchange-connect-manage-as316",
			id: log_id,
			sc: sc,
			connect: aptConnectedStatus,
		});
	});
	
', yii\web\View::POS_END);
?>


<div class="connect-page" id="aptWallet" tabindex="-1" style="display:block">
	<div class="wrap-page">
	
		<!-- Start calc -->
		<div id="wrap-apt-form">
		
			<div class="app-navigation">
				<a href="/app" alt="<?=Yii::t('Api', 'Back link')?>"><i class="fas fa-arrow-left app-back"></i></a>
			</div>

			<div id="apt-exchange-connect-manage-as316"></div>

		</div>
		<!-- End calc -->
	</div>
</div>