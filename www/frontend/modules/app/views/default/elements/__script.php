<?php
//Base function
require dirname(__FILE__).'/__script/__app.php';

//Ton Deposit
require dirname(__FILE__).'/__script/__ton_deposit.php';

//Chat Ai JS scripts
require dirname(__FILE__).'/__script/__chatai.php';

//Bybit Wallet
require dirname(__FILE__).'/__script/__bybit_wallet.php';

//OKX Wallet
require dirname(__FILE__).'/__script/__okx_wallet.php';

//SOL Wallet
require dirname(__FILE__).'/__script/__sol_wallet.php';

//SUI Wallet
require dirname(__FILE__).'/__script/__sui_wallet.php';

//APT Wallet
require dirname(__FILE__).'/__script/__apt_wallet.php';

//ETH Wallet
require dirname(__FILE__).'/__script/__eth_wallet.php';

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
	var coinsSummActive = 0;
	var tonSummActiveCurrency = 0;
	var tonConnectedStatus='.$status['ton'].';
	var bybitConnectedStatus='.$status['bybit'].';
	var okxConnectedStatus='.$status['okx'].';
	var solConnectedStatus='.$status['sol'].';
	var suiConnectedStatus='.$status['sui'].';
	var aptConnectedStatus='.$status['apt'].';
	var ethConnectedStatus='.$status['eth'].';

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
	};
	
	
	
	
	
	
	
	
	
	
	

	var storage_type = {
		button: "abcpbuttonsearch",
		recent: "abcpbuttonrecent",
		select: "abcpbuttonselect",
		status: "abcpbuttonstatus",
		settings1: "abcpbuttonsettings1",
	}

	function convertData(data) {

		var hand_book = {
			base: {},
			conv1: {},
			conv2: {},
			conv3: {},			
		};
		
		try {
			let search_object = JSON.parse(exchange);
			jQuery.each(search_object, function(key, value) {

				if (value.id==data.base) {
					hand_book.base = value;
				}
			
				if (value.id==data.conv1) {
					hand_book.conv1 = value;
				}
			
				if (value.id==data.conv2) {
					hand_book.conv2 = value;
				}
				
				if (value.id==data.conv3) {
					hand_book.conv3 = value;
				}
				
				if (value.id==data.active) {
					hand_book.active = value;
				}
			});

		} catch (e) {

			addNotify(e.message, "error");										
		}

		var nominal = 1;
		
		if (data.active==data.conv1) {
			var nominal = data.conv4;
		} else if(data.active==data.conv2) {
			var nominal = data.conv5;
		} else if(data.active==data.conv3) {
			var nominal = data.conv6;
		}

		if (data.num==1) {

			jQuery("#dd_conv_1").attr("data-id", hand_book.base.id);
			jQuery("#ti-conv-1").attr("data-id", hand_book.base.id);
			jQuery("#dd_conv_1").html(hand_book.base.symbol);		
			jQuery("#conv1").find(".img-circle").attr("src", hand_book.base.src);
			jQuery("#conv2").find(".img-circle").attr("src", hand_book.conv2.src);
			jQuery("#conv3").find(".img-circle").attr("src", hand_book.conv3.src);
			
			if (data.active==data.conv1) {
				var conv2 = nominal*hand_book.base.price/hand_book.conv2.price;
				var conv3 = nominal*hand_book.base.price/hand_book.conv3.price;
				if (hand_book.conv2.type==2) {
					jQuery("#ti-conv-2").val(getFormat(conv2, 2));
				} else {
					jQuery("#ti-conv-2").val(getFormat(conv2));
				}
				
				if (hand_book.conv3.type==2) {
					jQuery("#ti-conv-3").val(getFormat(conv3, 2));
				} else {
					jQuery("#ti-conv-3").val(getFormat(conv3));
				}
			} else {
				var conv = nominal*hand_book.active.price/hand_book.base.price;	
				if (hand_book.base.type==2) {
					jQuery("#ti-conv-1").val(getFormat(conv, 2));
				} else {
					jQuery("#ti-conv-1").val(getFormat(conv));
				}
			}
		
		} else if(data.num==2) {

			jQuery("#ti-conv-2").attr("data-id", hand_book.base.id);
			jQuery("#dd_conv_2").attr("data-id", hand_book.base.id);
			jQuery("#dd_conv_2").html(hand_book.base.symbol);
			jQuery("#conv2").find(".img-circle").attr("src", hand_book.base.src);
			jQuery("#conv1").find(".img-circle").attr("src", hand_book.conv1.src);
			jQuery("#conv3").find(".img-circle").attr("src", hand_book.conv3.src);
	
			if (data.active==data.conv2) {
				var conv1 = nominal*hand_book.base.price/hand_book.conv1.price;
				var conv3 = nominal*hand_book.base.price/hand_book.conv3.price;
				
				if (hand_book.conv1.type==2) {
					jQuery("#ti-conv-1").val(getFormat(conv1, 2));
				} else {
					jQuery("#ti-conv-1").val(getFormat(conv1));
				}
				
				if (hand_book.conv3.type==2) {
					jQuery("#ti-conv-3").val(getFormat(conv3, 2));
				} else {
					jQuery("#ti-conv-3").val(getFormat(conv3));
				}
			} else {
				var conv = nominal*hand_book.active.price/hand_book.base.price;	
				if (hand_book.base.type==2) {
					jQuery("#ti-conv-2").val(getFormat(conv, 2));
				} else {
					jQuery("#ti-conv-2").val(getFormat(conv));
				}
			}
			
		} else if(data.num==3) {

			jQuery("#ti-conv-3").attr("data-id", hand_book.base.id);
			jQuery("#dd_conv_3").attr("data-id", hand_book.base.id);
			jQuery("#dd_conv_3").html(hand_book.base.symbol);
			jQuery("#conv3").find(".img-circle").attr("src", hand_book.base.src);
			jQuery("#conv1").find(".img-circle").attr("src", hand_book.conv1.src);
			jQuery("#conv2").find(".img-circle").attr("src", hand_book.conv2.src);
			
			if (data.active==data.conv3) {
				var conv1 = nominal*hand_book.base.price/hand_book.conv1.price;
				var conv2 = nominal*hand_book.base.price/hand_book.conv2.price;	
				if (hand_book.conv1.type==2) {
					jQuery("#ti-conv-1").val(getFormat(conv1, 2));
				} else {
					jQuery("#ti-conv-1").val(getFormat(conv1));
				}
				
				if (hand_book.conv2.type==2) {
					jQuery("#ti-conv-2").val(getFormat(conv2, 2));
				} else {
					jQuery("#ti-conv-2").val(getFormat(conv2));
				}
			} else {
				var conv = nominal*hand_book.active.price/hand_book.base.price;
				if (hand_book.base.type==2) {
					jQuery("#ti-conv-3").val(getFormat(conv, 2));
				} else {
					jQuery("#ti-conv-3").val(getFormat(conv));
				}
			}

		} else if(data.num==4) {
			
			var base =  data.conv4; 
			var conv2 = base*hand_book.base.price/hand_book.conv2.price;
			var conv3 = base*hand_book.base.price/hand_book.conv3.price;
			
			if (hand_book.conv2.type==2) {
				jQuery("#ti-conv-2").val(getFormat(conv2, 2));
			} else {
				jQuery("#ti-conv-2").val(getFormat(conv2));
			}
			
			if (hand_book.conv3.type==2) {
				jQuery("#ti-conv-3").val(getFormat(conv3, 2));
			} else {
				jQuery("#ti-conv-3").val(getFormat(conv3));
			}
			
		} else if(data.num==5) {
			
			var base =  data.conv5; 
			var conv1 = base*hand_book.base.price/hand_book.conv1.price;
			var conv3 = base*hand_book.base.price/hand_book.conv3.price;
			
			if (hand_book.conv1.type==2) {
				jQuery("#ti-conv-1").val(getFormat(conv1, 2));
			} else {
				jQuery("#ti-conv-1").val(getFormat(conv1));
			}
			
			if (hand_book.conv3.type==2) {
				jQuery("#ti-conv-3").val(getFormat(conv3, 2));
			} else {
				jQuery("#ti-conv-3").val(getFormat(conv3));
			}
			
		} else if(data.num==6) {
			
			var base =  data.conv6; 
			var conv1 = base*hand_book.base.price/hand_book.conv1.price;
			var conv2 = base*hand_book.base.price/hand_book.conv2.price;
			
			jQuery("#ti-conv-1").val(getFormat(conv1));
			jQuery("#ti-conv-2").val(getFormat(conv2));
			
			if (hand_book.conv1.type==2) {
				jQuery("#ti-conv-1").val(getFormat(conv1, 2));
			} else {
				jQuery("#ti-conv-1").val(getFormat(conv1));
			}
			
			if (hand_book.conv2.type==2) {
				jQuery("#ti-conv-2").val(getFormat(conv2, 2));
			} else {
				jQuery("#ti-conv-2").val(getFormat(conv2));
			}
			
		} else {

			addNotify("'.Yii::t('Error', 'Missing convert type').'", "error");
			return false;
		}	
	}

	function convert(num, id) {

		clearNotify();		
		
		jQuery("#ti-conv-1").val(sanitizeStr(jQuery("#ti-conv-1").val()));
		jQuery("#ti-conv-2").val(sanitizeStr(jQuery("#ti-conv-2").val()));
		jQuery("#ti-conv-3").val(sanitizeStr(jQuery("#ti-conv-3").val()));

		var data = {
			"conv1": jQuery("#dd_conv_1").attr("data-id"),
			"conv2": jQuery("#dd_conv_2").attr("data-id"),
			"conv3": jQuery("#dd_conv_3").attr("data-id"),
			"conv4": jQuery("#ti-conv-1").val(),
			"conv5": jQuery("#ti-conv-2").val(),
			"conv6": jQuery("#ti-conv-3").val(),
			"base": 0,
			"num": num,
			"log_id": log_id,
			"active": jQuery(".currency_active").attr("data-id"),
		};
		
		data.base = id;
		
		if (num==1 || num==2 || num==3) {

			convertData(data);

		} else if(num==4) {
			
			clearActive();			
			jQuery("#ti-conv-1").addClass("currency_active");

		} else if(num==5) {
			
			clearActive();
			jQuery("#ti-conv-2").addClass("currency_active");
			
		} else if(num==6) {
			
			clearActive();
			jQuery("#ti-conv-3").addClass("currency_active");

		} else {
			
			addNotify("'.Yii::t('Error', 'Missing or Incorrect num select').'", "error");
			return false;
		}	

		convertData(data);
	}
	
	function copyValue(event, type) {

		if (type==1) {
		
			var value = $(event).parents(".text_input").find("input.form-currency").val();
			
		} else if(type==2) {
			
			var value = $(event).find(".copy_address").html();
			
		} else if(type==3) {

			var value = $(event).attr("data-address");
		}

		navigator.clipboard.writeText(value)
        .then(() => {
            addNotify("'.Yii::t('Api', 'Copy Success').'", "success");
        })
        .catch((e) => {
            addNotify(e, "error");
        });	
	}
	
	function abcpLocalStorage(type, value) {

		if (typeof type==="undefined" || type===undefined || !type) {
			
			addNotify("'.Yii::t('Api', 'Storage not type').'", "error");
			return false;
			
		} else if(type==1) {
			
			if (typeof value==="undefined" || value===undefined || !value) {
				addNotify("'.Yii::t('Api', 'Button not value').'", "error");
				return false;
			}
			
			return localStorage.setItem(storage_type.button, value);
			
		} else if(type==2) {

			return localStorage.getItem(storage_type.button);
			
		} else if(type==3) {

			if (typeof value==="undefined" || value===undefined || !value) {
				addNotify("'.Yii::t('Api', 'Button not value').'", "error");
				return false;
			}
			
			var array = [];
			var str = localStorage.getItem(storage_type.recent);

			if (typeof str==="undefined" || str===undefined || !str) {
			
				array[0] = value;	
				localStorage.setItem(storage_type.recent, JSON.stringify(array));

			} else {

				try {
					
					var array = JSON.parse(str);
	
					array.forEach(function(item, i, array) {
						if (item==value) {
							delete array[i];
						}
					});
					
					array = sortingArray(array);
					if (typeof array!=="undefined" && array!==undefined && array) {
					
						var count = array.length;
				
						if(count>=count_recent) {

							var del_key = count-count_recent;
							
							for (let i = 0; i <= del_key; i++) {
								delete array[i];
								count--;
							}						
						}
					
						array = sortingArray(array);
						
						array[count] = value;

						localStorage.setItem(storage_type.recent, JSON.stringify(array));
					} else {
						
						array[0] = value;
						localStorage.setItem(storage_type.recent, JSON.stringify(array));
					}

				} catch (e) {
					
					array[0] = value;
					localStorage.setItem(storage_type.recent, JSON.stringify(array));
					addNotify(e.message, "error");						
				}		
			}	
			
		} else if(type==4) {

			var array = [];
			var str = localStorage.getItem(storage_type.recent);
			
			if (typeof str==="undefined" || str===undefined || !str) {
			
				return false;
				addNotify("'.Yii::t('Api', 'Missing recent value').'", "warning");	

			} else {

				try {
					
					var array = JSON.parse(str);
					
					return array;
					
				} catch (e) {

					addNotify(e.message, "error");						
				}		
			}
			
		} else if(type==5) {
			
			var obj = {
				1: {
					id: jQuery("#dd_conv_1").attr("data-id"),
					active: 0,
					symbol: jQuery("#dd_conv_1").text(),
				},
				2: {
					id: jQuery("#dd_conv_2").attr("data-id"),
					active: 0,
					symbol: jQuery("#dd_conv_2").text(),
				},
				3: {
					id: jQuery("#dd_conv_3").attr("data-id"),
					active: 0,
					symbol: jQuery("#dd_conv_3").text(),
				},
			};
			
			var active_id = jQuery(".currency_active").parents(".card-currency").find(".currency_block").attr("data-num");
			
			if (typeof active_id!=="undefined" && active_id!==undefined && active_id) {
				
				obj[active_id]["active"] = 1;
				
			} else {
				
				obj[globe_num]["active"] = 1;
			}

			localStorage.setItem(storage_type.select, JSON.stringify(obj));

		} else if(type==6) {

			var str = localStorage.getItem(storage_type.select);

			if (typeof str!=="undefined" && str!==undefined && str) {
			
				try {
					
					var obj = JSON.parse(str);
					var active_id = 0;
					for (key in obj) {
						
						if (obj[key].active) {
							
							globe_num = key;
							active_id = obj[key].id;
							clearActive();			
							jQuery("#ti-conv-" + key).addClass("currency_active").val(1);
	
						} else {
							
							jQuery("#dd_conv_" + key).attr("data-id", obj[key].id).text(obj[key].symbol);
							
						}
					};

					convert(globe_num, active_id);

				} catch (e) {
					
					console.log(e);						
				}		

			}

		} else if(type==9) {

			return localStorage.setItem(storage_type.settings1, value);
			
		} else if(type==10) {

			return localStorage.getItem(storage_type.settings1);
				
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//displayConnectMenu(type=0, flag=0)
	function displayConnectMenu(type=0, flag=0, address, id) {
		if (typeof type==="undefined" || type===undefined || !type) {
			return false;
		} 

		var elem;
		var ident;
		
		if (type==1) {
			elem = jQuery("#ton-wallet-click-button");
			ident = "ton";
		} else {
			return false;
		}

		var popover = bootstrap.Popover.getInstance(elem);
		popover.dispose();

		if (flag) {

			var parseAddress = string_replace(address, "...", 6, 6);
	
			var template = "<div class=\"popover disconnect_popover\" role=\"tooltip\"><div class=\"popover-content\"><div class=\"view_address text_input\"><span>" + parseAddress + "</span> <img src=\"/images/icons/copy2.svg\" alt=\"copy\" title=\"copy\"><div class=\"copy_address\">" + address + "</div></div><div class=\"" + ident + "_disconnect_button\"><div class=\"dataid\" id=\"" + id + "\"></div><div class=\"mdi mdi-logout\"></div><div class=\"popover_text\">'.Yii::t('Api', 'Disconnect').'</div><div class=\"clearfix\"></div></div></div></div>";

		} else {
			
			var template = "<div class=\"popover connect_popover\" role=\"tooltip\"><div class=\"popover-content\"><div acc-id=\"" + id + "\" class=\"" + ident + "_connect_button\"><div class=\"mdi mdi-login\"></div><div class=\"popover_text\">'.Yii::t('Api', 'Connect').'</div><div class=\"clearfix\"></div></div></div></div>";

		}	
		
		elem.popover({
			placement: "bottom",
			content: " ",
			trigger: "click",
			template: template,
		});
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

	
	
	function sendAddress(address) {	

		displayBackdrop(1, 1);

		jQuery.ajax({
			"url": "/app/gettonbalance",
			"type": "post",
			"dataType": "json",
			"contentType": "application/json",
			"data": JSON.stringify({"type": 2, "address": address, "log_id": log_id, sc: sc}),
			"success": function(response){

				displayBackdrop(1, 0);
				if (!response.error) {

					displayConnectIcon(1, 1);
					displayConnectMenu(1, 1, response.address, response.id);
					
					tonSummActive = response.summ;
					getAllActive();
					
					userActives.grafema = response.grafema;
					userActives.address = response.address;
			
					if (
						typeof response.data==="object" && 
						response.data!==undefined && 
						response.data
					) {
			
						for (var key in response.data) {
							
							if (typeof userActivesMin.ton[response.data[key].asset]==="undefined" || userActivesMin.ton[response.data[key].asset]===undefined || !userActivesMin.ton[response.data[key].asset] || userActivesMin.ton[response.data[key].asset]!=="object") {
							
								userActivesMin.ton[response.data[key].asset] = {};	

							}
							
							if (typeof userActivesMin.ton[response.data[key].asset].active==="undefined" || userActivesMin.ton[response.data[key].asset].active===undefined || !userActivesMin.ton[response.data[key].asset].active || userActivesMin.ton[response.data[key].asset].active!=="object") {
						
								userActivesMin.ton[response.data[key].asset]["active"] = {};
						
							}	
				
							if (typeof userActives.data.ton[response.data[key].asset]==="undefined" || userActives.data.ton[response.data[key].asset]===undefined || !userActives.data.ton[response.data[key].asset] || userActives.data.ton[response.data[key].asset]!=="object") {
						
								userActives.data.ton[response.data[key].asset] = {};	

							}
							
							if (typeof userActives.data.ton[response.data[key].asset].active==="undefined" || userActives.data.ton[response.data[key].asset].active===undefined || !userActives.data.ton[response.data[key].asset].active || userActives.data.ton[response.data[key].asset].active!=="object") {
										
								userActives.data.ton[response.data[key].asset]["active"] = {};
										
							}
									
							userActives.data.ton[response.data[key].asset].asset = response.data[key].asset;	
								
							response.data[key].active.forEach((val) => {
				
								userActivesMin.ton[response.data[key].asset].active[val.symbolid] = {
									"symbol": val.symbol,
									"balance": val.balance,
									"price": val.price,
								}
					
								userActives.data.ton[response.data[key].asset].active[val.symbolid] = {
									"img": val.img,
									"symbol": val.symbol,
									"name": val.name,
									"currency_value": val.currency_value,
									"symbolid": val.symbolid,
									"coinid": val.coinid,
									"balance": val.balance,
									"apr": val.apr,
									"price": val.price,
									"asset": val.asset,
									"type": "suiactive",
									"connectname": response.data[key].connectname,
									"network": val.network,
									"network_icon": val.network_icon,
								};
							});	
						};
	
						jQuery("#wrap-actives #title_balance").html("");
						tonConnectedStatus=true;
						addListCoin();
						
					} else {
						
						addNotify("'.Yii::t('Error', 'Wallet not connect').'", "error");
					}
					
				} else {
					
					addNotify(response.message, "error");
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				displayBackdrop(1, 0);
				addNotify(thrownError, "error");
			}
		}).done(function (data) {
			//okxconnect();
		}).fail(function (jqXHR, textStatus) {
			//okxconnect();
			//okxconnect();
		});
	}
	
	function tondisconnect(id) {
		
		console.log(id);

		displayBackdrop(1, 1);
		
		tonConnectedStatus = false;
		
		tonRecalculation();
		
		addListCoin();

		if (!bybitConnectedStatus && !okxConnectedStatus && !solConnectedStatus && !suiConnectedStatus && !aptConnectedStatus) {
			jQuery("#asModal #title_balance").html("'.Yii::t('Api', 'Connect your wallet to see list of assets').'");
		}

		jQuery.ajax({
			"url": "/app/gettonbalance",
			"type": "post",
			"dataType": "json",
			"contentType": "application/json",
			"data": JSON.stringify({"type": 3, "id": id, "log_id": log_id, sc: sc}),
			"success": function(response){

				displayBackdrop(1, 0);
				
				if (!response.error) {
					displayConnectIcon(1, 0);
					displayConnectMenu(1, 0);
					jQuery("#user_balance").html("");
					userActives.data.ton = {};
					addListCoin();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				displayBackdrop(1, 0);
				addNotify(thrownError, "error");
			}
		});
	}
	
	function tonconnected() {	

		displayBackdrop(1, 1);
	
		jQuery.ajax({
			"url": "/app/gettonbalance",
			"type": "post",
			"dataType": "json",
			"contentType": "application/json",
			"data": JSON.stringify({"type": 1, "log_id": log_id, sc: sc}),
			"success": function(response){
		
				displayBackdrop(1, 0);

				if (!response.error) {
					
					displayConnectIcon(1, 1);
					displayConnectMenu(1, 1, response.address, response.id);

					tonSummActive = response.summ;
					getAllActive();
					
					userActives.grafema = response.grafema;
					userActives.address = response.address;
	
					if (
						typeof response.data==="object" && 
						response.data!==undefined && 
						response.data
					) {
			
						for (var key in response.data) {
							
							if (typeof userActivesMin.ton[response.data[key].asset]==="undefined" || userActivesMin.ton[response.data[key].asset]===undefined || !userActivesMin.ton[response.data[key].asset] || userActivesMin.ton[response.data[key].asset]!=="object") {
							
								userActivesMin.ton[response.data[key].asset] = {};	

							}
							
							if (typeof userActivesMin.ton[response.data[key].asset].active==="undefined" || userActivesMin.ton[response.data[key].asset].active===undefined || !userActivesMin.ton[response.data[key].asset].active || userActivesMin.ton[response.data[key].asset].active!=="object") {
						
								userActivesMin.ton[response.data[key].asset]["active"] = {};
						
							}	
				
							if (typeof userActives.data.ton[response.data[key].asset]==="undefined" || userActives.data.ton[response.data[key].asset]===undefined || !userActives.data.ton[response.data[key].asset] || userActives.data.ton[response.data[key].asset]!=="object") {
						
								userActives.data.ton[response.data[key].asset] = {};	

							}
							
							if (typeof userActives.data.ton[response.data[key].asset].active==="undefined" || userActives.data.ton[response.data[key].asset].active===undefined || !userActives.data.ton[response.data[key].asset].active || userActives.data.ton[response.data[key].asset].active!=="object") {
										
								userActives.data.ton[response.data[key].asset]["active"] = {};
										
							}
									
							userActives.data.ton[response.data[key].asset].asset = response.data[key].asset;	
								
							response.data[key].active.forEach((val) => {
				
								userActivesMin.ton[response.data[key].asset].active[val.symbolid] = {
									"symbol": val.symbol,
									"balance": val.balance,
									"price": val.price,
								}
					
								userActives.data.ton[response.data[key].asset].active[val.symbolid] = {
									"img": val.img,
									"symbol": val.symbol,
									"name": val.name,
									"currency_value": val.currency_value,
									"symbolid": val.symbolid,
									"coinid": val.coinid,
									"balance": val.balance,
									"apr": val.apr,
									"price": val.price,
									"asset": val.asset,
									"type": "suiactive",
									"connectname": response.data[key].connectname,
									"network": val.network,
									"network_icon": val.network_icon,
								};
							});	
						};
	
						jQuery("#wrap-actives #title_balance").html("");
						tonConnectedStatus=true;
						addListCoin();
						
					} else {
						
						addNotify("'.Yii::t('Error', 'Wallet not connect').'", "error");
					}
					
				} else {
					
					addNotify(response.message, "error");
				}	
			},
			error: function(xhr, ajaxOptions, thrownError) {
				displayBackdrop(1, 0);
				addNotify(thrownError, "error");
			}
		});
	}





	
	
	
	
	

	//addListCoin()
	function addListCoin() {

		var html = "";
		var allCoinsData = [];
	
		if (
			typeof userActives.data.ton!=="undefined" && 
			userActives.data.ton!==undefined && 
			userActives.data.ton
		) {
			for (var key1 in userActives.data.ton) {
				
				if (
					typeof userActives.data.ton[key1]!=="undefined" && 
					userActives.data.ton[key1]!==undefined && 
					userActives.data.ton[key1]
				) {
		
					if (
						typeof userActives.data.ton[key1].active!=="undefined" && 
						userActives.data.ton[key1].active!==undefined && 
						userActives.data.ton[key1].active &&
						typeof userActives.data.ton[key1].active==="object"
					) {
					
						for (var key2 in userActives.data.ton[key1].active) {
							if (
								typeof allCoinsData[key2]==="undefined" || 
								allCoinsData[key2]===undefined ||
								allCoinsData[key2].length==0 ||
								typeof allCoinsData[key2]==="function"
							) {
								allCoinsData[key2]=[];
							}
							
							allCoinsData[key2].push(userActives.data.ton[key1].active[key2]);
						}
					}					
				}
			}
		}

		if (
			typeof userActives.data.bybit!=="undefined" && 
			userActives.data.bybit!==undefined && 
			userActives.data.bybit
		) {
			for (key1 in userActives.data.bybit) {
				
				if (
					typeof userActives.data.bybit[key1]!=="undefined" && 
					userActives.data.bybit[key1]!==undefined && 
					userActives.data.bybit[key1]
				) {
		
					if (
						typeof userActives.data.bybit[key1].active!=="undefined" && 
						userActives.data.bybit[key1].active!==undefined && 
						userActives.data.bybit[key1].active &&
						typeof userActives.data.bybit[key1].active==="object"
					) {
					
						for (key2 in userActives.data.bybit[key1].active) {
							if (
								typeof allCoinsData[key2]==="undefined" || 
								allCoinsData[key2]===undefined ||
								allCoinsData[key2].length==0 ||
								typeof allCoinsData[key2]==="function"
							) {
								allCoinsData[key2]=[];
							}
							
							allCoinsData[key2].push(userActives.data.bybit[key1].active[key2]);
						}
					}					

					if (
						typeof userActives.data.bybit[key1].trading!=="undefined" && 
						userActives.data.bybit[key1].trading!==undefined && 
						userActives.data.bybit[key1].trading
					) {

						for (key2 in userActives.data.bybit[key1].trading) {
							if (
								typeof allCoinsData[key2]==="undefined" || 
								allCoinsData[key2]===undefined ||
								allCoinsData[key2].length==0 ||
								typeof allCoinsData[key2]==="function"
							) {
								allCoinsData[key2]=[];
							}
							
							allCoinsData[key2].push(userActives.data.bybit[key1].trading[key2]);
						}	
					}
				}
			}
		}
		
		if (
			typeof userActives.data.okx!=="undefined" && 
			userActives.data.okx!==undefined && 
			userActives.data.okx
		) {
			for (key1 in userActives.data.okx) {
				
				if (
					typeof userActives.data.okx[key1]!=="undefined" && 
					userActives.data.okx[key1]!==undefined && 
					userActives.data.okx[key1]
				) {
		
					if (
						typeof userActives.data.okx[key1].active!=="undefined" && 
						userActives.data.okx[key1].active!==undefined && 
						userActives.data.okx[key1].active &&
						typeof userActives.data.okx[key1].active==="object"
					) {
					
						for (key2 in userActives.data.okx[key1].active) {
							if (
								typeof allCoinsData[key2]==="undefined" || 
								allCoinsData[key2]===undefined ||
								allCoinsData[key2].length==0 ||
								typeof allCoinsData[key2]==="function"
							) {
								allCoinsData[key2]=[];
							}
							
							allCoinsData[key2].push(userActives.data.okx[key1].active[key2]);
						}
					}					

					if (
						typeof userActives.data.okx[key1].trading!=="undefined" && 
						userActives.data.okx[key1].trading!==undefined && 
						userActives.data.okx[key1].trading
					) {

						for (key2 in userActives.data.okx[key1].trading) {
							if (
								typeof allCoinsData[key2]==="undefined" || 
								allCoinsData[key2]===undefined ||
								allCoinsData[key2].length==0 ||
								typeof allCoinsData[key2]==="function"
							) {
								allCoinsData[key2]=[];
							}
							
							allCoinsData[key2].push(userActives.data.okx[key1].trading[key2]);
						}	
					}
				}
			}
		}

		if (
			typeof userActives.data.sol!=="undefined" && 
			userActives.data.sol!==undefined && 
			userActives.data.sol
		) {
			for (key1 in userActives.data.sol) {
				
				if (
					typeof userActives.data.sol[key1]!=="undefined" && 
					userActives.data.sol[key1]!==undefined && 
					userActives.data.sol[key1]
				) {
		
					if (
						typeof userActives.data.sol[key1].active!=="undefined" && 
						userActives.data.sol[key1].active!==undefined && 
						userActives.data.sol[key1].active &&
						typeof userActives.data.sol[key1].active==="object"
					) {
					
						for (key2 in userActives.data.sol[key1].active) {
							if (
								typeof allCoinsData[key2]==="undefined" || 
								allCoinsData[key2]===undefined ||
								allCoinsData[key2].length==0 ||
								typeof allCoinsData[key2]==="function"
							) {
								allCoinsData[key2]=[];
							}
							
							allCoinsData[key2].push(userActives.data.sol[key1].active[key2]);
						}
					}					
				}
			}
		}
		
		if (
			typeof userActives.data.sui!=="undefined" && 
			userActives.data.sui!==undefined && 
			userActives.data.sui
		) {
			for (key1 in userActives.data.sui) {
				
				if (
					typeof userActives.data.sui[key1]!=="undefined" && 
					userActives.data.sui[key1]!==undefined && 
					userActives.data.sui[key1]
				) {
		
					if (
						typeof userActives.data.sui[key1].active!=="undefined" && 
						userActives.data.sui[key1].active!==undefined && 
						userActives.data.sui[key1].active &&
						typeof userActives.data.sui[key1].active==="object"
					) {
					
						for (key2 in userActives.data.sui[key1].active) {
							if (
								typeof allCoinsData[key2]==="undefined" || 
								allCoinsData[key2]===undefined ||
								allCoinsData[key2].length==0 ||
								typeof allCoinsData[key2]==="function"
							) {
								allCoinsData[key2]=[];
							}
							
							allCoinsData[key2].push(userActives.data.sui[key1].active[key2]);
						}
					}					
				}
			}
		}
	
		if (
			typeof userActives.data.apt!=="undefined" && 
			userActives.data.apt!==undefined && 
			userActives.data.apt
		) {

			for (key1 in userActives.data.apt) {
				
				if (
					typeof userActives.data.apt[key1]!=="undefined" && 
					userActives.data.apt[key1]!==undefined && 
					userActives.data.apt[key1]
				) {
		
					if (
						typeof userActives.data.apt[key1].active!=="undefined" && 
						userActives.data.apt[key1].active!==undefined && 
						userActives.data.apt[key1].active &&
						typeof userActives.data.apt[key1].active==="object"
					) {
					
						for (key2 in userActives.data.apt[key1].active) {
							if (
								typeof allCoinsData[key2]==="undefined" || 
								allCoinsData[key2]===undefined ||
								allCoinsData[key2].length==0 ||
								typeof allCoinsData[key2]==="function"
							) {
								allCoinsData[key2]=[];
							}
							
							allCoinsData[key2].push(userActives.data.apt[key1].active[key2]);
						}
					}					
				}
			}
		}

		if (
			typeof userActives.data.eth!=="undefined" && 
			userActives.data.eth!==undefined && 
			userActives.data.eth
		) {
			for (key1 in userActives.data.eth) {
				
				if (
					typeof userActives.data.eth[key1]!=="undefined" && 
					userActives.data.eth[key1]!==undefined && 
					userActives.data.eth[key1]
				) {
		
					if (
						typeof userActives.data.eth[key1].active!=="undefined" && 
						userActives.data.eth[key1].active!==undefined && 
						userActives.data.eth[key1].active &&
						typeof userActives.data.eth[key1].active==="object"
					) {

						for (key2 in userActives.data.eth[key1].active) {
	
							if (
								typeof allCoinsData[key2]==="undefined" || 
								allCoinsData[key2]===undefined ||
								allCoinsData[key2].length==0 ||
								typeof allCoinsData[key2]==="function"
							) {
								allCoinsData[key2]=[];
							}
							
							allCoinsData[key2].push(userActives.data.eth[key1].active[key2]);
						}
					}					
				}
			}
		}

		if (
			typeof allCoinsData!=="undefined" && 
			allCoinsData!==undefined && 
			allCoinsData
		) {

			for (var key in allCoinsData) {

				var img = "";
				var symbol = "";
				var type = "";
				var currency_value = 0;
				var balance = 0;
				var price = 0;
				var apr = "";
				var class_blc = "middle_value";
				var service_icon = ""
				var name = "";
				var network = "";
		
				for (var index in allCoinsData[key]) {

					if (!img) {
						img = allCoinsData[key][index].img;
					}
					
					if (!symbol) {
						symbol = allCoinsData[key][index].symbol;
					}
					
					if (!name) {
						name = allCoinsData[key][index].name;
					}

					if (
						typeof allCoinsData[key][index].listCoin!=="undefined" && 
						allCoinsData[key][index].listCoin!==undefined && 
						allCoinsData[key][index].listCoin
					) {
					
						allCoinsData[key][index].listCoin.forEach((val, token) => {

							if (
								typeof val.network!=="undefined" &&
								val.network!==undefined &&
								val.network
							) {
								network = val.network;
							}
							
							if (
								typeof val.protocol!=="undefined" &&
								val.protocol!==undefined &&
								val.protocol
							) {
								network += " <br>" + val.protocol;
							}
							
							if (
								typeof val.apr!=="undefined" &&
								val.apr!==undefined &&
								val.apr
							) {
								network += " <br>" + val.apr;
							}
		
							if (!service_icon) {
							
								if (
									typeof val.network_icon!=="undefined" &&
									val.network_icon!==undefined &&
									val.network_icon
								) {

									service_icon += "<img class=\"service_icon_first ton_icon\" title=\"" + network + "\" src=\"" + val.network_icon + "\" data-bs-toggle=\"tooltip\" rel=\"tooltip\" data-bs-html=\"true\">&nbsp";
								}

							} else {	

								if (
									typeof val.network_icon!=="undefined" &&
									val.network_icon!==undefined &&
									val.network_icon
								) {
	
									service_icon += "<img class=\"service_icon_second ton_icon\" title=\"" + network + "\" src=\"" + val.network_icon + "\"  data-bs-toggle=\"tooltip\" rel=\"tooltip\" data-bs-html=\"true\">&nbsp";
								}						
							}
							
							balance += parseFloat(val.balance);
							currency_value += parseFloat(val.currency_value);
							price = parseFloat(val.price);

						});
					
						if (currency_value<1) {
							class_blc = "small_value";
						}

					} else {

						if (
							typeof allCoinsData[key][index].network!=="undefined" &&
							allCoinsData[key][index].network!==undefined &&
							allCoinsData[key][index].network
						) {
							network = allCoinsData[key][index].network;
						}
	
						if (allCoinsData[key][index].type=="tonactive") {
							if (!apr && key=="ton") {
								apr += "Earn ";
								if (allCoinsData[key][index].apr) {
									apr += parseInt(allCoinsData[key][index].apr, 10) + "% APR";
								}
							} else if (!apr && key=="usdt") {
								apr += "Earn ";
								if (allCoinsData[key][index].apr) {
									apr += parseInt(allCoinsData[key][index].apr, 10) + "% APR";
								}
							}
						}
					
						balance += parseFloat(allCoinsData[key][index].balance);
						currency_value += parseFloat(allCoinsData[key][index].currency_value);
						price = parseFloat(allCoinsData[key][index].price);
					
						if (currency_value<1) {
							class_blc = "small_value";
						}
		
						if (!service_icon) {
							
							if (
								typeof allCoinsData[key][index].network_icon!=="undefined" &&
								allCoinsData[key][index].network_icon!==undefined &&
								allCoinsData[key][index].network_icon
							) {
								service_icon += "<img class=\"service_icon_first ton_icon\" data-bs-toggle=\"tooltip\" rel=\"tooltip\" data-bs-html=\"true\" title=\"" + network + "\" src=\"" + allCoinsData[key][index].network_icon + "\">&nbsp";
							}

						} else {	

							if (
								typeof allCoinsData[key][index].network_icon!=="undefined" &&
								allCoinsData[key][index].network_icon!==undefined &&
								allCoinsData[key][index].network_icon
							) {
								service_icon += "<img class=\"service_icon_second ton_icon\" data-bs-toggle=\"tooltip\" rel=\"tooltip\" data-bs-html=\"true\" title=\"" + network + "\" src=\"" + allCoinsData[key][index].network_icon + "\">&nbsp";
							}						
						}
					}
				}				
	
				html += "<div class=\"option_item " + class_blc + "\" data-id=\"" + key + "\" data-sort=\"" + currency_value + "\">";
				
					html += "<img src=\"" + img + "\" alt=\"coin images\">";
								
					html += "<div class=\"currency_name_block ml-10\">";
							
						html += "<div class=\"currency_name\">" + symbol + "&nbsp<span>" + name + "</span></div>";
						html += "<div class=\"currency_symbol\">" + service_icon + "</div>";
				
					html += "</div>";
		
					html += "<div class=\"currency_graf ml-10\">" + apr + "</div>";
							
					html += "<div class=\"currency_price_block ml-10\">";
						
						html += "<div class=\"currency_price\">" + formatValue(currency_value, 1) + " " + userActives.grafema + "</div>";
						html += "<div class=\"currency_volat\">" + formatValue(balance) + "</div>";
						
					html += "</div>";
				
					html += "<div class=\"clearfix\"></div>";
					
					html += "<div class=\"block_target_bar\">";
					if (isTarget(symbol)) {
						html += getTargetProgressBar(symbol, price);
					}
					html += "</div>";
					
				html += "</div>";
			};
		}

		jQuery("#wrap-actives #user_balance").html(html);
		
		$("[rel=\"tooltip\"]").tooltip();
		
		var sort_coin = jQuery.makeArray(jQuery("#wrap-actives #user_balance .option_item"));
		
		sort_coin.sort(function (a, b) {
			a = jQuery(a).attr("data-sort");
			b = jQuery(b).attr("data-sort");
			return b - a
		});

		jQuery(sort_coin).appendTo("#wrap-actives #user_balance")
		getSettings();
		//addDetailsCoin();	
	}

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//addDetailsCoin(symbol)
	function addDetailsCoin(symbol) {

		jQuery("#adModal #wrap_actives_coin").html("");

		if (typeof symbol==="undefined" || symbol===undefined || !symbol) { 
			var symbol = $("#adModal").attr("data-id");
			if (typeof symbol==="undefined" || symbol===undefined || !symbol) { 
				return false;
			}
		}
	
		var html_ton = ""
		var html_bybit = "";
		var html_okx = "";
		var html_sol = "";
		var html_sui = "";
		var html_apt = "";
		var html_eth = "";
		coinsSummActive = 0;
		tonSummActiveCurrency = 0;
		var price = 0;
		var currency = 0;
		var coins = 0;
		var connectname = "";
		
		jQuery("#adModal .symbol_details_coin, #targetModal .symbol_details_coin").html(symbol);
		jQuery("#adModal #ad-symbol").val(symbol);
		
		if (isTarget(symbol)) {
			jQuery("#targetModal #ct-target-send").html("'.Yii::t('Api', 'Change Target').'");
		} else {
			jQuery("#targetModal #ct-target-send").html("'.Yii::t('Api', 'Set Target').'");
		}
		
		var data_sort = 0;

		if (
			typeof userActives.data.ton!=="undefined" && 
			userActives.data.ton!==undefined && 
			userActives.data.ton && 
			typeof userActives.data.ton==="object"
		) {

			for (key in userActives.data.ton) {
				if (
					typeof userActives.data.ton[key].active[symbol]!=="undefined" && 
					userActives.data.ton[key].active[symbol]!==undefined && 
					userActives.data.ton[key].active[symbol] && 
					typeof userActives.data.ton[key].active[symbol]==="object"
				) {	
					if (userActives.data.ton[key].active[symbol].symbolid==symbol) {
						
						var summ_coin = "middle_value";
						if (parseFloat(userActives.data.ton[key].active[symbol].currency_value)<1) {
							summ_coin = "small_value";
						}
	
						coinsSummActive += parseFloat(userActives.data.ton[key].active[symbol].balance);
						tonSummActiveCurrency += parseFloat(userActives.data.ton[key].active[symbol].currency_value);
						getCoinsActive();
						
						coins += parseFloat(userActives.data.ton[key].active[symbol].balance);
						price = parseFloat(userActives.data.ton[key].active[symbol].price);
						currency += parseFloat(userActives.data.ton[key].active[symbol].currency_value);
	
						jQuery("#adModal .name_details_coin, #targetModal .name_details_coin").html(formatValue(userActives.data.ton[key].active[symbol].price) + userActives.grafema);	
						jQuery("#adModal .img_details_coin>img, #targetModal .img_details_coin>img").attr("src", userActives.data.ton[key].active[symbol].img);
						
						html_ton += "<div class=\"option_item " + summ_coin + "\" data-sort=\"" + userActives.data.ton[key].active[symbol].currency_value + "\">";
		
						html_ton += "<img src=\"/images/logos/tonkeeper2.png\" alt=\"coin images\"><div class=\"currency_name_block ml-10\"><div class=\"currency_name\">Account 1</div><div class=\"currency_symbol\">'.Yii::t('Api', 'TON Wallet').'</div></div><div class=\"currency_graf ml-10\"></div><div class=\"currency_price_block ml-10\"><div class=\"currency_price\">" + formatValue(userActives.data.ton[key].active[symbol].currency_value, 1) + " " + userActives.grafema + "</div><div class=\"currency_volat\">" + formatValue(userActives.data.ton[key].active[symbol].balance) + "</div></div><div class=\"clearfix\"></div>";
						
						if (symbol=="ton") {
							if (log_id==8) {
							var deposit = new TonDeposit();
							html_ton += deposit.getDeposit({
								id: '.$id.',
								sc: "'.$sc.'",
								coin: symbol,
								apr: userActives.data.ton[key].active[symbol].apr,
								price: userActives.data.ton[key].active[symbol].price,
							});
							} 

						} else if(symbol=="usdt") {
							
							//html_ton +=addUSDTDeposit(userActives.data.ton[key]);
						}

						html_ton += "</div>";
					}
				}	
			};
			
			if (symbol=="ton" || symbol=="usdt") {
				if (!jQuery("#adModal").hasClass("show")) {						
					jQuery("#adModal #wrap_actives_coin").append(html_ton);
				};
			} else {
				jQuery("#adModal #wrap_actives_coin").append(html_ton);
			}

			jQuery("#question-addon4").popover({
				placement: "right",
				content: "This is the body of Popover",
				//trigger: "focus",
				template: "<div class=\"popover question_popover\" role=\"tooltip\"><div class=\"popover-arrow\" style=\"position: absolute; top: 0px; transform: translate(0px, 12px);\"></div><div class=\"popover-content\"><div class=\"question_addon_popover\"><div class=\"popover_text\">'.addslashes(Yii::t('Api', 'Wee recommend to leave 1 TON for comission')).' </div><div class=\"clearfix\"></div></div></div></div>",
			}).show();				

			jQuery("#question-addon5").popover({
				placement: "right",
				content: "This is the body of Popover",
				//trigger: "focus",
				template: "<div class=\"popover question_popover\" role=\"tooltip\"><div class=\"popover-arrow\" style=\"position: absolute; top: 0px; transform: translate(0px, 12px);\"></div><div class=\"popover-content\"><div class=\"question_addon_popover\"><div class=\"popover_text\">'.addslashes(Yii::t('Api', 'For using this pool')).' </div><div class=\"clearfix\"></div></div></div></div>",
			}).show();
		}
		
		if (
			typeof userActives.data.bybit!=="undefined" && 
			userActives.data.bybit!==undefined && 
			userActives.data.bybit && 
			typeof userActives.data.bybit==="object"
		) {
			for (key in userActives.data.bybit) {
				if (
					typeof userActives.data.bybit[key].active[symbol]!=="undefined" && 
					userActives.data.bybit[key].active[symbol]!==undefined && 
					userActives.data.bybit[key].active[symbol] && 
					typeof userActives.data.bybit[key].active[symbol]==="object"
				) {	
					if (userActives.data.bybit[key].active[symbol].symbolid==symbol) {
						
						var connectname = "";
						if (
							typeof userActives.data.bybit[key].active[symbol].connectname!=="undefined" && 
							userActives.data.bybit[key].active[symbol].connectname!==undefined && 
							userActives.data.bybit[key].active[symbol].connectname
						) {	
							connectname = userActives.data.bybit[key].active[symbol].connectname;
						}
						
						jQuery("#adModal .img_details_coin>img, #targetModal .img_details_coin>img").attr("src", userActives.data.bybit[key].active[symbol].img);

						if (
							typeof userActives.data.bybit[key].active[symbol].listCoin!=="undefined" && 
							userActives.data.bybit[key].active[symbol].listCoin!==undefined && 
							userActives.data.bybit[key].active[symbol].listCoin
						) {	
						
							userActives.data.bybit[key].active[symbol].listCoin.forEach((val, index) => {
								
								var summ_coin = "middle_value";
								if (parseFloat(val.currency_value)<1) {
									summ_coin = "small_value";
								}
								
								coinsSummActive += val.balance*1;
								tonSummActiveCurrency += val.currency_value*1;
								coins += parseFloat(val.balance);
								price = parseFloat(val.price);
								currency += parseFloat(val.currency_value);

								html_bybit += "<div class=\"option_item " + summ_coin + "\" data-sort=\"" + val.sort + "\"><img src=\"" + val.network_icon + "\" title=\"" + val.network + "\" alt=\"coin images\"><div class=\"currency_name_block ml-10\"><div class=\"currency_name\">" + connectname + "</div><div class=\"currency_symbol\">" + val.network + "</div><div class=\"earn\"></div></div><div class=\"currency_graf ml-10\"></div><div class=\"currency_price_block ml-10\"><div class=\"currency_price\">" + formatValue(val.currency_value) + " " + userActives.grafema + "</div><div class=\"currency_volat\">" + formatValue(val.balance) + "</div></div><div class=\"clearfix\"></div></div>";
	
							});
						}
						
						getCoinsActive();

						jQuery("#adModal .name_details_coin, #targetModal .name_details_coin").html(formatValue(price) + userActives.grafema);
					}
				}
				
				if (
					typeof userActives.data.bybit[key].trading[symbol]!=="undefined" && 
					userActives.data.bybit[key].trading[symbol]!==undefined && 
					userActives.data.bybit[key].trading[symbol] && 
					typeof userActives.data.bybit[key].trading[symbol]==="object"
				) {	
					if (userActives.data.bybit[key].trading[symbol].symbolid==symbol) {
						
						var connectname = "";
						if (
							typeof userActives.data.bybit[key].trading[symbol].connectname!=="undefined" && 
							userActives.data.bybit[key].trading[symbol].connectname!==undefined && 
							userActives.data.bybit[key].trading[symbol].connectname
						) {	
							connectname = userActives.data.bybit[key].trading[symbol].connectname;
						}
						
						jQuery("#adModal .img_details_coin>img, #targetModal .img_details_coin>img").attr("src", userActives.data.bybit[key].trading[symbol].img);

						if (
							typeof userActives.data.bybit[key].trading[symbol].listCoin!=="undefined" && 
							userActives.data.bybit[key].trading[symbol].listCoin!==undefined && 
							userActives.data.bybit[key].trading[symbol].listCoin
						) {	
						
							userActives.data.bybit[key].trading[symbol].listCoin.forEach((val, index) => {
								
								var summ_coin = "middle_value";
								if (parseFloat(val.currency_value)<1) {
									summ_coin = "small_value";
								}
								
								coinsSummActive += val.balance*1;
								tonSummActiveCurrency += val.currency_value*1;
								coins += parseFloat(val.balance);
								price = parseFloat(val.price);
								currency += parseFloat(val.currency_value);

								html_bybit += "<div class=\"option_item " + summ_coin + "\" data-sort=\"" + val.sort + "\"><img src=\"" + val.network_icon + "\" title=\"" + val.network + "\" alt=\"coin images\"><div class=\"currency_name_block ml-10\"><div class=\"currency_name\">" + connectname + "</div><div class=\"currency_symbol\">" + val.network + "</div><div class=\"earn\"></div></div><div class=\"currency_graf ml-10\"></div><div class=\"currency_price_block ml-10\"><div class=\"currency_price\">" + formatValue(val.currency_value) + " " + userActives.grafema + "</div><div class=\"currency_volat\">" + formatValue(val.balance) + "</div></div><div class=\"clearfix\"></div></div>";
	
							});
						}
						
						getCoinsActive();

						jQuery("#adModal .name_details_coin, #targetModal .name_details_coin").html(formatValue(price) + userActives.grafema);
					}		
				}
			};

			jQuery("#adModal #wrap_actives_coin").append(html_bybit);
		}
		
		if (
			typeof userActives.data.okx!=="undefined" && 
			userActives.data.okx!==undefined && 
			userActives.data.okx && 
			typeof userActives.data.okx==="object"
		) {
			for (key in userActives.data.okx) {
				if (
					typeof userActives.data.okx[key].active[symbol]!=="undefined" && 
					userActives.data.okx[key].active[symbol]!==undefined && 
					userActives.data.okx[key].active[symbol] && 
					typeof userActives.data.okx[key].active[symbol]==="object"
				) {	
					if (userActives.data.okx[key].active[symbol].symbolid==symbol) {
						
						var connectname = "";
						if (
							typeof userActives.data.okx[key].active[symbol].connectname!=="undefined" && 
							userActives.data.okx[key].active[symbol].connectname!==undefined && 
							userActives.data.okx[key].active[symbol].connectname
						) {	
							connectname = userActives.data.okx[key].active[symbol].connectname;
						}
						
						jQuery("#adModal .img_details_coin>img, #targetModal .img_details_coin>img").attr("src", userActives.data.okx[key].active[symbol].img);

						if (
							typeof userActives.data.okx[key].active[symbol].listCoin!=="undefined" && 
							userActives.data.okx[key].active[symbol].listCoin!==undefined && 
							userActives.data.okx[key].active[symbol].listCoin
						) {	
						
							userActives.data.okx[key].active[symbol].listCoin.forEach((val, index) => {
								
								var summ_coin = "middle_value";
								if (parseFloat(val.currency_value)<1) {
									summ_coin = "small_value";
								}
								
								coinsSummActive += val.balance*1;
								tonSummActiveCurrency += val.currency_value*1;
								coins += parseFloat(val.balance);
								price = parseFloat(val.price);
								currency += parseFloat(val.currency_value);

								html_okx += "<div class=\"option_item " + summ_coin + "\" data-sort=\"" + val.sort + "\"><img src=\"" + val.network_icon + "\" title=\"" + val.network + "\" alt=\"coin images\"><div class=\"currency_name_block ml-10\"><div class=\"currency_name\">" + connectname + "</div><div class=\"currency_symbol\">" + val.network + "</div><div class=\"earn\"></div></div><div class=\"currency_graf ml-10\"></div><div class=\"currency_price_block ml-10\"><div class=\"currency_price\">" + formatValue(val.currency_value) + " " + userActives.grafema + "</div><div class=\"currency_volat\">" + formatValue(val.balance) + "</div></div><div class=\"clearfix\"></div></div>";
	
							});
						}
						
						getCoinsActive();

						jQuery("#adModal .name_details_coin, #targetModal .name_details_coin").html(formatValue(price) + userActives.grafema);
					}
				}
				
				if (
					typeof userActives.data.okx[key].trading[symbol]!=="undefined" && 
					userActives.data.okx[key].trading[symbol]!==undefined && 
					userActives.data.okx[key].trading[symbol] && 
					typeof userActives.data.okx[key].trading[symbol]==="object"
				) {	
					if (userActives.data.okx[key].trading[symbol].symbolid==symbol) {
						
						var connectname = "";
						if (
							typeof userActives.data.okx[key].trading[symbol].connectname!=="undefined" && 
							userActives.data.okx[key].trading[symbol].connectname!==undefined && 
							userActives.data.okx[key].trading[symbol].connectname
						) {	
							connectname = userActives.data.okx[key].trading[symbol].connectname;
						}
						
						jQuery("#adModal .img_details_coin>img, #targetModal .img_details_coin>img").attr("src", userActives.data.okx[key].trading[symbol].img);

						if (
							typeof userActives.data.okx[key].trading[symbol].listCoin!=="undefined" && 
							userActives.data.okx[key].trading[symbol].listCoin!==undefined && 
							userActives.data.okx[key].trading[symbol].listCoin
						) {	
						
							userActives.data.okx[key].trading[symbol].listCoin.forEach((val, index) => {
								
								var summ_coin = "middle_value";
								if (parseFloat(val.currency_value)<1) {
									summ_coin = "small_value";
								}
								
								coinsSummActive += val.balance*1;
								tonSummActiveCurrency += val.currency_value*1;
								coins += parseFloat(val.balance);
								price = parseFloat(val.price);
								currency += parseFloat(val.currency_value);

								html_okx += "<div class=\"option_item " + summ_coin + "\" data-sort=\"" + val.sort + "\"><img src=\"" + val.network_icon + "\" title=\"" + val.network + "\" alt=\"coin images\"><div class=\"currency_name_block ml-10\"><div class=\"currency_name\">" + connectname + "</div><div class=\"currency_symbol\">" + val.network + "</div><div class=\"earn\"></div></div><div class=\"currency_graf ml-10\"></div><div class=\"currency_price_block ml-10\"><div class=\"currency_price\">" + formatValue(val.currency_value) + " " + userActives.grafema + "</div><div class=\"currency_volat\">" + formatValue(val.balance) + "</div></div><div class=\"clearfix\"></div></div>";
	
							});
						
						}
						
						getCoinsActive();

						jQuery("#adModal .name_details_coin, #targetModal .name_details_coin").html(formatValue(price) + userActives.grafema);
					}		
				}
			};

			jQuery("#adModal #wrap_actives_coin").append(html_okx);
		}

		if (
			typeof userActives.data.sol!=="undefined" && 
			userActives.data.sol!==undefined && 
			userActives.data.sol && 
			typeof userActives.data.sol==="object"
		) {
			for (key in userActives.data.sol) {
				if (
					typeof userActives.data.sol[key].active[symbol]!=="undefined" && 
					userActives.data.sol[key].active[symbol]!==undefined && 
					userActives.data.sol[key].active[symbol] && 
					typeof userActives.data.sol[key].active[symbol]==="object"
				) {	
					if (userActives.data.sol[key].active[symbol].symbolid==symbol) {
						
						var connectname = "";
						if (
							typeof userActives.data.sol[key].active[symbol].connectname!=="undefined" && 
							userActives.data.sol[key].active[symbol].connectname!==undefined && 
							userActives.data.sol[key].active[symbol].connectname
						) {	
							connectname = userActives.data.sol[key].active[symbol].connectname;
						}
						
						jQuery("#adModal .img_details_coin>img, #targetModal .img_details_coin>img").attr("src", userActives.data.sol[key].active[symbol].img);

						if (
							typeof userActives.data.sol[key].active[symbol].listCoin!=="undefined" && 
							userActives.data.sol[key].active[symbol].listCoin!==undefined && 
							userActives.data.sol[key].active[symbol].listCoin
						) {	
						
							userActives.data.sol[key].active[symbol].listCoin.forEach((val, index) => {
								
								var summ_coin = "middle_value";
								if (parseFloat(val.currency_value)<1) {
									summ_coin = "small_value";
								}
								
								coinsSummActive += val.balance*1;
								tonSummActiveCurrency += val.currency_value*1;
								coins += parseFloat(val.balance);
								price = parseFloat(val.price);
								currency += parseFloat(val.currency_value);

								html_sol += "<div class=\"option_item " + summ_coin + "\" data-sort=\"" + val.sort + "\"><img src=\"" + val.network_icon + "\" title=\"" + val.network + "\" alt=\"coin images\"><div class=\"currency_name_block ml-10\"><div class=\"currency_name\">" + connectname + "</div><div class=\"currency_symbol\">" + val.network + "</div><div class=\"earn\"></div></div><div class=\"currency_graf ml-10\"></div><div class=\"currency_price_block ml-10\"><div class=\"currency_price\">" + formatValue(val.currency_value) + " " + userActives.grafema + "</div><div class=\"currency_volat\">" + formatValue(val.balance) + "</div></div><div class=\"clearfix\"></div></div>";
	
							});
						
						}
						
						getCoinsActive();

						jQuery("#adModal .name_details_coin, #targetModal .name_details_coin").html(formatValue(price) + userActives.grafema);
					}
				}
			};
			
			jQuery("#adModal #wrap_actives_coin").append(html_sol);
		}
		
		if (
			typeof userActives.data.sui!=="undefined" && 
			userActives.data.sui!==undefined && 
			userActives.data.sui && 
			typeof userActives.data.sui==="object"
		) {
			for (key in userActives.data.sui) {
				if (
					typeof userActives.data.sui[key].active[symbol]!=="undefined" && 
					userActives.data.sui[key].active[symbol]!==undefined && 
					userActives.data.sui[key].active[symbol] && 
					typeof userActives.data.sui[key].active[symbol]==="object"
				) {	
					if (userActives.data.sui[key].active[symbol].symbolid==symbol) {
						
						var connectname = "";
						if (
							typeof userActives.data.sui[key].active[symbol].connectname!=="undefined" && 
							userActives.data.sui[key].active[symbol].connectname!==undefined && 
							userActives.data.sui[key].active[symbol].connectname
						) {	
							connectname = userActives.data.sui[key].active[symbol].connectname;
						}
						
						jQuery("#adModal .img_details_coin>img, #targetModal .img_details_coin>img").attr("src", userActives.data.sui[key].active[symbol].img);

						if (
							typeof userActives.data.sui[key].active[symbol].listCoin!=="undefined" && 
							userActives.data.sui[key].active[symbol].listCoin!==undefined && 
							userActives.data.sui[key].active[symbol].listCoin
						) {	
						
							userActives.data.sui[key].active[symbol].listCoin.forEach((val, index) => {
								
								var summ_coin = "middle_value";
								if (parseFloat(val.currency_value)<1) {
									summ_coin = "small_value";
								}
								
								coinsSummActive += val.balance*1;
								tonSummActiveCurrency += val.currency_value*1;
								coins += parseFloat(val.balance);
								price = parseFloat(val.price);
								currency += parseFloat(val.currency_value);

								html_sui += "<div class=\"option_item " + summ_coin + "\" data-sort=\"" + val.sort + "\"><img src=\"" + val.network_icon + "\" title=\"" + val.network + "\" alt=\"coin images\"><div class=\"currency_name_block ml-10\"><div class=\"currency_name\">" + connectname + "</div><div class=\"currency_symbol\">" + val.network + "</div><div class=\"earn\"></div></div><div class=\"currency_graf ml-10\"></div><div class=\"currency_price_block ml-10\"><div class=\"currency_price\">" + formatValue(val.currency_value) + " " + userActives.grafema + "</div><div class=\"currency_volat\">" + formatValue(val.balance) + "</div></div><div class=\"clearfix\"></div></div>";
	
							});
						
						}
						
						getCoinsActive();

						jQuery("#adModal .name_details_coin, #targetModal .name_details_coin").html(formatValue(price) + userActives.grafema);
					}
				}
			};
			
			jQuery("#adModal #wrap_actives_coin").append(html_sui);
		}
		
		if (
			typeof userActives.data.apt!=="undefined" && 
			userActives.data.apt!==undefined && 
			userActives.data.apt && 
			typeof userActives.data.apt==="object"
		) {
			for (key in userActives.data.apt) {
				
				console.log(userActives.data.apt[key].active[symbol]);
				
				
				if (
					typeof userActives.data.apt[key].active[symbol]!=="undefined" && 
					userActives.data.apt[key].active[symbol]!==undefined && 
					userActives.data.apt[key].active[symbol] && 
					typeof userActives.data.apt[key].active[symbol]==="object"
				) {	
					if (userActives.data.apt[key].active[symbol].symbolid==symbol) {
						
						var connectname = "";
						if (
							typeof userActives.data.apt[key].active[symbol].connectname!=="undefined" && 
							userActives.data.apt[key].active[symbol].connectname!==undefined && 
							userActives.data.apt[key].active[symbol].connectname
						) {	
							connectname = userActives.data.apt[key].active[symbol].connectname;
						}
						
						jQuery("#adModal .img_details_coin>img, #targetModal .img_details_coin>img").attr("src", userActives.data.apt[key].active[symbol].img);

						if (
							typeof userActives.data.apt[key].active[symbol].listCoin!=="undefined" && 
							userActives.data.apt[key].active[symbol].listCoin!==undefined && 
							userActives.data.apt[key].active[symbol].listCoin
						) {	
						
							userActives.data.apt[key].active[symbol].listCoin.forEach((val, index) => {
								
								var summ_coin = "middle_value";
								if (parseFloat(val.currency_value)<1) {
									summ_coin = "small_value";
								}
								
								coinsSummActive += val.balance*1;
								tonSummActiveCurrency += val.currency_value*1;
								coins += parseFloat(val.balance);
								price = parseFloat(val.price);
								currency += parseFloat(val.currency_value);
								
								var titleCoin = val.network;
								if (typeof val.protocol!=="undefined" && val.protocol!==undefined && val.protocol && val.protocol.length>0) {
									
									if (typeof val.network_link!=="undefined" && val.network_link!==undefined && val.network_link && val.network_link.length>0) {
										
										titleCoin += " | <a href=\"" + val.network_link + "\" class=\"protocol-link\" target=\"_blank\">" + val.protocol + "</a>";
										
									} else {
		
										titleCoin += " | " + val.protocol;
									}
								}
								
								if (typeof val.apr!=="undefined" && val.apr!==undefined && val.apr && val.apr.length>0) {
									titleCoin += " | " + val.apr;
								}

								html_apt += "<div class=\"option_item " + summ_coin + "\" data-sort=\"" + val.sort + "\"><img src=\"" + val.network_icon + "\" title=\"" + val.network + "\" alt=\"coin images\"><div class=\"currency_name_block ml-10\"><div class=\"currency_name\">" + connectname + "</div><div class=\"currency_symbol\">" + titleCoin + "</div><div class=\"earn\"></div></div><div class=\"currency_graf ml-10\"></div><div class=\"currency_price_block ml-10\"><div class=\"currency_price\">" + formatValue(val.currency_value) + " " + userActives.grafema + "</div><div class=\"currency_volat\">" + formatValue(val.balance) + "</div></div><div class=\"clearfix\"></div></div>";
	
							});
						
						}
						
						getCoinsActive();

						jQuery("#adModal .name_details_coin, #targetModal .name_details_coin").html(formatValue(price) + userActives.grafema);							
					}
				}
			};
			
			jQuery("#adModal #wrap_actives_coin").append(html_apt);
		}
		
		if (
			typeof userActives.data.eth!=="undefined" && 
			userActives.data.eth!==undefined && 
			userActives.data.eth && 
			typeof userActives.data.eth==="object"
		) {

			for (key in userActives.data.eth) {
				if (
					typeof userActives.data.eth[key].active[symbol]!=="undefined" && 
					userActives.data.eth[key].active[symbol]!==undefined && 
					userActives.data.eth[key].active[symbol] && 
					typeof userActives.data.eth[key].active[symbol]==="object"
				) {	
					if (userActives.data.eth[key].active[symbol].symbolid==symbol) {
						
						var connectname = "";
						if (
							typeof userActives.data.eth[key].active[symbol].connectname!=="undefined" && 
							userActives.data.eth[key].active[symbol].connectname!==undefined && 
							userActives.data.eth[key].active[symbol].connectname
						) {	
							connectname = userActives.data.eth[key].active[symbol].connectname;
						}
						
						jQuery("#adModal .img_details_coin>img, #targetModal .img_details_coin>img").attr("src", userActives.data.eth[key].active[symbol].img);

						if (
							typeof userActives.data.eth[key].active[symbol].listCoin!=="undefined" && 
							userActives.data.eth[key].active[symbol].listCoin!==undefined && 
							userActives.data.eth[key].active[symbol].listCoin
						) {	
							
							userActives.data.eth[key].active[symbol].listCoin.forEach((val, index) => {
								
								var summ_coin = "middle_value";
								if (parseFloat(val.currency_value)<1) {
									summ_coin = "small_value";
								}
								
								coinsSummActive += val.balance*1;
								tonSummActiveCurrency += val.currency_value*1;
								coins += parseFloat(val.balance);
								price = parseFloat(val.price);
								currency += parseFloat(val.currency_value);
				
								var titleCoin = val.network;
								if (typeof val.protocol!=="undefined" && val.protocol!==undefined && val.protocol && val.protocol.length>0) {
									
									if (typeof val.network_link!=="undefined" && val.network_link!==undefined && val.network_link && val.network_link.length>0) {
										
										titleCoin += " | <a href=\"" + val.network_link + "\" class=\"protocol-link\">" + val.protocol + "</a>";
										
									} else {
			
										titleCoin += " | " + val.protocol;
									}
								}
								
								if (typeof val.apr!=="undefined" && val.apr!==undefined && val.apr && val.apr.length>0) {
					
									titleCoin += " | " + val.apr;
								}

								html_eth += "<div class=\"option_item " + summ_coin + "\" data-sort=\"" + val.sort + "\"><img src=\"" + val.network_icon + "\" title=\"" + val.network + "\" alt=\"coin images\"><div class=\"currency_name_block ml-10\"><div class=\"currency_name\">" + connectname + "</div><div class=\"currency_symbol\">" + titleCoin + "</div><div class=\"earn\"></div></div><div class=\"currency_graf ml-10\"></div><div class=\"currency_price_block ml-10\"><div class=\"currency_price\">" + formatValue(val.currency_value) + " " + userActives.grafema + "</div><div class=\"currency_volat\">" + formatValue(val.balance) + "</div></div><div class=\"clearfix\"></div></div>";
	
							});
						
						}

						getCoinsActive();

						jQuery("#adModal .name_details_coin, #targetModal .name_details_coin").html(formatValue(price) + userActives.grafema);	
	
					}
				}
			};
			
			jQuery("#adModal #wrap_actives_coin").append(html_eth);
		}

		var sort_coin = jQuery.makeArray(jQuery("#adModal #wrap_actives_coin .option_item"));
		
		sort_coin.sort(function (a, b) {
			a = jQuery(a).attr("data-sort");
			b = jQuery(b).attr("data-sort");
			return b - a
		});

		jQuery(sort_coin).appendTo("#adModal #wrap_actives_coin")

		jQuery("#adModal #ad-price").val(price);
		jQuery("#adModal #ad-currency").val(currency);
		jQuery("#adModal #ad-coins").val(coins);
		
		jQuery("#adModal #target_actions-button").html("'.Yii::t('Api', 'Target').'");
		
		if (isTarget(symbol)) {			
			jQuery("#adModal #target_actions-button").html("'.Yii::t('Api', 'Change Target').'");
			
		}
		
		getSettings();
		addTargetPage(symbol);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//addUSDTDeposit(userDataWallet)
	function addUSDTDeposit(userDataWallet) {
		var aqua_balance = 0;
		if (
			typeof userDataWallet.active["aquausd"]!== "undefined" &&
			userDataWallet.active["aquausd"]!==undefined &&
			userDataWallet.active["aquausd"] &&
			typeof userDataWallet.active["aquausd"].balance!== "undefined" &&
			userDataWallet.active["aquausd"].balance!==undefined &&
			userDataWallet.active["aquausd"].balance
		) {
			aqua_balance = userDataWallet.active["aquausd"].balance;
		}

		var usdt_apr = "";
		var usdt_balance = 0;
		if (
			typeof userDataWallet.active["usdt"].balance!== "undefined" &&
			userDataWallet.active["usdt"].balance!==undefined &&
			userDataWallet.active["usdt"].balance
		) {
			usdt_balance = toFloatDecimals(userDataWallet.active["usdt"].balance, 2);
		}

		if (
			typeof userDataWallet.active["usdt"].apr!== "undefined" &&
			userDataWallet.active["usdt"].apr!==undefined &&
			userDataWallet.active["usdt"].apr
		) {
			usdt_apr += "APR=" + userDataWallet.active["usdt"].apr;
		}

		var usdt_exchange_balance = 0;
		var usdt_send_balance = 0;
		var aqua_send_balance = 0;
		var usdt_wallet_balance = 0;
		var swap_button = "";
		var depo_button = "";
		var usdt_class = "is-valid";
		var aqua_class = "is-valid";
		var aqua_price_balance = 0;
				
		if (usdt_balance) {		
			aqua_price_balance = usdt_balance*userDataWallet.active["usdt"].price;
		}

		if (usdt_balance && aqua_balance) {
			aqua_send_balance = usdt_balance*userDataWallet.active["usdt"].price;
			usdt_send_balance = usdt_balance;

			if (aqua_send_balance<=aqua_balance) {

				aqua_class = "is-valid";
				jQuery("#swapBlock2").hide();
			
			} else {
			
				aqua_class = "is-invalid";
				usdt_exchange_balance = aqua_send_balance-aqua_balance;
				jQuery("#swapBlock2").show();
			
			}
			
		} else if(usdt_balance && !aqua_balance) {
			
			var aqua_class = "is-invalid";
			
			aqua_send_balance = usdt_balance*userDataWallet.active["usdt"].price;
			usdt_exchange_balance = aqua_send_balance;
			jQuery("#swapBlock2").show();

			
		} else if(!usdt_balance && aqua_balance) {	
			
			var usdt_class = "is-invalid";
			var aqua_class = "is-valid";
			jQuery("#swapBlock2").hide();
			jQuery("#poolBlock2").hide();
			
		} else {
			
			var aqua_class = "is-invalid";
			var usdt_class = "is-invalid";
			var swap_button = "disabled";
			var depo_button = "disabled";			
		}
	
		
		if (usdt_exchange_balance>0) {
			usdt_exchange_balance = customRound(usdt_exchange_balance, 4);
		}

		if (usdt_send_balance>0) {
			usdt_send_balance = toFloatDecimals(usdt_send_balance, 4);
		}	
		if (!usdt_send_balance) {
			usdt_send_balance = "";
		}
		
		if (aqua_send_balance>0) {
			aqua_send_balance = toFloatDecimals(aqua_send_balance, 4);
		}
		
		if (!aqua_send_balance) {
			aqua_send_balance = "";
		}
		
		usdt_exchange_balance = toFloatAmont(usdt_exchange_balance);
		if (!usdt_exchange_balance) {
			usdt_exchange_balance = "";
		}

		usdt_wallet_balance = toFloatAmont(usdt_balance);
		if (!usdt_wallet_balance) {
			usdt_wallet_balance = "";
		}

		var html_deposit = "<div class=\"earn\">";
		
			html_deposit += "<div style=\"font-size:18px;margin-bottom:4px;\">'.Yii::t('Api', 'I want to Deposit').'&nbsp;<div id=\"question-addon5\" class=\"is-external-info\"></div></div>";
							
			html_deposit += "<div style=\"border:1px solid #fff;border-radius:8px;padding:15px;margin-bottom:8px;\">";
							
				html_deposit += "<div style=\"margin-bottom:8px\"></div>";
							
				html_deposit += "<div style=\"width:calc(50% - 5px);height:60px\" class=\"form-floating float-start\"><input style=\"width:100%;height:60px;font-size:20px\" type=\"text\" class=\"form-control float-start\" id=\"inputToDeposit2\" placeholder=\"'.Yii::t('Api', 'USDT to Deposit').'\" autocomplete=\"off\" inputmode=\"numeric\" value=\"" + toFloatDecimals(usdt_wallet_balance, 4) + "\"><label for=\"inputToDeposit2\">'.Yii::t('Api', 'USDT to Deposit').'</label></div>";
				
				html_deposit += "<div style=\"width:10px;height:60px;padding:20px 5px 0 5px\" class=\"float-start\"></div>";
				
				html_deposit += "<div style=\"width:calc(50% - 5px);height:60px\" class=\"form-floating float-start\"><input style=\"width:100%;height:60px;font-size:20px\" type=\"text\" class=\"form-control float-start\" id=\"inputToDepositLeft2\" placeholder=\"'.Yii::t('Api', 'AQUA').'\" autocomplete=\"off\" inputmode=\"numeric\" value=\"" + toFloatDecimals(aqua_price_balance, 4) + "\"><label for=\"inputToDepositLeft2\">'.Yii::t('Api', 'AQUA').'</label></div>";

				html_deposit += "<div class=\"clearfix\"></div>";
				
			html_deposit += "</div>";
							
			html_deposit += "<div id=\"deposit_apr2\" style=\"font-size:18px;margin-bottom:4px;\">'.Yii::t('Api', 'Deposit').' " + usdt_apr + "&nbsp;<div id=\"question-addon5\" class=\"is-external-info\"></div></div>";
							
			html_deposit += "<div style=\"border:1px solid #fff;border-radius:8px;padding:15px;margin-bottom:8px;\"><div style=\"margin-bottom:8px\">'.Yii::t('Api', 'Build LP using').'</div>";

				html_deposit += "<div style=\"width:calc(50% - 60px);height:60px\" class=\"form-floating float-start\"><input style=\"width:100%;height:60px;font-size:20px\" type=\"text\" class=\"form-control float-start " + usdt_class + "\" id=\"inputUSDTDeposit2\" placeholder=\"'.Yii::t('Api', 'USDT').'\" autocomplete=\"off\" inputmode=\"numeric\" value=\"" + usdt_send_balance + "\"><label for=\"inputUSDTDeposit2\">'.Yii::t('Api', 'USDT').'</label></div>";
								
				html_deposit += "<div style=\"width:10px;height:60px;padding:20px 5px 0 5px\" class=\"float-start\"></div>";
							
				html_deposit += "<div style=\"width:calc(50% - 60px);height:60px\" class=\"form-floating float-start\"><input style=\"width:100%;height:60px;font-size:20px\" type=\"text\" class=\"form-control float-start " + aqua_class + "\" id=\"inputAQUADeposit2\" placeholder=\"'.Yii::t('Api', 'AQUA').'\" autocomplete=\"off\" inputmode=\"numeric\" value=\"" + aqua_send_balance + "\"><label for=\"inputAQUADeposit2\">'.Yii::t('Api', 'AQUA').'</label></div>";

				html_deposit += "<button id=\"usdtaqua-add-liquidity-button\" class=\"btn btn-outline-light float-end\" style=\"width:100px;height:60px;font-size:20px\">'.Yii::t('Api', 'Deposit').'</button>";

				html_deposit += "<div class=\"clearfix\"></div>";

			html_deposit += "</div>";
			
			html_deposit += "<div id=\"swapBlock2\">";
			
				html_deposit += "<div style=\"font-size:18px;margin-bottom:4px;\">'.Yii::t('Api', 'Swap').'</div>";
								
				html_deposit += "<div style=\"border:1px solid #fff;border-radius:8px;padding:15px;margin-bottom:8px;\">";
								
					html_deposit += "<div style=\"margin-bottom:8px\">'.Yii::t('Api', 'Exchange your TON to balance assets for this pool').'<br>'.Yii::t('Api', 'AQUA Balance').': " + aqua_balance + "</div>";
								
					html_deposit += "<div style=\"width:calc(100% - 110px);height:60px\" class=\"form-floating float-start\"><input style=\"width:100%;height:60px;font-size:20px\" type=\"text\" class=\"form-control\" id=\"inputSwap2\" placeholder=\"'.Yii::t('Api', 'USDT to AQUA').'\" autocomplete=\"off\" inputmode=\"numeric\" value=\"" + usdt_exchange_balance + "\"><label for=\"inputSwap2\">'.Yii::t('Api', 'USDT to AQUA').'</label></div>";

					html_deposit += "<div style=\"width:10px;\" class=\"float-start\"></div>";
								
					html_deposit += "<button id=\"swap-usdtaqua-button\" class=\"btn btn-outline-light float-end\" style=\"width:100px;height:60px;font-size:20px\">'.Yii::t('Api', 'Swap').'</button><div class=\"clearfix\"></div>";
								
				html_deposit += "</div>";
			html_deposit += "</div>";	

		html_deposit += "</div>";
		
		return html_deposit;
	}
	
	//sendTransactionData(type, queryId, status)
	function sendTransactionData(type, queryId, status) {
		if (typeof type!=="undefined" && type!==undefined && type) {
			
			if (type=="addtonusdt") {
				
				
			} else if(type="swaptonusdt") {
				
				if (status==1) {
					jQuery("#inputSwap").val("");
				}
				
			} else if(type="addusdtaqua") {
				
				if (status==1) {
					jQuery("#inputSwap2").val("");
				}
				
				
			} else if(type="swapusdtaqua") {
				
				jQuery("#inputSwap").val("");
			}
		} 
	}
	
	
	
	
	
	
	
	
	
	
	
	//addTargetPage(symbol)
	function addTargetPage(symbol) {
		
		var value = 2;
		var summ_coins = parseFloat(jQuery("#adModal #ad-coins").val());
		var price_coins = parseFloat(jQuery("#adModal #ad-price").val());

		if (isTarget(symbol)) {	

			var dataTarget = getTarget(symbol);
			value = parseFloat(dataTarget.multiply);
			var price = parseFloat(dataTarget.price);
			jQuery("#adModal #target-info").html("'.Yii::t('Api', 'Current Target').': " + price).show();

		} else {

			jQuery("#adModal #target-info").hide();
			var price = 0;
		}	
		
		var summ_price = summ_coins*price;
		
		jQuery("#targetModal #inputPrice").val(formatValue(price));			
		jQuery("#targetModal #inputAmount").val(formatValue(summ_price));
		jQuery("#customRange1").val(value);
		jQuery("#ad-user-price").val(value);
	}
	
	function targetform() {

		var symbol = $("#adModal #ad-symbol").val();
		if (typeof symbol==="undefined" || symbol===undefined || !symbol) {
			addNotify("'.Yii::t('Error', 'Missing Symbol Coins').'", "error");
			return false;
		}
		
		var target_price = $("#targetModal #inputPrice").val();
		if (typeof target_price==="undefined" || target_price===undefined || !target_price) {
			addNotify("'.Yii::t('Error', 'Not Value Price').'", "error");
			return false;
		}
		
		var price = $("#adModal #ad-price").val();
		if (typeof price==="undefined" || price===undefined || !price) {
			addNotify("'.Yii::t('Error', 'Not Value Price').'", "error");
			return false;
		}

		var coins = $("#adModal #ad-coins").val();
		if (typeof coins==="undefined" || coins===undefined || !coins) {
			addNotify("'.Yii::t('Error', 'Not Value Coins').'", "error");
			return false;
		}
		
		var multiply = $("#targetModal #ad-user-price").val();
		if (typeof multiply==="undefined" || multiply===undefined || !multiply) {
			addNotify("'.Yii::t('Error', 'Not Value Multiply').'", "error");
			return false;
		}

		var spinner = "<i class=\"fas fa-asterisk fa-spin\"></i>";
		var text_button = jQuery("#targetModal #ct-target-send").text();
		jQuery("#targetModal #ct-target-send").html(spinner + "&nbsp;" + text_button);
	
		jQuery.ajax({
			"url": "/app/addtarget",
			"type": "post",
			"dataType": "json",
			"contentType": "application/json",
			"data": JSON.stringify({"coins": coins, "symbol": symbol, "price": target_price, "current_price": price, "multiply": multiply, "log_id": log_id, sc: sc}),
			"success": function(response){

				jQuery("#targetModal #ct-target-send").html(text_button);

				jQuery("#adModal").attr("data-id", symbol);
				closeAllModal();
				var modal = new bootstrap.Modal(document.getElementById("adModal"), {
					backdrop: false,
					keyboard: false			
				});
				modal.show();

				if (response) {

					if (!response.error) {
						
						if (
							typeof response.targets!=="undefined" &&
							response.targets!==undefined &&
							response.targets
						) {
							targets = JSON.stringify(response.targets);
						}

						if (
							typeof response.message.change_target!=="undefined" &&
							response.message.change_target!==undefined
						) {

							if (response.message.change_target==1) {
								addNotify("'.Yii::t('Api', 'Success Add Target').'", "success");
								jQuery("#adModal #target_actions-button").html("'.Yii::t('Api', 'Change Target').'");
								jQuery("#targetModal #ct-target-send").html("'.Yii::t('Api', 'Set Target').'");
							} else {
								addNotify("'.Yii::t('Api', 'Success Change Target').'", "success");
								jQuery("#adModal #target_actions-button").html("'.Yii::t('Api', 'Change Target').'");
								jQuery("#targetModal #ct-target-send").html("'.Yii::t('Api', 'Change Target').'");
							}
							
						} else {
							addNotify("'.Yii::t('Api', 'Success Add Target').'", "success");
						}
			
						var bar = getTargetProgressBar(symbol, price);
						jQuery("#asModal .option_item[data-id=\"" + symbol + "\"] .block_target_bar").html(bar);
			
					} else {		
						addNotify(response.message, "error");
						return false;
					}
					
				} else {
					addNotify("'.Yii::t('Error', 'Server not response').'", "error");
					return false;
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				addNotify(thrownError, "error");
				return false;
			}
		});	
		
	}
	
	//getTargetProgressBar(symbol, price)
	function getTargetProgressBar(symbol, price) {

		var bar = "";
		
		if (typeof symbol==="undefined" || symbol===undefined || !symbol) {
			return bar;
		}
		
		symbol = symbol.toLowerCase();
		
		if (typeof targets==="undefined" || targets===undefined || !targets) {
			return bar;
		}

		var barValue = "";
		var default_price = parseFloat(price);
		
		try {
			let targetsObj = JSON.parse(targets);
		
			if (
				typeof targetsObj!=="undefined" && 
				targetsObj!==undefined && 
				targetsObj &&
				typeof targetsObj==="object"
			) {
				for (key in targetsObj) {
					if (targetsObj[key].symbol==symbol) {
						var current = parseFloat(targetsObj[key].current);
						var target_price = parseFloat(targetsObj[key].price);
						var difference = 0;
						var offset = 0;
						
						if (target_price<=current) {
							barValue=100;
						} else {
							difference = target_price - current;

							if (default_price<=current) {
								barValue = 0;
							} else {
								offset = default_price - current;
		
								if (
									typeof difference==="undefined" || 
									difference===undefined || 
									!difference || 
									isNaN(difference) || 
									!Number.isFinite(difference)
								) {
									barValue = 100;
								} else {

									if (offset==0) {
										barValue = 0;
									} else {
										barValue = (offset/difference)*100;
										if (
											typeof barValue==="undefined" || 
											barValue===undefined || 
											!barValue || 
											isNaN(barValue) || 
											!Number.isFinite(barValue)
										) {
											barValue = 0;
										}
									}
								}
							}
						}
					}
				}
			}

		} catch (e) {
			console.log(e.message);	
			return bar;
		}

		bar += "<div class=\"wrap-progress-bar\">";
			bar += "<div class=\"row\">";
				bar += "<div class=\"float-start left-block-target\">'.Yii::t('Api', 'Target').'</div>";
				bar += "<div class=\"float-start right-block-target\">";
					bar += "<div class=\"progress\">";
						bar += "<div class=\"progress-bar\" role=\"progressbar\" style=\"width:" + barValue + "%\" aria-valuenow=\"100%\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div>";
					bar += "</div>";
				bar += "</div>";
			bar += "</div>";
		bar += "</div>";

		bar += "<div class=\"clearfix\"></div>";
		
		return bar;
	}
	
	
	
	
	
	
	
	

	//document ready
	jQuery(document).ready(function($) {	

		var chat = new Chatai();
		chat.getChat({
			username: username,
			userpic: userpic,
			appname: "FinKeeper",
			apppic: "/images/favicons/web-app-manifest-512x512.png",
			elementChat: "as-chatai",
			elementForm: "bottom-chat-form",
			id: '.$id.',
			sc: "'.$sc.'",
			portfolio: userActivesMin,
			wallet: {
				type: "sui",
				address: "'.$wallet['sui']['address'].'",
				balance: '.$wallet['sui']['balance'].',
				price: '.$wallet['sui']['price'].',
				navi: '.$wallet['sui']['navi'].',
				rewards: '.$wallet['sui']['rewards'].',
				currency: "'.$grafema.'",	
			},
			
		});

		var bybit = new Bybit();
		bybit.getBybit({
			elementButton: "bybit-exchange-connect-button-as854",
			elementForm: "bybit-exchange-connect-manage-as854",
			id: '.$id.',
			sc: "'.$sc.'",
			connect: '.$status['bybit'].',
		});
		
		var okx = new OKX();
		okx.getOKX({
			elementButton: "okx-exchange-connect-button-as858",
			elementForm: "okx-exchange-connect-manage-as858",
			id: '.$id.',
			sc: "'.$sc.'",
			connect: '.$status['okx'].',
		});	

		var sol = new SOL();
		sol.getSOL({
			elementButton: "sol-exchange-connect-button-as864",
			elementForm: "sol-exchange-connect-manage-as864",
			id: '.$id.',
			sc: "'.$sc.'",
			connect: '.$status['sol'].',
		});	
		
		var sui = new SUI();
		sui.getSUI({
			elementButton: "sui-exchange-connect-button-as164",
			elementForm: "sui-exchange-connect-manage-as164",
			id: '.$id.',
			sc: "'.$sc.'",
			connect: '.$status['sui'].',
		});	
		
		var apt = new APT();
		apt.getAPT({
			elementButton: "apt-exchange-connect-button-as316",
			elementForm: "apt-exchange-connect-manage-as316",
			id: '.$id.',
			sc: "'.$sc.'",
			connect: '.$status['apt'].',
		});
		
		var eth = new ETH();
		eth.getETH({
			elementButton: "eth-exchange-connect-button-as628",
			elementForm: "eth-exchange-connect-manage-as628",
			id: '.$id.',
			sc: "'.$sc.'",
			connect: '.$status['eth'].',
		});

		$("#smart-toy").on("click", function() {
			
			updatePopover("smart-toy", "'.Yii::t('Api', 'You will receive message in Telegram bot').'", "right", "hover");
			
			var elem = jQuery("#smart-toy");
			var popover = bootstrap.Popover.getInstance(elem);
			popover.show();
			
			sendDataAl(1);
		});
		
		$("#smart-toy-active").on("click", function() {
			
			updatePopover("smart-toy-active", "'.Yii::t('Api', 'You will receive message in Telegram bot').'", "right", "hover");
			
			var elem = jQuery("#smart-toy-active");
			var popover = bootstrap.Popover.getInstance(elem);
			popover.show();
			
			sendDataAl(2);
		});

		if (tonConnectedStatus==1) {
			tonconnected();
		}

		$("#smart-toy, #smart-toy-active").popover({
			placement: "right",
			content: "This is the body of Popover",
			trigger: "hover",
			template: "<div class=\"popover question_popover\" role=\"tooltip\"><div class=\"popover-arrow\" style=\"position: absolute; top: 0px; transform: translate(0px, 12px);\"></div><div class=\"popover-content\"><div class=\"question_addon_popover\"><div class=\"popover_text\">'.addslashes(str_replace(["\n", "\r"], "", Yii::t('Api', 'AI assistant'))).'</div><div class=\"clearfix\"></div></div></div></div>",
		});

		// Popover menu
		jQuery("#ton-wallet-click-button").popover({
			placement: "bottom",
			content: " ",
			container: "body",
			trigger: "click",
			template: "<div class=\"popover connect_popover\" role=\"tooltip\"><div class=\"popover-content\"><div class=\"ton_connect_button\"><div class=\"mdi mdi-logout\"></div><div class=\"popover_text\">'.addslashes(str_replace(["\n", "\r"], "", Yii::t('Api', 'Connect'))).'</div><div class=\"clearfix\"></div></div></div></div>",
		});
	});
', yii\web\View::POS_END);