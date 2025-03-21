<?php 

$this->registerCss("
	#as2-chat-active-refresh,
	#as2-rewiew-wallet,
	#as2-wallet-copy {
		cursor:pointer;
	}
	.as2-title-chat {
		font-size:24px;
		margin-bottom:14px;
	}
	#as2-btn-chat-rewards,
    #as2-btn-chat-deposit,
	#as2-btn-chat-deposit-2,
	#as2-btn-chat-withdraw,
	#as2-btn-chat-withdraw-2,
	.btn-as2button {
		margin-bottom:14px;
		background:#47e7ce;
		color:#000;
		border:2px solid #ffffff;
		border-radius:12px;
		font-size:14px;
	}
	.btn-as2button:hover {
		border:2px solid #ffffff;
	}
	#as2-question-addon-chat {
		color:#ccc;
		font-size:24px;
		cursor:pointer;
		margin-top:10px;
		float:right;
	}
	.as2_question_popover_chat {
		max-width: 30%;
	}
	@media (max-width: 1400px) {
		.as2_question_popover_chat {
			max-width: 50%;
		}
	}
	@media (max-width: 700px) {
		.as2_question_popover_chat {
			max-width: 80%;
		}
	}
	#as2-chat-active-send-loader,
	#as2-chat-active-send {
		background:#47e7ce;
		font-weight:18;
		height:48px;
		width:50px;
		padding:0 10px 0 10px;
		cursor:pointer;
	}
	#as2-chat-active-send-loader .far {
		font-size:24px;
	}
	#as2-chat-active-send .mdi {
		font-size:30px;
	}
	.as2-form-currency-chat {
		outline:none !important;
		box-shadow:none !important;
		border:0 !important;
		color:#ffffff !important;
		background:#3e3567 !important;
		font-size:18px;
		font-weight:normal !important;
		border-radius:8px;
		padding:0 10px 0 10px !important;
		height:48px !important;
		z-index:9999;
	}
	.speech-bubble .as2-form-currency-chat {
		height:34px !important;
	}
	.as2-form-currency-chat:focus {
		border:0 !important;
		font-weight:normal !important;
	}
	.as2-form-currency-chat::placeholder {
	  color:#8b86a4;
	  opacity: 1; 
	  font-weight:normal !important;
	  padding-right:4px;
	}
	.as2-form-currency-chat::-ms-input-placeholder {
	  color:#8b86a4;
	  opacity: 1; 
	  font-weight:normal !important;
	  padding-right:4px;
	}
	.as2-text_chat_input {
		position:relative;
		width:calc(100% - 60px);
		float:left;
		overflow:hidden;
	}
	.as2-text_chat_send {
		position:relative;
		width:50px;
		float:right;
		overflow:hidden;
		border:2px solid #ffffff;
		border-radius:12px;
	}
	#as2-chat-form-as {
		width:100%;
		height:calc(100vh - 100px);
		background:#3e3567;
		border-radius:10px;
		padding:15px 15px 40px 15px;
		overflow-y:auto;	
	}
	#as2-chat-form-as .sl-item {
		position: relative;
		padding-bottom: 12px;
	}
	#as2-chat-form-as .p-b-md {
		padding-bottom: 16px !important;
	}
	#as2-chat-form-as .avatar {
		width: 30px;
		height: 30px;
		vertical-align: middle;
		border-radius: 50%;
		position: absolute;
		left: 0px;
		top: 0px;
		display: inline-block;
		transition: all .5s ease;
		font-size:20px;
		padding:3px 0 0 0;
		color:#000;
	}
	#as2-chat-form-as .avatar img {
		 border-radius: 50%;
	}
	#as2-chat-form-as .m-l-sm {
		margin-left: 34px !important;
	}
	#as2-chat-form-as h5.m-t-0 {
		margin-bottom: 3px;
		padding-top:10px;
	}
	#as2-chat-form-as .m-t-0 {
		margin-top: 0 !important;
	}
	#as2-chat-form-as .m-r-xs {
		margin-right: 6px !important;
		font-size:14px;
	}
	#as2-chat-form-as .speech-bubble {    
		border-radius: 10px;
		padding: 15px;
		position: relative;
		background: #040217;
		word-wrap: break-word;
		margin-left:-34px;
		font-size:18px;
		color:#fff;
		line-height: 1.4;
	}
	#as2-chat-form-as .speech-bubble::before, 
	#as2-chat-form-as .speech-bubble::after {
		content: '';
		position: absolute;
		left: 20px;
		top: -20px;
		border: 10px solid transparent;
		border-bottom: 10px solid #040217;
	}
	#as2-chat-form-as .date-message {
		padding-top: 4px;
		font-style: italic;
		font-size: 12px;
		font-weight: bold;
	}
	.as2-currency_button {
		color: #ffffff;
		padding: 8px;
		border: 2px solid #3E3567;
		border-radius: 12px;
		cursor: pointer;
		display: table;
		float: left;
		font-size: 16px;
		background-color: #3E3567;
	}
	#as2-chat-form-as .ps-scrollbar-x-rail,
	#as2-chat-form-as .ps-scrollbar-y-rail {
		display:none;
	}
	#as2-chat-form-as .as2-transfer-ok,
	#as2-chat-form-as .as2-transfer-cancel,
	#as2-chat-form-as .as2-transfer-ok-2,
	#as2-chat-form-as .as2-withdraw-ok-2,
	#as2-chat-form-as .as2-rewards-cancel,
	#as2-chat-form-as .as2-rewards-ok,
    #as2-chat-form-as .as2-aptospools-ok	{
		font-size:16px;
	}	
	#as2-chat-form-as .speech-bubble a {
		font-size:14px;
	}
	#as2-chat-form-as .speech-bubble .mdi-clock-outline {
		font-size:22px;
		color:#f9b16d;
	}
	#as2-chat-form-as .speech-bubble .mdi-check-circle-outline {
		font-size:22px;
		color:#a8f759;
	}
	#as2-chat-form-as .speech-bubble .mdi-close-octagon {
		font-size:24px;
		color:#ffaaaa;
	}
	#smart-toy-aiagent {
		margin-bottom:-7px;
	}
	#as2-chat-form-as .launchpools-title-exchange {
		margin-top:10px;
	}
	#as2-chat-form-as .launchpools-title-exchange>a {
		float:left;
		display:block;
	}
	#as2-chat-form-as .launchpools-title-exchange img {
		width:30px;
	}	
	#as2-chat-form-as .launchpools-title-exchange>div {
		float:left;
		height:36px;
		padding-top:4px;
	}
	#as2-chat-form-as .launchpools-title-exchange>div>a {
		color:#47e7ce;
		font-size:100%;
	}
	#as2-chat-form-as .launchpools-pools-exchange {
		margin-top:8px;
		border:1px solid #fff;
		border-radius:8px;
		padding:10px;
	}
	#as2-chat-form-as .launchpools-pools-exchange .launchpools-pools-exchange-icon {
		float:left;
		width:30px;
		height:30px;
	}
	#as2-chat-form-as .launchpools-pools-exchange .launchpools-pools-exchange-icon img {
		width:30px;
	}
	#as2-chat-form-as .launchpools-pools-exchange .launchpools-pools-exchange-coin {
		float:left;
		width:calc(100% - 30px);
		padding-top:4px;
	}
	#as2-chat-form-as .launchpools-pools-exchange-date .launchpools-pools-exchange-date-left-block {
		float:left;
		width:150px;
	}
	#as2-chat-form-as .launchpools-pools-exchange-date .launchpools-pools-exchange-date-right-block {
		float:left;
		width:calc(100% - 150px);
	}
	#as2-chat-form-as .launchpools-pools-exchange-coins {
		margin-top:8px;
	}
	@media (min-width: 768px) {
		#as2-chat-form-as .launchpools-pools-exchange-coins .ascol {
			border-right:1px solid #fff;
			border-top:1px solid transparent;
		}
	}
	@media (max-width: 767px) {
		#as2-chat-form-as .launchpools-pools-exchange-coins .ascol {
			border-right:1px solid transparent !important;
			border-top:1px solid #fff;
		}
	}
	#as2-chat-form-as .launchpools-portfolio {
		margin-top:8px;
		border-top:1px solid #fff;
		padding-top:4px;
	}
	#as2-chat-form-as .launchpools-portfolio .yes_enough {
		color:#adf7ad;
	}
	#as2-chat-form-as .launchpools-portfolio .not_enough {
		color:#fcc2c2;
	}
	#as2-chat-form-as .aptospools-pools {
		border:1px solid #fff;
		padding:10px;
		border-radius:8px;
	}
	#as2-chat-form-as .aptospools-pools .row {
		border-bottom:1px solid #fff;
		padding:10px;
	}
	#as2-chat-form-as .aptospools-pools .row>div>img {
		width:16px;
	}
	#as2-chat-form-as .aptospools-pools .row>div>a {
		font-size:100%;
	}
	#as2-chat-form-as .aptospools-pools .brd-b {
		border-bottom:1px solid #fff;
	}
	#as2-chat-form-as .aptospools-portfolio {
		padding-top:8px;
	}
	@media (min-width: 768px) {
		#as2-chat-form-as .aptospools-pools .brd-r {
			border-right:1px solid #fff;
		}
	}
	@media (max-width: 767px) {
		#as2-chat-form-as .aptospools-pools .brd-r {
			border-right:1px solid transparent !important;
		}
	}
	
	#as2-chat-form-as .aptospools-title-wallets img {
		width:30px;
		float:left;
	}	
	#as2-chat-form-as .aptospools-title-wallets>div {
		float:left;
		height:36px;
		padding-top:4px;
	}
	#as2-chat-form-as .aptos-pools-provider {
		font-weight:normal;
		font-size:14px;
	}
	#as2-chat-form-as .block-datetime {
		font-size:14px;
		opacity:0.5;
		padding-top:10px;
	}
	
", ['id'=>'as2-chat']);

$this->registerJs('

	class Chatai {

		constructor() {
			this.maxMessage = 10;
			this.storage = "as2chat";
			this.chat = "";
			this.form = "";
			this.userpic = "";
			this.username = "'.Yii::t('Api', 'You').'";
			this.apppic = "";
			this.appname = "'.Yii::t('Api', 'App').'";
			this.id = 0;
			this.sc = "";
			this.portfolio = "";
			this.wallet = {
				type: "",
				address: "",
				balance: 0,
				price: 0,
				currency: "",
				navi: 0,
				rewards: 0,
			};
			this.app = new appFinkeeper();
			this.showBalance = false;
			this.showDeposit = false;
		}

		/**
		 * getChat(options)
		 */
		getChat(options) {
			
			if (typeof options!=="undefined" && options!==undefined && options) {
				
				if (typeof options.username!=="undefined" && options.username!==undefined && options.username) {
					this.username = options.username;
				}
				
				if (typeof options.userpic!=="undefined" && options.userpic!==undefined && options.userpic) {
					this.userpic = options.userpic;
				}
				
				if (typeof options.appname!=="undefined" && options.appname!==undefined && options.appname) {
					this.appname = options.appname;
				}
				
				if (typeof options.apppic!=="undefined" && options.apppic!==undefined && options.apppic) {
					this.apppic = options.apppic;
				}
				
				if (typeof options.elementChat!=="undefined" && options.elementChat!==undefined && options.elementChat) {
					this.chat = options.elementChat;
				}
				
				if (typeof options.elementForm!=="undefined" && options.elementForm!==undefined && options.elementForm) {
					this.form = options.elementForm;
				}
				
				if (typeof options.id!=="undefined" && options.id!==undefined && options.id) {
					this.id = options.id;
				}
				
				if (typeof options.sc!=="undefined" && options.sc!==undefined && options.sc) {
					this.sc = options.sc;
				}
				
				if (typeof options.portfolio!=="undefined" && options.portfolio!==undefined && options.portfolio) {
					this.portfolio = options.portfolio;
				}
	
				if (typeof options.wallet!=="undefined" && options.wallet!==undefined && options.wallet) {
					
					if (typeof options.wallet.type!=="undefined" && options.wallet.type!==undefined && options.wallet.type) {
						this.wallet.type = options.wallet.type;
					}
					
					if (typeof options.wallet.address!=="undefined" && options.wallet.address!==undefined && options.wallet.address) {
						this.wallet.address = options.wallet.address;
					}
					
					if (typeof options.wallet.balance!=="undefined" && options.wallet.balance!==undefined && options.wallet.balance) {
						this.wallet.balance = options.wallet.balance;
					}
					
					if (typeof options.wallet.price!=="undefined" && options.wallet.price!==undefined && options.wallet.price) {
						this.wallet.price = options.wallet.price;
					}
					
					if (typeof options.wallet.currency!=="undefined" && options.wallet.currency!==undefined && options.wallet.currency) {
						this.wallet.currency = options.wallet.currency;
					}

					if (typeof options.wallet.navi!=="undefined" && options.wallet.navi!==undefined && options.wallet.navi) {
						this.wallet.navi = options.wallet.navi;
					}	

					if (typeof options.wallet.rewards!=="undefined" && options.wallet.rewards!==undefined && options.wallet.rewards) {
						this.wallet.rewards = options.wallet.rewards;
					}
				}
			}

			this.createChat();
			this.createForm();
		}
		
		/**
		 * createChat()
		 */
		createChat() {

			var chat = "";
			var $this = this;
			
			if (this.wallet.address) {

				chat += "<div class=\"row\" style=\"margin:0 0 20px 0;font-size:18px\"><div class=\"as2_create_aiagent_wallet\">'.Yii::t('Api', 'AI agent wallet').' " + this.wallet.type.toUpperCase() + " :&nbsp;" + this.app.stringReplace(this.wallet.address, "...", 8, 8) + "&nbsp;&nbsp;<span id=\"as2-wallet-copy\" data-address=\"" + this.wallet.address + "\"><img src=\"/images/icons/copy.svg\" alt=\"\" title=\"\"></span>&nbsp;&nbsp;<a href=\"https://suivision.xyz/account/" + this.wallet.address + "\" target=\"_blank\" id=\"as2-rewiew-wallet\"><img src=\"/images/icons/globe.svg\" alt=\"\" title=\"\"></a>";
				
				
				if (this.showBalance) {
				
					chat += "<br>'.Yii::t('Api', 'Balance').':&nbsp;<span id=\"wallet-coin-balance\">" + this.wallet.balance + "</span>&nbsp;" + this.wallet.type.toUpperCase() + " (<span id=\"wallet-coin-price\">" + this.wallet.price + "</span>" + this.wallet.currency + ") ";
					
					if (typeof this.wallet.navi!=="undefined" && this.wallet.navi!==undefined && this.wallet.navi) {
						chat += "/ <span id=\"wallet-coin-navi\">" + this.wallet.navi + "</span>&nbsp;'.Yii::t('Api', 'Navi').'&nbsp;";
					}
			
					chat += "/ <span id=\"wallet-coin-rewards\">" + this.wallet.rewards + "</span>&nbsp;'.Yii::t('Api', 'Rewards').'&nbsp;";
		
					chat += " <span id=\"as2-chat-active-refresh\" class=\"mdi mdi-rotate-right\" title=\"'.Yii::t('Api', 'Update balance').'\" style=\"cursor:pointer\"></span>";
				
				}
				
				chat += "</div></div>"

			} else {
				
				if (this.showBalance) {
				
					chat += "<div class=\"row\" style=\"margin:0 0 20px 0;\"><div class=\"as2_create_aiagent_wallet\"><div id=\"as2-create-aiagent-wallet\" class=\"as2-currency_button mt-17 mr-10\">'.Yii::t('Api', 'Create AI agent wallet').'</div></div></div>";
				}

			}
			
			chat +="<div class=\"text-center as2-title-chat\">'.Yii::t('Api', 'Chat').'</div>";
	
			chat +="<button class=\"btn btn-as2button\" id=\"as2-btn-chat-portfolio\">'.Yii::t('Api', 'Analise Portfolio').'</button>&nbsp;";
			
			chat +="<button class=\"btn btn-as2button\" id=\"as2-btn-chat-launchpools\">'.Yii::t('Api', 'Exchange LaunchPools').'</button>&nbsp;";
			
			chat +="<button class=\"btn btn-as2button\" id=\"as2-btn-chat-aptospools\">'.Yii::t('Api', 'Show Aptos Pools').'</button>&nbsp;";
			
			if (this.showDeposit) {
				chat +="<button class=\"btn\" id=\"as2-btn-chat-deposit\">'.Yii::t('Api', 'Put on Deposit').'</button>&nbsp;";

				chat +="<button class=\"btn\" id=\"as2-btn-chat-withdraw\">'.Yii::t('Api', 'Withdraw Deposit').'</button>&nbsp;";
			
				chat +="<button class=\"btn\" id=\"as2-btn-chat-rewards\">'.Yii::t('Api', 'Navi claimall').'</button>";
			}
				
			chat +="<a tabindex=\"0\" role=\"button\" id=\"as2-question-addon-chat\" class=\"fa fa-question-circle\"></a>";
			chat +="<div class=\"clearfix\"></div>";
				
			chat +="<div id=\"as2-chat-form-as\" class=\"chat_form_as\"></div>";

			jQuery("#" + this.chat).html(chat); 
			
			jQuery("#" + this.chat).delegate("#as2-wallet-copy", "click", function(e) {
				var value = jQuery(this).attr("data-address");
				$this.app.copyValue(value);
			});
				
			jQuery("#" + this.chat).delegate("#as2-chat-active-refresh", "click", function() {
				$this.updateBalanceCreateWallet("sui");
			});
			
			jQuery("#" + this.chat).delegate("#as2-btn-chat-portfolio", "click", function() {
				$this.sendPortfolioAiagentAS2();
			});
			
			jQuery("#" + this.chat).delegate("#as2-btn-chat-launchpools", "click", function() {
				$this.sendLaunchpoolsAiagentAS2();
			});
			
			jQuery("#" + this.chat).delegate("#as2-btn-chat-aptospools", "click", function() {
				$this.sendAptospoolsAiagentAS2();
			});
			
			$("#as2-question-addon-chat").popover({
				placement: "left",
				content: "This is the body of Popover",
				trigger: "focus",
				template: "<div class=\"popover question_popover as2_question_popover_chat\" role=\"tooltip\"><div class=\"popover-arrow\" style=\"position: absolute; top: 0px; transform: translate(0px, 12px);\"></div><div class=\"popover-content\"><div class=\"question_addon_popover\"><div class=\"popover_text\">'.addslashes(str_replace(["\n", "\r"], "", Yii::t('Api', 'Question FinKeeper Help'))).'<br><a href=\"https://finkeeper.gitbook.io/finkeeper/en\" target=\"_blank\">'.Yii::t('Api', 'Detailed instructions').' <i class=\"fa fa-external-link-alt\"></i></a></div><div class=\"clearfix\"></div></div></div></div>",
			});
			
			jQuery("#as2-chat-form-as").perfectScrollbar({
				wheelSpeed:0.3,
				wheelPropagation: true,
				minScrollbarLength: 20,
				maxHeight: "300px"
			});
			
			$this.getAsModalBlockSize();
			
			jQuery(window).on("resize", function() {
				$this.getAsModalBlockSize();
			});	
			
			jQuery("#" + this.chat).delegate(".as2-transfer-cancel", "click", function() {
				var id = jQuery(this).parents(".sl-item").attr("data-id");
				$this.deleteChat(id);
			});

			jQuery("#" + this.chat).delegate(".as2-transfer-ok", "click", function() {
				var id = jQuery(this).parents(".sl-item").attr("data-id");
				if (typeof id==="undefined" || id===undefined || !id) {
					return false;
				}
				
				$this.disableChatButton(id);
				
				var type = jQuery(this).attr("data-type");
				if (typeof type==="undefined" || type===undefined || !type) {
					$this.app.addNotify("'.Yii::t('Error', 'Not coin type').'", "error");
					return false;
				}

				var trnid = jQuery(this).attr("data-trnid");
				if (typeof trnid==="undefined" || trnid===undefined || !trnid) {
					$this.app.addNotify("'.Yii::t('Error', 'Missing amount').'", "error");
					return false;
				}

				$this.sendButtonProcess(trnid, type);
			});
	
			jQuery("#" + this.chat).delegate("#as2-create-aiagent-wallet", "click", function() {
				$this.createWalletProcess();
			});

			jQuery("#" + this.chat).delegate("#as2-btn-chat-deposit", "click", function() {
				$this.depositButtonProcessAS2();
			});
			
			jQuery("#" + this.chat).delegate("#as2-btn-chat-withdraw", "click", function() {
				$this.withdrawButtonProcessAS2();
			});
			
			jQuery("#" + this.chat).delegate("#as2-btn-chat-rewards", "click", function() {
				$this.rewardsButtonProcessAS2("sui");
			});
			
			jQuery("#" + this.chat).delegate(".as2-rewards-cancel", "click", function() {
				var id = jQuery(this).parents(".sl-item").attr("data-id");
				$this.deleteChat(id);
			});
	
			this.sendMessageAl();
		}
		
		/**
		 * createForm()
		 */
		createForm() {
			
			var $this = this;

			var form = "<div class=\"input-chat\">";
			form += "<div class=\"as2-text_chat_input\">";
			form += "<input type=\"text\" autocomplete=\"off\" id=\"as2-chatai-active-input\" class=\"form-control border-left-0 as2-form-currency-chat\" placeholder=\"'.Yii::t('Api', 'Text').'\" tabindex=\"-1\" value=\"\">";
			form += "</div>";
			form += "<div class=\"as2-text_chat_send\">";
			form += "<button type=\"button\" id=\"as2-chat-active-send\" class=\"btn btn-info\"><span class=\"mdi mdi-chat-plus-outline\"></span></button>";
			form += "<button type=\"button\" id=\"as2-chat-active-send-loader\" style=\"display:none\"><span class=\"far fa-hourglass fa-spin\"></span></button>";
			form += "</div><div class=\"clearfix\"></div></div>";

			jQuery("#" + this.form).html(form); 
			
			jQuery("#" + this.form).delegate("#as2-chat-active-send", "click", function(e) {
				e.preventDefault();
				var text = jQuery("#as2-chatai-active-input").val();
				jQuery("#as2-chatai-active-input").val("");
				$this.sendMessageAl(text);		
			});
			
			document.addEventListener("keyup", function(e) {
				if(e.keyCode == 13) {
					e.preventDefault();
					var text = $("#as2-chatai-active-input").val();
					if (typeof text==="undefined" || text===undefined || !text) {
						return false;
					}

					jQuery("#as2-chatai-active-input").val("");
					$this.sendMessageAl(text);	
				}
			});
		}
		
		/**
		 * viewChat()
		 */
		viewChatAS2() {
			var chatObj = this.getChatAS2();

			if (typeof this.username==="undefined" || this.username===undefined || !this.username) {
				var username = "'.Yii::t('Api', 'You').'";
			} else {
				var username = this.username;
			}
			
			if (typeof this.userpic==="undefined" || this.userpic===undefined || !this.userpic) {
				
				var firstLitera = string_replace(this.username, "", 1, 0);
				var userpic = "<div style=\"background:#47e7ce\" class=\"text-center avatar\"><span>" + firstLitera.toUpperCase() + "</span></div>";

			} else {
				
				var userpic = "<div class=\"avatar\"><img style=\"width:30px\" src=\"" + this.userpic + "\"></div>";
				
			}
			
			var apppic = "<div class=\"avatar\"><img style=\"width:30px\" src=\"" + this.apppic + "\"></div>";
			
			var appname = this.appname;
			
			$("#as2-chat-form-as").html("");
			chatObj.reverse();
			chatObj.forEach(function(item, i, chatObj) {
		
				if (typeof item==="undefined" || item===undefined || item===null || !item) {
					return false;
				}
				
				if (item.hidden==1) {
					return false;
				}

				if (item.type==1) {
					
					var pic = userpic;
					var name = username;
					
				} else if(item.type==2) {
					
					var pic = apppic;
					var name = appname;
				
				} else {
					
					return false;
				}
				
				if (item.hidden==1) {
					return false;
				}
				
				var html = "<div data-id=\"" + item.id + "\" class=\"sl-item p-b-md\">" + pic + "<div class=\"sl-content m-l-sm\"><h5 class=\"m-t-0\"><div class=\"m-r-xs pull-left\"><b>" + name + "</b></div><div class=\"clearfix\"></div></h5><div class=\"speech-bubble\">" + item.message + "</div></div></div>";
				
				$("#as2-chat-form-as").append(html);
			});
		}
		
		/**
		 * getChatAS2()
		 */
		getChatAS2() {
			chatObj = [];
			var $this = this;
			var fromIdentify = "finkeeperuser156974";
			var toIdentify = "finkeeperai15697426";
			var chat = localStorage.getItem(this.storage);

			if (typeof chat==="undefined" || chat===undefined || !chat) {
				return chatObj;
			}		
				
			try {

				var chatObj = JSON.parse(chat);
				
			} catch (err) {
				
				$this.app.addNotify("'.Yii::t('Error', 'Failed to receive messages').'", "error");
				var chatObj = {};
				return chatObj;
			}
			
			var newChatObj = {};
			chatObj.forEach(function(item, i, chatObj) {
				
				if (typeof item==="undefined" || item===undefined || item===null || !item) {
					return false;
				}
				
				if (typeof item==="string") {
					
					regex1 = new RegExp(fromIdentify, "g");
					regex2 = new RegExp(toIdentify, "g");
					
					if (regex1.test(item)) {
						
						var search = fromIdentify.length;
						var message = item.slice(search);
						var id = $this.app.getRandomID(i);
						
						chatObj[i] = {
							"message": message,
							"type": 1,
							"hidden": 0,
							"id": id,
						};

					} else if(regex2.test(item)) {
						
						var search = toIdentify.length;
						var message = item.slice(search);
						var id = $this.app.getRandomID(i);
			
						chatObj[i] = {
							"message": message,
							"type": 2,
							"hidden": 0,
							"id": id,
						};
					} 
				}
			});

			return chatObj;
		}
		
		/**
		 * addChatAS2(str, type, hidden)
		 */
		addChatAS2(str, type, hidden) {
			if (typeof str==="undefined" || str===undefined || !str) {
				return false;
			}
			
			var chatObj = this.getChatAS2();
			var newChatObj = chatObj.filter(element => element !== null);
			
			var len = newChatObj.length;
			if (len>=this.maxMessage) {
				newChatObj.shift();
			}
			
			var id = this.app.getRandomID(type);				
			var mess = {
				"message": str,
				"type": type,
				"hidden": hidden,
				"id": id,			
			}

			newChatObj.push(mess);
			var value = JSON.stringify(newChatObj);
			localStorage.setItem(this.storage, value);
			
			return id;
		}
		
		/**
		 * deleteChat(id)
		 */
		deleteChat(id) {
			if (typeof id==="undefined" || id===undefined || !id) {
				return false;
			}
			
			var dataId = id;
			var element = jQuery("[data-id=\"" + dataId + "\"]");
			element.remove();
			 
			var chatObj = this.getChatAS2();

			chatObj.forEach(function(item, i, chatObj) {
				
				if (typeof item==="undefined" || item===undefined || item===null || !item) {
					return false;
				}
	
				if (item.id==dataId) {
					delete chatObj[i];
				}
			});
			
			var value = JSON.stringify(chatObj);
			localStorage.setItem(this.storage, value);			
		}
		
		/**
		 * disableChatButton(id)
		 */
		disableChatButton(id) {
			if (typeof id==="undefined" || id===undefined || !id) {
				return false;
			}
			
			var dataId = id;
			var elem = jQuery("[data-id=\"" + dataId + "\"]").find(".as2-transfer-ok, .as2-deposit-ok, .as2-withdraw-ok, .as2-rewards-ok, .as2-aptospools-ok");
	
			elem.attr("disabled", true);
			var buttonName = elem.html();
			elem.html(buttonName + " <i class=\"fas fa-asterisk fa-spin\"></i>");

			var chatObj = this.getChatAS2();
			
			chatObj.forEach(function(item, i, chatObj) {
				
				if (typeof item==="undefined" || item===undefined || item===null || !item) {
					return false;
				}
	
				if (item.id==dataId) {
					chatObj[i].message = item.message.replaceAll("data-ev", "disabled")
				}
			});
			
			var value = JSON.stringify(chatObj);
			localStorage.setItem(this.storage, value);			
		}
		
		/**
		 * changeButtonChat(load)
		 */
		changeButtonChat(load) {	
			if (typeof load==="undefined" || load===undefined || !load) {
				$("#as2-chat-active-send").show();
				$("#as2-chat-active-send-loader").hide();
			} else {
				$("#as2-chat-active-send").hide();
				$("#as2-chat-active-send-loader").show();
			}	
		}
		
		// AI Agent
		
		/**
		 * createWalletProcess()
		 */
		createWalletProcess() {
			
			var buttonName = jQuery("#as2-create-aiagent-wallet").html();
			jQuery("#as2-create-aiagent-wallet").html(buttonName + "&nbsp;<i class=\"fas fa-asterisk fa-spin\"></i>");

			var $this = this;
			jQuery.ajax({
				"url": "/app/alassistant",
				"type": "post",
				"dataType": "json",
				"contentType": "application/json",
				"data": JSON.stringify({data: "create", "log_id": $this.id, sc: $this.sc, type: 4}),
				"success": function(response){
		
					jQuery("#as2-create-aiagent-wallet").html(buttonName);

					if (response) {
					
						if (!response.error) {
							
							var html = "'.Yii::t('Api', 'AI agent wallet').' " + $this.wallet.type.toUpperCase() + " :&nbsp;" + $this.app.stringReplace(response.message, "...", 8, 8) + "&nbsp;&nbsp;<span id=\"as2-wallet-copy\" data-address=\"" + response.message + "\"><img src=\"/images/icons/copy.svg\" alt=\"\" title=\"\"></span>&nbsp;&nbsp;<a href=\"https://suivision.xyz/account/" + response.message + "\" target=\"_blank\" id=\"as2-rewiew-wallet\"><img src=\"/images/icons/globe.svg\" alt=\"\" title=\"\"></a><br>'.Yii::t('Api', 'Balance').':&nbsp;0&nbsp;" + $this.wallet.type.toUpperCase();

							jQuery("#" + $this.chat + " .as2_create_aiagent_wallet").html(html);
			
						} else {		
							$this.app.addNotify(response.message, "error");
							return false;
						}
					
					} else {
						$this.app.addNotify("'.Yii::t('Error', 'Server not response').'", "error");
						return false;
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					$this.app.addNotify(thrownError, "error");
					return false;
				}
			});
		}
		
		/**
		 * pdateBalanceCreateWallet(coin)
		 */
		updateBalanceCreateWallet(coin) {
		
			$("#as2-chat-active-refresh").addClass("mdi-spin");

			if (
				typeof coin==="undefined" ||
				coin===undefined ||
				!coin
			) {
				this.app.addNotify("'.Yii::t('Error', 'Missing coin').'", "error");
				return false;
			}
			
			var $this = this;
			jQuery.ajax({
				"url": "/app/alassistant",
				"type": "post",
				"dataType": "json",
				"contentType": "application/json",
				"data": JSON.stringify({data: coin, "log_id": $this.id, sc: $this.sc, type: 8}),
				"success": function(response){
					
					$("#as2-chat-active-refresh").removeClass("mdi-spin");

					if (response) {
					
						if (!response.error) {
							
							$("#wallet-coin-balance").html(response[coin].balance);
							$("#wallet-coin-price").html(response[coin].price);
							$("#wallet-coin-navi").html(response[coin].navi);
							$("#wallet-coin-rewards").html(response[coin].rewards);
			
						} else {		
							$this.app.addNotify(response.message, "error");
							return false;
						}
					
					} else {
						$this.app.addNotify("'.Yii::t('Error', 'Server not response').'", "error");
						return false;
					}			
				},
				error: function(xhr, ajaxOptions, thrownError) {
					$this.app.addNotify(thrownError, "error");
					return false;
				}
			});	
		}
		
		/**
		 * sendMessageAl(str)
		 */
		sendMessageAl(str, hidden) {
			
			this.changeButtonChat(1);	
			if (typeof str==="undefined" || str===undefined || !str) {
				this.viewChatAS2();
				this.changeButtonChat(0);
				
			} else {
				
				if (typeof hidden!=="undefined" && hidden!==undefined && hidden==1) {
					this.addChatAS2(str, 1, 1);
				} else {
					this.addChatAS2(str, 1, 0);
				}
	
				this.viewChatAS2();
				this.sendAiAgentAS2(str);
			}
		}
		
		/**
		 * sendAiAgentAS2(str)
		 */
		sendAiAgentAS2(str) {
			var portfolio = this.portfolio;
			var $this = this;

			jQuery.ajax({
				"url": "/app/alassistant",
				"type": "post",
				"dataType": "json",
				"contentType": "application/json",
				"data": JSON.stringify({"data": str, "portfolio": portfolio, "log_id": $this.id, sc: $this.sc, type: 3, coin: "sui"}),
				"success": function(response){
					
					$(".fa-asterisk").remove();
	
					if (response) {
					
						if (!response.error) {
							
							if ($this.id==8) {
							var balance = 100; 
							} else {
								$this.wallet.balance;
							}
							
							var aidata = $this.parseResponseAS2(response);
							
							var message = "";

							if (!aidata) {
							
								$this.app.addNotify("'.Yii::t('Error', 'Ai agent not response').'", "error");
							
							} else if(aidata.function==1 && aidata.name=="transfer") {
								
								var amount = parseFloat(aidata.amount);
								var addressTo = aidata.address;
								var addressFrom = $this.wallet.address;
								var addressToShort = $this.app.stringReplace(addressTo, "...", 8, 8);
								var addressFromShort = $this.app.stringReplace(addressFrom, "...", 8, 8);
								var message = "";
								
								if (amount==0) {
									
									message = "'.Yii::t('Api', 'Amount is empty').'";
									
								} else if(amount>balance) {
									
									message = "'.Yii::t('Api', 'Not enough assets').'";

								} else {
									
									message = "'.Yii::t('Api', 'Transfer  Coins').'<p></p><button data-type=\"sui\" data-trnid=\"" + aidata.trnid + "\" class=\"btn btn-light as2-transfer-ok\" data-ev>'.Yii::t('Api', 'OK').'</button>&nbsp;<button class=\"btn btn-light as2-transfer-cancel\">'.Yii::t('Api', 'Cancel').'</button>";
								}

								message = message.replaceAll("{coin}", amount);
								message = message.replaceAll("{wallet}", addressToShort);
								message = message.replaceAll("{createwallet}", addressFromShort);

								$this.addChatAS2(message, 2, 0);
								$this.viewChatAS2();
								
							} else if(aidata.function==2 && aidata.name=="deposit") {
								
								var amount = parseFloat(aidata.amount);
								var addressFrom = $this.wallet.address;
								var addressFromShort = $this.app.stringReplace(addressFrom, "...", 8, 8);

								if (amount==0) {
									
									message = "'.Yii::t('Api', 'Amount is empty').'";
									
								} else if(amount>balance) {
									
									message = "'.Yii::t('Api', 'Not enough assets Deposit').'";

								} else {
									
									message = "'.Yii::t('Api', 'Deposit  Coins').'<p></p><button data-trnid=\"" + aidata.trnid + "\" data-type=\"sui\" class=\"btn btn-light as2-transfer-ok\" data-ev>'.Yii::t('Api', 'OK').'</button>&nbsp;<button class=\"btn btn-light as2-transfer-cancel\">'.Yii::t('Api', 'Cancel').'</button>";
								}
								
								var apr = "(" + aidata.apr + "% APR)";
			
								message = message.replaceAll("{coin}", amount);
								message = message.replaceAll("{createwallet}", addressFrom);
								message = message.replaceAll("{apr}", apr);
								
								$this.addChatAS2(message, 2, 0);
								$this.viewChatAS2();
							
							} else if(aidata.function==3 && aidata.name=="withdraw") {
					
								var amount = parseFloat(aidata.amount);
								var addressFrom = $this.wallet.address;
								var addressFromShort = $this.app.stringReplace(addressFrom, "...", 8, 8);
								
								if (amount==0) {
									
									message = "'.Yii::t('Api', 'Amount is empty').'";

								} else {
									
									message = "'.Yii::t('Api', 'Withdraw  Coins').'<p></p><button data-trnid=\"" + aidata.trnid + "\" data-type=\"sui\" class=\"btn btn-light as2-transfer-ok\" data-ev>'.Yii::t('Api', 'OK').'</button>&nbsp;<button class=\"btn btn-light as2-transfer-cancel\">'.Yii::t('Api', 'Cancel').'</button>";
								}
								
								message = message.replaceAll("{coin}", amount);
								message = message.replaceAll("{createwallet}", addressFrom);
								
								$this.addChatAS2(message, 2, 0);
								$this.viewChatAS2();

							} else if(aidata.message) {
								
								$this.addChatAS2(aidata.message, 2, 0);
								$this.viewChatAS2();
								
							} else {

								$this.app.addNotify("'.Yii::t('Error', 'Ai agent not response').'", "error");
							}

							$this.changeButtonChat(0);
							
						} else {		
							$this.app.addNotify(response.message, "error");
							$this.changeButtonChat(0);
							return false;
						}
					
					} else {
						$this.app.addNotify("'.Yii::t('Error', 'Server not response').'", "error");
						$this.changeButtonChat(0);
						return false;
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					$this.app.addNotify(thrownError, "error");
					$(".fa-asterisk").remove();
					$this.changeButtonChat(0);
					return false;
				}
			});	
		}
		
		/**
		 * sendButtonProcess(trnid, type)
		 */
		sendButtonProcess(trnid, type) {
		
			if (
				typeof trnid==="undefined" ||
				typeof trnid==="undefined" ||
				trnid===undefined
			) {
				this.app.addNotify("'.Yii::t('Error', 'Not ID Message').'", "error");
				return false;
			}

			var $this = this;
			
			jQuery.ajax({
				"url": "/app/alassistant",
				"type": "post",
				"dataType": "json",
				"contentType": "application/json",
				"data": JSON.stringify({data: trnid, "log_id": $this.id, sc: $this.sc, type: 5}),
				"success": function(response){
		
					$(".fa-asterisk").remove();
				
					if (response) {
					
						if (!response.error) {
				
							var message = "'.Yii::t('Api', 'Transaction Send').'<br>";
							type = type.toUpperCase();
							message = message.replaceAll("{token}", type);
							message = message.replaceAll("{status}", "<span class=\"mdi mdi-clock-outline\" id=\"as2-trans-status\"></span>");
							
							message += "'.Yii::t('Api', 'Transaction View Link').': <a href=\"https://suivision.xyz/txblock/" + response.message + "\" target=\"_blanck\">https://suivision.xyz/txblock/" + response.message + "</a>";

							var id = $this.addChatAS2(message, 2, 0);
							$this.viewChatAS2();
							
							var status = "";
							var timer = setInterval(function() {
								status = $this.getTransactionStatusAS2(id, response.message);
								if (status=="success" || status=="error") {
									clearInterval(timer); 
								}
							}, 6000);
							
							setTimeout(function() {
								clearInterval(timer); 
							}, 300000);
							
						} else {		
							$this.app.addNotify(response.message, "error");
							return false;
						}
					
					} else {
						$this.app.addNotify("'.Yii::t('Error', 'Server not response').'", "error");
						return false;
					}			
				},
				error: function(xhr, ajaxOptions, thrownError) {
					$(".fa-asterisk").remove();
					$this.app.addNotify(thrownError, "error");
					return false;
				}
			});
		}
		
		/**
		 * getTransactionStatusAS2(id, digest)
		 */
		getTransactionStatusAS2(id, digest) {

			if (
				typeof id==="undefined" ||
				id===undefined ||
				!id
			) {
				this.app.addNotify("'.Yii::t('Error', 'Missing id').'", "error");
				return false;
			}
			
			if (
				typeof digest==="undefined" ||
				digest===undefined ||
				!digest
			) {
				this.app.addNotify("'.Yii::t('Error', 'Missing digest').'", "error");
				return false;
			}

			var $this = this;
			jQuery.ajax({
				"url": "/app/alassistant",
				"type": "post",
				"dataType": "json",
				"contentType": "application/json",
				"data": JSON.stringify({data: digest, "log_id": $this.id, sc: $this.sc, type: 10}),
				"success": function(response){
		
					if (response) {
					
						if (!response.error) {
							
							var dataId = id;

							$this.setTransactionStatusAS2(response.data, id);
							var search = "mdi-clock-outline";
							if (response.data=="success") {
								var replace = "mdi-check-circle-outline";
							} else if(response.data=="failure") {
								var replace = "mdi-close-octagon";								
							} else {
								return false;
							}
							
							var chatObj = $this.getChatAS2();
							
							chatObj.forEach(function(item, i, chatObj) {
				
								if (typeof item==="undefined" || item===undefined || item===null || !item) {
									return false;
								}
					
								if (item.id==dataId) {
									chatObj[i].message = item.message.replaceAll(search, replace)
								}
							});
			
							var value = JSON.stringify(chatObj);
							localStorage.setItem($this.storage, value);	
							
							return response.data;
							
						} else {		
							$this.app.addNotify(response.message, "error");
							return false;
						}
					
					} else {
						$this.app.addNotify("'.Yii::t('Error', 'Server not response').'", "error");
						return false;
					}			
				},
				error: function(xhr, ajaxOptions, thrownError) {
					$this.app.addNotify(thrownError, "error");
					return false;
				}
			});	
		}
		
		/**
		 * setTransactionStatusAS2(status)
		 */
		setTransactionStatusAS2(status, id) {
	
			if (status=="success") {
				
				jQuery("[data-id=\"" + id + "\"]").find("#as2-trans-status").removeClass();
				jQuery("[data-id=\"" + id + "\"]").find("#as2-trans-status").addClass("mdi mdi-check-circle-outline");
				
			} else if(status=="failure") {
				
				jQuery("[data-id=\"" + id + "\"]").find("#as2-trans-status").removeClass();
				jQuery("[data-id=\"" + id + "\"]").find("#as2-trans-status").addClass("mdi mdi-close-octagon");
			}			
		}

		// Buttons AI Agent (deposit, withdraw, rewards)
		
		/**
		 * depositButtonProcessAS2()
		 */
		depositButtonProcessAS2() {
			
			var $this = this;
			var str = " <div class=\"input-group\">'.Yii::t('Api', 'How much do you want to deposit?').'&nbsp;<input type=\"text\" class=\"as2-send-to-deposit-2 form-control as2-form-currency-chat\" style=\"max-width:100px !important;width:100px !important\">&nbsp;<button class=\"btn btn-light as2-transfer-ok-2\" type=\"button\">'.Yii::t('Api', 'OK').'</button></div>"; 
	
			this.addChatAS2(str, 1, 0);
			this.viewChatAS2();

			jQuery("#" + this.chat).delegate(".as2-transfer-ok-2", "click", function() {

				var amount = jQuery(this).parent(".input-group").find(".as2-send-to-deposit-2").val();
				
				var message = "Deposit " + amount + " SUI";

				if (typeof amount==="undefined" || amount===undefined || !amount) {
					$this.app.addNotify("'.Yii::t('Error', 'Not amount').'", "error");
					return false;
				}
				
				$(".fa-asterisk").remove();
				var elem = jQuery(this);
				var buttonName = elem.html();
				elem.html(buttonName + " <i class=\"fas fa-asterisk fa-spin\"></i>");
		
				$this.sendAiAgentAS2(message)
			});	
		}
		
		/**
		 * withdrawButtonProcessAS2()
		 */
		withdrawButtonProcessAS2() {
			
			var $this = this;
			var str = " <div class=\"input-group\">'.Yii::t('Api', 'How much do you want to withdraw from the deposit?').'&nbsp;<input type=\"text\" class=\"as2-send-to-withdraw-2 form-control as2-form-currency-chat\" style=\"max-width:100px !important;width:100px !important\">&nbsp;<button class=\"btn btn-light as2-withdraw-ok-2\" type=\"button\">'.Yii::t('Api', 'OK').'</button></div>"; 
	
			this.addChatAS2(str, 1, 0);
			this.viewChatAS2();

			jQuery("#" + this.chat).delegate(".as2-withdraw-ok-2", "click", function() {
				
				var amount = jQuery(this).parent(".input-group").find(".as2-send-to-withdraw-2").val();
				
				var message = "Withdraw " + amount + " SUI";

				if (typeof amount==="undefined" || amount===undefined || !amount) {
					$this.app.addNotify("'.Yii::t('Error', 'Not amount').'", "error");
					return false;
				}
				
				$(".fa-asterisk").remove();
				var elem = jQuery(this);
				var buttonName = elem.html();
				elem.html(buttonName + " <i class=\"fas fa-asterisk fa-spin\"></i>");
		
				$this.sendAiAgentAS2(message)
			});	
			
		}
		
		/**
		 * rewardsButtonProcessAS2(coin)
		 */
		rewardsButtonProcessAS2(coin) {
			
			var buttonName = jQuery("#as2-btn-chat-rewards").html();
			jQuery("#as2-btn-chat-rewards").html(buttonName + "&nbsp;<i class=\"fas fa-asterisk fa-spin\"></i>");

			var str = "'.Yii::t('Api', 'Rewards claimall').'<p></p><button data-type=\"sui\" class=\"btn btn-light as2-rewards-ok\" data-ev>'.Yii::t('Api', 'OK').'</button>&nbsp;<button class=\"btn btn-light as2-rewards-cancel\">'.Yii::t('Api', 'Cancel').'</button>";
			
			var id = this.addChatAS2(str, 1, 0);
			this.viewChatAS2();
			
			$(".fa-asterisk").remove();
			
			var $this = this;
			
			jQuery("#" + this.chat).delegate(".as2-rewards-ok", "click", function() {

				$this.disableChatButton(id);
			
				jQuery.ajax({
					"url": "/app/alassistant",
					"type": "post",
					"dataType": "json",
					"contentType": "application/json",
					"data": JSON.stringify({data: "rewards", "log_id": $this.id, sc: $this.sc, type: 11}),
					"success": function(response){
						
						$(".fa-asterisk").remove();
	
						if (response) {
						
							if (!response.error) {
								
								$("#wallet-coin-balance").html(response[coin].balance);
								$("#wallet-coin-price").html(response[coin].price);
								$("#wallet-coin-navi").html(response[coin].navi);
								$("#wallet-coin-rewards").html(response[coin].rewards);
				
								message += "'.Yii::t('Api', 'Transaction View Link').': <a href=\"https://suivision.xyz/txblock/" + response.digest + "\" target=\"_blanck\">https://suivision.xyz/txblock/" + response.digest + "</a>";

								var id = $this.addChatAS2(message, 2, 0);
								$this.viewChatAS2();
							
								var status = "";
								var timer = setInterval(function() {
									status = $this.getTransactionStatusAS2(id, response.message);
									if (status=="success" || status=="error") {
										clearInterval(timer); 
									}
								}, 6000);
								
								setTimeout(function() {
									clearInterval(timer); 
								}, 300000);
						
							} else {		
								$this.app.addNotify(response.message, "error");
								return false;
							}
						
						} else {
							$this.app.addNotify("'.Yii::t('Error', 'Server not response').'", "error");
							return false;
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						$this.app.addNotify(thrownError, "error");
						return false;
					}
				});
			
			});
		}
		
		// Button send portfolio AI Agent
		
		/**
		 * sendPortfolioAiagentAS2()
		 */
		sendPortfolioAiagentAS2() {
			var query = "'.Yii::t('Api', 'Analise Portfolio').'";
			var elem = jQuery("#as2-btn-chat-portfolio");
			var buttonName = elem.html();
			elem.html(buttonName + " <i class=\"fas fa-asterisk fa-spin\"></i>");
			this.sendMessageAl(query, 1);
		}

		// Aptos launchpools button
		
		/**
		 * sendAptospoolsAiagentAS2()
		 */
		sendAptospoolsAiagentAS2() {
			
			var elem = jQuery("#as2-btn-chat-aptospools");
			var buttonName = elem.html();
			elem.html(buttonName + " <i class=\"fas fa-asterisk fa-spin\"></i>");
			this.getAptosPools();	
		}
		
		/**
		 * getAptosPools()
		 */
		getAptosPools() {
			
			var $this = this;
			
			var portfolio = this.getUserActives();
			
			var str = " <div class=\"input-group\">'.Yii::t('Api', 'Enter token for pool suggestion').'&nbsp;<input type=\"text\" class=\"as2-send-to-aptos-pools form-control as2-form-currency-chat\" style=\"max-width:100px !important;width:100px !important\" placeholder=\"USD\">&nbsp;<button class=\"btn btn-light as2-aptospools-ok\" type=\"button\">'.Yii::t('Api', 'OK').'</button></div>"; 

			var id = this.addChatAS2(str, 2, 0);
			this.viewChatAS2();
			
			
			$(".fa-asterisk").remove();
			
			jQuery("#" + this.chat).delegate(".as2-aptospools-ok", "click", function() {

				$this.disableChatButton(id);

				var token = jQuery(this).parent(".input-group").find(".as2-send-to-aptos-pools").val();
				if (typeof token==="undefined" || token===undefined || !token) {
					token = "usd";
				}
				
				jQuery.ajax({
					"url": "/app/aptospools",
					"type": "post",
					"dataType": "json",
					"contentType": "application/json",
					"data": JSON.stringify({"token": token, "log_id": $this.id, sc: $this.sc}),
					"success": function(response){
						
						$(".fa-asterisk").remove();
	
						if (response && response.length>0) {
					
							if (!response.error) {
								
								var currentDate = $this.app.getDate();
						
								var message = "<div class=\"aptospools-title-wallets\"><img src=\"/images/logos/apt2.png\"><div>&nbsp;'.Yii::t('Api', 'Aptos Pools').'</div></div><div class=\"float-end block-datetime\">" + currentDate + "</div><div class=\"clearfix\"></div>";

								message += "<div class=\"aptospools-pools\"><div class=\"row\"><div class=\"col-xs-12 col-sm-6 brd-r\">'.Yii::t('Api', 'Asset').'</div><div class=\"col-xs-12 col-sm-3 brd-r\">'.Yii::t('Api', 'Protocol').'</div><div class=\"col-xs-12 col-sm-3\">'.Yii::t('Api', 'Supply APR').'</div></div>";	
						
								var portfolioData = {};
								var key;
								for (key in response) {
									
									message += "<div class=\"row\"><div class=\"col-xs-12 col-sm-6 brd-r\">" + response[key].asset + "<span class=\"aptos-pools-provider\">&nbsp;(" + response[key].provider + ")</span></div><div class=\"col-xs-12 col-sm-3 brd-r\"><img src=\"" + response[key].protocol_icon + "\">&nbsp;<a href=\"" + response[key].protocol_link + "\" target=\"_blank\">" + response[key].protocol + "</a></div><div class=\"col-xs-12 col-sm-3\">" + formatValue(response[key].totalAPY, 1) + "%</div></div>"

									/*
									var coin_id = response[key].asset.toLowerCase();
									if (typeof portfolio[coin_id]!=="undefined" && portfolio[coin_id]!==undefined && portfolio[coin_id]) {
										portfolioData[coin_id] = portfolio[coin_id];
									}
                                    */	
								};
								
								token = token.toLowerCase();
								for (key in portfolio) {
									key = key.toLowerCase();
									if (key.includes(token)) {
										portfolioData[key] = portfolio[key];
									}
								}
								
								
		
								var portfolioMessage = "";
								if (typeof portfolioData!=="undefined" && portfolioData!==undefined && portfolioData) {

									var key
									for (key in portfolioData) {
										portfolioMessage += formatValue(portfolioData[key]) + "&nbsp;" + key.toUpperCase() + ",&nbsp;";
									}
									
									portfolioMessage = portfolioMessage.slice(0, -7);
								}
						
								if (typeof portfolioMessage!=="undefined" && portfolioMessage!==undefined && portfolioMessage) {
									message += "<div class=\"aptospools-portfolio\">'.Yii::t('Api', 'In your portfolio').':&nbsp;" + portfolioMessage + "</div>";
								}
								
								message += "</div>";
								
								$this.addChatAS2(message, 2, 0);
								$this.viewChatAS2();
						
							} else {		
								$this.app.addNotify(response.message, "error");
								return false;
							}
						
						} else {
						
							var message = "'.Yii::t('Api', 'There is no pools for').' " + token;
							$this.addChatAS2(message, 2, 0);
							$this.viewChatAS2();
						}	
					},
					error: function(xhr, ajaxOptions, thrownError) {
						$this.app.addNotify(thrownError, "error");
					}
				});	
			});
		}
	
		// Exchange launchpools button
		
		/**
		 * sendLaunchpoolsAiagentAS2()
		 */
		sendLaunchpoolsAiagentAS2() {
			
			var elem = jQuery("#as2-btn-chat-launchpools");
			var buttonName = elem.html();
			elem.html(buttonName + " <i class=\"fas fa-asterisk fa-spin\"></i>");
			this.getLaunchPools();	
		}

		/**
		 * getLaunchPools()
		 */
		getLaunchPools() {
			
			var portfolio = this.getUserActives();
			
			var $this = this;
		
			jQuery.ajax({
				"url": "/app/launchpools",
				"type": "post",
				"dataType": "json",
				"contentType": "application/json",
				"data": JSON.stringify({"log_id": $this.id, sc: $this.sc}),
				"success": function(response){
					
					$(".fa-asterisk").remove();
					var message = "";
					
					console.log(response);
					
					if (response) {
					
						if (!response.error) {
							var key;
							for (key in response) {
								
								var updateMessage = "";
								if (
									typeof response[key].time!=="undefined" &&
									response[key].time!==undefined &&
									response[key].time
								) {
									
									updateMessage = "&nbsp;('.Yii::t('Api', 'update').' " + response[key].time + " '.Yii::t('Api', 'minutes ago').')";
								}
								
								if (
									typeof response[key].exchange_link!=="undefined" &&
									response[key].exchange_link!==undefined &&
									response[key].exchange_link
								) {
									
									var currentDate = $this.app.getDate();
									
									message += "<div class=\"launchpools-title-exchange\"><a href=\"" + response[key].exchange_link + "\" target=\"_blank\"><img src=\"" + response[key].exchange_icon + "\"></a><div>&nbsp;<a href=\"" + response[key].exchange_link + "\" target=\"_blank\">" + response[key].exchange + "</a>" + updateMessage + "</div></div><div class=\"float-end block-datetime\">" + currentDate + "</div><div class=\"clearfix\"></div>";

								} else {
									
									message += "<div class=\"launchpools-title-exchange\"><img src=\"" + response[key].exchange_icon + "\" class=\"float-start\"><div>&nbsp;" + response[key].exchange +  updateMessage + "</div></div><div class=\"clearfix\"></div>";
								}

								
								
								
								if (
									typeof response[key].pools!=="undefined" &&
									response[key].pools!==undefined &&
									response[key].pools &&
									response[key].pools.length>0
								) {
		
									response[key].pools.forEach(function(pools, i) {

										message += "<div class=\"launchpools-pools-exchange\"><div class=\"launchpools-pools-exchange-icon\"><img src=\"" + pools.coini_icon + "\"></div><div class=\"launchpools-pools-exchange-coin\">&nbsp;<b>" + pools.coin + "</b></div><div class=\"clearfix\"></div>";
					
										if (!pools.stake_status) {
											message += "<div class=\"launchpools-pools-exchange-date\"><div class=\"launchpools-pools-exchange-date-left-block\">'.Yii::t('Api', 'start date').':</div><div class=\"launchpools-pools-exchange-date-right-block\">" + pools.stake_start + "&nbsp;(UTC)</div><div class=\"clearfix\"></div></div>";
										}
										
										message += "<div class=\"launchpools-pools-exchange-date\"><div class=\"launchpools-pools-exchange-date-left-block\">'.Yii::t('Api', 'end date').':</div><div class=\"launchpools-pools-exchange-date-right-block\">" + pools.stake_end + "&nbsp;(UTC)</div><div class=\"clearfix\"></div></div>";
										
										message += "<div class=\"launchpools-pools-exchange-coins\"><div class=\"row\">";
										
										var portfolioMessage = "";
										
										pools.list.forEach(function(list, i) {

											var borderColor = "#fff";
											if (i>=2) {
												borderColor = "transparent";
											}
											
											message += "<div class=\"ascol col-sm-12 col-md-4\" style=\"border-right:1px solid " + borderColor + "\"><b>" + list.coin + "</b>&nbsp;" + list.apr + " APR%<br>";
											
											if (
												typeof list.min!=="undefined" && 
												list.min!==undefined && 
												list.min
											) {
									
												message += "Min:&nbsp;" + list.min + "&nbsp;" + list.coin + "<br>";
											}
		
											message += "Max:&nbsp;" + list.max + "&nbsp;" + list.coin + "</div>";
											
											var coin_id = list.coin.toLowerCase();
											if (typeof portfolio[coin_id]!=="undefined" && portfolio[coin_id]!==undefined && portfolio[coin_id]) {

												var class_summ = "not_enough";
												if (portfolio[coin_id]>=list.min) {
													class_summ = "yes_enough";
												} 

												portfolioMessage += "<span class=\"" + class_summ + "\">" + formatValue(portfolio[coin_id]) + "</span>" + "&nbsp;" + list.coin.toUpperCase() + ",&nbsp;";
																						
											}	
										});
										
										if (typeof portfolioMessage!=="undefined" && portfolioMessage!==undefined && portfolioMessage) {
											message +="<div class=\"launchpools-portfolio\">'.Yii::t('Api', 'In your portfolio').':&nbsp;" + portfolioMessage.slice(0, -7) + "</div>";
										}

										message += "</div></div></div>";
									});
								
								} else {
									message += "'.Yii::t('Api' , 'No pools available right').'";
								}
							}

							$this.addChatAS2(message, 2, 0);
							$this.viewChatAS2();

						} else {		
							$this.app.addNotify(response.message, "error");
							return false;
						}
					
					} else {
						$this.app.addNotify("'.Yii::t('Error', 'Server not response').'", "error");
						return false;
					}				
				},
				error: function(xhr, ajaxOptions, thrownError) {
					$this.app.addNotify(thrownError, "error");
					return false;
				}
			});	
		}

		// additional
		
		/**
		 * parseResponseAS2(response)
		 */
		parseResponseAS2(response) {

			if (
				typeof response==="undefined" ||
				response===undefined ||
				!response
			) {
				return "";	
			}

			var apr = 0;
			if (
				typeof response.apr!=="undefined" &&
				response.apr!==undefined &&
				response.apr
			) {
				var apr = response.apr;	
			}

			if (
				typeof response.message==="undefined" ||
				response.message===undefined ||
				!response.message
			) {
				return "";	
			}
	
			if (
				typeof response.message.function==="undefined" ||
				response.message.function===undefined ||
				!response.message.function
			) {
				response.apr = apr;
				return response;
			}

			response.message.apr = apr;
			return response.message;
		}
		
		/**
		 * getAsModalBlockSize()
		 */
		getAsModalBlockSize() {
			var height = $("body").height();	
			jQuery("#as2-chat-form-as").height(height-200);
		}
		
		/**
		 * getUserActives()
		 */
		getUserActives() {
			var portfolio={};
			if (typeof userActivesMin!=="undefined" && userActivesMin!==undefined && userActivesMin && typeof userActivesMin==="object") {
				var key1;
				for (key1 in userActivesMin) {
					if (typeof userActivesMin[key1]!=="undefined" && userActivesMin[key1]!==undefined && userActivesMin[key1] && typeof userActivesMin[key1]==="object") {
						var key2;
						for (key2 in userActivesMin[key1]) {
							if (typeof userActivesMin[key1][key2]!=="undefined" && userActivesMin[key1][key2]!==undefined && userActivesMin[key1][key2] && typeof userActivesMin[key1][key2]==="object") {
								
								if (typeof userActivesMin[key1][key2].active!=="undefined" && userActivesMin[key1][key2].active!==undefined && userActivesMin[key1][key2].active && typeof userActivesMin[key1][key2].active==="object") {
			
									var coin;
									for (coin in userActivesMin[key1][key2].active) {

										if (typeof portfolio[coin]!=="undefined" && portfolio[coin]!==undefined && portfolio[coin]) {
	
											portfolio[coin] += parseFloat(userActivesMin[key1][key2].active[coin].balance);

										} else {
											
											portfolio[coin] = {};
											portfolio[coin] =  parseFloat(userActivesMin[key1][key2].active[coin].balance);
										}
									}
								}
								
								if (typeof userActivesMin[key1][key2].trading!=="undefined" && userActivesMin[key1][key2].trading!==undefined && userActivesMin[key1][key2].trading && typeof userActivesMin[key1][key2].trading==="object") {
								
									var coin;
									for (coin in userActivesMin[key1][key2].trading) {
										
										if (typeof portfolio[coin]!=="undefined" && portfolio[coin]!==undefined && portfolio[coin]) {
	
											portfolio[coin] += parseFloat(userActivesMin[key1][key2].trading[coin].balance);

										} else {
											
											portfolio[coin] = {};
											portfolio[coin] =  parseFloat(userActivesMin[key1][key2].trading[coin].balance);
										}
										
									}
								}	
							}
						}
					}
				};
			}
			
			return portfolio;		
		}
	}
', yii\web\View::POS_END);