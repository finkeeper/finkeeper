<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\bootstrap5\Modal;
use common\widgets\Alert;
use backend\modules\editors\modules\translations\TranslationsModule;

$this->title = Yii::t('EditorsTranslations', 'View Category');
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
						Yii::t('EditorsTranslations', 'Create Message'), 
						Url::toRoute(['/editors/translations/create']),
						[
							'class' => 'btn btn-primary',
						]
					)?>
				</div>
			
				<div class="grid_container">
					
					<?=Alert::widget()?>
				
					<?php Pjax::begin(['id' => 'category_list_pajax']); ?>

						<?= GridView::widget([
							'dataProvider' => $model->searchCategory(),
							'id'=>'category_list_grid',
							'pager' => [
								'class' => 'yii\bootstrap4\LinkPager'
							],
							'tableOptions' => [
								'class' => 'table table-striped table-bordered',
							],
							'columns' => [
								['class' => 'yii\grid\SerialColumn'],

								[
									'label' => '<small>'.Yii::t('EditorsTranslations', 'Category').'</small>',
									'encodeLabel' => false,
									'attribute' => 'category',
									'format' => 'raw',
									'value' => function($data){
										return $data->category;
									},
								],
								
								[
									'class' => 'yii\grid\ActionColumn',
									'template' => '{update}',
									'contentOptions' => ['class' => 'action-column'],
									'buttons' => [
										'update' => function ($url, $model) {

											return Html::a(
												'<i class="fas fa-pen" aria-hidden="true"></i>', 
												Url::toRoute(['/editors/translations/messages', 'Translations[category]'=>$model->category])
											);

										},
									],
								],
							],
						]); ?>
						
					<?php Pjax::end(); ?>
					
				</div>
				<div class="search_container">
					
					<?= $this->render('__search', [
						'model' => $model,
					]) ?>
				
				</div>
			</div>
		</div>
	</div>
</div>