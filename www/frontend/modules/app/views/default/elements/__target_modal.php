<?php
use yii\bootstrap5\Html;
?>


<div class="modal fade" id="targetModal" tabindex="-1" aria-labelledby="targetModalLabel" data-parent="adModal">
	<div class="modal-dialog">
		<!-- Start calc -->
		<div id="wrap-target-form">
		
			<div class="app-navigation">
				<i class="fas fa-arrow-left app-back"></i>
			</div>
		
			<div class="header_details_coin">				
				<div class="img_details_coin">
					<img src="/images/cryptologo/default_coin.webp">
				</div>

				<div class="title_details_coin">				
					<div class="symbol_details_coin">
						<?=Yii::t('Api', 'Coin')?>
					</div>					
					<div class="name_details_coin">
						<?=Yii::t('Api', 'Assets Coins')?>
					</div>					
				</div>				
				<div class="price_details_coin">				
					<div class="value_details_coin"><span class="wrap_details_coin"></span> <?=$grafema?></div>				
					<div class="currency_details_coin"></div>					
				</div>
				<div class="clearfix"></div>	
			</div>

			<div id="inwestedBlock">

				<div class="title_form_invested">
					<?=Yii::t('Api', 'Invested')?>&nbsp;
					<!--<div id="question-addon10" class="is-external-info"></div>-->
				</div>
							
				<div class="block_form_invested">

					<div class="form-floating float-start block_form_input">
						
						<input type="text" class="form-control float-start" id="inputAmount" placeholder="<?=Yii::t('Api', 'Current Amount')?> <?=$grafema?>" autocomplete="off" inputmode="numeric" value="" disabled>
						
						<label for="inputAmount"><?=Yii::t('Api', 'Current Amount')?>  <?=$grafema?></label>
					</div>
					
					<div class="float-start block_form_input_sep"></div>
					
					<div class="form-floating float-start block_form_input">
					
						<input type="text" class="form-control float-start" id="inputPrice" placeholder="<?=Yii::t('Api', 'Current Price')?> <?=$grafema?>" autocomplete="off" inputmode="numeric" value="">
						
						<label for="inputPrice"><?=Yii::t('Api', 'Current Price')?> <?=$grafema?></label>
					</div>
		
					<div class="clearfix"></div>
					
					<div class="block_form_range" style="position:relative;height:70px;">
						<div style="height:40px">
							<label for="customRange1" class="form-label float-start"><?=Yii::t('Api', 'Leverage')?></label>
							<div style="width:70px;" class="float-end">
								<span class="float-start" style="width:14px;font-size:18px;color:#fff">X</span>
								<input class="float-start" style="width:50px;font-size:16px;font-weight:bold" type="text" class="form-control" id="ad-user-price" placeholder="" autocomplete="off" inputmode="numeric" value="2" >	
							</div>
						</div>
						<div style="width:100%;height:30px;color:#ffffff;font-size:12px;position:absolute;top:42px;left:0;z-index:100">
						
							<div style="width:100%;height:14px;">
								<div style="width:calc(20% - 3px);float:left;">x1</div>
								<div style="width:calc(20% - 2px);float:left;">x2</div>
								<div style="width:calc(20% - 2px);float:left;">x3</div>
								<div style="width:calc(20% - 3px);float:left;">x4</div>
								<div style="width:calc(20% - 10px);float:left;">x5</div>
								<div class="text-right" style="width:20px;float:left;">x6</div>
							</div>
							<div style="width:100%;height:16px;">
								<div style="height:17px;width:calc(20% + 4px);float:left;border-right:1px solid #fff;"></div>
								<div style="height:17px;width:calc(20% - 2px);float:left;border-right:1px solid #fff;"></div>
								<div style="height:17px;width:calc(20% - 4px);float:left;border-right:1px solid #fff;"></div>
								<div style="height:17px;width:calc(20% - 2px);float:left;border-right:1px solid #fff;"></div>
								<div style="width:20%;float:left;"></div>
							</div>
							
						</div>
						<input style="margin-top:10px;" type="range" class="form-range range-cust" id="customRange1" value="2" min="1" max="6" step="0.25">					
					</div>
					
					<?=Html::button(Yii::t('Form', 'Set Target'), [
						'id' => 'ct-target-send',
						'class' =>  'btn-turquoise',
					])?>
					
				</div>
				
			</div>

		</div>
	</div>
</div>