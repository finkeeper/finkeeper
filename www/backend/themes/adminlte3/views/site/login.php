<?php
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
?>
<div class="card">
    <div class="card-body login-card-body">

        <?php $form = ActiveForm::begin(['id' => 'login-form']) ?>

			<?= $form->field($model,'login', [
				'options' => ['class' => 'form-group has-feedback'],
				'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-envelope"></span></div></div>',
				'template' => '{beginWrapper}{input}{error}{endWrapper}',
				'wrapperOptions' => ['class' => 'input-group mb-3']
			])
				->label(false)
				->textInput(['placeholder' => $model->getAttributeLabel(Yii::t('Menu', 'login')), 'autofocus' => true]) ?>

			<?= $form->field($model, 'password', [
				'options' => ['class' => 'form-group has-feedback'],
				'inputTemplate' => '{input}<div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>',
				'template' => '{beginWrapper}{input}{error}{endWrapper}',
				'wrapperOptions' => ['class' => 'input-group mb-3']
			])
				->label(false)
				->passwordInput(['placeholder' => $model->getAttributeLabel(Yii::t('Menu', 'Password'))]) ?>

			<div class="row">
				<div class="col-8">
					<?= $form->field($model, 'rememberMe')->checkbox([
						'template' => '<div class="icheck-primary">{input}{label}</div>',
						'labelOptions' => [
							'class' => ''
						],
						'uncheck' => null
					])
						->label(Yii::t('Form', 'Remember me')) ?>
				</div>
				
				<div class="col-4">
					<?= Html::submitButton(Yii::t('Form', 'Sign in'), ['class' => 'btn btn-primary btn-block']) ?>
				</div>
			</div>

        <?php ActiveForm::end(); ?>

		
		<?php /*
        <p class="mb-1">
            <a href="/resetpassword"><?=Yii::t('Form', 'Forgot password')?></a>
        </p>
		*/ ?>
    </div>
    <!-- /.login-card-body -->
</div>