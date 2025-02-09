<!-- Bottom navigation -->
<div class="bottom-navigation">
	<div class="bottom-navigation-line">
		<div id="home_line" class="active-line"></div>
		<div id="as_line" class="default-line"></div>
		<div id="fr_line" class="default-line"></div>
	</div>
	<ul class="bottom-navigation__icons">
		<li class="mr-70 navigation_active">
			<a tabindex="0" role="button" href="javascript:void(0)" id="close-all-modal">
				<img src="/images/icons/coins2_active.svg" alt="coins" class="bottom_button_nav_active" />
				<img src="/images/icons/coins2.svg" alt="coins" class="bottom_button_nav_default" style="display:none"/>
				<div class="text-center text-footer-nav"><?=Yii::t('Api', 'Coins')?></div>
			</a>
		</li>
		<li class="mr-70">
			<a tabindex="0" role="button" href="javascript:void(0)" id="as-modal">
				<img src="/images/icons/assets_active.svg" alt="coins" class="bottom_button_nav_active"  style="display:none"/>
				<img src="/images/icons/assets.svg" alt="assets" class="bottom_button_nav_default" />
				<div class="text-center text-footer-nav"><?=Yii::t('Api', 'Assets')?></div>
			</a>
		</li>
		<li>
			<a tabindex="0" role="button" href="javascript:void(0)" id="fr-modal">
				<img src="/images/icons/friends_active.svg" alt="coins" class="bottom_button_nav_active"  style="display:none"/>
				<img src="/images/icons/friends.svg" alt="friends" class="bottom_button_nav_default" />
				<div class="text-center text-footer-nav"><?=Yii::t('Api', 'Friends')?></div>
			</a>
		</li>
	</ul>
</div>	  