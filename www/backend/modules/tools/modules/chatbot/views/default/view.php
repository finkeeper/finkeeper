<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\bootstrap5\Modal;
use common\widgets\Alert;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use backend\modules\tools\modules\chatbot\assets\ModuleAsset;

ModuleAsset::register($this);

$this->title = Yii::t('Backend', 'Page Chatbot');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("
	jQuery(document).ready(function($) {

	});	
", yii\web\View::POS_END);
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12 col-lg-offset-0">
			<div class="site-chatbot theme_content">
				<div class="table-responsive">
					
					<div class="button_container text-right">

						<?php Modal::begin([
							'title' => Yii::t('Backend', 'Add chatbot'),
							'size' => 'modal-lg',
							'id' => 'modal-chatbot',
							'toggleButton' => [
								'label' => Yii::t('Backend', 'Add chatbot'),
								'class' => 'btn btn-info add-chatbot',
							],
						]); ?>

							<div class="modal-row">	
							
								<?=Html::dropDownList('bot_type', '', [
									0 => Yii::t('Backend', 'Bot Type'),
									1 => Yii::t('Backend', 'Telegram'),
								], [
									'id' => 'bot_type',
									'class' =>  'form-control',
								])?>
							
							
							</div>
							
							<div class="modal-row">
							
								<?=Html::textInput('bot_name', '',[
									'autocomplete' => 'off', 
									'id' => 'bot_name',
									'class' =>  'form-control',
									'placeholder' => Yii::t('Backend', 'Bot Name'),
								])?>

							</div>
							
							<div class="modal-row">

								<?=Html::textarea('bot_desription', '', [
									'id' => 'bot_desription',
									'class' =>  'form-control',
									'placeholder' => Yii::t('Backend', 'Bot Description'),
								])?>
								
							</div>
							
							<div class="modal-row">
							
								<?=Html::textInput('bot_url', '', [
									'autocomplete' => 'off', 
									'id' => 'bot_url',
									'class' =>  'form-control',
									'placeholder' => Yii::t('Backend', 'Bot URL'),
								])?>
							
							</div>
							
							<div class="modal-row">
							
								<?=Html::textInput('bot_token', '', [
									'autocomplete' => 'off', 
									'id' => 'bot_token',
									'class' =>  'form-control',
									'placeholder' => Yii::t('Backend', 'Bot Token'),
								])?>
							
							</div>
							
							<div class="modal-row text-left">
							
								<?=Html::checkbox('show_menu', '', [
									'id' => 'show_menu',
									'class' =>  '',
									'label' => Yii::t('Backend', 'Bot Show Menu'),
								])?>
							
								<p>&nbsp;</p>
							</div>
							
							<div class="modal-row text-left">
								<i class="fa fa-info-circle" aria-hidden="true" style="color:#37799f;font-size:16px"></i>&nbsp;<span><?=Yii::t('Backend', 'Chatbot Instruction')?></span>

								<p>&nbsp;</p>
							</div>

							<?=Html::Button(Yii::t('Backend', 'Add Button'),
								[
									'class' => 'btn btn-success',
									'id' => 'add-chatbot',
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
								'dataProvider' => $model->search(),
								'id'=>'chatbot_list',
								'tableOptions' => [
									'class' => 'table table-striped table-bordered',
								],
								'columns' => [
									['class' => 'yii\grid\SerialColumn'],

									[
										'label' => '',
										'encodeLabel' => false,
										'attribute' => 'bot_type',
										'format' => 'raw',
										'value' => function($data){
											
											if ($data->bot_type==1) {
											
												return Yii::t('Backend', 'Telegram');
												
											} else {
												
												return '';
											}
										},
									],
									
									[
										'label' => Yii::t('Backend', 'Bot Name'),
										'encodeLabel' => false,
										'attribute' => 'bot_name',
										'format' => 'raw',
										'value' => function($data){
											
											return $data->bot_name;
											
										},
									],
									
									[
										'label' => Yii::t('Backend', 'Show Menu'),
										'encodeLabel' => false,
										'attribute' => 'show_menu',
										'format' => 'raw',
										'value' => function($data){
											
											if (!empty($data->show_menu)) {
											
												return '<i class="fa fa-power-off" aria-hidden="true" style="color:green"></i>';
												
											} else {
												
												return '<i class="fa fa-power-off" aria-hidden="true" style="color:red"></i>';
											}
										},
									],
									
									[
										'class' => 'yii\grid\ActionColumn',
										'template' => '{update}',
										'contentOptions' => ['class' => 'action-column'],
										'buttons' => [
											'update' => function ($url, $model) {

												return Html::a(
													'<i class="fas fa-pencil-alt color-red" aria-hidden="true"></i>', 
													//Url::toRoute(['/tools/chatbot/update', 'id'=>$model->id_bot])
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
													Url::toRoute(['/tools/chatbot/delete', 'id'=>$model->id_bot])
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