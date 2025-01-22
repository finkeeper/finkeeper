<?php
use yii\bootstrap5\Html;
?>

<div class="modal fade" id="asModal" tabindex="-1" aria-labelledby="asModalLabel" data-parent="">
	<div class="modal-dialog">
		<!-- Start tonconnect -->
		<div id="wrap-actives">
			<div class="row" style="margin:0 0 20px 0;">
				
				<div id="ton-wallet-connect-button" class="wallet-connect-button">
					<div id="ton-connect" style="display:none"></div>
					<span class="mdi mdi-wifi-off"></span>
					<div tabindex="0" role="button" id="ton-wallet-click-button" class="areal-click-button"></div>
					<div class="backdrop-connect-button" style="display:none">
						<span class="far fa-hourglass fa-spin"></span>
					</div>
				</div>
				
				<div id="sol-wallet-connect-button" class="wallet-connect-button">
					<span class="mdi mdi-wifi-off"></span>
					<div tabindex="0" role="button" id="sol-wallet-click-button" class="areal-click-button"></div>
					<div class="backdrop-connect-button" style="display:none">
						<span class="far fa-hourglass fa-spin"></span>
					</div>
				</div>
				
				<div id="bybit-exchange-connect-button" class="wallet-connect-button">
					<span class="mdi mdi-wifi-off"></span>
					<div tabindex="0" role="button" id="bybit-exchange-click-button" class="areal-click-button"></div>
					<div class="backdrop-connect-button" style="display:none">
						<span class="far fa-hourglass fa-spin"></span>
					</div>
				</div>
				
				<div id="okx-exchange-connect-button" class="wallet-connect-button">
					<span class="mdi mdi-wifi-off"></span>
					<div tabindex="0" role="button" id="okx-exchange-click-button" class="areal-click-button"></div>
					<div class="backdrop-connect-button" style="display:none">
						<span class="far fa-hourglass fa-spin"></span>
					</div>
				</div>
			
				<div class="clearfix"></div>
			</div>
			
			<div class="clearfix"></div>
			<div id="wrap-balance" class="">		
				<div id="title_balance"><?=Yii::t('Api', 'Connect your wallet to see list of assets')?></div>
				
				<?php //if (in_array($id_client, [10, 15])) { ?>
				<?php if (!empty($used_gpt1)) { ?>
	
					<div id="all-summ-active">
						<span class="all-summ-price">0</span> <?=$grafema?> <img src="/images/svg/element/smart_toy.svg" id="smart-toy"> <div id="search-actives" class="mdi mdi-magnify"></div>
					</div>
					
				<?php } else { ?>
				
				<div id="all-summ-active">
					<span class="all-summ-price">0</span> <?=$grafema?> <div id="search-actives" class="mdi mdi-magnify"></div>
				</div>
				
				<?php } ?>
				
				<div id="form-search-active" style="display:none">
					<div class="input-group">
					
						<div class="input-group-text bg-transparent border-right-0" id="close-search">
							<img width="14px" src="/images/icons/close.svg" alt="" title="" />
						</div>

						<?=Html::textInput('search_active_input', '',[
							'autocomplete' => 'off', 
							'id' => 'search-active-input',
							'class' =>  'form-control border-left-0 form-currency-search',
							'type' => 'search',
							'placeholder' => Yii::t('Api', 'Search by name'),
							'aria-label' => Yii::t('Api', 'Search by name'),
							//'aria-describedby' => 'basic-addon1',
						])?>
										
					</div>
				</div>
	
				<div id="user_balance"></div>
				<div class="mta-50"></div>
			</div>
			<!-- End Ton-Connect -->
				
		</div>
		<!-- End tonconnect -->
	</div>
</div>