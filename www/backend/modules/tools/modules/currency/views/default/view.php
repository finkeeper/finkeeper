<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\bootstrap5\Modal;
use common\widgets\Alert;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use backend\modules\tools\modules\currency\assets\ModuleAsset;

ModuleAsset::register($this);

$this->title = Yii::t('Backend', 'Page Currency');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("
	jQuery(document).ready(function($) {
		
		$('#add-fiat').on('click', function() {

			var value = $(this).parents('.modal-body').find('#curr').val();

			$('#modal-fiat').modal('hide');
			
			if (!value) {
				
				$('#update-results').html('<div class=\"alert alert-danger mt-10\">".Yii::t('Backend', 'Missing currency')."</div>');
				
			} else {
			
				$.ajax({
				url: '/tools/currency/create',  
				type: 'POST',
				data: {value: value},
				success: function (response) {
					
					console.log(response);

					if (response) {
						try {
							data = JSON.parse(response); 
							if (data.error) {
													
								$('#update-results').html('<div class=\"alert alert-danger mt-10\">' + data.message + '</div>');
								setTimeout(function(){location.reload();}, 5000);

							} else {
											
								$('#update-results').html('<div class=\"alert alert-success mt-10\">".Yii::t('Backend', 'Success currency')."</div>');
			
							}
			
						} catch(e) {

							$('#update-results').html('<div class=\"alert alert-danger mt-10\">' + e + '</div>');
	
						}
					}								
				},
				error: function (err) {

					$('#update-results').html('<div class=\"alert alert-danger mt-10\">' + err + '</div>');

				},	
			});
			
			
			}
		});		
	});	
", yii\web\View::POS_END);
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12 col-lg-offset-0">
			<div class="site-exchange theme_content">
				<div class="table-responsive">
				
					<div class="button_container text-right">

						<?php Modal::begin([
							'title' => Yii::t('Backend', 'Add currency'),
							'size' => 'modal-lg',
							'id' => 'modal-fiat',
							'toggleButton' => [
								'label' => Yii::t('Backend', 'Add currency'),
								'class' => 'btn btn-info add-fiat',
							],
						]); ?>

							<div class="row">
							
								<?=Html::textarea('curr', '', [
									'id' => 'curr',
									'class' =>  'form-control',
									'style' => 'min-height:20%',
								])?>
							
								<p>&nbsp;</p>
							</div>
							
							<div class="row">
							
								<div class="text-left">
								
									<i class="fa fa-info-circle" aria-hidden="true" style="color:#37799f;font-size:16px"></i>&nbsp;
									<?=Yii::t('Backend', 'Fiat Instruction')?>
									
								</div>
								
								<p>&nbsp;</p>
							</div>

							<?=Html::Button(Yii::t('Backend', 'Add Fiat'),
								[
									'class' => 'btn btn-success',
									'id' => 'add-fiat',
								]
							)?>

						<?php Modal::end(); ?>
						
					</div>
					
					<div class="grid_container">
					
						<p>&nbsp;</p>
						<?=Alert::widget()?>
						
						<div id="update-results"></div>
						
						<?php Pjax::begin(['id' => 'exchange_list']); ?>

							<?= GridView::widget([
								'dataProvider' => $model->search(2),
								'id'=>'exchange_list',
								'tableOptions' => [
									'class' => 'table table-striped table-bordered',
								],
								'columns' => [
									['class' => 'yii\grid\SerialColumn'],

									[
										'label' => '',
										'encodeLabel' => false,
										'attribute' => 'image',
										'format' => 'raw',
										'value' => function($data){
											
											if (!empty($data->image)) {
											
												$path = getcwd().$data->image;
												if (file_exists($path)) {
		
													return Html::img($data->image, [
														'alt' => 'images',
														'style' => 'width:30px',
													]);
												}
												
												return '';
												
											} else {
												
												return '<i class="fas fa-chart-line" aria-hidden="true"></i>';
											}
										},
									],
									
									[
										'label' => Yii::t('Backend', 'Name Currency'),
										'encodeLabel' => false,
										'attribute' => 'name',
										'format' => 'raw',
										'value' => function($data){
											return $data->name;
										},
									],
									
									[
										'label' => Yii::t('Backend', 'Symbol Currency'),
										'encodeLabel' => false,
										'attribute' => 'symbol',
										'format' => 'raw',
										'value' => function($data){
											return strtoupper($data->symbol);
										},
									],
									
									[
										'label' => Yii::t('Backend', 'Price Currency'),
										'encodeLabel' => false,
										'attribute' => 'value',
										'format' => 'raw',
										'value' => function($data){
											return number_format($data->value, 2, '.', ' ');
										},
									],
									
									[
										'label' => Yii::t('Backend', 'Currency Currency'),
										'encodeLabel' => false,
										'attribute' => 'currency',
										'format' => 'raw',
										'value' => function($data){
											return $data->currency;
										},
									],
									
									[
										'label' => 'API',
										'encodeLabel' => false,
										'attribute' => 'api',
										'format' => 'raw',
										'value' => function($data){
											return $data->api;
										},
									],
									
									[
										'label' => 'ID API',
										'encodeLabel' => false,
										'attribute' => 'id_api',
										'format' => 'raw',
										'value' => function($data){
											return $data->id_api;
										},
									],

									[
										'label' => Yii::t('Backend', 'Date of change'),
										'encodeLabel' => false,
										'attribute' => 'date_of_change',
										'format' => 'raw',
										'value' => function($data){
											return $data->date_of_change;
										},
									],
									
									[
										'class' => 'yii\grid\ActionColumn',
										'template' => '{update}',
										'contentOptions' => ['class' => 'action-column'],
										'buttons' => [
											'update' => function ($url, $model) {

												return Html::a(
													'<i class="fas fa-edit color-blue" aria-hidden="true"></i>', 
													Url::toRoute(['/tools/currency/update', 'id'=>$model->id_crupto])
												);

											},
										],
									],

									[
										'class' => 'yii\grid\ActionColumn',
										'template' => '{delete}',
										'contentOptions' => ['class' => 'action-column'],
										'buttons' => [
											'delete' => function ($url, $model) {

												return Html::a(
													'<i class="fas fa-trash-alt color-red" aria-hidden="true"></i>', 
													Url::toRoute(['/tools/currency/delete', 'id'=>$model->id_crupto])
												);

											},
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
</div>