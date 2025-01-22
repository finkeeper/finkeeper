<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use common\widgets\Alert;
use yii\captcha\Captcha;

$this->title = Yii::t('Title', 'Stakingcalc');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('https://unpkg.com/@tonconnect/ui@latest/dist/tonconnect-ui.min.js', ['position' => \yii\web\View::POS_HEAD]);

$this->registerJs('

	function calcform() {
		
		var apr = parseInt($("#ct-calc-apr").val(), 10);
		if (typeof apr==="undefined" || apr===undefined || isNaN(apr) || !apr) {
			apr = 0;
		}
		
		var deposit = parseInt($("#ct-calc-deposit").val(), 10);
		if (typeof deposit==="undefined" || deposit===undefined || isNaN(deposit) || !deposit) {
			deposit = 0;
		}
		
		var days = parseInt($("#ct-calc-days").val(), 10);
		if (typeof days==="undefined" || days===undefined || isNaN(days) || !days) {
			days = 0;
		}
		
		var daily_yield = apr/365*deposit/100;
		if (typeof daily_yield==="undefined" || daily_yield===undefined || isNaN(daily_yield) || !daily_yield) {
			daily_yield = 0;
		}

		$("#ct-calc-daily_yield").val(daily_yield.toFixed(2));
		
		var sum_yield = daily_yield*days;
		if (typeof sum_yield==="undefined" || sum_yield===undefined || isNaN(sum_yield) || !sum_yield) {
			sum_yield = 0;
		}

		$("#ct-calc-sum_yield").val(sum_yield.toFixed(2));
	}

	jQuery(document).ready(function($) {
		
		calcform();

		$("#ct-calc-apr").on("keyup", function() {
			calcform();
		});

		$("#ct-calc-deposit").on("keyup", function() {
			calcform();
		});
		
		$("#ct-calc-days").on("keyup", function() {
			calcform();
		});
		
		const tonConnectUI = new TON_CONNECT_UI.TonConnectUI({
			manifestUrl: "https://bank.ctfn.pro/tonconnect-manifest.json",
			buttonRootId: "ton-connect"
		});
		
		tonConnectUI.connectionRestored.then(restored => {
			if (restored) {
				
				jQuery.ajax({
					"url": "/getaddress",
					"type": "post",
					"dataType": "json",
					"contentType": "application/json",
					"data": JSON.stringify({"address": tonConnectUI.wallet.account.address}),
					"success": function(response){
						if (response) {

							if (!response.error) {
							
								response.data.forEach((val) => {
				
									jQuery("#balance").append("<div>Name: " + val.name + " Balance: " + val.balance + "</div>");		
									
								});
								
							} else {		
								console.error(response.message)	
								return false;
							}
							
						} else {
							console.error("Server not response");
							return false;
						}
					},
					error: function(e) {
						console.error(e);
					}
				});

			} else {
				console.error("Connection was not restored.");
			}
		});
	});
', yii\web\View::POS_END);

$this->registerCss('
	.passwd-input {
		width: calc(100% - 30px);
		float:left;
	}
	.passwd-button {
		width:30px;
		height:51px;
		padding:0;
		float:right;
	}
	.passwd-show-button	{
		padding:0;
		margin:0;
	}
	.passwd-show-button .fa	{
		font-size:14px;
	}
	.passwd-generate-button,
	.passwd-show-button button {
		height:22px;
		width:22px;
	}
	.wrap-ton-connect {
		position:absolute;
		top:30px;
		left:120px;
		z-index:9999;
	}
');
?>

<div class="wrap-ton-connect">
	<div id="ton-connect"></div>
	<div id="balance"></div>
</div>
<!-- Hero Start -->
<section class="content position-relative">
	<div class="site-login">
		<div class="bg-overlay bg-linear-gradient-2"></div>
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 p-0">
					<div class="d-flex flex-column min-vh-100 p-4">
						<!-- Start Logo -->
						<div class="text-center">
							<div class="logo"><img src="/images/logo/logo-dark.png" alt=""></div>
						</div>
						<!-- End Logo -->

						<!-- Start Content -->
						<div class="title-heading text-center my-auto">
							<div class="form-signin px-4 py-5 bg-white rounded-md shadow-sm">

								<h5 class="mb-4"><?=Yii::t('Title', 'Stakingcalc')?></h5>
								
								<?=Alert::widget()?>
			
								<div class="row">
									<div class="col-lg-12">
										<div class="form-floating mb-2">
											
											<?=Html::textInput('apr', '',[
												'autocomplete' => 'off', 
												'id' => 'ct-calc-apr',
												'class' =>  'form-control',
												'placeholder' => Yii::t('Form', 'Calc APR'),
												'type' => 'number',
											])?>
											
											<label for="loginform-login">
												<?=Yii::t('Form', 'Calc APR')?> %:
											</label>
											<div class="has-feedback">
												<span class="glyphicon form-control-feedback"></span>
											</div>
											
										</div>
									</div><!--end col-->

									<div class="col-lg-12">
										<div class="form-floating mb-2">
											
											<?=Html::textInput('deposit', '',[
												'autocomplete' => 'off', 
												'id' => 'ct-calc-deposit',
												'class' =>  'form-control',
												'placeholder' => Yii::t('Form', 'Calc Deposit'),
												'type' => 'number',
											])?>
											
											<label for="loginform-login">
												<?=Yii::t('Form', 'Calc Deposit')?> $:
											</label>
											<div class="has-feedback">
												<span class="glyphicon form-control-feedback"></span>
											</div>
											
										</div>
									</div><!--end col-->
							
									<div class="col-lg-12">
										<div class="form-floating mb-2">
											
											<?=Html::textInput('days', '',[
												'autocomplete' => 'off', 
												'id' => 'ct-calc-days',
												'class' =>  'form-control',
												'placeholder' => Yii::t('Form', 'Calc Days'),
												'type' => 'number',
											])?>
											
											<label for="loginform-login">
												<?=Yii::t('Form', 'Calc Days')?>:
											</label>
											<div class="has-feedback">
												<span class="glyphicon form-control-feedback"></span>
											</div>
											
										</div>
									</div><!--end col-->
									
									<hr>
									
									<div class="col-lg-12">
										<div class="form-floating mb-2">
											
											<?=Html::textInput('daily_yield', '',[
												'autocomplete' => 'off', 
												'id' => 'ct-calc-daily_yield',
												'class' =>  'form-control',
												'placeholder' => Yii::t('Form', 'Calc Daily Yield'),
												'readonly' => true,
											])?>
											
											<label for="loginform-login">
												<?=Yii::t('Form', 'Calc Daily Yield')?> $:
											</label>
											<div class="has-feedback">
												<span class="glyphicon form-control-feedback"></span>
											</div>
											
										</div>
									</div><!--end col-->
				
									<div class="col-lg-12">
										<div class="form-floating mb-2">
											
											<?=Html::textInput('sum_yield', '',[
												'autocomplete' => 'off', 
												'id' => 'ct-calc-sum_yield',
												'class' =>  'form-control',
												'placeholder' => Yii::t('Form', 'Calc Sum Yield'),
												'readonly' => true,
											])?>
											
											<label for="loginform-login">
												<?=Yii::t('Form', 'Calc Sum Yield')?> $:
											</label>
											<div class="has-feedback">
												<span class="glyphicon form-control-feedback"></span>
											</div>
											
										</div>
									</div><!--end col-->

								</div><!--end row-->

							</div>
						</div>
						<!-- End Content -->
						<!-- Start Footer -->
						<?= $this->render(
							'@app/themes/th1/views/site/elements/__footer_2.php'
						) ?>
						<!-- End Footer -->
					</div>
				</div><!--end col-->
			</div><!--end row-->
		</div><!--end container-->
	</div>
</section><!--end section-->
<!-- Hero End -->


