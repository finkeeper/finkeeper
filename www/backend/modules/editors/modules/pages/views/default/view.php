<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use common\widgets\Alert;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use backend\modules\editors\modules\pages\PagesModule;

$this->title = Yii::t('Backend', 'Page Editor');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("
	jQuery(document).ready(function($) {

	});	
", yii\web\View::POS_END);
?>

<div class="container-fluid"> 
	<div class="row">
		<div class="col-lg-12 col-lg-offset-0">
			<div class="site-statistics theme_content">
				<div class="table-responsive">
					
					<?=Alert::widget()?>
					
					<?php Pjax::begin(['id' => 'statistics_list']); ?>

						<?= GridView::widget([
							'dataProvider' => $model->search(),
							'id'=>'statistics_list',
							'tableOptions' => [
								'class' => 'table table-striped table-bordered',
							],
							'columns' => [
								['class' => 'yii\grid\SerialColumn'],

								[
									'label' => '<small>'.Yii::t('Backend', 'Page Name').'</small>',
									'encodeLabel' => false,
									'attribute' => 'id_page',
									'format' => 'raw',
									'value' => function($data){
										return Yii::t('Backend', 'Page_'.$data->id_page);
									},
								],
								
								[
									'class' => 'yii\grid\ActionColumn',
									'template' => '{update}',
									'contentOptions' => ['class' => 'action-column'],
									'buttons' => [
										
									],
								],
							],
						]); ?>
						
					<?php Pjax::end(); ?>
				</div>
			</div>
		</div>
	</div>
</div>