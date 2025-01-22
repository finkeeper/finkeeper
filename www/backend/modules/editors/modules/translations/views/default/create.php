<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap5\Modal;
use common\widgets\Alert;
use backend\modules\editors\modules\translations\TranslationsModule;

$this->title = Yii::t('EditorsTranslations', 'Create Messages');
$this->params['breadcrumbs'][] = [
	'label' => ' | '.$this->title,
];

?>

<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12 col-lg-offset-0">
			<div class="site-translations-editor theme_content">
			
				<div class="button_container text-right">
					<?=Html::a(
						Yii::t('EditorsTranslations', 'Return'), 
						Url::toRoute(['/editors/translations/view']),
						[
							'class' => 'btn btn-default',
						]
					)?>
				</div>
				
				<?= $this->render('__form', [
					'model' => $model,
				]) ?>
				
			</div>
		</div>
	</div>
</div>