<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use common\widgets\Alert;
use yii\captcha\Captcha;

$this->title = Yii::t('Title', 'Password Reset Confirm');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs('
	var date = "'.$model->reset_token_expire.'";
	var current = "'.time().'";
	
	function str_rand() {
		var result = "";
		var words = "0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
		var max_position = words.length - 1;
		
		for( i = 0; i < 10; ++i ) {
			position = Math.floor ( Math.random() * max_position );
			result = result + words.substring(position, position + 1);
		}
		
		return result;
	}
	
	function getTimer() {
		current++;
		var sec = date - current;
		if ((typeof sec==="string" || typeof sec==="number") && !isNaN(sec - 0) && sec!=="" && sec>0) {
			$("#timer").addClass("alert alert-success").html("Осталось секунд: " + sec + "");
		} else {
			$("#timer").addClass("alert alert-danger").html("Время действия кода подтверждения истекло");
		}

		setTimeout("getTimer()", 1000);
	}
	
	jQuery(document).ready(function($) {
		$("#generate-password").on("click", function() {
			$("#passwordresetconfirm-password, #passwordresetconfirm-confirm_password").attr("type", "text").val(str_rand());
			$("#show-password").find("i").removeClass("fa-eye").addClass("fa-eye-slash");
		});
		
		$("#show-password").on("click", function() {
			var type_input = $("#passwordresetconfirm-password").attr("type");
			if (typeof type_input!=="undefined" && type_input!==undefined && type_input=="text") {
				$("#show-password").find("i").removeClass("fa-eye-slash").addClass("fa-eye");
				$("#passwordresetconfirm-password, #passwordresetconfirm-confirm_password").attr("type", "password")
			} else {
				$("#show-password").find("i").removeClass("fa-eye").addClass("fa-eye-slash");
				$("#passwordresetconfirm-password, #passwordresetconfirm-confirm_password").attr("type", "text");
			}
		});

		setTimeout("getTimer()", 1000);
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
	.passwd-generate-button,
	.passwd-show-button	{
		padding:0;
		margin:0;
	}
	.passwd-generate-button .fa,
	.passwd-show-button .fa	{
		font-size:14px;
	}
	.passwd-generate-button button,
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
	<div class="site-reset-confirm">
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

								<?php $form = ActiveForm::begin(['id' => 'signup-form']); ?>
							
									<h5 class="mb-4"><?=Yii::t('Title', 'Password Reset Confirm');?></h5>
									
									<?=Alert::widget()?>
									
									<div class="col-12 text-center mt-4">
										<div id="timer"></div>
									</div><!--end col-->
									
									<div class="row">
										<div class="row">
											<div class="passwd-input">
												
												<?= $form->field($model, 'password', [
													'template' => '<div class="form-floating mb-3">{input}{error}<label for="passwordresetconfirm-password">'.Yii::t('Form', 'Password').':</label><div class="has-feedback"><span class="glyphicon form-control-feedback"></span></div></div>'
												])->passwordInput(['placeholder'=>Yii::t('Form', 'Password'), 'autocomplete' => 'off', 'autofocus' => true]) ?>
					
											</div>
											<div  class="passwd-button">
												<div class="passwd-generate-button">
													<button id="generate-password" class="btn btn-warning" type="button" title="<?=Yii::t('Frontend', 'Generate password')?>"><i class="fa fa-key" aria-hidden="true"></i></button>
												</div>
												<div class="passwd-show-button">
													<button id="show-password" class="btn btn-info" type="button" title="<?=Yii::t('Frontend', 'Show password')?>"><i class="fa fa-eye" aria-hidden="true"></i></button>
												</div>
											</div>
										</div><!--end col-->
										
										<div class="col-lg-12">
										
											<?= $form->field($model, 'confirm_password', [
												'template' => '<div class="form-floating mb-3">{input}{error}<label for="passwordresetconfirm-confirm_password">'.Yii::t('Form', 'Password Confirm Form').':</label><div class="has-feedback"><span class="glyphicon form-control-feedback"></span></div></div>'
											])->textInput(['placeholder'=>Yii::t('Form', 'Password Confirm Form'), 'autocomplete' => 'off', 'type'=>'password']) ?>
										
										</div><!--end col-->
										
										<div class="col-lg-12">
										
											<?=$form->field($model, 'verifyCode')->widget(\himiklab\yii2\recaptcha\ReCaptcha3::className())->label(false)?>
										
										</div><!--end col-->
				
										<div class="col-lg-12">
											
											<?= Html::submitButton(Yii::t('Form', 'btn Save Passwd'), ['class' => 'btn btn-primary rounded-md w-100', 'name' => 'password-button']) ?>

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





