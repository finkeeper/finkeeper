<?php
use yii\bootstrap5\Html;
?>

<div class="modal fade" id="frModal" tabindex="-1" aria-labelledby="frModalLabel" data-parent="">
	<div class="modal-dialog">
		<!-- Start friends -->
		<div id="wrap-friends">
			
			<div class="friends_title text-center">
				<?=Yii::t('Title', 'Invite friends & earn points')?>
			</div>	
			
			<div class="text_input mb-2">
				<div class="link_data">
					<a href="javascript:void(0)" class="copy_button">
						<?=Yii::t('Api', 'copy link')?>&nbsp;<img src="/images/icons/copy.svg" alt="" title="" />
					</a>
				</div>
				<div class="clearfix"></div>
					
				<?=Html::textInput('fr_link_1', '',[
					'autocomplete' => 'off', 
					'id' => 'fr-link-1',
					'class' =>  'form-currency',
					'type' => 'text',
				])?>	
					
			</div>
			
			<div class="text_awards mb-2 text-center">

				<?=Yii::t('Api', 'Your Points')?>: <b class="awards_friends">0</b>
				
			</div>

			<div id="text_faq">
				<div class="fr_start"><?=Yii::t('Api', 'FAQ')?>:</div>
				<div class="fr_line_1"></div>
				<div class="fr_title">
					<div class="fr_title_1">
						<?=Yii::t('Api', 'FriendsTitle1')?>
					</div>
					<div class="fr_title_2">
						<?=Yii::t('Api', 'FriendsTitle2')?>
					</div>
					<div class="fr_title_3">
						<?=Yii::t('Api', 'FriendsTitle3')?>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>

			<div id="referrals_username">
				
				<?=Yii::t('Title', 'Invited friends')?>: 0
				
				<div class="option_friends">
					<?=Yii::t('Api', 'Unlock Hints and earn Points by inviting your friends to join FinKeeper')?> :)
				</div>
			
			</div>
				
		</div>
		<!-- End friends -->
	</div>
</div>