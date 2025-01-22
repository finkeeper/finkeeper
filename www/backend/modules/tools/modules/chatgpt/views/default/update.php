<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use common\widgets\Alert;
use yii\bootstrap5\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use backend\modules\tools\modules\chatgpt\assets\ModuleAsset;

ModuleAsset::register($this);

$this->title = Yii::t('Backend', 'Page Chatgpt');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("
	jQuery(document).ready(function($) {
		
		var url = window.location.href;
		var parse = URL.parse(url);
		history.pushState(null, null, parse.pathname);

		if (parse.hash=='#nav-active') {
			$('#nav-active-tab').trigger('click');	
		}
	});	
", yii\web\View::POS_END);
?>

<p>&nbsp;</p>
<?=Alert::widget()?>

<nav>
	<div class="nav nav-tabs" id="nav-tab" role="tablist">
		<button class="nav-link active" id="nav-list-tab" data-toggle="tab" data-target="#nav-list" type="button" role="tab" aria-controls="nav-list" aria-selected="true"><?=Yii::t('Backend', 'List coins')?></button>
		<button class="nav-link" id="nav-active-tab" data-toggle="tab" data-target="#nav-active" type="button" role="tab" aria-controls="nav-active" aria-selected="false"><?=Yii::t('Backend', 'Actives')?></button>
	</div>
</nav>
<div class="tab-content" id="nav-tabContent">
	<div class="tab-pane fade show active" id="nav-list" role="tabpanel" aria-labelledby="nav-list-tab">

		<div class="container-fluid">
			<div class="site-chatgpt theme_content">
				<div class="grid_container">
					<p>&nbsp;</p>
					<?php 
					$form = ActiveForm::begin([
						'id' => 'gpt-form', 
						'class' => 'form-horizontal', 
						//'options'=>['enctype'=>'multipart/form-data'],
						'action' => ['/tools/chatgpt/update'],
					]); ?>
					
						<?=Html::hiddenInput('Gptchat[id]' , '1', [])?>

						<div class="row">
							<div class="col-lg-12">
									
								<?php if ($model1->getErrors()) { ?>
										
									<div class="bs-callout bs-callout-danger">
										<?= $form->errorSummary($model1); ?>
									</div>
										
								<?php } ?>
									
							</div>
						</div>
						
						<div class="row">
							<div class="col-lg-12">
								<?=$form->field($model1, 'used',[])->checkbox()?>
								<p>&nbsp;</p>
							</div>
						</div>
							
						<div class="row">
							<div class="col-lg-12">
								
								<h4><?=Yii::t('Backend', 'Form Direction')?></h4>
								<div class="alert alert-warning"><?=Yii::t('Backend', 'Help Direction')?></div>

								<?= $form->field($model1, 'direction',[
									'template' => '<div class="form-group"><div class="col-md-12 col-sm-12 col-xs-12 has-feedback">{input}<span class="glyphicon form-control-feedback"></span></div><div class="clearfix"></div></div>{error}'
								])->textarea(['rows' => '10'])->label(false)?>
								
								<h4><?=Yii::t('Backend', 'Form System')?></h4>
								
								<?= $form->field($model1, 'system',[
									'template' => '<div class="form-group"><div class="col-md-12 col-sm-12 col-xs-12 has-feedback">{input}<span class="glyphicon form-control-feedback"></span></div><div class="clearfix"></div></div>{error}'
								])->textarea(['rows' => '10'])->label(false)?>

							</div>								
						</div>
						<p class="clearfix">&nbsp;</p>
						<div class="row">
							<div class="col-sm-12 text-right">
								<?= Html::submitButton(Yii::t('Backend', 'btnSave'), ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
							</div>
						</div>
						<p class="clearfix">&nbsp;</p>

					<?php ActiveForm::end(); ?> 

				</div>
			</div>
		</div>

	</div>
	<div class="tab-pane fade" id="nav-active" role="tabpanel" aria-labelledby="nav-active-tab">
		<div class="container-fluid">
			<div class="site-chatgpt theme_content">
				<div class="grid_container">
					<p>&nbsp;</p>
					<?php 
					$form = ActiveForm::begin([
						'id' => 'gptactives-form', 
						'class' => 'form-horizontal', 
						//'options'=>['enctype'=>'multipart/form-data'],
						'action' => ['/tools/chatgpt/update'],
					]); ?>
					
						<?=Html::hiddenInput('Gptchat[id]' , '2', [])?>

						<div class="row">
							<div class="col-lg-12">
									
								<?php if ($model2->getErrors()) { ?>
										
									<div class="bs-callout bs-callout-danger">
										<?= $form->errorSummary($model2); ?>
									</div>
										
								<?php } ?>
									
							</div>
						</div>
						
						<div class="row">
							<div class="col-lg-12">
								<?=$form->field($model2, 'used',[])->checkbox()?>
								<p>&nbsp;</p>
							</div>
						</div>
							
						<div class="row">
							<div class="col-lg-12">
								
								<h4><?=Yii::t('Backend', 'Form Direction')?></h4>
								<div class="alert alert-warning"><?=Yii::t('Backend', 'Help Direction2')?></div>

								<?= $form->field($model2, 'direction',[
									'template' => '<div class="form-group"><div class="col-md-12 col-sm-12 col-xs-12 has-feedback">{input}<span class="glyphicon form-control-feedback"></span></div><div class="clearfix"></div></div>{error}'
								])->textarea(['rows' => '10'])->label(false)?>
								
								<h4><?=Yii::t('Backend', 'Form System')?></h4>
								
								<?= $form->field($model2, 'system',[
									'template' => '<div class="form-group"><div class="col-md-12 col-sm-12 col-xs-12 has-feedback">{input}<span class="glyphicon form-control-feedback"></span></div><div class="clearfix"></div></div>{error}'
								])->textarea(['rows' => '10'])->label(false)?>

							</div>								
						</div>
						<p class="clearfix">&nbsp;</p>
						<div class="row">
							<div class="col-sm-12 text-right">
								<?= Html::submitButton(Yii::t('Backend', 'btnSave'), ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
							</div>
						</div>
						<p class="clearfix">&nbsp;</p>

					<?php ActiveForm::end(); ?> 

				</div>
			</div>
		</div>
	</div>
</div>