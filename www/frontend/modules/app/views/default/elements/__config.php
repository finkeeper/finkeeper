<?php

$this->registerJs('
	var log_id = '.$id.';
	var sc = "'.$sc.'";
	var username = "'.$username.'";
	var userpic = "'.$userpic.'";
	var lang = "'.$lang.'";
	var globe_num = 0;
	var exchange = \''.json_encode($currency).'\';
	var targets = \''.json_encode($targets).'\';
	var page_url = "'.$page_url.'";
	var count_recent = 10;
	var tonSummActive = 0;
	var bybitSummActive = 0;
	var okxSummActive = 0;
	var solSummActive = 0;
	var suiSummActive = 0;
	var aptSummActive = 0;
	var ethSummActive = 0;
	var btcSummActive = 0;
	var coinsSummActive = 0;
	var tonSummActiveCurrency = 0;
	var tonConnectedStatus='.$status['ton'].';
	var bybitConnectedStatus='.$status['bybit'].';
	var okxConnectedStatus='.$status['okx'].';
	var solConnectedStatus='.$status['sol'].';
	var suiConnectedStatus='.$status['sui'].';
	var aptConnectedStatus='.$status['apt'].';
	var ethConnectedStatus='.$status['eth'].';
	var btcConnectedStatus='.$status['btc'].';
	var grafema = "'.$grafema.'";

	var userActives={
		"grafema": "",
		"data": {
			"ton": {},
			"bybit":{},
			"okx":{},
			"sol":{},
			"sui":{},
			"apt":{},
			"eth":{},
			"btc":{},
		},
	};
	
	var userActivesMin = {
		"ton": {},
		"bybit":{},
		"okx":{},
		"sol":{},
		"sui":{},
		"apt":{},
		"eth":{},
		"btc":{},
	};
	
	var suiWallet = {
		type: "sui",
		address: "'.$wallet['sui']['address'].'",
		balance: '.$wallet['sui']['balance'].',
		price: '.$wallet['sui']['price'].',
		navi: '.$wallet['sui']['navi'].',
		rewards: '.$wallet['sui']['rewards'].',
		cyrrency: grafema,	
	};

	var storage_type = {
		button: "abcpbuttonsearch",
		recent: "abcpbuttonrecent",
		select: "abcpbuttonselect",
		status: "abcpbuttonstatus",
		settings1: "abcpbuttonsettings1",
		settings2: "abcpbuttonsettings2",
	}
', yii\web\View::POS_END);