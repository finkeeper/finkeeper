<?php

use yii\bootstrap5\Html;

$this->registerCss("
	#wrap-sol-form {
		padding:32px 16px 160px 16px;
		background-color:#0f0638;
	}
	.as2868-wcb {
		background-image: url(/images/logos/sol_connect.png);
		position:relative;
		width:60px;
		height:60px;
		background-repeat:no-repeat;
		background-position:right bottom;
		background-size:90%;
		background-color:#000000;
		float:left;
		border:2px solid #fff;
		border-radius:10px;
		cursor:pointer;
		margin:10px auto 0 auto;
		overflow:hidden;
		z-index:4;
	}
	.as2868-wcb .mdi-wifi {
		color:#00ff7f;
		font-size:20px;
		position:absolute;
		top:0px;
		left:2px;
	}
	.as2868-wcb .mdi-wifi-off {
		color:#ccc;
		font-size:20px;
		position:absolute;
		top:0px;
		left:2px;
	}
	.as2868-wcb .mdi-eye-off {
		color:#ccc;
		font-size:20px;
		position:absolute;
		bottom:0px;
		right:2px;
	}
	.as2868-wcb .fa-hourglass {
		position:absolute;
		top:50%;
		left:50%;
		margin:-10px 0 0 -10px;
		font-size:22px;
		color:#000;
	}
	.as2868-wcb .connect-loader {
		width:26px;
		height:26px;
		position:absolute;
		top:50%;
		left:50%;
		margin:-13px 0 0 -13px;
	}
	.as2868-wcb .as2868-bcb {
		position:absolute;
		top:0;
		left:0;
		right:0;
		bottom:0;
		background:#fff;
		opacity:0.8;
		z-index:6;
	}
	.as2868-wcb .as2868-acb {
		position:absolute;
		top:2px;
		left:2px;
		right:2px;
		bottom:2px;
		background:transparent;
		z-index:5;
		width:calc(100% - 4px);
		height:calc(100% - 4px);
	}
	.as2868-pc {
		background:transparent;
		font-size:16px !important;
		color:#000000;
		height:fit-content;
		width:fit-content;
		cursor:pointer;
	}
	.as2868-pc:hover {
		background:#F1F3F5;
	}
	.as2868-pt a {
		text-decoration:underline !important;
		color:#000000;
	}
	.as2868-connect_popover_active {
		box-shadow: 0 4px 24px rgba(0, 0, 0, 0.16);
		overflow:hidden;
		box-sizing:border-box;
		padding:4px;
		border-radius:16px;
		margin-top:0px !important;
		width:190px;
		height:125px;
	}
	.as2868-connect_popover {
		box-shadow: 0 4px 24px rgba(0, 0, 0, 0.16);
		overflow:hidden;
		box-sizing:border-box;
		padding:4px;
		border-radius:16px;
		margin-top:0px !important;
		width:190px;
		height:54px;
	}
	.as2868-disconnect_popover {
		box-shadow: 0 4px 24px rgba(0, 0, 0, 0.16);
		overflow:hidden;
		box-sizing:border-box;
		padding:4px;
		border-radius:16px;
		margin-top:0px !important;
		width:190px;
		height:80px;
	}
	@media (max-width: 700px) {
		.as2868-connect_popover_active,
		.as2868-connect_popover{
			width:170px !important;
			margin:auto !important;
		}
		.as2868-disconnect_popover {
			width:200px !important;
			margin:auto !important;
		}
	}
	.as2868-connect_popover_active>div,
	.as2868-disconnect_popover>div,
	.as2868-connect_popover>div {
		width:fit-content;
		margin:auto;
	}
	.as2868-connect_popover_active a,
	.as2868-connect_popover a {
		text-decoration:none !important;
	}
	.as2868-connect_popover_active  .as2868-pc,
	.as2868-disconnect_popover .as2868-pc,
	.as2868-connect_popover .as2868-pc {
		background:transparent;
		font-size:16px !important;
		color:#000000;
		height:30px;
		cursor:pointer;
	}
	.as2868-disconnect_popover .as2868-pc:hover,
	.as2868-connect_popover .as2868-pc:hover {
		background:#F1F3F5;
	}
	.as2868-connect_popover_active .as2868-pc img,
	.as2868-disconnect_popover .as2868-pc img,
	.as2868-connect_popover .as2868-pc img {
		margin:8px 8px 0 10px;
		float:left;
	}
	.as2868-connect_popover_active .as2868-pc .mdi,
	.as2868-connect_popover .as2868-pc .mdi {
		font-size:22px;
		margin:3px 4px 0 0;
		float:left;
	}
	.as2868-disconnect_popover .as2868-pc .mdi {
		font-size:22px;
		margin:3px 4px 0 0;
		float:left;
	}
	.as2868-connect_popover_active .as2868-pt,
	.as2868-connect_popover .as2868-pt {
		margin:4px 0 0 0;
		font-size:20px !important;
		color:#000000;
		font-weight:600;
		float:left;
	}
	.as2868-disconnect_popover .as2868-pt {
		margin:4px 0 0 0;
		font-size:20px !important;
		color:#000000;
		font-weight:600;
		float:left;
	}
	#as2869-wbbf .fa-asterisk {
		color:#000000;
		font-size:18px;
	}
	#as2869-wbbf .input-group {
		border:2px solid #fff;
		border-radius:12px;
		background-color:#3e3567;
	}
	#as2869-wbbf .input-group-text {
		background:#ffffff;
		border:0 !important;
		color:#ffffff;
		background:#3e3567 !important;
		font-size:16px;
		font-weight:normal !important;
		border-radius:10px;
		padding:10px 5px 10px 10px !important;
	}
	#as2869-wbbf .as2869-fcs {
		outline:none !important;
		box-shadow:none !important;
		border:0 !important;
		color:#ffffff;
		background:#3e3567;
		font-size:24px;
		font-weight:normal !important;
		border-radius:12px;
		padding:10px 0 !important;
		height:48px !important!
	}
	#as2869-wbbf .as2869-fcs:focus {
		border:0 !important;
		font-weight:normal !important;
	}
	#as2869-wbbf .as2869-fcs::placeholder {
	  color:#8b86a4;
	  opacity: 1; 
	  font-weight:normal !important;
	}
	#as2869-wbbf .as2869-fcs::-ms-input-placeholder {
	  color:#8b86a4;
	}
	#as2869-wbbf .input-group-text img {
		width:24px;
	}
	#as2869-wbbf .btn-turquoise {
		bottom:70px;
		width:100;
	}
	#as2869-wbbf .fa-question-circle {
		color:#8b86a4;
		font-size:22px;
		cursor:pointer;
		background:transparent;
		outline:none;
		border:none;
		padding:0;
		margin:0;
	}
	#as2869-wbbf .as2869-qa {
		padding-right:10px !important;
	}
	@media (min-width: 700px) {
		#as2869-wbbf .btn-turquoise {   
			width:calc(700px - 30px);
		}
	}
	#as2869-wbbf #as2869-bbc {
		margin-bottom:25px;
		font-size:18px;
	}
	#as2869-wbbf #as2869-bbc #as2869-aba {
		color:green;
	}
	#as2869-wbbf #as2869-bbc .mdi-logout {
		cursor:pointer;
		color:#f79f4c;
	}
	#as2869-wbbf #as2869-bbc .copy_button {
		cursor:pointer;
	}
	#as2869-wbbf #as2869-bbc .as2869-tbcb {
		font-size:22px;
		margin-bottom:25px;
	}
	.as2868-question_popover {
		border-radius:8px;
		padding:10px;
	}
	.as2868-question_popover .fa-external-link-alt {
		color:#666666;
		text-decoration:underline !important;
		font-size:14px;
	}
	.as2869-btwc {
		background:hsla(230, 100%, 67%, 1) !important;
		border-color:#0f0638;
		color:#fff;
	}
	.as2868-spin-active {
		color:#fff;
		font-size:30px;
		margin:0 0 0 50%;
	}
	
", ['id'=>'as2-sol']);

$this->registerJs('

	class SOL {
		
		constructor() {
			this.button = "";
			this.form = "";
			this.id = 0;
			this.sc = "";
			this.app = new appFinkeeper();
			this.connect = 0;
			this.solSummActive = 0;
			this.userActives = {sol:{}};
			this.userActivesMin = {sol:{}};
			this.turnSOLStatus = 0;
			this.saveSOLActives = {};
		}
		
		/**
		 * optionsSOL(options)
		 */
		optionsSOL(options) {
			
			if (typeof options!=="undefined" && options!==undefined && options) {
				
				if (typeof options.elementButton!=="undefined" && options.elementButton!==undefined && options.elementButton) {
					this.button = options.elementButton;
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
				
				if (typeof options.connect!=="undefined" && options.connect!==undefined && options.connect) {
					this.connect = options.connect;
				}
				
				this.turnSOLStatus = this.app.getSettingsLS("solturn");
			}
		}
		
		/**
		 * getSOL(options)
		 */
		getSOL(options) {
			
			this.optionsSOL(options);
			this.createButton();
			this.createForm();
			this.solconnect(1);
		}
		
		/**
		 * createButton()
		 */
		createButton() {

			var button = "";
			var $this = this;
		
			jQuery("#" + this.button).addClass("as2868-wcb");
			
			var turnClass = "as2868-turn-off";
			var turnIcon = "mdi mdi-eye-off";
			var turnText = "'.Yii::t('Api', 'Turn off').'";
			var visibleIcon = "";
			if (typeof $this.turnSOLStatus!=="undefined" && $this.turnSOLStatus!==undefined && $this.turnSOLStatus==1) {
				var turnClass = "as2868-turn-on";
				var turnIcon = "mdi mdi-eye-outline";
				var turnText = "'.Yii::t('Api', 'Turn on').'";
				var visibleIcon = "<span class=\"mdi mdi-eye-off as2-eye\"></span>";
			}

			button += "<span class=\"mdi mdi-wifi-off as2-wifi\"></span><div tabindex=\"0\" role=\"button\" id=\"as2868-becb\" class=\"as2868-acb\"></div><div class=\"as2868-bcb\" style=\"display:none\"><span class=\"far fa-hourglass fa-spin\"></span></div>" + visibleIcon;
		
			jQuery("#" + this.button).html(button); 
		
			jQuery("#as2868-becb").popover({
				placement: "bottom",
				content: " ",
				container: "body",
				trigger: "click",
				template: "<div class=\"popover as2868-connect_popover\" role=\"tooltip\"><div class=\"as2868-pc\"><div class=\"as2868-bycb\"><div class=\"mdi mdi-cog-outline\"></div><div class=\"as2868-pt\"><a href=\"/app/connect?id=4\" alt=\"'.Yii::t('Api', 'Manage SOL').'\">'.addslashes(str_replace(["\n", "\r"], "", Yii::t('Api', 'Manage'))).'</a></div><div class=\"clearfix\"></div></div><div class=\"as2868-bycb  " + turnClass + "\" data-id=\"sol\" data-status=\"0\"><div class=\"" + turnIcon + "\"></div><div class=\"as2868-pt\">" + turnText + "</div><div class=\"clearfix\"></div></div><div class=\"as2868-bycb as2868-refresh\" data-id=\"sol\"><div class=\"mdi mdi-refresh\"></div><div class=\"as2868-pt\">'.Yii::t('Api', 'Refresh').'</div><div class=\"clearfix\"></div></div></div></div>",
			});
	
			jQuery(document).delegate(".as2868-refresh", "click", function() {
				var elem = jQuery("#as2868-becb");
				var popover = bootstrap.Popover.getInstance(elem);
				popover.hide();
				userActives.data.sol = {};
				$this.solconnect(1);				
			});
			
			jQuery(document).delegate(".as2868-turn-off", "click", function() {
				
				$this.app.setSettingsLS("solturn", 1);
				$this.turnSOLStatus = 1;
				$this.updateManagePopover($this.connect);
		
				if (typeof userActives.data.sol!=="undefined" && userActives.data.sol!==undefined && userActives.data.sol) {
					userActives.data.sol = {};
					solSummActive = 0;
					getAllActive()
					addListCoin(2);
					jQuery("#" + $this.button).append("<span class=\"mdi mdi-eye-off as2-eye\"></span>");
				}
			});

			jQuery(document).delegate(".as2868-turn-on", "click", function() {

				$this.app.setSettingsLS("solturn", 0);
				$this.turnSOLStatus = 0;
				$this.updateManagePopover($this.connect);

				if (typeof userActives.data.sol!=="undefined" && userActives.data.sol!==undefined && userActives.data.sol) {
					userActives.data.sol = $this.saveSOLActives;
					solSummActive = $this.solSummActive;
					getAllActive()
					addListCoin(2);
					jQuery("#" + $this.button).find(".as2-eye").remove();
				}
			});
		}		
		
		/**
		 * createForm()
		 */
		createForm() {

			var form = "";
			var $this = this;
			
			form += "<div id=\"as2869-wbbf\"><div id=\"as2869-bbc\"><div  class=\"float-start\"  style=\"width:calc(100% - 80px)\"><div class=\"as2869-tbcb\">'.Yii::t('Api', 'Connection account').':</div><div id=\"as2869-aba\"></div></div><div class=\"float-start\" style=\"width:80px;overflow:hidden\"><img style=\"max-width:100%\"  src=\"/images/svg/currency/sol.svg\"></div><div class=\"clearfix\"></div></div>";

			form += "<div class=\"as2869-ba\">'.Yii::t('Api', 'Or connect SOL wallet via WalletConnect').'</div>";

			form += "<div class=\"input-group-sol mt-17\">'.addslashes(Html::button(Yii::t('Api', 'Connect Wallet'), [
				'id' => 'sol-appkit-connect-button-as864',
				'class' =>  'btn-turquoise as2869-btwc',
			])).'</div>";

			form += "<div class=\"as2869-ba\">'.Yii::t('Api', 'Please provide your SOL address wallet').'</div>";
			
			form +="<div class=\"input-group as2869-igb mt-17\"><div class=\"input-group-text bg-transparent border-right-0\" id=\"as2869-ba1\"><img src=\"/images/icons/lock.svg\" alt=\"\" title=\"\" /></div>'.addslashes(Html::textInput('exname', '',[
				'autocomplete' => 'off', 
				'id' => 'as2869-cbe',
				'class' =>  'form-control as2869-fcs',
				'placeholder' => Yii::t('Api', 'Bybit exname'),
				'type' => 'text',
			])).'<div class=\"input-group-text bg-transparent border-left-0 as2869-qa\"><a tabindex=\"0\" role=\"button\" id=\"as2869-qa1\" class=\"fa fa-question-circle\"></a></div></div>";
	
			form +="<div class=\"input-group as2869-igb mt-17\"><div class=\"input-group-text bg-transparent border-right-0\" id=\"as2869-ba2\"><img src=\"/images/icons/lock.svg\" alt=\"\" title=\"\" /></div>'.addslashes(Html::textInput('address', '',[
				'autocomplete' => 'off', 
				'id' => 'as2869-cba',
				'class' =>  'form-control as2869-fcs',
				'placeholder' => Yii::t('Api', 'SOL Address Wallet'),
				'type' => 'password',
			])).'<div class=\"input-group-text bg-transparent border-left-0 as2869-qa\"><a tabindex=\"0\" role=\"button\" id=\"as2869-qa2\" class=\"fa fa-question-circle\"></a></div></div>";
		
			form += "'.addslashes(Html::button(Yii::t('Form', 'SOL Address Wallet Sent'), [
				'id' => 'as2869-cba-send',
				'class' =>  'btn-turquoise',
			])).'</div>";

			jQuery("#" + this.form).html(form); 
			
			$("#as2869-qa1").popover({
				placement: "left",
				content: "This is the body of Popover",
				trigger: "focus",
				template: "<div class=\"popover as2868-question_popover\" role=\"tooltip\"><div class=\"popover-arrow\" style=\"position: absolute; top: 0px; transform: translate(0px, 12px);\"></div><div class=\"as2868-pc\"><div class=\"as2869-qap\"><div class=\"as2868-pt\">'.addslashes(str_replace(["\n", "\r"], "", Yii::t('Api', 'Question SOL Name'))).' <a href=\"https://finkeeper.gitbook.io/finkeeper/integration/exchange\" target=\"_blank\">'.Yii::t('Api', 'Detailed instructions').' <i class=\"fa fa-external-link-alt\"></i></a></div><div class=\"clearfix\"></div></div></div></div>",
			});
			
			$("#as2869-qa2").popover({
				placement: "left",
				content: "This is the body of Popover",
				trigger: "focus",
				template: "<div class=\"popover as2868-question_popover\" role=\"tooltip\"><div class=\"popover-arrow\" style=\"position: absolute; top: 0px; transform: translate(0px, 12px);\"></div><div class=\"as2868-pc\"><div class=\"as2869-qap\"><div class=\"as2868-pt\">'.addslashes(str_replace(["\n", "\r"], "", Yii::t('Api', 'Question SOL Address'))).' <a href=\"https://finkeeper.gitbook.io/finkeeper/integration/exchange\" target=\"_blank\">'.Yii::t('Api', 'Detailed instructions').' <i class=\"fa fa-external-link-alt\"></i></a></div><div class=\"clearfix\"></div></div></div></div>",
			});

			$("#as2869-bbc").delegate("#as2869-aba .mdi-logout", "click", function() {
				var idConnect = $(this).attr("data-id");
				$this.solconnect(3, "", idConnect);
			});
			
			$("#as2869-wbbf").delegate("#as2869-cba-send", "click", function() {
				$this.displayButtonBackdrop(1);
				$this.solconnect(2, "");
			});
			
			jQuery(document).delegate("#sol-appkit-connect-button-as864", "click", function() {
				var send_button = jQuery(this);
				var text_button = jQuery(send_button).text();
				var spinner = "<i class=\"fas fa-asterisk fa-spin\" style=\"color:#fff\"></i>";
				jQuery(send_button).html(spinner + "&nbsp;" + text_button);
			});
			
			$("#as2869-wbbf").delegate(".copy_button", "click", function() {
				var address = $(this).find("input[type=hidden]").val();
				$this.app.copyValue(address);
			});
		}
		
		/**
		 * solconnect(type, address, id) 
		 */
		solconnect(type, address, id) {
	
			var $this = this;
	
			if (typeof type==="undefined" || type===undefined || !type) {
				$this.app.addNotify("'.Yii::t('Error', 'Missing type connect').'", "error");
				return false;
			}
		
			if (type==1) {

				if (!this.connect) {
					return false;
				}
				
				var spinner = "<i class=\"fas fa-spinner fa-spin as2868-spin-active\"></i>";
				jQuery("#as2869-aba").html(spinner);

			} else if(type==2) {
				
				if (address==="undefined" || address===undefined || !address) {
		
					var address = $("#as2869-cba").val();
					if (typeof address==="undefined" || address===undefined || !address) {
						addNotify("'.Yii::t('Error', 'Missing SOL Address Wallet').'", "error");
						$(".fa-asterisk").remove();
						return false;
					}
			
				}
				
				var exname = $("#as2869-cbe").val();
				if (typeof exname==="undefined" || exname===undefined || !exname) {
					var exname = "";
				}

			} else if(type==3) {

				if (typeof id==="undefined" || id===undefined || !id) {
					$this.app.addNotify("'.Yii::t('Error', 'Missing SOL Account ID').'", "error");
					return false;
				}

				$this.displayDisconnectIcon(id, 1);
			}
			
			this.displayIconBackdrop(1);

			jQuery.ajax({
				"url": "/app/getsolbalance",
				"type": "post",
				"dataType": "json",
				"contentType": "application/json",
				"data": JSON.stringify({"type": type, id: id, "log_id": $this.id, sc: $this.sc, "address": address, exname: exname}),
				"success": function(response){

					$(".fa-asterisk").remove();
					$this.displayIconBackdrop(0);
					jQuery("#as2869-aba").html("");
					
					if (type==2) {
						
						$this.displayButtonBackdrop(0);
						
					} else if(type==3) {
						
						$this.displayDisconnectIcon(id, 0);
					}
				
					if (response) {
				
						if (!response.error) {

							if (type==1) {

								$this.displayConnectIcon(1);

							} else if(type==2) {
							
								$this.displayConnectIcon(1);
			
								jQuery("#as2869-cbe").val("");
								jQuery("#as2869-cba").val("");
				
							} else if(type==3) {
							
								if (response.connect==0) { 
								
									$this.displayConnectIcon(0);
								
								} else {
									
									$this.displayConnectIcon(1);
								}
							}
	
							$this.createObjectsActives(response);
							$this.connect = response.connect;
					
						} else {	
							$this.app.addNotify("SOL: " + response.message, "error");
							$this.displayConnectIcon(0);
							solConnectedStatus = false;
							$this.connect = 0;
							return false;
						}
						
					} else {
						$this.app.addNotify("SOL: '.Yii::t('Error', 'Server not response').'", "error");
						$this.displayConnectIcon(0);
						solConnectedStatus = false;
						$this.connect = 0;
						return false;
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					$this.displayIconBackdrop(0);
					$this.app.addNotify("SOL: " + thrownError, "error");
					$this.displayConnectIcon(0);
					solConnectedStatus = false;
					$this.connect = 0;
					return false;
				}
			});
		
		}
		
		/**
		 * displayButtonBackdrop(flag=0)
		 */
		displayButtonBackdrop(flag=0) {

			var send_button = jQuery("#as2869-cba-send");
			var text_button = jQuery(send_button).text();
			var spinner = "<i class=\"fas fa-asterisk fa-spin\"></i>";
		
			if (flag) {
				if (typeof send_button !=="undefined" && send_button!==undefined && send_button) {
					jQuery(send_button).html(spinner + "&nbsp;" + text_button);
				}
			} else {
				if (typeof send_button !=="undefined" && send_button!==undefined && send_button) {
					jQuery(send_button).html(text_button);
				}
			}	
		}
		
		/**
		 * displayIconBackdrop(flag=0)
		 */
		displayIconBackdrop(flag=0) {

			var elem = jQuery("#" + this.button + " .as2868-bcb");
		
			if (flag) {
				jQuery(elem).css("left", "0px");
				jQuery(elem).show();
			} else {
				jQuery(elem).css("left", "1000px");
				jQuery(elem).hide();
			}	
		}
		
		/**
		 * displayIconBackdrop(flag=0)
		 */
		displayDisconnectIcon(id, flag=0) {

			if (typeof id==="undefined" || id===undefined || !id) {
				addNotify("'.Yii::t('Error', 'Missing OKX Account ID').'", "error");
				return false;
			}
	
			var elem = jQuery("[data-id=" + id + "]");

			if (flag) {
				jQuery(elem).removeClass("mdi-logout").addClass("mdi-loading mdi-spin");
			} else {
				jQuery(elem).removeClass("mdi-loading mdi-spin").addClass("mdi-logout");
			}	
		}
		
		/**
		 * displayConnectIcon(flag=0)
		 */
		displayConnectIcon(flag=0) {

			var elem = jQuery("#" + this.button + " .as2-wifi");

			if (flag) {
				jQuery(elem).removeClass("mdi-wifi-off").addClass("mdi-wifi");
				this.updateManagePopover(1);
			} else {
				jQuery(elem).removeClass("mdi-wifi").addClass("mdi-wifi-off");
				this.updateManagePopover(0);
			}	
		}
		
		/**
		 * createObjectsActives(response)
		 */
		createObjectsActives(response) {
	
			if (typeof response==="undefined" || response===undefined || !response || typeof response!=="object") {
				addNotify("'.Yii::t('Error', 'Missing SOL Data').'", "error");
				return false;
			}
			
			if (typeof response.data==="undefined" || response.data===undefined || !response.data || typeof response.data!=="object") {
				addNotify("'.Yii::t('Error', 'Missing SOL Data').'", "error");
				return false;
			}
			
			var $this = this;
			
			$this.solSummActive = response.summ;
			userActives.grafema = response.grafema;
							
			solSummActive = response.summ;
			getAllActive();

			for (var key in response.data) {
					
				if (typeof userActivesMin.sol[response.data[key].asset]==="undefined" || userActivesMin.sol[response.data[key].asset]===undefined || !userActivesMin.sol[response.data[key].asset] || userActivesMin.sol[response.data[key].asset]!=="object") {
							
					userActivesMin.sol[response.data[key].asset] = {};	

				}
				
				if (typeof userActivesMin.sol[response.data[key].asset].active==="undefined" || userActivesMin.sol[response.data[key].asset].active===undefined || !userActivesMin.sol[response.data[key].asset].active || userActivesMin.sol[response.data[key].asset].active!=="object") {
							
					userActivesMin.sol[response.data[key].asset]["active"] = {};
							
				}	
				
				if (typeof userActivesMin.sol[response.data[key].asset].trading==="undefined" || userActivesMin.sol[response.data[key].asset].trading===undefined || !userActivesMin.sol[response.data[key].asset].trading || userActivesMin.sol[response.data[key].asset].trading!=="object") {
					userActivesMin.sol[response.data[key].asset]["trading"] = {};
				}	
				
				
				if (typeof userActives.data.sol[response.data[key].asset]==="undefined" || userActives.data.sol[response.data[key].asset]===undefined || !userActives.data.sol[response.data[key].asset] || userActives.data.sol[response.data[key].asset]!=="object") {
							
					userActives.data.sol[response.data[key].asset] = {};	

				}
				
				if (typeof userActives.data.sol[response.data[key].asset].active==="undefined" || userActives.data.sol[response.data[key].asset].active===undefined || !userActives.data.sol[response.data[key].asset].active || userActives.data.sol[response.data[key].asset].active!=="object") {
							
					userActives.data.sol[response.data[key].asset]["active"] = {};
							
				}	
				
				if (typeof userActives.data.sol[response.data[key].asset].trading==="undefined" || userActives.data.sol[response.data[key].asset].trading===undefined || !userActives.data.sol[response.data[key].asset].trading || userActives.data.sol[response.data[key].asset].trading!=="object") {
					userActives.data.sol[response.data[key].asset]["trading"] = {};
				}	
		
				userActives.data.sol[response.data[key].asset].asset = response.data[key].asset;
	
				if (typeof response.data[key].error==="undefined" || response.data[key].error===undefined || !response.data[key].error || response.data[key].error.length==0) {
				
					var htmlConnect = "<div class=\"row_" + response.data[key].asset + "\"><span id=\"sol_name_" + response.data[key].asset + "\">" + response.data[key].connectname + "</span>&nbsp;&nbsp;<span id=\"sol_uid_" + response.data[key].asset + "\">" + $this.app.stringReplace(response.data[key].asset, "...", 6, 6) + "</span>&nbsp;&nbsp;<span class=\"copy_button\"><img src=\"/images/icons/copy.svg\" alt=\"\" title=\"\"><input type=\"hidden\" value=\"" + response.data[key].asset + "\"></span>&nbsp;&nbsp;<span id=\"sol_disconnect_" + response.data[key].asset + "\" class=\"mdi mdi-logout\" data-id=\"" + response.data[key].asset + "\" title=\"'.Yii::t('Api', 'Disconnect').'\"></span></div>";
					
				} else {
					
					var htmlConnect = "<div class=\"row_" + response.data[key].asset + "\" style=\"color:red\"><span id=\"sol_name_" + response.data[key].asset + "\">" + response.data[key].connectname + "</span>&nbsp;&nbsp;<span id=\"sol_uid_" + response.data[key].asset + "\">" + $this.app.stringReplace(response.data[key].asset, "...", 6, 1) + "</span>&nbsp;&nbsp;<span class=\"copy_button\"><img src=\"/images/icons/copy.svg\" alt=\"\" title=\"\"><input type=\"hidden\" value=\"" + response.data[key].asset + "\"></span>&nbsp;&nbsp;<span id=\"sol_disconnect_" + response.data[key].asset + "\" class=\"mdi mdi-logout\" data-id=\"" + response.data[key].asset + "\" title=\"'.Yii::t('Api', 'Disconnect').'\"></span><span class=\"error_sol_connect\">(" + response.data[key].error + ")</span></div>";
				}
		
				jQuery("#as2869-aba").append(htmlConnect);
				
				if (response.data[key].active && response.data[key].active.length) {

					response.data[key].active.forEach((val) => {
				
						userActivesMin.sol[response.data[key].asset].active[val.symbolid] = {
							"symbol": val.symbol,
							"balance": val.balance,
							"price": val.price,
						}
						
						if (typeof userActives.data.sol[response.data[key].asset].active[val.symbolid]==="undefined" || userActives.data.sol[response.data[key].asset].active[val.symbolid]===undefined || !userActives.data.sol[response.data[key].asset].active[val.symbolid] || userActives.data.sol[response.data[key].asset].active[val.symbolid].length==0) {

							userActives.data.sol[response.data[key].asset].active[val.symbolid] = {
								"img": val.img,
								"symbol": val.symbol,
								"name": val.name,
								"symbolid": val.symbolid,
								"coinid": val.coinid,
								"asset": val.asset,
								"type": "solactive",
								"connectname": response.data[key].connectname,
								"source": "sol",
								"listCoin": [],
							};
						}

						userActives.data.sol[response.data[key].asset].active[val.symbolid].listCoin.push({
							"currency_value": val.currency_value,
							"balance": val.balance,
							"sort": val.sort,
							"apr": val.apr,
							"price": val.price,
							"network": val.network,
							"network_icon": val.network_icon,
						});
					});
				}
		
				jQuery("#asModal #title_balance").html("");
				$this.connect = 1;
				solConnectedStatus = true;
	
			}
	
			if (typeof (addListCoin) === "function") {
				$this.saveSOLActives = userActives.data.sol;
				if (typeof $this.turnSOLStatus!=="undefined" && $this.turnSOLStatus!==undefined && $this.turnSOLStatus==1) {
					userActives.data.sol = {};
					solSummActive = 0;
					getAllActive();
				}	
				addListCoin(2);
			}
		}
		
		/**
		 * updateManagePopover(connect=0)
		 */
		updateManagePopover(connect=0) {
			
			var $this = this;
			
			var turnClass = "as2868-turn-off";
			var turnIcon = "mdi mdi-eye-off";
			var turnText = "'.Yii::t('Api', 'Turn off').'";

			if (typeof $this.turnSOLStatus!=="undefined" && $this.turnSOLStatus!==undefined && $this.turnSOLStatus==1) {
				var turnClass = "as2868-turn-on";
				var turnIcon = "mdi mdi-eye-outline";
				var turnText = "'.Yii::t('Api', 'Turn on').'";
			}

			var template = "";
			if (!connect) {
				
				template = "<div class=\"popover as2868-connect_popover\" role=\"tooltip\"><div class=\"as2868-pc\"><div class=\"as2868-bycb\"><div class=\"mdi mdi-cog-outline\"></div><div class=\"as2868-pt\"><a href=\"/app/connect?id=4\" alt=\"'.Yii::t('Api', 'Manage SOL').'\">'.addslashes(str_replace(["\n", "\r"], "", Yii::t('Api', 'Manage'))).'</a></div><div class=\"clearfix\"></div></div><div class=\"as2868-bycb " + turnClass + "\" data-id=\"sol\" data-status=\"0\"><div class=\"" + turnIcon + "\"></div><div class=\"as2868-pt\">" + turnText + "</div><div class=\"clearfix\"></div></div><div class=\"as2868-bycb as2868-refresh\" data-id=\"sol\"><div class=\"mdi mdi-refresh\"></div><div class=\"as2868-pt\">'.Yii::t('Api', 'Refresh').'</div><div class=\"clearfix\"></div></div></div></div>";
				
			} else {
				
				template = "<div class=\"popover as2868-connect_popover_active\" role=\"tooltip\"><div class=\"as2868-pc\"><div class=\"as2868-bycb\"><div class=\"mdi mdi-cog-outline\"></div><div class=\"as2868-pt\"><a href=\"/app/connect?id=4\" alt=\"'.Yii::t('Api', 'Manage SOL').'\">'.addslashes(str_replace(["\n", "\r"], "", Yii::t('Api', 'Manage'))).'</a></div><div class=\"clearfix\"></div></div><div class=\"as2868-bycb " + turnClass + "\" data-id=\"sol\" data-status=\"0\"><div class=\"" + turnIcon + "\"></div><div class=\"as2868-pt\">" + turnText + "</div><div class=\"clearfix\"></div></div><div class=\"as2868-bycb as2868-refresh\" data-id=\"sol\"><div class=\"mdi mdi-refresh\"></div><div class=\"as2868-pt\">'.Yii::t('Api', 'Refresh').'</div><div class=\"clearfix\"></div></div></div></div>";
			}
			
			this.app.updatePopover("as2868-becb", template);
		}
	}
', yii\web\View::POS_END);