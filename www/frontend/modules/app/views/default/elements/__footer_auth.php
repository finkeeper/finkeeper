<!-- Bottom navigation -->
<div id="bottom-toolbar" class="bottom-toolbar">
	<div class="bottom-navigation">
		<div class="bottom-navigation-line">
			<div id="home_line" class="active-line"></div>
			<div id="as_line" class="default-line"></div>
			<div id="fr_line" class="default-line"></div>
		</div>
		<ul class="bottom-navigation__icons">
			<li class="mr-70 navigation_active" style="margin-top:4px">
				<a tabindex="0" role="button" href="javascript:void(0)" id="as-modal">
					<img src="/images/icons/assets_active.svg" alt="coins" class="bottom_button_nav_active"  style="display:none"/>
					<img src="/images/icons/assets.svg" alt="assets" class="bottom_button_nav_default" />
					<div class="text-center text-footer-nav"><?=Yii::t('Api', 'Assets')?></div>
				</a>
			</li>
			<li class="mr-70" style="margin-top:4px">
				<a tabindex="0" role="button" href="javascript:void(0)" id="au-modal">
					<img src="/images/icons/login_active.svg" alt="coins" class="bottom_button_nav_active"  style="display:none"/>
					<img src="/images/icons/login.svg" alt="assets" class="bottom_button_nav_default" />
					<div class="text-center text-footer-nav"><?=Yii::t('Api', 'Login')?></div>
				</a>
			</li>
			<li style="margin-top:4px">
				<a tabindex="0" role="button" href="javascript:void(0)" id="rg-modal">
					<img src="/images/icons/signup_active.svg" alt="coins" class="bottom_button_nav_active"  style="display:none"/>
					<img src="/images/icons/signup.svg" alt="assets" class="bottom_button_nav_default" />
					<div class="text-center text-footer-nav"><?=Yii::t('Api', 'Sign up')?></div>
				</a>
			</li>
		</ul>
	</div>	  
</div>