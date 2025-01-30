<?php

use yii\bootstrap5\Html;
?>
<div class="modal fade" id="adModal" tabindex="-1" aria-labelledby="adModalLabel" data-parent="asModal">
	
	<?=Html::hiddenInput('ad-symbol', '',[
		'id' => 'ad-symbol',
		'type' => 'hidden',
	])?>

	<?=Html::hiddenInput('ad-currency', '',[
		'id' => 'ad-currency',
		'type' => 'hidden',
	])?>

	<?=Html::hiddenInput('ad-price', '',[
		'id' => 'ad-price',
		'type' => 'hidden',
	])?>

	<?=Html::hiddenInput('ad-coins', '',[
		'id' => 'ad-coins',
		'type' => 'hidden',
	])?>
	
	<div class="modal-dialog">
		<!-- Start detailscoin -->
		<div id="wrap-detailscoin">		
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
				<?php if (!empty($used_gpt2)) { ?>
					<div class="info_details_coin">	
						<img src="/images/svg/element/smart_toy.svg" id="smart-toy-active">
					</div>
				<?php } ?>
				<div class="price_details_coin">				
					<div class="value_details_coin"><span class="wrap_details_coin"></span> <?=$grafema?></div>				
					<div class="currency_details_coin"></div>					
				</div>
				<div class="clearfix"></div>	
			</div>
			<div id="wrap_actives_coin"></div>
			<div id="block_target_actions">
				<div class="float-start">
					<div id="target-info"></div>
				</div>
				<div class="float-end">
					<a tabindex="0" role="button" id="target_actions-button" class="button button--main button--ex-small"><?=Yii::t('Api', 'Target')?></a>
				</div>
				<div class="clearfix"></div>
				<p>&nbsp;</p>
			</div>
			
		</div>
		<!-- End detailscoin -->					
	</div>
</div>