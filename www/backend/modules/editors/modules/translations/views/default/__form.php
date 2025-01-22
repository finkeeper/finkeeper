<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap5\Modal;
use common\widgets\Alert;
use yii\bootstrap5\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use backend\modules\editors\modules\translations\models\Translations;
use backend\modules\editors\modules\translations\TranslationsModule;

$this->registerJs("
	jQuery(document).ready(function($) {
		$('#translations-category').change(function(){
			if ($(this).val()==0) {
				var html = '<input type=\'text\' id=\'create-new-category\' class=\'form-control\'>';
				$('#cf-modal-load').find('.modal-title').html('".Yii::t('EditorsTranslations', 'New Category')."');
				$('#cf-modal-load').find('.modal-body').html(html);
				$('#cf-modal-load').toggle();	
			}
		});
		
		$('#btn-add-cf-modal').on('click', function() {
			$('#cf-modal-load').toggle();
			var category = $('#create-new-category').val();
			$('#translations-category').append('<option selected value=\'' + category + '\'>' + category + '</option>').trigger('change');
		})
	});	
", yii\web\View::POS_END);
?>

<div class="site-translations-form theme_content">

	<?=Alert::widget()?>
	
	<?php 
	$form = ActiveForm::begin([
		'id' => 'translations-form', 
		'class' => 'form-horizontal', 
	]); ?>
	
		<div class="row">
			<div class="col-lg-12">
					
				<?php if ($model->getErrors()) { ?>
						
					<div class="bs-callout bs-callout-danger">
						<?= $form->errorSummary($model); ?>
					</div>
						
				<?php } ?>
					
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-12">
				<div class="category_container">
					<label for="translations-category">
						<?=Yii::t('EditorsTranslations', 'Category')?>
					</label>
				
					<div class="container-fluid">
						<?=$form->field($model, 'category', [
							'template' => '<div class="alert-danger lcp_error_message">{error}</div>{beginWrapper}{input}{endWrapper}'
						])->dropDownList(
							[
								'0' => Yii::t('EditorsTranslations', 'Create New Category'),
							] + $model->getCategory(),
							[
								'prompt' => Yii::t('EditorsTranslations', 'Select Category'),
								'options' => [
									'0' => [
										'id' => 'create-category',
									],
								],
								'class' => 'form-control',
							]
						)->label(false)?>
					</div>
				</div>
				
				<label for="translations-message">
					<?=Yii::t('EditorsTranslations', 'Message Key')?>
				</label>
				
				<?= $form->field($model, 'message',[
					'template' => '<div class="alert-danger lcp_error_message">{error}</div><div class="form-group"><div class="col-md-12 col-sm-12 col-xs-12 has-feedback">{input}<span class="glyphicon form-control-feedback"></span></div><div class="clearfix"></div></div>'
				])->textarea([
					'rows' => '1',
				])->label(false)?>
				
				
				<?php if (!empty($model->translation_arr) && is_array($model->translation_arr)) { ?>
				
					<?php foreach ($model->translation_arr as $language => $text) { ?>
				
						<label for="translations-translation_arr_<?=strtolower($language)?>">
							<?=Yii::t('EditorsTranslations', 'Language')?>: <?=$language?>
						</label>
						
						<?= $form->field($model, 'translation_arr['.$language.']',[
							'template' => '<div class="alert-danger lcp_error_message">{error}</div><div class="form-group"><div class="col-md-12 col-sm-12 col-xs-12 has-feedback">{input}<span class="glyphicon form-control-feedback"></span></div><div class="clearfix"></div></div>'
						])->textarea([
							'rows' => '5',
							'id' => 'translations-translation_arr_'.strtolower($language),
						])->label(false)?>

					<?php } ?>
				
				<?php } else if (empty($model->id)) { ?>
					
					<?php foreach (Yii::$app->params['supported_lang'] as $lang) { ?>
					
						<label for="translations-translation_arr_<?=strtolower($lang).'-'.strtolower($lang)?>">
							<?=Yii::t('EditorsTranslations', 'Language')?>: <?=strtolower($lang).'-'.strtoupper($lang)?>
						</label>
						
						<?= $form->field($model, 'translation_arr['.strtolower($lang).'-'.strtoupper($lang).']',[
							'template' => '<div class="alert-danger lcp_error_message">{error}</div><div class="form-group"><div class="col-md-12 col-sm-12 col-xs-12 has-feedback">{input}<span class="glyphicon form-control-feedback"></span></div><div class="clearfix"></div></div>'
						])->textarea([
							'rows' => '5',
							'id' => 'translations-translation_arr_'.strtolower($lang).'-'.strtolower($lang),
						])->label(false)?>
					
					<?php } ?>
					
				<?php } ?>

				<p class="clearfix">&nbsp;</p>
				<div class="row">
					<div class="col-sm-12 text-right">
						<?= Html::submitButton(Yii::t('EditorsTranslations', 'btnSave'), ['class' => 'btn btn-primary m-4', 'name' => 'save-button']) ?>
					</div>
				</div>
			</div>
		</div>

	<?php ActiveForm::end(); ?> 

</div>