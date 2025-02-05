<?php
use yii\bootstrap5\Html;
?>


<div class="modal fade" id="solModal" tabindex="-1" aria-labelledby="solModalLabel" data-parent="asModal">
	<div class="modal-dialog">
		<!-- Start calc -->
		<div id="wrap-sol-form">
		
			<div class="app-navigation">
				<i class="fas fa-arrow-left app-back"></i>
			</div>
			
			<div class="sol_auth"><?=Yii::t('Api', 'Or connect SOL wallet via WalletConnect')?></div>

			<div class="input-group-sol mt-17">
	
				<?=Html::button(Yii::t('Api', 'Connect Wallet'), [
					'id' => 'wallet-connect',
					'class' =>  'btn-turquoise btn-wc',
				])?>
				
			</div>
			
			<div class="sol_auth"><?=Yii::t('Api', 'Please provide your SOL address wallet')?></div>
					
			<div class="input-group input-group-sol mt-17">
			
				<div class="input-group-text bg-transparent border-right-0" id="basic-addon10">
					<img src="/images/icons/lock.svg" alt="" title="" />
				</div>

				<?=Html::textInput('address', '',[
					'autocomplete' => 'off', 
					'id' => 'ct-sol-address',
					'class' =>  'form-control form-currency-search',
					'placeholder' => Yii::t('Form', 'SOL Address Wallet'),
					'type' => 'password',
				])?>
				
				<div class="input-group-text bg-transparent border-left-0 quest-addon">
					<a tabindex="0" role="button" id="question-addon10" class="fa fa-question-circle"></a>
				</div>
								
			</div>
			
			<?=Html::button(Yii::t('Form', 'SOL Address Wallet Sent'), [
				'id' => 'ct-sol-api-send',
				'class' =>  'btn-turquoise',
			])?>

		</div>
		<!-- End calc -->
	</div>
</div>