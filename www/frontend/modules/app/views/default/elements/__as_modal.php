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
						
						<div id="sui-wallet-connect-button" class="wallet-connect-button">
							<span class="mdi mdi-wifi-off"></span>
							<div tabindex="0" role="button" id="sui-wallet-click-button" class="areal-click-button"></div>
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
				
				
				<?php if (empty($wallet['sui']['address'])) { ?>
					
					<div class="row" style="margin:0 0 20px 0;">	
						<div class="create_aiagent_wallet">
							<div id="create-aiagent-wallet" class="currency_button mt-17 mr-10"><?=Yii::t('Api', 'Create AI agent wallet')?></div>
						</div>
					</div>
					
				<?php } else { ?>
				
					<div class="row" style="margin:0 0 20px 0;font-size:18px">	
						<div class="create_aiagent_wallet">
							<?=Yii::t('Api', 'AI agent SUI wallet')?>:&nbsp;<?=substr_replace($wallet['sui']['address'], '...', 8, -8)?>&nbsp;&nbsp;<span id="as-wallet-copy" data-address="<?=$wallet['sui']['address']?>"><img src="/images/icons/copy.svg" alt="" title=""></span>&nbsp;&nbsp;<a href="https://suivision.xyz/account/<?=$wallet['sui']['address']?>" target="_blank" id="as-rewiew-wallet"><img src="/images/icons/globe.svg" alt="" title=""></a><br>
							<?=Yii::t('Api', 'Balance')?>:&nbsp;<?=$wallet['sui']['balance']?>&nbsp;SUI (<?=$wallet['sui']['price']?><?=$grafema?>)
						</div>
					</div>
				
				<?php } ?>
			
				<h1 class="text-center"><?=Yii::t('Api', 'Chat')?></h1>
	
				<a tabindex="0" role="button" id="question-addon-chat" class="fa fa-question-circle"></a>
				
				<div id="chat-form-as" class="chat_form_as" style="overflow-y:auto;height:calc(100vh - 100px);padding-bottom:90px;"></div>
				
			</div>
			
			
			
		</div>
	</div>
</div>