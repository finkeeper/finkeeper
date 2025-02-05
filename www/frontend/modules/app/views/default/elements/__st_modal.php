<?php
use yii\bootstrap5\Html;
?>

<div class="modal fade" id="stModal" tabindex="-1" aria-labelledby="stModalLabel" data-parent="">
	<div class="modal-dialog">
		<!-- Start calc -->
		<div id="wrap-calc">
		
			<div class="app-navigation">
				<i class="fas fa-arrow-left app-back"></i>
			</div>
		
			<div class="page-inner">	
				<div class="cards--11">
					
					<div class="card-info">

						<div class="top-breadcrumbs">
					
							<?=Yii::t('Api', 'APR calc')?>
							
						</div>
				
					</div>
				
					<div class="card-coin-def mt-20">
									
						<div id="calc1" class="card-currency">

							<div class="text_input">

								<label for="ct-calc-apr">
									<?=Yii::t('Form', 'Calc APR')?> %
								</label>
								<?=Html::textInput('apr', '',[
									'autocomplete' => 'off', 
									'id' => 'ct-calc-apr',
									'class' =>  'form-calc',
									'type' => 'text',
									'inputmode' => 'numeric',
								])?>

								<div class="clearfix"></div>
						
							</div>
						
						</div>

						<div id="calc2" class="card-currency mt-20">

							<div class="text_input">

								<label for="ct-calc-deposit">
									<?=Yii::t('Form', 'Calc Deposit')?> $
								</label>
								<?=Html::textInput('deposit', '',[
									'autocomplete' => 'off', 
									'id' => 'ct-calc-deposit',
									'class' =>  'form-calc',
									'type' => 'text',
									'inputmode' => 'numeric',
								])?>

								<div class="clearfix"></div>
						
							</div>
						
						</div>
			
						<div id="calc3" class="card-currency mt-20">

							<div class="text_input">

								<label for="ct-calc-days">
									<?=Yii::t('Form', 'Calc Days')?>
								</label>
								<?=Html::textInput('days', '',[
									'autocomplete' => 'off', 
									'id' => 'ct-calc-days',
									'class' =>  'form-calc',
									'type' => 'text',
									'inputmode' => 'numeric',
								])?>

								<div class="clearfix"></div>
						
							</div>
						
						</div>

					</div>
								
					<div class="card-coin-def mt-20">
					
						<div id="calc4" class="card-currency mt-20">

							<div class="text_input">

								<label for="ct-calc-daily_yield">
									<?=Yii::t('Form', 'Calc Daily Yield')?> $
								</label>
								<?=Html::textInput('daily_yield', '',[
									'autocomplete' => 'off', 
									'id' => 'ct-calc-daily_yield',
									'class' =>  'form-calc',
									'readonly' => true,
								])?>

								<div class="clearfix"></div>
						
							</div>
						
						</div>
					
						<div id="calc5" class="card-currency mt-20">

							<div class="text_input">

								<label for="ct-calc-sum_yield">
									<?=Yii::t('Form', 'Calc Sum Yield')?> $
								</label>
								<?=Html::textInput('sum_yield', '',[
									'autocomplete' => 'off', 
									'id' => 'ct-calc-sum_yield',
									'class' =>  'form-calc',
									'readonly' => true,
								])?>

								<div class="clearfix"></div>
						
							</div>
						
						</div>
					
					</div>
				
				</div>
			</div>
	
		</div>
		<!-- End calc -->
	</div>
</div>