<?php
use yii\bootstrap5\Html;
?>

<div class="modal fade" id="asModal" aria-labelledby="asModalLabel" data-bs-focus="false" data-page="modal" data-parent="">
	<div class="modal-dialog">
		<!-- Start tonconnect -->
		
		<div class="row">
			<div id="list-active-page" class="col-xxl-6" style="overflow:hidden;height:100%">

				<div id="wrap-actives">
				
					<div class="app-navigation">
						<i class="fas fa-arrow-left app-back"></i>
					</div>

					<div class="row" style="margin:0 0 20px 0;">
						<div id="ton-exchange-connect-button-as218"></div>
						<div id="apt-exchange-connect-button-as316"></div>
						<div id="sol-exchange-connect-button-as864"></div>
						<div id="sui-exchange-connect-button-as164"></div>
						<div id="eth-exchange-connect-button-as628"></div>
						<div id="btc-wallet-connect-button-as1958"></div>
						<div id="bybit-exchange-connect-button-as854"></div>
						<div id="okx-exchange-connect-button-as858"></div>
						<div class="clearfix"></div>
					</div>

					<div class="clearfix"></div>
					<div id="wrap-balance">		
						<div id="title_balance"><?=Yii::t('Api', 'Connect your wallet to see list of assets')?></div>
											
						<div id="all-summ-active">
							<span class="all-summ-price">0</span> <?=$grafema?> <img class="pull-start" src="/images/svg/element/smart_toy.svg" id="smart-toy-aiagent"> <div id="search-actives" class="mdi mdi-magnify"></div>
						</div>
						
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
			
						<div id="user_balance" style="overflow-y:auto;height:calc(100vh - 100px);padding-bottom:10px;"></div>
					</div>

					<!-- End Ton-Connect -->
						
				</div>
				<!-- End tonconnect -->

			</div>
			<div id="chat-active-page" class="col-xxl-6" style="overflow:hidden">
			
				<div class="app-navigation">
					<i class="fa fa-times" id="chat-close" style="display:none"></i>
				</div>	
	
				<div id="as-chatai"></div>
				
			</div>
			
			
			
		</div>
	</div>
</div>