<?php 
use yii\helpers\Html;
use common\widgets\Alert;
use yii\bootstrap5\ActiveForm;

$form = ActiveForm::begin([
	'method' => 'get',
	'action' => ['/editors/translations/messages'],
]) ?>
	
	<div class="row">
		<div class="col-lg-12 text-right">

			<?=$form->field($model, 'translation_search', [])->textInput(['type'=>'text', 'placeholder'=>Yii::t('EditorsTranslations', 'Search Message')])->label(false) ?>

			<?= Html::input('submit', null, Yii::t('EditorsTranslations', 'Search'), ['class' => 'btn btn-primary m-4']) ?>

		</div>
	</div>
	
<?php ActiveForm::end() ?>