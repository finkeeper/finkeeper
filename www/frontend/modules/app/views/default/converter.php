<?php

use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap5\ActiveForm;
use common\widgets\Alert;
use common\models\Exchange;
use yii\helpers\Url;

$this->title = Yii::t('Title', 'FinKeeper');
$this->params['breadcrumbs'][] = $this->title;

$page_url = '/app?id='.$id.'&sc='.$sc;

$lang = 'en';
if (Yii::$app->language=='ru-RU') {
	$lang = 'ru';
}

$currency = [];
if (!empty($exchange)) {
	foreach ($exchange as $val) {
		$currency[] = [
			'id' => $val['id'],
			'symbol' => $val['name'],
			'name' => $val['text_name'],
			'price' => $val['price'],
			'src' => $val['img'],
			'type' => $val['type'],
		];
	}
}

$this->registerJsFile('https://unpkg.com/@tonconnect/ui@latest/dist/tonconnect-ui.min.js', ['position' => \yii\web\View::POS_HEAD]);

//Connect JS scripts
Yii::$app->view->render('elements/__script',[
	'id' => $id,
	'sc' => $sc,
	'currency' => $currency,
	'targets' => $targets,
	'status' => $status,
	'page_url' => $page_url,
	'username' => $username,
	'userpic' => $userpic,
	'wallet' => $wallet,
	'lang' => $lang,
]);


// Modal window for choosing currencies
echo Yii::$app->view->render('elements/__select_modal',[
	'exchange' => $exchange,
]);

// Modal window for choosing language
echo Yii::$app->view->render('elements/__lg_modal',[
	'lang' => $lang,
]);

// Modal window friends
echo Yii::$app->view->render('elements/__fr_modal',[
	'friends' => $friends,
]);

// Modal window wallet
echo Yii::$app->view->render('elements/__as_modal',[
	'grafema' => $grafema,
	'used_gpt1' => $used_gpt1,
	'wallet' => $wallet,
]);

// Modal staking calc
echo Yii::$app->view->render('elements/__st_modal');

// Modal Bybit form
echo Yii::$app->view->render('elements/__bybit_modal');

// Modal OKX form
echo Yii::$app->view->render('elements/__okx_modal');

// Modal Active Details
echo Yii::$app->view->render('elements/__ad_modal',[
	'grafema' => $grafema,
	'used_gpt2' => $used_gpt2,
]);

// Modal Target Form
echo Yii::$app->view->render('elements/__target_modal',[
	'grafema' => $grafema,
]);

// Modal Solana form
echo Yii::$app->view->render('elements/__sol_modal');

// Modal Sui form
echo Yii::$app->view->render('elements/__sui_modal');

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
						<?=$friends['friends']?> <?=Yii::t('Api', 'friends joined FinKeeper')?>
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

<div id="bottom-toolbar" class="bottom-toolbar">
	<?=Yii::$app->view->render('elements/__footer')?>
</div>

<div id="bottom-as-toolbar" class="bottom-toolbar" style="display:none">
	<div class="row">
		<div class="col-xxl-6 user-actives-list">
			<?=Yii::$app->view->render('elements/__footer')?>
		</div>
		<div class="col-xxl-6 user-actives-chat">
			<?=Yii::$app->view->render('elements/__chat_footer')?>
		</div>
	</div>
</div>

<div class="card-info bottom-fixed">

	<div id="conv-notify"></div>
	
	<?=Alert::widget()?>

</div>
