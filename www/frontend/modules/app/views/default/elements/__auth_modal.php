<?php
use yii\bootstrap5\Html;
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
				</div>
			</div>
			
		</div>
		<!-- End auth -->
	</div>
</div>