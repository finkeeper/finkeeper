<?php
$this->registerCss("
	.as2_card_info {
		width:600px;
		position:absolute;
		top:10px;
		left:50%;
		z-index:9999;
		background:transparent;
		opacity:1;
		margin-left:-300px;
		overflow: hidden; 
		border-radius:4px;
	}
	@media (max-width: 700px) {
		.as2_card_info {
			left:10%;
			width:80%;
			margin-left:0;
		}
	}
	.as2-card-error {
		display: block;
		padding: 15px 20px;
		border: 1px #f77e7e solid;
		color: #f77e7e;
		text-align: left;
		font-size: 1.4rem;
		font-weight: 600;
		border-radius: 10px;
		background:#0f0638;
		opacity:0.9;
	}
	.as2-card-warning {
		display: block;
		padding: 15px 20px;
		background-color: transparent;
		border: 1px #ff7f00 solid;
		color: #ff7f00;
		text-align: left;
		font-size: 1.4rem;
		font-weight: 600;
		border-radius: 10px;
		background:#0f0638;
		opacity:0.9;
	}
	.as2-card-success {
		display: block;
		padding: 15px 20px;
		background-color: transparent;
		border: 1px #06e873 solid;
		color: #06e873;
		text-align: left;
		font-size: 1.4rem;
		font-weight: 600;
		border-radius: 10px;
		background:#0f0638;
		opacity:0.9;
	}
", ['id'=>'as2-app']);

$this->registerJs('

	class appFinkeeper {
		
		constructor() {
		
		
		}
		
		/**
		 * addNotify(message, type)
		 */
		addNotify(message, type) {
			if (jQuery("#as2-app-notify").length>0) {
				jQuery("#as2-app-notify").remove();
			}
			
			var newDiv = document.createElement("div");
			newDiv.id = "as2-app-notify";
			newDiv.className = "as2_card_info";
			document.body.appendChild(newDiv);

			jQuery("#as2-app-notify").html(message).addClass("as2-card-" + type).fadeOut(30000, function() {
				jQuery("#as2-app-notify").html("").removeClass("as2-card-" + type).show();
			});
		}
		
		/**
		 * getRandomID(number)
		 */
		getRandomID(number) {	
			return  Math.floor(Math.random() * (1000 - 1) + 1) + number;
		}
		
		/**
		 * stringReplace(str, sub, start, end)
		 */
		stringReplace(str, sub, start, end) {

			if (typeof sub==="undefined" || sub===undefined || !sub || typeof sub!=="string") {
				var sub = "";
			}
			
			if (typeof start==="undefined" || start===undefined || !start || typeof start!=="number") {
				var start = 0;
			}
			
			if (typeof end==="undefined" || end===undefined || !end || typeof end!=="number") {
				var end = 0;
			}
			
			if (typeof str==="undefined" || str===undefined || !str || typeof str!=="string") {
				return str;	
			}
			
			var newStr = "";
			
			if (!str) {
				return newStr;		
			}
			
			if (start>0) {
				newStr += str.slice(0, start);
			}
			
			if (sub) {
				newStr += sub;
			}
			
			if (end>0) {
				var length = str.length;
				length = length - end;
				newStr += str.slice(length);
			}

			return newStr;
		}
		
		/**
		 * copyValue(value)
		 */
		copyValue(value) {
			var $this = this;
			navigator.clipboard.writeText(value)
			.then(() => {
				this.addNotify("'.Yii::t('Api', 'Copy Success').'", "success");
			})
			.catch((e) => {
				this.addNotify(e, "error");
			});	
		}
		
		/**
		 * toFloatAmont(amount)
		 */
		toFloatAmont(amount) {

			amount = parseFloat(amount);
			if (isNaN(amount) || typeof amount==="undefined" || amount===undefined || !amount) {
				amount = 0;
			}

			return amount;
		}
		
		/**
		 * toFloatDecimals(value, precision)
		 */
		toFloatDecimals(value, precision) {
			var precision = precision || 0,
				power = Math.pow(10, precision),
				absValue = Math.abs(Math.round(value * power)),
				result = (value < 0 ? "-" : "") + String(Math.floor(absValue / power));

			if (precision > 0) {
				var fraction = String(absValue % power),
					padding = new Array(Math.max(precision - fraction.length, 0) + 1).join("0");
				result += "." + padding + fraction;
			}
			
			return result.replace(/(0*$|\.0*$)/, "");
		}
		
		/**
		 * sanitizeNumber(str)
		 */
		sanitizeNumber(str) {

			if (typeof str ==="undefined" || str===undefined || !str) {
				return "";
			}

			str = str.replaceAll(" ", "");
			str = str.replaceAll(",", ".");
			str = str.replace(/[^\d.]/ig, "");

			return str;
		}
		
		/** 
		 * formatValue(value, type=0)
		 */
		formatValue(value, type=0) {
			if (isNaN(value) || typeof value==="undefined" || value===undefined || !value) {
				return 0;
			}
			
			var num = value;

			num = parseFloat(num);
			
			if (type==1) {		
				if (num>0.1) {
					return toFloatDecimals(num, 2).replace(/(0*$|\.0*$)/, "");
				}		
			}
			
			if(num>0.01) {
				return toFloatDecimals(num, 3).replace(/(0*$|\.0*$)/, "");
			} else if(num>0.001) {	
				return toFloatDecimals(num, 4).replace(/(0*$|\.0*$)/, "");
			} else if(num>0.0001) {	
				return toFloatDecimals(num, 5).replace(/(0*$|\.0*$)/, "");
			} else if(num>0.00001) {	
				return toFloatDecimals(num, 6).replace(/(0*$|\.0*$)/, "");
			} else if(num>0.000001) {	
				return toFloatDecimals(num, 7).replace(/(0*$|\.0*$)/, "");
			} else if(num>0.0000001) {	
				return toFloatDecimals(num, 8).replace(/(0*$|\.0*$)/, "");
			} else if(num>0.00000001) {	
				return toFloatDecimals(num, 9).replace(/(0*$|\.0*$)/, "");
			} else if(num>0.000000001) {	
				return toFloatDecimals(num, 10).replace(/(0*$|\.0*$)/, "");
			} else if(num>0.0000000001) {	
				return toFloatDecimals(num, 11).replace(/(0*$|\.0*$)/, "");
			} else if(num>0.00000000001) {	
				return toFloatDecimals(num, 12).replace(/(0*$|\.0*$)/, "");
			} else {
				return toFloatDecimals(num, 2).replace(/(0*$|\.0*$)/, "");
			}
		}
		
		/** 
		 * formatValue(value, type=0)
		 */
		getDate(format) {
			
			var date = new Date(); 
			var day = date.getDate();
			var month = date.getMonth() + 1;
			var year = date.getFullYear();
			var hours = date.getHours();
			var minutes = date.getMinutes();

			month = month.toString();
			if (month.length==1) {
				month = "0" + month;
			}

			year = year.toString().slice(2);

			return day + "." + month + "." + year + " " + hours + ":" + minutes;
									
		}
		
		/** 
		 * updatePopover(id="", template="", placement="bottom", trigger="click")
		 */
		updatePopover(id="", template="", placement="bottom", trigger="click") {
	
			if (typeof id==="undefined" || id===undefined || !id) {
				return false;
			} 
	
			if (typeof template==="undefined" || template===undefined || !template) {
				return false;
			} 

			var elem = jQuery("#" + id);
			if (!elem.length) {
				return false;
			}
			
			var popover = bootstrap.Popover.getInstance(elem);
			popover.dispose();

			elem.popover({
				placement: placement,
				content: " ",
				trigger: trigger,
				template: template,
			});
		}
		
		/**
		 * getSettingsLS(type, value)
		 */
		getSettingsLS(type) {
			
			var key = "as2settings" + type;	
			return localStorage.getItem(key);
		}
		
		/**
		 * setSettingsLS(type, value)
		 */
		setSettingsLS(type, value) {
			
			var key = "as2settings" + type;	
			return localStorage.setItem(key, value);
		}
	}
', yii\web\View::POS_END);		