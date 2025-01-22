<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use common\widgets\Alert;
use yii\captcha\Captcha;

$this->title = Yii::t('Title', 'Password Reset');
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('
	.warning-link {
		font-weight:bold;
		font-size: 16px;
	}
');
?>

<?= $this->render(
	'@app/themes/th1/views/site/elements/__header_2.php'
) ?>

<!-- Hero Start -->
<section class="content position-relative">
	<div class="site-reset">
		<div class="bg-overlay bg-linear-gradient-2"></div>
		<div class="container-fluid">
			<div class="row">
				<div class="col-12 p-0">
					<div class="d-flex flex-column min-vh-100 p-4">
						<!-- Start Logo -->
						<div class="text-center">
							<a href="/"><img src="/images/logo/logo-dark.png" alt=""></a>
						</div>
						<!-- End Logo --> 

						<!-- Start Content -->
						<div class="title-heading text-center my-auto">
							<div class="form-signin px-4 py-5 bg-white rounded-md shadow-sm">
	
								<?php $form = ActiveForm::begin(['id' => 'reset-form']); ?>
								
									<h5 class="mb-4"><?=Yii::t('Title', 'Password Reset')?></h5>
						
									<p class="text-muted"><?=Yii::t('Frontend', 'Help reset password')?></p>
									
									<?=Alert::widget()?>
	
									<div class="row">
										<div class="col-lg-12">
								
											<?=$form->field($model, 'login', [
												'template' => '<div class="form-floating mb-2">{input}{error}<label for="signupform-login">'.Yii::t('Form', 'EmailAddress').':</label><div class="has-feedback"><span class="glyphicon form-control-feedback"></span></div></div>',
												'errorOptions' => ['encode' => false],
											])->textInput(['type'=>'email', 'placeholder'=>'name@example.com', 'autocomplete' => 'off']) ?>
											
										</div><!--end col-->
										
										<div class="col-lg-12">
										
											<?=$form->field($model, 'verifyCode')->widget(\himiklab\yii2\recaptcha\ReCaptcha3::className())->label(false)?>
										
										</div><!--end col-->

										
										<div class="col-lg-12">
											
											<?= Html::submitButton(Yii::t('Frontend', 'Send'), ['class' => 'btn btn-primary rounded-md w-100', 'name' => 'login-button']) ?>

										</div><!--end col-->

									</div>
	
								 <?php ActiveForm::end(); ?>
	
							</div>
						</div>
						<!-- End Content -->
						<!-- Start Footer -->
						<?= $this->render(
							'@app/themes/th1/views/site/elements/__footer_2.php'
						) ?>
						<!-- End Footer -->
					</div>
				</div><!--end col-->
			</div><!--end row-->
		</div><!--end container-->
	</div>
</section><!--end section-->
<!-- Hero End -->

