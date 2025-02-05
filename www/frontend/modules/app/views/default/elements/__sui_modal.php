<?php
use yii\bootstrap5\Html;
?>


<div class="modal fade" id="suiModal" tabindex="-1" aria-labelledby="suiModalLabel" data-parent="asModal">
	<div class="modal-dialog">
		<!-- Start calc -->
		<div id="wrap-sui-form">
		
			<div class="app-navigation">
				<i class="fas fa-arrow-left app-back"></i>
			</div>
			
			<div class="sui_auth"><?=Yii::t('Api', 'Please provide your SUI address wallet')?></div>
					
			<div class="input-group input-group-sol mt-17">
			
				<div class="input-group-text bg-transparent border-right-0" id="basic-addon11">
					<img src="/images/icons/lock.svg" alt="" title="" />
				</div>

				<?=Html::textInput('address', '',[
					'autocomplete' => 'off', 
					'id' => 'ct-sui-address',
					'class' =>  'form-control form-currency-search',
					'placeholder' => Yii::t('Form', 'SUI Address Wallet'),
					'type' => 'password',
				])?>
				
				<div class="input-group-text bg-transparent border-left-0 quest-addon">
					<a tabindex="0" role="button" id="question-addon11" class="fa fa-question-circle"></a>
				</div>
								
			</div>
			
			<?=Html::button(Yii::t('Form', 'SUI Address Wallet Sent'), [
				'id' => 'ct-sui-api-send',
				'class' =>  'btn-turquoise',
			])?>

		</div>
		<!-- End calc -->
	</div>
</div>