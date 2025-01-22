<?php
use yii\bootstrap5\Html;
?>

<div class="modal fade" id="lgModal" tabindex="-1" aria-labelledby="lgModalLabel" data-parent="">
	<div class="modal-dialog">
		<!-- Start settings -->
		<div id="wrap-settings">
			<div id="set-language">
					
				<div class="settings-info">
				
					<div class="top-settings-icon">
						<img src="/images/icons/globe.svg" alt="globe"/>
					</div>
						
					<div class="top-settings-text">
						<?=Yii::t('Api', 'Language')?>
					</div>
						
					<div class="clearfix"></div>
				
				</div>		
								
				<div data-lang="ru" class="option_item">
						
					<?=Html::img('/images/svg/flags/ru.svg', [
						'alt' => 'images',
						'style' => 'width:20px',
						'id' => 'img-ru',
					])?>
					
					<div class="currency_name_block ml-10">
						<div class="currency_name"><?=Yii::t('Api', 'Russian')?></div>
						<div class="currency_symbol">ru-RU</div>
					</div>
					
					<div class="currency_price_block ml-10">
						<div class="lang_radio">
							<?=Html::radio('select_lg', ($lang=='ru') ? true : false,[
								'id' => 'select-ru',
								'class' =>  'lg-radio',
								'value' => 'ru',
							])?>
						</div>
					</div>
					
					<div class="clearfix"></div>
				</div>				
						
				<div data-lang="en" class="option_item">
				
					<?=Html::img('/images/svg/flags/gb.svg', [
						'alt' => 'images',
						'style' => 'width:20px',
						'id' => 'img-ru',
					])?>
					
					<div class="currency_name_block ml-10">
						<div class="currency_name"><?=Yii::t('Api', 'English')?></div>
						<div class="currency_symbol">en-EN</div>
					</div>
	
					<div class="currency_price_block ml-10">
						<div class="lang_radio">
							<?=Html::radio('select_lg', ($lang=='en') ? true : false,[
								'id' => 'select-en',
								'class' =>  'lg-radio',
								'value' => 'en',
							])?>
						</div>
					</div>
					
					<div class="clearfix"></div>
				</div>
				
				<div class="settings-info mt-20">
			
					<div class="top-settings-icon">
						<img src="/images/icons/assets.svg" alt="assets" />
					</div>
					
					<div class="top-settings-text">
						<?=Yii::t('Api', 'Hide small amounts')?>
					</div>
					
					<div class="clearfix"></div>
			
				</div>
				
				<div data-option="hsa" class="option_item">

					<?=Html::img('/images/icons/settings.svg', [
						'alt' => 'images',
						'style' => 'width:20px;margin-top:-2px',
						'id' => 'img-hsa',
					])?>
					
					<div class="currency_name_block ml-10">
						<div class="currency_name"><1$</div>
					</div>
	
					<div class="currency_price_block ml-10">
						<div class="setttings_checkbox">
							<?=Html::checkbox('select_hsa', !empty($hsa) ? true : false,[
								'id' => 'select-hsa',
								'class' =>  'setttings-checkbox',
							])?>
						</div>
					</div>
					
					<div class="clearfix"></div>
				</div>
	
			</div>	
		</div>
		<!-- End settings -->
	</div>
</div>