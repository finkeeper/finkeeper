<?php

use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap5\ActiveForm;
use common\widgets\Alert;
use common\models\Exchange;
use yii\helpers\Url;

$this->title = Yii::t('Title', 'FinKeeper');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('https://unpkg.com/@tonconnect/ui@latest/dist/tonconnect-ui.min.js', ['position' => \yii\web\View::POS_HEAD]);

$lang = 'en';

?>

<div class="auth_loader" id="auth-loader">
	<img src="/images/gif/loader.gif" alt="loader" title="loader">
</div>

<div class="auth_page" id="auth-page">
	<div class="auth_title"><?=Yii::t('Api', 'Account verified')?></div>
	<div class="auth_button">
		<button id="auth-in-website" class="btn btn-primary btn-xl">
			<?=Yii::t('Api', 'Continue in website')?>
		</button>
	</div>
	<div class="auth_tg_link">
		<a href="https://t.me/finkeeper_test_bot" alt="" title="">
			<?=Yii::t('Api', 'Stay in Telegram')?>
		</a>
	</div>
</div>

<div id="content">

	<?php

	//Connect JS scripts
	require dirname(__FILE__).'/elements/__script.php';

	// Modal window for choosing currencies
	require dirname(__FILE__).'/elements/__select_modal.php';

	// Modal window for choosing language
	require dirname(__FILE__).'/elements/__lg_modal.php';

	// Modal window friends
	require dirname(__FILE__).'/elements/__fr_modal.php';

	// Modal window wallet
	require dirname(__FILE__).'/elements/__as_modal.php';

	// Modal staking calc
	require dirname(__FILE__).'/elements/__st_modal.php';

	// Modal Bybit form
	require dirname(__FILE__).'/elements/__bybit_modal.php';

	// Modal OKX form
	require dirname(__FILE__).'/elements/__okx_modal.php';

	// Modal Active Details
	require dirname(__FILE__).'/elements/__ad_modal.php';

	// Modal Target Form
	require dirname(__FILE__).'/elements/__target_modal.php';

	// Modal Solana form
	require dirname(__FILE__).'/elements/__sol_modal.php';

	// Modal Sui form
	require dirname(__FILE__).'/elements/__sui_modal.php';

	/*
	// console for ios
	<script>
		window.onerror = function(msg, url, lineNo, columnNo, error) {
			alert(error + ' ' + url);
			return false;
		}
	</script>
	*/
	?>

	<div class="page page--main" data-page="main">

		<!-- PAGE CONTENT -->
		<div class="page__content page__content--with-bottom-nav">
			<div class="page-inner">	
				<div class="cards--11">
					<div class="card-info">
					
						<div class="top-nav">
							<a href="javascript:void(0)" id="lg-modal">
								<img src="/images/svg/element/settings.svg" alt="logo"/>
							</a>
						</div>
						
						<div class="top-breadcrumbs">
					
							<?=Yii::t('Api', 'Exchange Rates')?> / <?=Yii::t('Api', 'Currency')?>
							
						</div>
				
					</div>

					<div class="card-coin-def mt-20">

						<div id="conv1" class="card-currency">
							
							<div class="drop_down_list">

								<div class="currency_block" data-num="1">
								
									<span class="circle-image">
										<?=Html::img($exchange['USD']['img'], [
											'alt' => 'images',
											'style' => 'width:100%;height:100%;object-fit:cover',
											'id' => 'img-conv-'.$exchange['USD']['id'],
											'class' => 'img-circle',
										])?>
									</span>
								
									<div id="dd_conv_1" data-id="<?=$exchange['USD']['id']?>" class="currency_list_data"><?=$exchange['USD']['name']?></div>
									
									<div class="clearfix"></div>
									
								</div>
			
							</div>
							
							<div class="text_input">
								
								<div class="copy_button">
									
									<a href="javascript:void(0)"  class="copy_value">
										<img src="/images/icons/copy.svg" alt="" title="" />
									</a>
									
								</div>
								
								<div class="currency_value">
								
									<?php
									$price = 0;
									if (!empty($exchange['USD']['price'])) {
										$price = Exchange::getFormat(1/$exchange['USD']['price'], 1, 2);
									}
									?>

									<?=Html::textInput('ti_conv_1', $price,[
										'autocomplete' => 'off', 
										'id' => 'ti-conv-1',
										'class' =>  'form-currency currency_active',
										'type' => 'text',
										'inputmode' => 'numeric',
										'data-id' => $exchange['USD']['id'],
									])?>
									
								</div>
								
								<div class="clearfix"></div>
								
							</div>
						
						</div>
						
						<div id="conv2" class="card-currency mt-20">
						
							<div class="drop_down_list">
							
								<div class="currency_block" data-num="2">
								
									<span class="circle-image">
										<?=Html::img($exchange['RUB']['img'], [
											'alt' => 'images',
											'style' => 'width:100%;height:100%;object-fit:cover',
											'id' => 'img-conv-'.$exchange['RUB']['id'],
											'class' => 'img-circle',
										])?>
									</span>
								
									<div id="dd_conv_2" data-id="<?=$exchange['RUB']['id']?>" class="currency_list_data"><?=$exchange['RUB']['name']?></div>
									
									<div class="clearfix"></div>
									
								</div>
							
							</div>
							
							<div class="text_input">
							
								<div class="copy_button">
									
									<a href="javascript:void(0)"  class="copy_value">
										<img src="/images/icons/copy.svg" alt="" title="" />
									</a>
									
								</div>
								
								<div class="currency_value">
								
									<?php
									$price = 0;
									if (!empty($exchange['RUB']['price'])) {
										$price = Exchange::getFormat(1/$exchange['RUB']['price'], 1, 2);
									}
									?>

									<?=Html::textInput('ti_conv_2', $price,[
										'autocomplete' => 'off', 
										'id' => 'ti-conv-2',
										'class' =>  'form-currency',
										'type' => 'text',
										'inputmode' => 'numeric',
										'data-id' => $exchange['RUB']['id'],
									])?>
									
								</div>
								
								<div class="clearfix"></div>
							
							</div>
						
						</div>
						
						<div id="conv3" class="card-currency mt-20">
						
							<div class="drop_down_list">
							
								<div class="currency_block" data-num="3">
								
									<span class="circle-image">
										<?=Html::img($exchange['BTC']['img'], [
											'alt' => 'images',
											'style' => 'width:100%;height:100%;object-fit:cover',
											'id' => 'img-conv-'.$exchange['BTC']['id'],
											'class' => 'img-circle',
										])?>
									</span>
								
									<div id="dd_conv_3" data-id="<?=$exchange['BTC']['id']?>" class="currency_list_data"><?=$exchange['BTC']['name']?></div>
									
									<div class="clearfix"></div>
									
								</div>
							
							</div>
							
							<div class="text_input">
							
								<div class="copy_button">
									
									<a href="javascript:void(0)"  class="copy_value">
										<img src="/images/icons/copy.svg" alt="" title="" />
									</a>
									
								</div>
								
								<div class="currency_value">
								
									<?php
									$price = 0;
									if (!empty($exchange['BTC']['price'])) {
										$price = Exchange::getFormat(1/$exchange['BTC']['price'], 1);
									}
									?>
									
									<?=Html::textInput('ti_conv_3', $price,[
										'autocomplete' => 'off', 
										'id' => 'ti-conv-3',
										'class' =>  'form-currency',
										'type' => 'text',
										'inputmode' => 'numeric',
										'data-id' => $exchange['BTC']['id'],
									])?>
									
								</div>
								
								<div class="clearfix"></div>
							
							</div>
						
						</div>
					
					</div>
					
					<div class="bot-banner mt-20">
						
						<div class="banner_title">
							<?=Yii::t('Api', 'Invite friends to earn rewards')?>
						</div>
						
						<div class="banner_text">
							<span class="count-friends">0</span> <?=Yii::t('Api', 'friends joined FinKeeper')?>
						</div>
						
						<div class="banner_button mt-10">
							<button id="send-invite" class="btn-white-content"><?=Yii::t('Api', 'Let’s go!')?></button>
						</div>
					
						<div class="banner_bf"></div>
					</div>
					
					<div id="st-modal" class="btn-turquoise">
						<?=Yii::t('Api', 'APR Calculate')?>
					</div>
					
				</div>
			</div>	
		</div>
	</div>
	<div class="clearfix mta-70"></div>
	
	<!-- PAGE END -->

	<?php
	// Bottom navigation
	require dirname(__FILE__).'/elements/__footer.php';
	?>

	<div class="card-info bottom-fixed">

		<div id="conv-notify"></div>
		
		<?=Alert::widget()?>

	</div>
</div>

