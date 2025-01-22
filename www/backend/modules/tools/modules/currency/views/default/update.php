<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap5\Modal;
use common\widgets\Alert;
use yii\bootstrap5\ActiveForm;
use backend\modules\tools\modules\currency\assets\ModuleAsset;

ModuleAsset::register($this);

$this->title = Yii::t('Backend', 'Page Currency Update').' : '.$modelCurrencyConfig->symbol;
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="site-currency-form theme_content">

	<?=Alert::widget()?>
	
	<?php 
	$form = ActiveForm::begin([
		'id' => 'currency-form', 
		'class' => 'form-horizontal', 
	]); ?>
	
		<div class="row">
			<div class="col-lg-12">
					
				<?php if ($modelCurrencyConfig->getErrors()) { ?>
						
					<div class="bs-callout bs-callout-danger">
						<?= $form->errorSummary($modelCurrencyConfig); ?>
					</div>
						
				<?php } ?>
					
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-12">
			
			
				<div class="symbol_container">
				
					<label for="symbol">
						<?=Yii::t('Api', 'symbol')?>
					</label>
				
					<div class="container-fluid">
						<?=$form->field($modelCurrencyConfig, 'symbol', [
							'template' => '<div class="alert-danger lcp_error_message">{error}</div>{beginWrapper}{input}{endWrapper}'
						])->textInput()->label(false)?>
					</div>
				
				</div>
	
				<div class="symbol_container">
				
					<label for="logo">
						<?=Yii::t('Api', 'logo')?>
					</label>
				
					<div class="container-fluid">
						<?=$form->field($modelCurrencyConfig, 'logo', [
							'template' => '<div class="alert-danger lcp_error_message">{error}</div>{beginWrapper}{input}{endWrapper}'
						])->textInput()->label(false)?>
					</div>
				
				</div>
				
				<div class="name_container">
				
					<label for="name">
						<?=Yii::t('Api', 'name')?>
					</label>
				
					<div class="container-fluid">
						<?=$form->field($modelCurrencyConfig, 'name', [
							'template' => '<div class="alert-danger lcp_error_message">{error}</div>{beginWrapper}{input}{endWrapper}'
						])->textInput()->label(false)?>
					</div>
				
				</div>
				
				<div class="name_en_container">
				
					<label for="name_en">
						<?=Yii::t('Api', 'name_en')?>
					</label>
				
					<div class="container-fluid">
						<?=$form->field($modelCurrencyConfig, 'name_en', [
							'template' => '<div class="alert-danger lcp_error_message">{error}</div>{beginWrapper}{input}{endWrapper}'
						])->textInput()->label(false)?>
					</div>
				
				</div>
				
				<div class="value_container">
				
					<label for="value">
						<?=Yii::t('Api', 'value')?>
					</label>
				
					<div class="container-fluid">
						<?=$form->field($modelCurrencyConfig, 'value', [
							'template' => '<div class="alert-danger lcp_error_message">{error}</div>{beginWrapper}{input}{endWrapper}'
						])->textInput()->label(false)?>
					</div>
				
				</div>
				
				<div class="value_currency_container">
				
					<label for="value_currency">
						<?=Yii::t('Api', 'value_currency')?>
					</label>
				
					<div class="container-fluid">
						<?=$form->field($modelCurrencyConfig, 'value_currency', [
							'template' => '<div class="alert-danger lcp_error_message">{error}</div>{beginWrapper}{input}{endWrapper}'
						])->textInput()->label(false)?>
					</div>
				
				</div>
				
			</div>
		</div>
	
	
	<?php ActiveForm::end(); ?> 
	
</div>