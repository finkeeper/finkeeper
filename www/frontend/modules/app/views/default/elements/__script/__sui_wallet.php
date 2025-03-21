<?php

use yii\bootstrap5\Html;

$this->registerCss("
	#wrap-sui-form {
		padding:32px 16px 160px 16px;
		background-color:#0f0638;
	}
	.as2898-wcb {
		background-image: url(/images/logos/sui_connect.png);
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
	.as2898-wcb .mdi-wifi {
		color:#00ff7f;
		font-size:20px;
		position:absolute;
		top:0px;
		left:2px;
	}
	.as2898-wcb .mdi-wifi-off {
		color:#ccc;
		font-size:20px;
		position:absolute;
		top:0px;
		left:2px;
	}
	.as2898-wcb .fa-hourglass {
		position:absolute;
		top:50%;
		left:50%;
		margin:-10px 0 0 -10px;
		font-size:22px;
		color:#000;
	}
	.as2898-wcb .connect-loader {
		width:26px;
		height:26px;
		position:absolute;
		top:50%;
		left:50%;
		margin:-13px 0 0 -13px;
	}
	.as2898-wcb .as2898-bcb {
		position:absolute;
		top:0;
		left:0;
		right:0;
		bottom:0;
		background:#fff;
		opacity:0.8;
		z-index:6;
	}
	.as2898-wcb .as2898-acb {
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
	.as2898-pc {
		background:transparent;
		font-size:16px !important;
		color:#000000;
		height:fit-content;
		width:fit-content;
		cursor:pointer;
	}
	.as2898-pc:hover {
		background:#F1F3F5;
	}
	.as2898-pt a {
		text-decoration:underline !important;
		color:#000000;
	}
	.as2898-connect_popover {
		box-shadow: 0 4px 24px rgba(0, 0, 0, 0.16);
		overflow:hidden;
		box-sizing:border-box;
		padding:4px;
		border-radius:16px;
		margin-top:0px !important;
		width:190px;
		height:56px;
	}
	.as2898-disconnect_popover {
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
		.as2898-connect_popover{
			width:170px !important;
			margin:auto !important;
		}
		.as2898-disconnect_popover {
			width:200px !important;
			margin:auto !important;
		}
	}
	.as2898-disconnect_popover>div,
	.as2898-connect_popover>div {
		width:fit-content;
		margin:auto;
	}
	.as2898-disconnect_popover .as2898-pc,
	.as2898-connect_popover .as2898-pc {
		background:transparent;
		font-size:16px !important;
		color:#000000;
		height:30px;
		cursor:pointer;
	}
	.as2898-disconnect_popover .as2898-pc:hover,
	.as2898-connect_popover .as2898-pc:hover {
		background:#F1F3F5;
	}
	.as2898-disconnect_popover .as2898-pc img,
	.as2898-connect_popover .as2898-pc img {
		margin:8px 8px 0 10px;
		float:left;
	}
	.as2898-connect_popover .as2898-pc .mdi {
		font-size:22px;
		margin:3px 4px 0 0;
		float:left;
	}
	.as2898-disconnect_popover .as2898-pc .mdi {
		font-size:22px;
		margin:3px 4px 0 0;
		float:left;
	}
	.as2898-connect_popover .as2898-pt {
		margin:4px 0 0 0;
		font-size:20px !important;
		color:#000000;
		font-weight:600;
		float:left;
	}
	.as2898-disconnect_popover .as2898-pt {
		margin:4px 0 0 0;
		font-size:20px !important;
		color:#000000;
		font-weight:600;
		float:left;
	}
	#as2899-wbbf .fa-asterisk {
		color:#000000;
		font-size:18px;
	}
	#as2899-wbbf .input-group {
		border:2px solid #fff;
		border-radius:12px;
		background-color:#3e3567;
	}
	#as2899-wbbf .input-group-text {
		background:#ffffff;
		border:0 !important;
		color:#ffffff;
		background:#3e3567 !important;
		font-size:16px;
		font-weight:normal !important;
		border-radius:10px;
		padding:10px 5px 10px 10px !important;
	}
	#as2899-wbbf .as2899-fcs {
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
	#as2899-wbbf .as2899-fcs:focus {
		border:0 !important;
		font-weight:normal !important;
	}
	#as2899-wbbf .as2899-fcs::placeholder {
	  color:#8b86a4;
	  opacity: 1; 
	  font-weight:normal !important;
	}
	#as2899-wbbf .as2899-fcs::-ms-input-placeholder {
	  color:#8b86a4;
	}
	#as2899-wbbf .input-group-text img {
		width:24px;
	}
	#as2899-wbbf .btn-turquoise {
		bottom:70px;
		width:100;
	}
	#as2899-wbbf .fa-question-circle {
		color:#8b86a4;
		font-size:22px;
		cursor:pointer;
		background:transparent;
		outline:none;
		border:none;
		padding:0;
		margin:0;
	}
	#as2899-wbbf .as2899-qa {
		padding-right:10px !important;
	}
	@media (min-width: 700px) {
		#as2899-wbbf .btn-turquoise {   
			width:calc(700px - 30px);
		}
	}
	#as2899-wbbf #as2899-bbc {
		margin-bottom:25px;
		font-size:18px;
	}
	#as2899-wbbf #as2899-bbc #as2899-aba {
		color:green;
	}
	#as2899-wbbf #as2899-bbc .mdi-logout {
		cursor:pointer;
	}
	#as2899-wbbf #as2899-bbc .as2899-tbcb {
		font-size:22px;
		margin-bottom:25px;
	}
	.as2898-question_popover {
		border-radius:8px;
		padding:10px;
	}
	.as2898-question_popover .fa-external-link-alt {
		color:#666666;
		text-decoration:underline !important;
		font-size:14px;
	}
	.as2899-btwc {
		background:hsla(230, 100%, 67%, 1) !important;
		border-color:#0f0638;
		color:#fff;
	}
	#suikit-connect-button-as164 {
		position:relative;
	}
	#suikit-connect-button-as164 .fa-asterisk {
		position:absolute;
		top:20px;
		left:50%;
		z-index:100;
		margin-left:-120px;
	}
	
", ['id'=>'as2-sui']);

$this->registerJs('

	class SUI {
		
		constructor() {
			this.button = "";
			this.form = "";
			this.id = 0;
			this.sc = "";
			this.app = new appFinkeeper();
			this.connect = 0;
			this.suiSummActive = 0;
			this.userActives = {sui:{}};
			this.userActivesMin = {sui:{}};
		}
		
		/**
		 * optionsSUI(options)
		 */
		optionsSUI(options) {
			
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
			}
		}
		
		/**
		 * getSUI(options)
		 */
		getSUI(options) {
			
			this.optionsSUI(options);
			this.createButton();
			this.createForm();
		}
		
		/**
		 * createButton()
		 */
		createButton() {

			var button = "";
			var $this = this;
		
			jQuery("#" + this.button).addClass("as2898-wcb");
			
			button += "<span class=\"mdi mdi-wifi-off\"></span><div tabindex=\"0\" role=\"button\" id=\"as2898-becb\" class=\"as2898-acb\"></div><div class=\"as2898-bcb\" style=\"display:none\"><span class=\"far fa-hourglass fa-spin\"></span></div>";
		
			jQuery("#" + this.button).html(button); 
		
			jQuery("#as2898-becb").popover({
				placement: "bottom",
				content: " ",
				container: "body",
				trigger: "click",
				template: "<div class=\"popover as2898-connect_popover\" role=\"tooltip\"><div class=\"as2898-pc\"><div class=\"as2898-bycb\"><div class=\"mdi mdi-cog-outline\"></div><div class=\"as2898-pt\">'.addslashes(str_replace(["\n", "\r"], "", Yii::t('Api', 'Manage'))).'</div><div class=\"clearfix\"></div></div></div></div>",
			});

			this.suiconnect(1);
		}		
		
		/**
		 * createForm()
		 */
		createForm() {
		
			var form = "";
			var $this = this;
			
			form += "<div id=\"as2899-wbbf\"><div id=\"as2899-bbc\"><div  class=\"float-start\"  style=\"width:calc(100% - 60px)\"><div class=\"as2899-tbcb\">'.Yii::t('Api', 'Connection account').':</div><div id=\"as2899-aba\"></div></div><div class=\"float-start\" style=\"width:60px;overflow:hidden\"><img style=\"max-width:100%\"  src=\"/images/svg/currency/sui.svg\"></div><div class=\"clearfix\"></div></div>";

			form += "<div class=\"as2899-ba\">'.Yii::t('Api', 'Or connect SUI wallet via WalletConnect').'</div>";

			form += "<div class=\"input-group-sui mt-17\"><div id=\"suikit-connect-button-as164\"></div></div>";

			form += "<div class=\"as2899-ba\">'.Yii::t('Api', 'Please provide your SUI address wallet').'</div>";
			
			form +="<div class=\"input-group as2899-igb mt-17\"><div class=\"input-group-text bg-transparent border-right-0\" id=\"as2899-ba1\"><img src=\"/images/icons/lock.svg\" alt=\"\" title=\"\" /></div>'.addslashes(Html::textInput('exname', '',[
				'autocomplete' => 'off', 
				'id' => 'as2899-cbe',
				'class' =>  'form-control as2899-fcs',
				'placeholder' => Yii::t('Api', 'SUI exname'),
				'type' => 'text',
			])).'<div class=\"input-group-text bg-transparent border-left-0 as2899-qa\"><a tabindex=\"0\" role=\"button\" id=\"as2899-qa1\" class=\"fa fa-question-circle\"></a></div></div>";
	
			form +="<div class=\"input-group as2899-igb mt-17\"><div class=\"input-group-text bg-transparent border-right-0\" id=\"as2899-ba2\"><img src=\"/images/icons/lock.svg\" alt=\"\" title=\"\" /></div>'.addslashes(Html::textInput('address', '',[
				'autocomplete' => 'off', 
				'id' => 'as2899-cba',
				'class' =>  'form-control as2899-fcs',
				'placeholder' => Yii::t('Api', 'SUI Address Wallet'),
				'type' => 'password',
			])).'<div class=\"input-group-text bg-transparent border-left-0 as2899-qa\"><a tabindex=\"0\" role=\"button\" id=\"as2899-qa2\" class=\"fa fa-question-circle\"></a></div></div>";
		
			form += "'.addslashes(Html::button(Yii::t('Form', 'SUI Address Wallet Sent'), [
				'id' => 'as2899-cba-send',
				'class' =>  'btn-turquoise',
			])).'</div>";		
				
			jQuery("#" + this.form).html(form); 
			
			$("#as2899-qa1").popover({
				placement: "left",
				content: "This is the body of Popover",
				trigger: "focus",
				template: "<div class=\"popover as2898-question_popover\" role=\"tooltip\"><div class=\"popover-arrow\" style=\"position: absolute; top: 0px; transform: translate(0px, 12px);\"></div><div class=\"as2898-pc\"><div class=\"as2899-qap\"><div class=\"as2898-pt\">'.addslashes(str_replace(["\n", "\r"], "", Yii::t('Api', 'Question SUI Name'))).' <a href=\"https://finkeeper.gitbook.io/finkeeper/integration/exchange\" target=\"_blank\">'.Yii::t('Api', 'Detailed instructions').' <i class=\"fa fa-external-link-alt\"></i></a></div><div class=\"clearfix\"></div></div></div></div>",
			});
			
			$("#as2899-qa2").popover({
				placement: "left",
				content: "This is the body of Popover",
				trigger: "focus",
				template: "<div class=\"popover as2898-question_popover\" role=\"tooltip\"><div class=\"popover-arrow\" style=\"position: absolute; top: 0px; transform: translate(0px, 12px);\"></div><div class=\"as2898-pc\"><div class=\"as2899-qap\"><div class=\"as2898-pt\">'.addslashes(str_replace(["\n", "\r"], "", Yii::t('Api', 'Question SUI Address'))).' <a href=\"https://finkeeper.gitbook.io/finkeeper/integration/exchange\" target=\"_blank\">'.Yii::t('Api', 'Detailed instructions').' <i class=\"fa fa-external-link-alt\"></i></a></div><div class=\"clearfix\"></div></div></div></div>",
			});

			$("#as2899-bbc").delegate("#as2899-aba .mdi-logout", "click", function() {
				var idConnect = $(this).attr("data-id");
				$this.suiconnect(3, "", idConnect);
			});
			
			$("#as2899-wbbf").delegate("#as2899-cba-send", "click", function() {
				$this.displayButtonBackdrop(1);
				$this.suiconnect(2, "");
			});

			jQuery(document).delegate("[data-id=\"swk-wallet-connect\"]", "click", function() {
				var send_button = jQuery("#suikit-connect-button-as164");
				var spinner = "<i class=\"fas fa-asterisk fa-spin\" style=\"color:#fff\"></i>";
				jQuery(send_button).append(spinner);
			});
		}
		
		/**
		 * suiconnect(type, address, id)
		 */
		suiconnect(type, address, id) {
			
			var $this = this;
	
			if (typeof type==="undefined" || type===undefined || !type) {
				$this.app.addNotify("'.Yii::t('Error', 'Missing type connect').'", "error");
				return false;
			}
		
			if (type==1) {

				if (!this.connect) {
					return false;
				}

			} else if(type==2) {
				
				if (address==="undefined" || address===undefined || !address) {
		
					var address = $("#as2899-cba").val();
					if (typeof address==="undefined" || address===undefined || !address) {
						addNotify("'.Yii::t('Error', 'Missing SUI Address Wallet').'", "error");
						$(".fa-asterisk").remove();
						return false;
					}
			
				}
				
				var exname = $("#as2899-cbe").val();
				if (typeof exname==="undefined" || exname===undefined || !exname) {
					var exname = "";
				}
				
			} else if(type==3) {
				
				suiConnectedStatus = false;
				userActives.data.sui = {};
				userActivesMin.sui = {};
				suiSummActive = 0;
				getAllActive();
				
				if (
					!tonConnectedStatus && 
					!bybitConnectedStatus && 
					!okxConnectedStatus && 
					!solConnectedStatus && 
					!aptConnectedStatus &&
					!ethConnectedStatus
				) {
					jQuery("#asModal #title_balance").html("'.Yii::t('Api', 'Connect your wallet to see list of assets').'");
					
				}
	
				if (typeof id==="undefined" || id===undefined || !id) {
					$this.app.addNotify("'.Yii::t('Error', 'Missing SUI Account ID').'", "error");
					return false;
				}

				$this.displayDisconnectIcon(id, 1);
			}
			
			this.displayIconBackdrop(1);

			jQuery.ajax({
				"url": "/app/getsuibalance",
				"type": "post",
				"dataType": "json",
				"contentType": "application/json",
				"data": JSON.stringify({"type": type, id: id, "log_id": $this.id, sc: $this.sc, "address": address, exname: exname}),
				"success": function(response){

					$(".fa-asterisk").remove();
	
					$this.displayIconBackdrop(0);
					jQuery("#as2899-aba").html("");
					
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
			
								jQuery("#as2899-cbe").val("");
								jQuery("#as2899-cba").val("");
				
							} else if(type==3) {
							
								if (response.connect==0) { 
								
									$this.displayConnectIcon(0);
								
								} else {
									
									$this.displayConnectIcon(1);
								}
							}
	
							$this.createObjectsActives(response);
							
					
						} else {	
							$this.app.addNotify("SUI: " + response.message, "error");
							$this.displayConnectIcon(0);
							suiConnectedStatus = false;
							$this.connect = 0;
							return false;
						}
						
					} else {
						$this.app.addNotify("SUI: '.Yii::t('Error', 'Server not response').'", "error");
						$this.displayConnectIcon(0);
						suiConnectedStatus = false;
						$this.connect = 0;
						return false;
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					$this.displayIconBackdrop(0);
					$this.app.addNotify("SUI: " + thrownError, "error");
					$this.displayConnectIcon(0);
					suiConnectedStatus = false;
					$this.connect = 0;
					return false;
				}
			});
		
		}
		
		/**
		 * displayButtonBackdrop(flag=0)
		 */
		displayButtonBackdrop(flag=0) {

			var send_button = jQuery("#as2899-cba-send");
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

			var elem = jQuery("#" + this.button + " .as2898-bcb");
		
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

			var elem = jQuery("#" + this.button + " .mdi");

			if (flag) {
				jQuery(elem).removeClass("mdi-wifi-off").addClass("mdi-wifi");
			} else {
				jQuery(elem).removeClass("mdi-wifi").addClass("mdi-wifi-off");
			}	
		}
		
		/**
		 * createObjectsActives(response)
		 */
		createObjectsActives(response) {
	
			if (typeof response==="undefined" || response===undefined || !response || typeof response!=="object") {
				addNotify("'.Yii::t('Error', 'Missing SUI Data').'", "error");
				return false;
			}
			
			if (typeof response.data==="undefined" || response.data===undefined || !response.data || typeof response.data!=="object") {
				addNotify("'.Yii::t('Error', 'Missing SUI Data').'", "error");
				return false;
			}
			
			var $this = this;
			
			$this.suiSummActive = response.summ;
			userActives.grafema = response.grafema;
							
			suiSummActive = response.summ;
			getAllActive();

			for (var key in response.data) {
					
				if (typeof userActivesMin.sui[response.data[key].asset]==="undefined" || userActivesMin.sui[response.data[key].asset]===undefined || !userActivesMin.sui[response.data[key].asset] || userActivesMin.sui[response.data[key].asset]!=="object") {
							
					userActivesMin.sui[response.data[key].asset] = {};	

				}
				
				if (typeof userActivesMin.sui[response.data[key].asset].active==="undefined" || userActivesMin.sui[response.data[key].asset].active===undefined || !userActivesMin.sui[response.data[key].asset].active || userActivesMin.sui[response.data[key].asset].active!=="object") {
							
					userActivesMin.sui[response.data[key].asset]["active"] = {};
							
				}	
				
				if (typeof userActivesMin.sui[response.data[key].asset].trading==="undefined" || userActivesMin.sui[response.data[key].asset].trading===undefined || !userActivesMin.sui[response.data[key].asset].trading || userActivesMin.sui[response.data[key].asset].trading!=="object") {
					userActivesMin.sui[response.data[key].asset]["trading"] = {};
				}	
				
				
				if (typeof userActives.data.sui[response.data[key].asset]==="undefined" || userActives.data.sui[response.data[key].asset]===undefined || !userActives.data.sui[response.data[key].asset] || userActives.data.sui[response.data[key].asset]!=="object") {
							
					userActives.data.sui[response.data[key].asset] = {};	

				}
				
				if (typeof userActives.data.sui[response.data[key].asset].active==="undefined" || userActives.data.sui[response.data[key].asset].active===undefined || !userActives.data.sui[response.data[key].asset].active || userActives.data.sui[response.data[key].asset].active!=="object") {
							
					userActives.data.sui[response.data[key].asset]["active"] = {};
							
				}	
				
				if (typeof userActives.data.sui[response.data[key].asset].trading==="undefined" || userActives.data.sui[response.data[key].asset].trading===undefined || !userActives.data.sui[response.data[key].asset].trading || userActives.data.sui[response.data[key].asset].trading!=="object") {
					userActives.data.sui[response.data[key].asset]["trading"] = {};
				}	
		
				userActives.data.sui[response.data[key].asset].asset = response.data[key].asset;
	
				if (typeof response.data[key].error==="undefined" || response.data[key].error===undefined || !response.data[key].error || response.data[key].error.length==0) {
				
					var htmlConnect = "<div class=\"row_" + response.data[key].asset + "\"><span id=\"sui_name_" + response.data[key].asset + "\">" + response.data[key].connectname + "</span>&nbsp;&nbsp;<span id=\"sui_uid_" + response.data[key].asset + "\">" + $this.app.stringReplace(response.data[key].asset, "...", 6, 6) + "</span>&nbsp;&nbsp;<span id=\"sui_disconnect_" + response.data[key].asset + "\" class=\"mdi mdi-logout\" data-id=\"" + response.data[key].asset + "\" title=\"'.Yii::t('Api', 'Disconnect').'\"></span></div>";
					
				} else {
					
					var htmlConnect = "<div class=\"row_" + response.data[key].asset + "\" style=\"color:red\"><span id=\"sui_name_" + response.data[key].asset + "\">" + response.data[key].connectname + "</span>&nbsp;&nbsp;<span id=\"sui_uid_" + response.data[key].asset + "\">" + $this.app.stringReplace(response.data[key].asset, "...", 6, 1) + "</span>&nbsp;&nbsp;<span id=\"sui_disconnect_" + response.data[key].asset + "\" class=\"mdi mdi-logout\" data-id=\"" + response.data[key].asset + "\" title=\"'.Yii::t('Api', 'Disconnect').'\"></span><span class=\"error_sui_connect\">(" + response.data[key].error + ")</span></div>";
				}
		
				jQuery("#as2899-aba").append(htmlConnect);
				
				if (response.data[key].active && response.data[key].active.length) {

					response.data[key].active.forEach((val) => {
				
						userActivesMin.sui[response.data[key].asset].active[val.symbolid] = {
							"symbol": val.symbol,
							"balance": val.balance,
							"price": val.price,
						}
						
						if (typeof userActives.data.sui[response.data[key].asset].active[val.symbolid]==="undefined" || userActives.data.sui[response.data[key].asset].active[val.symbolid]===undefined || !userActives.data.м[response.data[key].asset].active[val.symbolid] || userActives.data.sui[response.data[key].asset].active[val.symbolid].length==0) {
							
							userActives.data.sui[response.data[key].asset].active[val.symbolid] = {
								"img": val.img,
								"symbol": val.symbol,
								"name": val.name,
								"symbolid": val.symbolid,
								"coinid": val.coinid,
								"asset": val.asset,
								"type": "ethactive",
								"connectname": response.data[key].connectname,
								"listCoin": [],
							};	
						}
						
						userActives.data.sui[response.data[key].asset].active[val.symbolid].listCoin.push({
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
				suiConnectedStatus = true;
	
			}

			addListCoin(2);
		}
	}
', yii\web\View::POS_END);