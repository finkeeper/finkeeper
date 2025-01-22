<?php

use yii\helpers\Html;

?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
	<!-- Left navbar links -->
    <ul class="navbar-nav">
        
		<li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars" title="<?=Yii::t('Menu', 'Collapse sidebar')?>"></i></a>
        </li>
		
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?=\yii\helpers\Url::home()?>" class="nav-link"><i class="fa fa-home" aria-hidden="true" title="<?=Yii::t('Menu', 'Home')?>"></i></a>
        </li>
		
        <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle"><?=Yii::t('Menu', 'Editing')?></a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                <li><a href="/editors/translations/view" class="dropdown-item"><?=Yii::t('Menu', 'Translations')?></a></li>
                <li><a href="/editors/pages/view" class="dropdown-item"><?=Yii::t('Menu', 'Pages')?></a></li>
            </ul>
        </li>
		<li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle"><?=Yii::t('Menu', 'Statistics')?></a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                <li><a href="/statistics/yandex/update" class="dropdown-item"><?=Yii::t('Menu', 'Yandex Merica')?></a></li>
                <li><a href="/statistics/google/update" class="dropdown-item"><?=Yii::t('Menu', 'Google Tag Manager')?></a></li>
            </ul>
        </li>
		<li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle"><?=Yii::t('Menu', 'Tools')?></a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
               <li><a href="/tools/exchange/view" class="dropdown-item"><?=Yii::t('Menu', 'Exchange')?></a></li>
			    <li><a href="/tools/currency/view" class="dropdown-item"><?=Yii::t('Menu', 'Currency')?></a></li>
			   <li><a href="/tools/chatbot/view" class="dropdown-item"><?=Yii::t('Menu', 'Chatbots')?></a></li>
			   <li><a href="/tools/chatgpt/update" class="dropdown-item"><?=Yii::t('Menu', 'Chatgpt')?></a></li>
            </ul>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <?= Html::a('<i class="fas fa-sign-out-alt"></i>', ['/logout'], ['data-method' => 'post', 'class' => 'nav-link', 'title'=>Yii::t('Menu', 'Logout')]) ?>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button" title="<?=Yii::t('Menu', 'Fullscreen')?>">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->