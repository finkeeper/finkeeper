<?php
use yii\bootstrap5\Html;
?>


<div class="modal fade" id="okxModal" tabindex="-1" aria-labelledby="okxModalLabel" data-parent="asModal">
	<div class="modal-dialog">
		<!-- Start calc -->
		<div id="wrap-okx-form">
			
			<div class="okx_auth"><?=Yii::t('Api', 'Please provide your OKX API key')?></div>
					
			<div class="input-group input-group-okx mt-17">
			
				<div class="input-group-text bg-transparent border-right-0" id="basic-addon6">
					<img src="/images/icons/lock.svg" alt="" title="" />
				</div>

				<?=Html::textInput('uid', '',[
					'autocomplete' => 'off', 
					'id' => 'ct-okx-uid',
					'class' =>  'form-control form-currency-search',
					'placeholder' => Yii::t('Form', 'OKX UID'),
					'type' => 'password',
				])?>
				
				<div class="input-group-text bg-transparent border-left-0 quest-addon">
					<a tabindex="0" role="button" id="question-addon6" class="fa fa-question-circle"></a>
				</div>
								
			</div>

			<div class="input-group input-group-okx mt-17">	
			
				<div class="input-group-text bg-transparent border-right-0" id="basic-addon7">
					<img src="/images/icons/lock.svg" alt="" title="" />
				</div>

				<?=Html::textInput('apikey', '',[
					'autocomplete' => 'off', 
					'id' => 'ct-okx-apikey',
					'class' =>  'form-control form-currency-search',
					'placeholder' => Yii::t('Form', 'OKX API Key'),
					'type' => 'password',
				])?>
				
				<div class="input-group-text bg-transparent border-left-0 quest-addon">
					<a tabindex="0" role="button" id="question-addon7" class="fa fa-question-circle"></a>
				</div>
														
			</div>
			
			<div class="input-group input-group-okx mt-17">
			
				<div class="input-group-text bg-transparent border-right-0" id="basic-addon8">
					<img src="/images/icons/lock.svg" alt="" title="" />
				</div>
					
				<?=Html::textInput('apisecret', '',[
					'autocomplete' => 'off', 
					'id' => 'ct-okx-apisecret',
					'class' =>  'form-control form-currency-search',
					'placeholder' => Yii::t('Form', 'OKX API Secret'),
					'type' => 'password',
				])?>
				
				<div class="input-group-text bg-transparent border-left-0 quest-addon">
					<a tabindex="0" role="button" id="question-addon8" class="fa fa-question-circle"></a>
				</div>
												
			</div>
			
			<div class="input-group input-group-okx mt-17">
			
				<div class="input-group-text bg-transparent border-right-0" id="basic-addon9">
					<img src="/images/icons/lock.svg" alt="" title="" />
				</div>
					
				<?=Html::textInput('password', '',[
					'autocomplete' => 'off', 
					'id' => 'ct-okx-password',
					'class' =>  'form-control form-currency-search',
					'placeholder' => Yii::t('Form', 'OKX Password'),
					'type' => 'password',
				])?>
				
				<div class="input-group-text bg-transparent border-left-0 quest-addon">
					<a tabindex="0" role="button" id="question-addon9" class="fa fa-question-circle"></a>
				</div>
												
			</div>
			
			<?=Html::button(Yii::t('Form', 'OKX API Get Data'), [
				'id' => 'ct-okx-api-send',
				'class' =>  'btn-turquoise',
			])?>

		</div>
		<!-- End calc -->
	</div>
</div>