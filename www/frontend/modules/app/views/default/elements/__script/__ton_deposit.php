<?php

use yii\bootstrap5\Html;

$this->registerCss("
	#as2856-btd .as2856-earn {
		margin:10px 0 0 0;
	}
	#as2856-btd .as2856-earn-title {
		font-size:18px;
		margin-bottom:4px;
	}
	#as2856-btd .as2856-is-external-info {
		display:inline-block;
		width:20px;
		height:20px;
		background-image: url(/images/icons/question-circle.svg);
		background-repeat: no-repeat;
		cursor:pointer;
		margin:0 0 -3px 0;
	}
	.as2856-question_popover {
		box-shadow: 0 4px 24px rgba(0, 0, 0, 0.16);
		overflow:hidden;
		box-sizing:border-box;
		padding:10px;
		border-radius:8px;
		margin-top:0px !important;
		
		color:#000;
	}
	.as2856-popover-arrow {
		position: absolute; 
		top: 0px; 
		transform: translate(0px, 12px);
	}	
	.as2856-popover-content {
		background:transparent;
		font-size:16px !important;
		color:#000000;
		height:fit-content;
		width:fit-content;
		cursor:pointer;
	}	
	.as2856-popover-content:hover {
		background:#F1F3F5;
	}
	.as2856-popover_text a {
		text-decoration:underline !important;
		color:#000000;
	}
	.as2856-fa-external-link-alt {
		color:#666666;
		text-decoration:underline !important;
		font-size:14px;
	}
	.as2856-question_addon_popover {
		
	}	
	#as2856-btd .as2856-popover_text {
		
	}
	#as2856-btd .as2856-wrap-form {
		border:1px solid #fff;
		border-radius:8px;
		padding:15px;
		margin-bottom:8px;
	}
	#as2856-btd .as2856-wr-input {
		width:calc(100% - 240px);
		height:60px;
	}
	#as2856-btd .as2856-wr-input input {
		width:100%;
		height:60px;
		font-size:20px;
	}
	#as2856-btd .as2856-cr-form {
		width:10px;
		height:60px;
		padding:0;
	}
	#as2856-btd .as2856-wrbd-form {
		width:100px;
		height:60px;
	}
	#as2856-btd .as2856-wrbd-form button {
		width:100px;
		height:60px;
		font-size:20px;
	}
	#as2856-btd .as2856-wrbw-form {
		width:120;
		height:60px;
	}
	#as2856-btd .as2856-wrbw-form button {
		width:120px;
		height:60px;
		font-size:20px;
	}
	#as2856-btd .as2856-wr-input input::placeholder,
	#as2856-btd .as2856-wr-input input::placeholder {
		color:#333333 !important;
	}

", ['id'=>'as2-ton-deposit']);

$this->registerJs('

	class TonDeposit {
		
		constructor() {
			this.id = 0;
			this.sc = "";
			this.coin = "";
			this.apr = "";
			this.price = 0;
			this.existCoins = ["ton", "usdt"];
			this.app = new appFinkeeper();
		}
		
		/**
		 * getDeposit(options)
		 */
		getDeposit(options) {
		
			if (typeof options!=="undefined" && options!==undefined && options) {

				if (typeof options.id!=="undefined" && options.id!==undefined && options.id) {
					this.id = options.id;
				}
				
				if (typeof options.sc!=="undefined" && options.sc!==undefined && options.sc) {
					this.sc = options.sc;
				}
				
				if (typeof options.coin!=="undefined" && options.coin!==undefined && options.coin) {
					this.coin = options.coin;
				}
				
				if (typeof options.apr!=="undefined" && options.apr!==undefined && options.apr) {
					this.apr = options.apr;
				}
				
				if (typeof options.price!=="undefined" && options.price!==undefined && options.price) {
					this.price = options.price;
				}
			}

			if (this.existCoins.includes(this.coin)) {
				
				if (this.coin=="ton") {

					return this.getTONUSDTForm();
				
				} else if(this.coin=="usdt") {
					
					jQuery("#as2856-question-addon5").popover({
						placement: "l",
						content: " ",
						trigger: "focus",
						template: "<div class=\"popover connect_popover\" role=\"tooltip\"><div class=\"popover-arrow\"></div><div class=\"as2856-popover-content\"><div class=\"as2856-question_addon_popover\"><div class=\"as2856-popover_text\">'.addslashes(Yii::t('Api', 'For using this pool')).' </div><div class=\"clearfix\"></div></div></div></div>",
					}).show();
					
					return this.getUSDTAQUAForm();
				}
			}
		}

		
		/**
		 * getTONUSDTForm()
		 */
		getTONUSDTForm() {
			
			var $this = this;

			jQuery(document).delegate("#as2856-question-addon4", "mouseenter", function() {

				jQuery("#as2856-question-addon4").popover({
					placement: "right",
					content: " ",
					trigger: "click",
					template: "<div class=\"popover question_popover\" role=\"tooltip\"><div class=\"popover-arrow\"></div><div class=\"as2856-popover-content\"><div class=\"as2856-question_addon_popover\"><div class=\"as2856-popover_text\">'.addslashes(Yii::t('Api', 'Wee recommend to leave 1 TON for comission')).' </div><div class=\"clearfix\"></div></div></div></div>",
				}).show();	

			});	
			
			jQuery(document).delegate("#as2856-question-addon4", "click", function(elem) {

				jQuery("#as2856-question-addon4").popover("show");

			});	
	
			jQuery(document).delegate("#as2856-add-liquidity", "click", function(){
				
				var userTonDeposit1 = jQuery(this).parents(".as2856-wrap-form").find("#as2856-inputTonDeposit").val();
				
				userTonDeposit = userTonDeposit1 / 2;
	
				userTonDeposit = $this.app.toFloatAmont(userTonDeposit);
				userTonDeposit = parseFloat(userTonDeposit);

				userUSDTDeposit = userTonDeposit*$this.price;
				userUSDTDeposit = $this.app.toFloatDecimals(userUSDTDeposit/1000, 6);
				userUSDTDeposit = parseFloat(userUSDTDeposit);
				
				userTonDeposit = (userTonDeposit*0.02)+userTonDeposit;

				console.log(userTonDeposit);
				console.log(userUSDTDeposit);
				
			});
			
			jQuery(document).delegate("#as2856-inputTonDeposit", "input", function(){
		
				jQuery(this).val($this.app.sanitizeNumber(jQuery(this).val()));

			});

			var form = "";

			form += "<div id=\"as2856-btd\"><div class=\"as2856-earn\">";
			
			form += "<div class=\"as2856-earn-title\">'.Yii::t('Api', 'Deposit').'&nbsp;"
			
			if (typeof this.apr!=="undefined" && this.apr!==undefined && this.apr) {
				
				form += "APR&nbsp;" + this.apr + "&nbsp;";
			}
			
			form += "<div id=\"as2856-question-addon4\" class=\"as2856-is-external-info\" data-bs-toggle=\"popover\"></div></div><div class=\"as2856-wrap-form\"><div class=\"form-floating float-start as2856-wr-input\"><input type=\"text\" class=\"form-control float-start\" id=\"as2856-inputTonDeposit\" placeholder=\"'.Yii::t('Api', 'TON').'\" autocomplete=\"off\" inputmode=\"numeric\"><label for=\"inputTonDeposit\">'.Yii::t('Api', 'TON').'</label></div><div class=\"as2856-cr-form float-start\"></div><div class=\"form-floating float-start as2856-wrbd-form\"><button id=\"as2856-add-liquidity\" class=\"btn btn-outline-light float-end\">'.Yii::t('Api', 'Deposit').'</button></div><div class=\"as2856-cr-form float-start\"></div><div class=\"form-floating float-start as2856-wrbw-form\"><button id=\"as2856-remove-liquidity\" class=\"btn btn-outline-light float-end\">'.Yii::t('Api', 'Withdraw').'</button></div><div class=\"clearfix\"></div></div></div></div>";

			return form;
		}
		
		/**
		 * getUSDTAQUAForm()
		 */
		getUSDTAQUAForm() {
			
			var form = "";
			var $this = this;

			console.log("usdt form");
		}
	}
	
', yii\web\View::POS_END);