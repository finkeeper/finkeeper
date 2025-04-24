<?php
use yii\bootstrap5\Html;
use frontend\modules\app\components\GoogleApi;

$google_data = GoogleApi::pstatic()->getAuthData();
?>

<div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" data-parent="">
	<div class="modal-dialog">
		<!-- Start auth -->
		<div id="wrap-auth-modal">
		
			<div class="modal-content">
				<div class="modal-header">
					<div class="modal-title"><?=Yii::t('Api', 'Verify with')?></div>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="items-list-option">
	
						<div id="fk-telegram-app" class="item-option">
							<div class="side-option">
								<div class="icon-option">
									<i class="mdi mdi-application"></i>
								</div>
								<div class="item-title"><?=Yii::t('Api', 'FinKeeper Telegram App')?></div>
							</div>
							<div class="icon-option">
								<i class="mdi mdi-chevron-right"></i>
							</div>
						</div>
						
					</div>

					<?php if (!empty($google_data) && empty($google_data['error'])) { ?>
		
						<div class="items-list-option">
							
							<div id="fk-google-oauth" class="item-option" data-bs-toggle="modal" data-bs-target="#googleModal">
								<div class="side-option">

									<div class="icon-option">
										<i class="mdi mdi-google"></i>
									</div>
									<div class="item-title">
										<?=Yii::t('Api', 'Sign in with Google')?>
									</div>
			
								</div>
								<div class="icon-option">
									<i class="mdi mdi-chevron-right"></i>
								</div>
							</div>

						</div>

					<?php } ?>
				</div>
			</div>
			
		</div>
		<!-- End auth -->
	</div>
</div>

<div class="modal fade" id="googleModal" tabindex="-1" aria-labelledby="googleModalLabel">
	<div class="modal-dialog modal-sm">
		<!-- Start auth -->
		<div id="wrap-google-modal">
			
			<div class="modal-content">
				<div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					 <div id="g_id_onload"
                         data-client_id="<?=$google_data['data']['client_id']?>"
                         data-callback="handleCredentialResponse">
                    </div>
                    <div style="width:100%" class="g_id_signin my-3 float-start"
						data-width="280"
						data-type="standard"
                        data-size="large"
                        data-theme="outline"
                        data-text="sign_in_with"
                        data-shape="rectangular"
                        data-logo_alignment="left">
                    </div>

                </div>
			</div>
		</div>
	</div>
</div>