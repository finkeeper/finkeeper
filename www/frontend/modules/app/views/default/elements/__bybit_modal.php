<?php
use yii\bootstrap5\Html;
?>


<div class="modal fade" id="bybitModal" tabindex="-1" aria-labelledby="bybitModalLabel" data-parent="asModal">
	<div class="modal-dialog">
		<!-- Start calc -->
		<div id="wrap-bybit-form">
		
			<div class="app-navigation">
				<i class="fas fa-arrow-left app-back"></i>
			</div>
			
			<div class="bybit_auth"><?=Yii::t('Api', 'Please provide your Bybit API key')?></div>
					
			<div class="input-group input-group-bybit mt-17">
			
				<div class="input-group-text bg-transparent border-right-0" id="basic-addon1">
					<img src="/images/icons/lock.svg" alt="" title="" />
				</div>

				<?=Html::textInput('uid', '',[
					'autocomplete' => 'off', 
					'id' => 'ct-bybit-uid',
					'class' =>  'form-control form-currency-search',
					'placeholder' => Yii::t('Form', 'Bybit UID'),
					'type' => 'password',
				])?>
				
				<div class="input-group-text bg-transparent border-left-0 quest-addon">
					<a tabindex="0" role="button" id="question-addon1" class="fa fa-question-circle"></a>
				</div>
								
			</div>

			<div class="input-group input-group-bybit mt-17">	
			
				<div class="input-group-text bg-transparent border-right-0" id="basic-addon2">
					<img src="/images/icons/lock.svg" alt="" title="" />
				</div>

				<?=Html::textInput('apikey', '',[
					'autocomplete' => 'off', 
					'id' => 'ct-bybit-apikey',
					'class' =>  'form-control form-currency-search',
					'placeholder' => Yii::t('Form', 'Bybit API Key'),
					'type' => 'password',
				])?>
				
				<div class="input-group-text bg-transparent border-left-0 quest-addon">
					<a tabindex="0" role="button" id="question-addon2" class="fa fa-question-circle"></a>
				</div>
														
			</div>
			
			<div class="input-group input-group-bybit mt-17">
			
				<div class="input-group-text bg-transparent border-right-0" id="basic-addon3">
					<img src="/images/icons/lock.svg" alt="" title="" />
				</div>
					
				<?=Html::textInput('apisecret', '',[
					'autocomplete' => 'off', 
					'id' => 'ct-bybit-apisecret',
					'class' =>  'form-control form-currency-search',
					'placeholder' => Yii::t('Form', 'Bybit API Secret'),
					'type' => 'password',
				])?>
				
				<div class="input-group-text bg-transparent border-left-0 quest-addon">
					<a tabindex="0" role="button" id="question-addon3" class="fa fa-question-circle"></a>
				</div>
												
			</div>
			
			<?=Html::button(Yii::t('Form', 'Bybit API Get Data'), [
				'id' => 'ct-bybit-api-send',
				'class' =>  'btn-turquoise',
			])?>

		</div>
		<!-- End calc -->
	</div>
</div>