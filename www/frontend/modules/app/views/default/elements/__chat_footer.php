<?php
use yii\bootstrap5\Html;
?>

<div class="bottom-chat-form">
	<div class="input-chat">
		<div class="text_chat_input">
			<?=Html::textInput('as_chat_input', '',[
				'autocomplete' => 'off', 
				'id' => 'chat-active-input',
				'class' =>  'form-control border-left-0 form-currency-chat',
				'placeholder' => Yii::t('Api', 'Text'),
				'tabindex' => -1,
			])?>	 
		</div>
		<div class="text_chat_send">		 
			<?=Html::button('<span class="mdi mdi-chat-plus-outline"></span>', [
				'id' => 'chat-active-send',
				'class' =>  'btn btn-info',
			])?>
			
			<?=Html::button('<span class="far fa-hourglass fa-spin">', [
				'id' => 'chat-active-send-loader',
				'style' =>  'display:none',
			])?>

		</div>
		<div class="clearfix"></div>
	</div>
</div>	  