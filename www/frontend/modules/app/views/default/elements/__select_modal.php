<?php
use yii\bootstrap5\Html;
use common\models\Exchange;
?>

<div class="modal fade" id="convModal" tabindex="-1" aria-labelledby="convModalLabel" data-parent="">
	<div class="modal-dialog">
		<!-- Start conv -->
		<div id="wrap-conv">
		
			<div class="app-navigation">
				<i class="fas fa-arrow-left app-back"></i>
			</div>
			
			<div class="modal-search">
				<div class="input-group">
					
					<div class="input-group-text bg-transparent border-right-0" id="basic-addon1">
						<img src="/images/icons/search.svg" alt="" title="" />
					</div>

					<?=Html::textInput('search_conv', '',[
						'autocomplete' => 'off', 
						'id' => 'search-conv',
						'class' =>  'form-control border-left-0 form-currency-search',
						'type' => 'search',
						'placeholder' => Yii::t('Api', 'Search by name'),
						'aria-label' => Yii::t('Api', 'Search by name'),
						'aria-describedby' => 'basic-addon1',
					])?>
									
				</div>
				
				<div class="currency_button_group">
				
					<div data-id="fiat" class="currency_button mt-17 mr-10">
						<?=Yii::t('Api', 'Fiat')?>
					</div>
					
					<div data-id="crypto" class="currency_button mt-17 mr-10">
						<?=Yii::t('Api', 'Crypto')?>
					</div>
					
					<div data-id="recent" class="currency_button mt-17 mr-10">
						<?=Yii::t('Api', 'Recent')?>
					</div>
					
					<div data-id="all" class="currency_button currency_button_active mt-17 mr-10">
						<?=Yii::t('Api', 'All')?>
					</div>

					<div class="clearfix"></div>
					
				</div>
			</div>
			
			<?php if (!empty($exchange) && is_array($exchange)) { ?>
				
				<?php foreach ($exchange as $key=>$value) { 
				
					$decimal = 4;
					if ($value['type']==2) {
						$decimal = 2;
					}
				
					$name = $value['name'];
					if (!empty($value['text_name'])) {
						$name = $value['text_name'];
					}
					?>
			
					<div data-id="<?=$value['id']?>" data-num="0" data-type="<?=$value['type']?>" class="option_item">
						
						<span class="circle-image">
							<?=Html::img($value['img'], [
								'alt' => 'images',
								'style' => 'width:100%;height:100%;object-fit:cover',
								'id' => 'img-conv-'.$value['id'],
								'class' => 'img-circle',
							])?>
						</span>
						
						<div class="currency_name_block ml-10">
							<div class="currency_name"><?=$key?></div>
							<div class="currency_symbol"><?=$name?></div>
						</div>
						<div class="currency_graf ml-10"></div>
						<div class="currency_price_block ml-10">
							<div class="currency_price">
								<?=Exchange::getFormat($value['price'], 1, $decimal)?>&nbsp;
								<?=Exchange::getGrafemCurrency($value['currency'])?>
							</div>
							<div class="currency_volat"></div>
						</div>
						<div class="clearfix"></div>
					</div>

				<?php } ?>

			<?php } ?>
				
		</div>
		<!-- End conv -->
	</div>
</div>