<?php

use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap5\ActiveForm;
use common\widgets\Alert;
use yii\helpers\Url;

$this->title = Yii::t('Title', 'FinKeeper');
$this->params['breadcrumbs'][] = $this->title;

//Connect JS scripts
Yii::$app->view->render('elements/__script_auth');
echo Yii::$app->view->render('elements/__auth_modal');

?>
	
<div class="page page--main" data-page="auth" id="asModal" tabindex="-1">

	<!-- PAGE CONTENT -->
	<div class="page__content page__content--with-bottom-nav">
		<div class="page-inner">	
			<div class="cards--12">	

				<!-- Start tonconnect -->
				<div id="wrap-actives">
					<div class="row" style="margin:0 0 20px 0;">
						
						<div id="ton-wallet-connect-button" class="wallet-connect-button">
							<div id="ton-connect" style="display:none"></div>
							<span class="mdi mdi-wifi-off"></span>
							<div tabindex="0" role="button" id="ton-wallet-click-button" class="areal-click-button"></div>
							<div class="backdrop-connect-button" style="display:none">
								<span class="far fa-hourglass fa-spin"></span>
							</div>
						</div>
						
						<div id="sol-wallet-connect-button" class="wallet-connect-button">
							<span class="mdi mdi-wifi-off"></span>
							<div tabindex="0" role="button" id="sol-wallet-click-button" class="areal-click-button"></div>
							<div class="backdrop-connect-button" style="display:none">
								<span class="far fa-hourglass fa-spin"></span>
							</div>
						</div>
						
						<div id="sui-wallet-connect-button" class="wallet-connect-button">
							<span class="mdi mdi-wifi-off"></span>
							<div tabindex="0" role="button" id="sui-wallet-click-button" class="areal-click-button"></div>
							<div class="backdrop-connect-button" style="display:none">
								<span class="far fa-hourglass fa-spin"></span>
							</div>
						</div>
						
						<div id="bybit-exchange-connect-button" class="wallet-connect-button">
							<span class="mdi mdi-wifi-off"></span>
							<div tabindex="0" role="button" id="bybit-exchange-click-button" class="areal-click-button"></div>
							<div class="backdrop-connect-button" style="display:none">
								<span class="far fa-hourglass fa-spin"></span>
							</div>
						</div>
						
						<div id="okx-exchange-connect-button" class="wallet-connect-button">
							<span class="mdi mdi-wifi-off"></span>
							<div tabindex="0" role="button" id="okx-exchange-click-button" class="areal-click-button"></div>
							<div class="backdrop-connect-button" style="display:none">
								<span class="far fa-hourglass fa-spin"></span>
							</div>
						</div>
					
						<div class="clearfix"></div>
					</div>
					
					<div class="clearfix"></div>
					<div id="wrap-balance" class="">		
						<div id="title_balance"><?=Yii::t('Api', 'Connect your wallet to see list of assets')?></div>
						<div class="mta-20"></div>
						<div class="text-center">
							<div class="btn btn-primary btn-xl" id="verifyYour"><?=Yii::t('Api', 'Verify Yourself')?></div>
						</div>
						<div class="mta-50"></div>
					</div>
					<!-- End Ton-Connect -->
						
				</div>
				<!-- End tonconnect -->

			</div>
		</div>
	</div>
</div>
<div class="clearfix mta-70"></div>

<?php
// Bottom navigation
echo Yii::$app->view->render('elements/__footer_auth');
?>

<div class="card-info bottom-fixed">

	<div id="conv-notify"></div>
	
	<?=Alert::widget()?>

</div>


