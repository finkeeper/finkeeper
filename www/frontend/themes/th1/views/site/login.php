<?php
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use common\widgets\Alert;
use yii\captcha\Captcha;

$this->title = Yii::t('Title', 'Login');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs('
	jQuery(document).ready(function($) {
		$("#show-password").on("click", function() {
			var type_input = $("#loginform-password").attr("type");
			if (typeof type_input!=="undefined" && type_input!==undefined && type_input=="text") {
				$("#show-password").find("i").removeClass("fa-eye-slash").addClass("fa-eye");
				$("#loginform-password").attr("type", "password")
			} else {
				$("#show-password").find("i").removeClass("fa-eye").addClass("fa-eye-slash");
				$("#loginform-password").attr("type", "text")
			}
		});
	});
', yii\web\View::POS_END);

$this->registerCss('
	.passwd-input {
		width: calc(100% - 30px);
		float:left;
	}
	.passwd-button {
		width:30px;
		height:51px;
		padding:0;
		float:right;
	}
	.passwd-show-button	{
		padding:0;
		margin:0;
	}
	.passwd-show-button .fa	{
		font-size:14px;
	}
	.passwd-generate-button,
	.passwd-show-button button {
		height:22px;
		width:22px;
	}
');
?>

<?= $this->render(
	'@app/themes/th1/views/site/elements/__header_2.php'
) ?>

<!-- Hero Start -->
<section class="content position-relative">
	<div class="site-login">
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

								<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
							
									<h5 class="mb-4"><?=Yii::t('Menu', 'Login')?></h5>
									
									<?=Alert::widget()?>
									
									<div class="row">
										<div class="col-lg-12">
	
											<?=$form->field($model, 'login', [
												'template' => '<div class="form-floating mb-2">{input}{error}<label for="loginform-login">'.Yii::t('Form', 'EmailAddress').':</label><div class="has-feedback"><span class="glyphicon form-control-feedback"></span></div></div>'
											])->textInput(['type'=>'email', 'placeholder'=>'name@example.com', 'autocomplete' => 'off', 'autofocus' => true]) ?>
											
										</div><!--end col-->

										<div class="row">
											<div class="passwd-input">

												<?= $form->field($model, 'password', [
													'template' => '<div class="form-floating mb-3">{input}<label for="loginform-password">'.Yii::t('Form', 'Password').':</label><div class="has-feedback"><span class="glyphicon form-control-feedback"></span></div></div>'
												])->passwordInput(['placeholder'=>Yii::t('Form', 'Password'), 'autocomplete' => 'off']) ?>
											
											</div>
											<div  class="passwd-button">
												<div class="passwd-generate-button"></div>
												<div class="passwd-show-button">
													<button id="show-password" class="btn btn-info" type="button" title="<?=Yii::t('Frontend', 'Show password')?>"><i class="fa fa-eye" aria-hidden="true"></i></button>
												</div>
											</div>
	
										</div><!--end col-->
								
										<div class="col-lg-12">
											<div class="d-flex justify-content-between">
												<div class="mb-3">
													
													<?=$form->field($model, 'rememberMe')->checkbox([
														'template' => '<div class="form-check align-items-center d-flex mb-0">{input}<label class="form-check-label text-muted ms-2" for="loginform-rememberme">'.Yii::t('Form', 'Remember me').'</label>{error}<div class="has-feedback"><div class="checkbox"><span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span><span class="glyphicon form-control-feedback"></span></div></div></div>',
														'checked' => true,
													])?>

												</div>
												<small class="text-muted mb-0"><a href="/passwordreset" class="text-muted fw-semibold"><?=Yii::t('Form', 'Forgot password')?> ?</a></small>
											</div>
										</div><!--end col-->
										
										<div class="col-lg-12">
										
											<?=$form->field($model, 'verifyCode')->widget(\himiklab\yii2\recaptcha\ReCaptcha3::className())->label(false)?>
										
										</div><!--end col-->
					
										<div class="col-lg-12">
											
											<?= Html::submitButton(Yii::t('Form', 'Sign in'), ['class' => 'btn btn-primary rounded-md w-100', 'name' => 'login-button']) ?>

										</div><!--end col-->

										<div class="col-12 text-center mt-4">
											<small><span class="text-muted me-2"><?=Yii::t('Form', 'Don\'t have an account')?> ?</span> <a href="/signup" class="text-dark fw-bold"><?=Yii::t('Form', 'Sign up')?></a></small>
										</div><!--end col-->
									</div><!--end row-->

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


