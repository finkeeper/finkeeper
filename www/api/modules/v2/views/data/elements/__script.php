<?php
$this->registerJs('

	Telegram.WebApp.expand();

	var log_id = '.$id.';
	var sc = "'.$sc.'";
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
	var coinsSummActive = 0;
	var tonSummActiveCurrency = 0;
	var tonConnectedStatus='.$status['ton'].';
	var bybitConnectedStatus='.$status['bybit'].';
	var okxConnectedStatus='.$status['okx'].';
	var solConnectedStatus='.$status['sol'].';
	var suiConnectedStatus='.$status['sui'].';
	var userActives={
		"grafema": "",
		"data": {
			"tonwallet": {
				0: {
					"asset": "",
					"active": {},
				}
			},
			"bybit":{
				0: {
					"asset": "",
					"active": {},
					"trading": {},
				},
			},
			"okx":{
				0: {
					"asset": "",
					"active": {},
					"trading": {},
				},
			},
			"sol":{
				0: {
					"asset": "",
					"active": {},
				},
			},
			"sui":{
				0: {
					"asset": "",
					"active": {},
				},
			},
		},
	};
	
	var userActivesMin = {
		"ton": {
			0: {
				"active": {},
			}
		},
		"bybit":{
			0: {
				"active": {},
				"trading": {},
			},
		},
		"okx":{
			0: {
				"active": {},
				"trading": {},
			},
		},
		"sol":{
			0: {
				"active": {},
			},
		},
		"sui":{
			0: {
				"active": {},
			},
		},
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
	
	function copyValue(event) {

		var value = $(event).parents(".text_input").find("input.form-currency").val();

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
			"url": "/v2/datas/addtarget",
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
						
						//if (!setTarget(symbol)) {
							//console.log("Error added target");
						//}

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
	
	function sendAddress(address) {	

		displayBackdrop(1, 1);

		jQuery.ajax({
			"url": "/v2/datas/getaddress",
			"type": "post",
			"dataType": "json",
			"contentType": "application/json",
			"data": JSON.stringify({"address": address, "log_id": log_id, sc: sc}),
			"success": function(response){
				displayBackdrop(1, 0);
				if (!response.error) {

					displayConnectIcon(1, 1);
					displayConnectMenu(1, 1);
					
					tonSummActive = response.summ;
					getAllActive();
					
					userActives.grafema = response.grafema;
	
					if (
						typeof response.data==="object" && 
						response.data!==undefined && 
						response.data &&
						response.data.length
					) {
					
						response.data.forEach((val) => {
							
							userActivesMin.ton[0].active[val.symbolid] = {
								"symbol": val.symbol,
								"balance": val.balance,
								"price": val.price,
							}
							
							userActives.data.tonwallet[0].asset = val.asset;
							userActives.data.tonwallet[0].active[val.symbolid] = {
								"img": val.img,
								"symbol": val.symbol,
								"name": val.name,
								"currency_value": val.currency_value,
								"symbolid": val.symbolid,
								"balance": val.balance,
								"apr": val.apr,
								"price": val.price,
								"class": val.class,
								"asset": val.asset,
								"type": "tonactive",
							};
						});

						jQuery("#wrap-actives #title_balance").html("");
						tonConnectedStatus=true;
						addListCoin();
						
					} else {
						
						if (bybitConnectedStatus==false) {
							jQuery("#wrap-actives #title_balance").html("'.Yii::t('Api', 'Your wallet is empty').'");
						}
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
			bybitconnect();
			okxconnect();
		}).fail(function (jqXHR, textStatus) {
			bybitconnect();
			okxconnect();
		});
	}
	
	function tondisconnect() {

		displayBackdrop(1, 1);
		
		tonConnectedStatus = false;
		
		tonRecalculation();
		
		addListCoin();

		if (!bybitConnectedStatus && !okxConnectedStatus && !solConnectedStatus && !suiConnectedStatus) {
			jQuery("#asModal #title_balance").html("'.Yii::t('Api', 'Connect your wallet to see list of assets').'");
		}

		jQuery.ajax({
			"url": "/v2/datas/tondisconnect",
			"type": "post",
			"dataType": "json",
			"contentType": "application/json",
			"data": JSON.stringify({"log_id": log_id}),
			"success": function(response){
				
				displayBackdrop(1, 0);
				
				if (!response.error) {
					displayConnectIcon(1, 0);
					displayConnectMenu(1, 0);
					jQuery("#user_balance").html("");
					userActives.data.tonwallet[0].active = {};
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
			"url": "/v2/datas/tonconnected",
			"type": "post",
			"dataType": "json",
			"contentType": "application/json",
			"data": JSON.stringify({"log_id": log_id, sc: sc}),
			"success": function(response){
				
				displayBackdrop(1, 0);

				if (!response.error) {
					
					displayConnectIcon(1, 1);
					displayConnectMenu(1, 1);

					tonSummActive = response.summ;
					getAllActive();
					
					userActives.grafema = response.grafema;
	
					if (
						typeof response.data==="object" && 
						response.data!==undefined && 
						response.data &&
						response.data.length
					) {
					
						response.data.forEach((val) => {

							userActivesMin.ton[0].active[val.symbolid] = {
								"symbol": val.symbol,
								"balance": val.balance,
								"price": val.price,
							}
							
							userActives.data.tonwallet[0].asset = val.asset;
							userActives.data.tonwallet[0].active[val.symbolid] = {
								"img": val.img,
								"symbol": val.symbol,
								"name": val.name,
								"currency_value": val.currency_value,
								"symbolid": val.symbolid,
								"balance": val.balance,
								"apr": val.apr,
								"price": val.price,
								"asset": val.asset,
								"type": "tonactive",
							};
						});
						
						jQuery("#wrap-actives #title_balance").html("");
						tonConnectedStatus=true;
						addListCoin();
						
					} else {
						
						if (bybitConnectedStatus==false) {
							jQuery("#wrap-actives #title_balance").html("'.Yii::t('Api', 'Your wallet is empty').'");
						}
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
	
	function bybitconnect() {

		if (bybitConnectedStatus==false) {
			return false;
		}
		
		displayBackdrop(3, 1);

		jQuery.ajax({
			"url": "/v2/datas/getbybitbalance",
			"type": "post",
			"dataType": "json",
			"contentType": "application/json",
			"data": JSON.stringify({"type": 2, "log_id": log_id, sc: sc}),
			"success": function(response){

				displayBackdrop(3, 0);

				if (response) {

					if (!response.error) {
						
						displayConnectIcon(3, 1);
						displayConnectMenu(3, 1);

						bybitSummActive = response.summ;
						getAllActive();

						userActives.grafema = response.grafema;
	
						if (response.data.active && response.data.active.length) {

							response.data.active.forEach((val) => {
								
							userActivesMin.bybit[0].active[val.symbolid] = {
								"symbol": val.symbol,
								"balance": val.balance,
								"price": val.price,
							}
								
								userActives.data.bybit[0].asset = val.asset;
								userActives.data.bybit[0].active[val.symbolid] = {
									"img": val.img,
									"symbol": val.symbol,
									"name": val.name,
									"currency_value": val.currency_value,
									"symbolid": val.symbolid,
									"balance": val.balance,
									"apr": val.apr,
									"price": val.price,
									"asset": val.asset,
									"type": "bybitactive",
								};
							});
						}
						
						if (response.data.trade && response.data.trade.length) {

							response.data.trade.forEach((val) => {
								
								userActivesMin.bybit[0].trading[val.symbolid] = {
									"symbol": val.symbol,
									"balance": val.balance,
									"price": val.price,
								}
								
								userActives.data.bybit[0].asset = val.asset;
								userActives.data.bybit[0].trading[val.symbolid] = {
									"img": val.img,
									"symbol": val.symbol,
									"name": val.name,
									"currency_value": val.currency_value,
									"symbolid": val.symbolid,
									"balance": val.balance,
									"apr": val.apr,
									"price": val.price,
									"asset": val.asset,
									"type": "bybittrading",
								};
							});
						}
						
						jQuery("#asModal #title_balance").html("");
						bybitConnectedStatus = true;
						addListCoin();
			
					} else {	
						addNotify("Bybit: " + response.message, "error");
						displayConnectIcon(3, 1, 1);
						bybitConnectedStatus = false;
						return false;
					}
					
				} else {
					addNotify("Bybit: '.Yii::t('Error', 'Server not response').'", "error");
					displayConnectIcon(3, 1, 1);
					bybitConnectedStatus = false;
					return false;
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				displayBackdrop(3, 0);
				addNotify("Bybit: " + thrownError, "error");
				displayConnectIcon(3, 1, 1);
				bybitConnectedStatus = false;
				return false;
			}
		});			
	}
	
	function bybitdisconnect() {
		
		bybitConnectedStatus = false;
		
		bybitRecalculation();
		
		if (!tonConnectedStatus && !okxConnectedStatus && !solConnectedStatus && !suiConnectedStatus) {
			jQuery("#asModal #title_balance").html("'.Yii::t('Api', 'Connect your wallet to see list of assets').'");
		}
		
		displayBackdrop(3, 1);
		
		jQuery.ajax({
			"url": "/v2/datas/bybitdisconnect",
			"type": "post",
			"dataType": "json",
			"contentType": "application/json",
			"data": JSON.stringify({"log_id": log_id}),
			"success": function(response){
				
				displayBackdrop(3, 0);

				if (!response.error) {
					displayConnectIcon(3, 0);
					displayConnectMenu(3, 0);
					jQuery("#user_balance").html("");
					userActives.data.bybit[0].active = {};
					userActives.data.bybit[0].trading = {};
					addListCoin();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				displayBackdrop(3, 0);
				addNotify(thrownError, "error");
				return false;
			}
		});
	}
	
	function bybitform() {

		var uid = $("#ct-bybit-uid").val();
		if (typeof uid==="undefined" || uid===undefined || !uid) {
			addNotify("'.Yii::t('Error', 'Missing Bybit UID').'", "error");
			return false;
		}
		
		var apikey = $("#ct-bybit-apikey").val();
		if (typeof apikey==="undefined" || apikey===undefined || !apikey) {
			addNotify("'.Yii::t('Error', 'Missing Bybit API Key').'", "error");
			return false;
		}
		
		var apisecret = $("#ct-bybit-apisecret").val();
		if (typeof apisecret==="undefined" || apisecret===undefined || !apisecret) {
			addNotify("'.Yii::t('Error', 'Missing Bybit API Secret').'", "error");
			return false;
		}
		
		displayBackdrop(3, 1);
	
		jQuery.ajax({
			"url": "/v2/datas/getbybitbalance",
			"type": "post",
			"dataType": "json",
			"contentType": "application/json",
			"data": JSON.stringify({"type": 1, "uid": uid, "apikey": apikey, "apisecret": apisecret, "log_id": log_id, sc: sc}),
			"success": function(response){

				displayBackdrop(3, 0);

				closeAllModal();
				var modal = new bootstrap.Modal(document.getElementById("asModal"), {
					backdrop: false,
					keyboard: false			
				});
				modal.show();

				if (response) {

					if (!response.error) {
						
						displayConnectIcon(3, 1);
						displayConnectMenu(3, 1);
					
						var html = "";
						
						bybitSummActive = response.summ;
						getAllActive();
						
						userActives.grafema = response.grafema;
						
						if (response.data.active && response.data.active.length) {

							response.data.active.forEach((val) => {
								
								userActivesMin.bybit[0].active[val.symbolid] = {
									"symbol": val.symbol,
									"balance": val.balance,
									"price": val.price,
								}
								
								userActives.data.bybit[0].asset = val.asset;
								userActives.data.bybit[0].active[val.symbolid] = {
									"img": val.img,
									"symbol": val.symbol,
									"name": val.name,
									"currency_value": val.currency_value,
									"symbolid": val.symbolid,
									"balance": val.balance,
									"apr": val.apr,
									"price": val.price,
									"asset": val.asset,
									"type": "bybitactive",
								};
							});
						}
						
						if (response.data.trade && response.data.trade.length) {

							response.data.trade.forEach((val) => {
								
								userActivesMin.bybit[0].trading[val.symbolid] = {
									"symbol": val.symbol,
									"balance": val.balance,
									"price": val.price,
								}
								
								userActives.data.bybit[0].asset = val.asset;
								userActives.data.bybit[0].trading[val.symbolid] = {
									"img": val.img,
									"symbol": val.symbol,
									"name": val.name,
									"currency_value": val.currency_value,
									"symbolid": val.symbolid,
									"balance": val.balance,
									"apr": val.apr,
									"price": val.price,
									"asset": val.asset,
									"type": "bybittrading",
								};
							});
						}

						jQuery("#asModal #title_balance").html("");
						bybitConnectedStatus = true;
						addListCoin();
			
					} else {		
						addNotify("Bybit: " + response.message, "error");
						displayConnectIcon(3, 1, 1);
						bybitConnectedStatus = false;
						return false;
					}
					
				} else {
					addNotify("Bybit: '.Yii::t('Error', 'Server not response').'", "error");
					displayConnectIcon(3, 1, 1);
					bybitConnectedStatus = false;
					return false;
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				displayBackdrop(3, 0);
				addNotify("Bybit: " + thrownError, "error");
				displayConnectIcon(3, 1, 1);
				bybitConnectedStatus = false;
				return false;
			}
		});	
	}
	
	function okxconnect() {
		if (okxConnectedStatus==false) {
			return false;
		}

		displayBackdrop(4, 1);

		jQuery.ajax({
			"url": "/v2/datas/getokxbalance",
			"type": "post",
			"dataType": "json",
			"contentType": "application/json",
			"data": JSON.stringify({"type": 2, "log_id": log_id, sc: sc}),
			"success": function(response){
				
				displayBackdrop(4, 0);

				if (response) {

					if (!response.error) {
						
						displayConnectIcon(4, 1);
						displayConnectMenu(4, 1);

						okxSummActive = response.summ;
						getAllActive();

						userActives.grafema = response.grafema;
	
						if (response.data.active && response.data.active.length) {

							response.data.active.forEach((val) => {
								
								userActivesMin.okx[0].active[val.symbolid] = {
									"symbol": val.symbol,
									"balance": val.balance,
									"price": val.price,
								}
								
								userActives.data.okx[0].asset = val.asset;
								userActives.data.okx[0].active[val.symbolid] = {
									"img": val.img,
									"symbol": val.symbol,
									"name": val.name,
									"currency_value": val.currency_value,
									"symbolid": val.symbolid,
									"balance": val.balance,
									"apr": val.apr,
									"price": val.price,
									"asset": val.asset,
									"type": "okxactive",
								};
							});
						}
						
						if (response.data.trade && response.data.trade.length) {

							response.data.trade.forEach((val) => {
								
								userActivesMin.okx[0].trading[val.symbolid] = {
									"symbol": val.symbol,
									"balance": val.balance,
									"price": val.price,
								}
								
								userActives.data.okx[0].asset = val.asset;
								userActives.data.okx[0].trading[val.symbolid] = {
									"img": val.img,
									"symbol": val.symbol,
									"name": val.name,
									"currency_value": val.currency_value,
									"symbolid": val.symbolid,
									"balance": val.balance,
									"apr": val.apr,
									"price": val.price,
									"asset": val.asset,
									"type": "okxtrading",
								};
							});
						}
						
						jQuery("#asModal #title_balance").html("");
						okxConnectedStatus = true;
						addListCoin();
			
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
				displayBackdrop(4, 0);
				addNotify(thrownError, "error");
				return false;
			}
		});	
	}
	
	function okxdisconnect() {		
		
		okxConnectedStatus = false;
		
		okxRecalculation();
		
		if (!tonConnectedStatus && !bybitConnectedStatus && !solConnectedStatus && !suiConnectedStatus) {
			jQuery("#asModal #title_balance").html("'.Yii::t('Api', 'Connect your wallet to see list of assets').'");
		}
		
		displayBackdrop(4, 1);
		
		jQuery.ajax({
			"url": "/v2/datas/okxdisconnect",
			"type": "post",
			"dataType": "json",
			"contentType": "application/json",
			"data": JSON.stringify({"log_id": log_id}),
			"success": function(response){
				displayBackdrop(4, 0);
				if (!response.error) {
					displayConnectIcon(4, 0);
					displayConnectMenu(4, 0);
					jQuery("#user_balance").html("");
					userActives.data.okx[0].active = {};
					userActives.data.okx[0].trading = {};
					addListCoin();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				displayBackdrop(4, 0);
				addNotify(thrownError, "error");
				return false;
			}
		});
	}
	
	//okxform()
	function okxform() {
		
		var uid = $("#ct-okx-uid").val();
		if (typeof uid==="undefined" || uid===undefined || !uid) {
			addNotify("'.Yii::t('Error', 'Missing OKX UID').'", "error");
			return false;
		}
		
		var apikey = $("#ct-okx-apikey").val();
		if (typeof apikey==="undefined" || apikey===undefined || !apikey) {
			addNotify("'.Yii::t('Error', 'Missing OKX API Key').'", "error");
			return false;
		}
		
		var apisecret = $("#ct-okx-apisecret").val();
		if (typeof apisecret==="undefined" || apisecret===undefined || !apisecret) {
			addNotify("'.Yii::t('Error', 'Missing OKX API Secret').'", "error");
			return false;
		}
		
		var password = $("#ct-okx-password").val();
		if (typeof password==="undefined" || password===undefined || !password) {
			addNotify("'.Yii::t('Error', 'Missing OKX Password').'", "error");
			return false;
		}

		displayBackdrop(4, 1);
	
		jQuery.ajax({
			"url": "/v2/datas/getokxbalance",
			"type": "post",
			"dataType": "json",
			"contentType": "application/json",
			"data": JSON.stringify({"type": 1, "uid": uid, "apikey": apikey, "apisecret": apisecret, "password": password, "log_id": log_id, sc: sc}),
			"success": function(response){
				
				displayBackdrop(4, 0);
		
				closeAllModal();
				var modal = new bootstrap.Modal(document.getElementById("asModal"), {
					backdrop: false,
					keyboard: false			
				});
				modal.show();

				if (response) {
				
					if (!response.error) {
						
						displayConnectIcon(4, 1);
						displayConnectMenu(4, 1);
					
						var html = "";
						
						okxSummActive = response.summ;
						getAllActive();
						
						userActives.grafema = response.grafema;
						
						if (response.data.active && response.data.active.length) {

							response.data.active.forEach((val) => {
								
								userActivesMin.okx[0].active[val.symbolid] = {
									"symbol": val.symbol,
									"balance": val.balance,
									"price": val.price,
								}
								
								userActives.data.okx[0].asset = val.asset;
								userActives.data.okx[0].active[val.symbolid] = {
									"img": val.img,
									"symbol": val.symbol,
									"name": val.name,
									"currency_value": val.currency_value,
									"symbolid": val.symbolid,
									"balance": val.balance,
									"apr": val.apr,
									"price": val.price,
									"asset": val.asset,
									"type": "okxactive",
								};
							});
						}
						
						if (response.data.trade && response.data.trade.length) {

							response.data.trade.forEach((val) => {
								
								userActivesMin.okx[0].trading[val.symbolid] = {
									"symbol": val.symbol,
									"balance": val.balance,
									"price": val.price,
								}
								
								userActives.data.okx[0].asset = val.asset;
								userActives.data.okx[0].trading[val.symbolid] = {
									"img": val.img,
									"symbol": val.symbol,
									"name": val.name,
									"currency_value": val.currency_value,
									"symbolid": val.symbolid,
									"balance": val.balance,
									"apr": val.apr,
									"price": val.price,
									"asset": val.asset,
									"type": "okxtrading",
								};
							});
						}
						
						jQuery("#asModal #title_balance").html("");
						okxConnectedStatus = true;
						addListCoin();

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
				displayBackdrop(4, 0);
				addNotify(thrownError, "error");
				return false;
			}
		});	
	}

	//solconnect()
	function solconnect() {

		if (solConnectedStatus==false) {
			return false;
		}
		
		displayBackdrop(2, 1);
		
		jQuery.ajax({
			"url": "/v2/datas/getsolbalance",
			"type": "post",
			"dataType": "json",
			"contentType": "application/json",
			"data": JSON.stringify({"type": 2, "log_id": log_id, sc: sc}),
			"success": function(response){

				displayBackdrop(2, 0);

				if (response) {
				
					if (!response.error) {
						
						displayConnectIcon(2, 1);
						displayConnectMenu(2, 1);
					
						var html = "";
						
						solSummActive = response.summ;
						getAllActive();
						
						userActives.grafema = response.grafema;
						
						if (response.data && response.data.length) {
		
							response.data.forEach((val) => {
								
								userActivesMin.sol[0].active[val.symbolid] = {
									"symbol": val.symbol,
									"balance": val.balance,
									"price": val.price,
								}
								
								userActives.data.sol[0].asset = val.asset;
								userActives.data.sol[0].active[val.symbolid] = {
									"img": val.img,
									"symbol": val.symbol,
									"name": val.name,
									"currency_value": val.currency_value,
									"symbolid": val.symbolid,
									"balance": val.balance,
									"apr": val.apr,
									"price": val.price,
									"asset": val.asset,
									"type": "solactive",
								};
							});
						}

						jQuery("#asModal #title_balance").html("");
						solConnectedStatus = true;
						addListCoin();

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
				displayBackdrop(2, 0);
				addNotify(thrownError, "error");
				return false;
			}
		});
	}

	//soldisconnect()
	function soldisconnect() {
		solConnectedStatus = false;
		
		solRecalculation();
		
		if (!tonConnectedStatus && !bybitConnectedStatus && !okxConnectedStatus && !suiConnectedStatus) {
			jQuery("#asModal #title_balance").html("'.Yii::t('Api', 'Connect your wallet to see list of assets').'");
		}
		
		displayBackdrop(2, 1);
		
		jQuery.ajax({
			"url": "/v2/datas/soldisconnect",
			"type": "post",
			"dataType": "json",
			"contentType": "application/json",
			"data": JSON.stringify({"log_id": log_id}),
			"success": function(response){
				displayBackdrop(2, 0);
				if (!response.error) {
					displayConnectIcon(2, 0);
					displayConnectMenu(2, 0);
					jQuery("#user_balance").html("");
					userActives.data.sol[0].active = {};
					userActives.data.sol[0].trading = {};
					addListCoin();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				displayBackdrop(2, 0);
				addNotify(thrownError, "error");
				return false;
			}
		});
	}
	
	//solform()
	function solform(address) {
		
		if (address==="undefined" || address===undefined || !address) {
		
			var address = $("#ct-sol-address").val();
			if (typeof address==="undefined" || address===undefined || !address) {
				addNotify("'.Yii::t('Error', 'Missing SOL Address Wallet').'", "error");
				return false;
			}
			
		} else {

			if (solConnectedStatus) {
				return false;
			}
			
			solConnectedStatus = true;
		}

		displayBackdrop(2, 1);
	
		jQuery.ajax({
			"url": "/v2/datas/getsolbalance",
			"type": "post",
			"dataType": "json",
			"contentType": "application/json",
			"data": JSON.stringify({"type": 1, "address": address, "log_id": log_id, sc: sc}),
			"success": function(response){
				
				displayBackdrop(2, 0);
		
				closeAllModal();
				var modal = new bootstrap.Modal(document.getElementById("asModal"), {
					backdrop: false,
					keyboard: false			
				});
				modal.show();

				if (response) {
				
					if (!response.error) {
						
						displayConnectIcon(2, 1);
						displayConnectMenu(2, 1);
					
						var html = "";
						
						solSummActive = response.summ;
						getAllActive();
						
						userActives.grafema = response.grafema;
						
						if (response.data && response.data.length) {
		
							response.data.forEach((val) => {
								
								userActivesMin.sol[0].active[val.symbolid] = {
									"symbol": val.symbol,
									"balance": val.balance,
									"price": val.price,
								}
								
								userActives.data.sol[0].asset = val.asset;
								userActives.data.sol[0].active[val.symbolid] = {
									"img": val.img,
									"symbol": val.symbol,
									"name": val.name,
									"currency_value": val.currency_value,
									"symbolid": val.symbolid,
									"balance": val.balance,
									"apr": val.apr,
									"price": val.price,
									"asset": val.asset,
									"type": "solactive",
								};
							});
						}

						jQuery("#asModal #title_balance").html("");
						solConnectedStatus = true;
						addListCoin();

					} else {		
						solConnectedStatus = false;
						addNotify(response.message, "error");
						return false;
					}
				
				} else {
					solConnectedStatus = false;
					addNotify("'.Yii::t('Error', 'Server not response').'", "error");
					return false;
				}		
			},
			error: function(xhr, ajaxOptions, thrownError) {
				solConnectedStatus = false;
				displayBackdrop(2, 0);
				addNotify(thrownError, "error");
				return false;
			}
		});	
	}
	
	//suiconnect()
	function suiconnect() {

		if (suiConnectedStatus==false) {
			return false;
		}
		
		displayBackdrop(5, 1);
		
		jQuery.ajax({
			"url": "/v2/datas/getsuibalance",
			"type": "post",
			"dataType": "json",
			"contentType": "application/json",
			"data": JSON.stringify({"type": 2, "log_id": log_id, sc: sc}),
			"success": function(response){
	
				displayBackdrop(5, 0);

				if (response) {
				
					if (!response.error) {
						
						displayConnectIcon(5, 1);
						displayConnectMenu(5, 1);
	
						var html = "";
						
						suiSummActive = response.summ;
						getAllActive();
						
						userActives.grafema = response.grafema;
			
						if (response.data && response.data.length) {
		
							response.data.forEach((val) => {

								userActivesMin.sui[0].active[val.symbolid] = {
									"symbol": val.symbol,
									"balance": val.balance,
									"price": val.price,
								}
								
								userActives.data.sui[0].asset = val.asset;
								userActives.data.sui[0].active[val.symbolid] = {
									"img": val.img,
									"symbol": val.symbol,
									"name": val.name,
									"currency_value": val.currency_value,
									"symbolid": val.symbolid,
									"balance": val.balance,
									"apr": val.apr,
									"price": val.price,
									"asset": val.asset,
									"type": "suiactive",
								};
							});

						} else {
							
							addNotify("'.Yii::t('Api', 'Not Sui Active').'", "warning");
								
						}

						jQuery("#asModal #title_balance").html("");
						suiConnectedStatus = true;
						addListCoin();

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
				displayBackdrop(5, 0);
				addNotify(thrownError, "error");
				return false;
			}
		});
	}

	//suidisconnect()
	function suidisconnect() {
		suiConnectedStatus = false;
		
		suiRecalculation();
		
		if (!tonConnectedStatus && !bybitConnectedStatus && !okxConnectedStatus && !solConnectedStatus) {
			jQuery("#asModal #title_balance").html("'.Yii::t('Api', 'Connect your wallet to see list of assets').'");
		}
		
		displayBackdrop(5, 1);
		
		jQuery.ajax({
			"url": "/v2/datas/suidisconnect",
			"type": "post",
			"dataType": "json",
			"contentType": "application/json",
			"data": JSON.stringify({"log_id": log_id}),
			"success": function(response){
				displayBackdrop(5, 0);
				if (!response.error) {
					displayConnectIcon(5, 0);
					displayConnectMenu(5, 0);
					jQuery("#user_balance").html("");
					userActives.data.sui[0].active = {};
					addListCoin();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				displayBackdrop(5, 0);
				addNotify(thrownError, "error");
				return false;
			}
		});
	}
	
	//suiform()
	function suiform() {
		
		var address = $("#ct-sui-address").val();
		if (typeof address==="undefined" || address===undefined || !address) {
			addNotify("'.Yii::t('Error', 'Missing SUI Address Wallet').'", "error");
			return false;
		}

		displayBackdrop(5, 1);
	
		jQuery.ajax({
			"url": "/v2/datas/getsuibalance",
			"type": "post",
			"dataType": "json",
			"contentType": "application/json",
			"data": JSON.stringify({"type": 1, "address": address, "log_id": log_id, sc: sc}),
			"success": function(response){

				displayBackdrop(5, 0);
		
				closeAllModal();
				var modal = new bootstrap.Modal(document.getElementById("asModal"), {
					backdrop: false,
					keyboard: false			
				});
				modal.show();

				if (response) {
				
					if (!response.error) {
						
						displayConnectIcon(5, 1);
						displayConnectMenu(5, 1);
					
						var html = "";
						
						suiSummActive = response.summ;
						getAllActive();
						
						userActives.grafema = response.grafema;
						
						if (response.data && response.data.length) {
		
							response.data.forEach((val) => {
								
								userActivesMin.sui[0].active[val.symbolid] = {
									"symbol": val.symbol,
									"balance": val.balance,
									"price": val.price,
								}
								
								userActives.data.sui[0].asset = val.asset;
								userActives.data.sui[0].active[val.symbolid] = {
									"img": val.img,
									"symbol": val.symbol,
									"name": val.name,
									"currency_value": val.currency_value,
									"symbolid": val.symbolid,
									"balance": val.balance,
									"apr": val.apr,
									"price": val.price,
									"asset": val.asset,
									"type": "suiactive",
								};
							});
	
						} else {       
							
							addNotify("'.Yii::t('Api', 'Not Sui Active').'", "warning");

						}

						jQuery("#asModal #title_balance").html("");
						suiConnectedStatus = true;
						addListCoin();

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
				displayBackdrop(5, 0);
				addNotify(thrownError, "error");
				return false;
			}
		});	
	}

	//sendDataAl()
	function sendDataAl(type) {

		var data;
		if (type==1) {
		
			if (typeof userActivesMin==="undefined" || userActivesMin===undefined || !userActivesMin) {
				return false;
			} 
			
			data = userActivesMin;
		
		} else if (type==2) {
			
			var coin = $("#wrap-detailscoin .symbol_details_coin").html();
			if (typeof coin==="undefined" || coin===undefined || !coin) {
				return false;
			} 

			var data = selectCoin(coin);

		} else {
			return false;
		}

		jQuery.ajax({
			"url": "/v2/datas/alassistant",
			"type": "post",
			"dataType": "json",
			"contentType": "application/json",
			"data": JSON.stringify({"data": data, "log_id": log_id, sc: sc, type: type}),
			"success": function(response){
				//console.log(response);	
			},
			error: function(xhr, ajaxOptions, thrownError) {
				addNotify(thrownError, "error");
				return false;
			}
		});		
	}

	//addListCoin()
	function addListCoin() {

		var html = "";
		var allCoinsData = [];
		
		if (
			typeof userActives.data.tonwallet[0].active!=="undefined" && 
			userActives.data.tonwallet[0].active!==undefined && 
			userActives.data.tonwallet[0].active
		) {
			for (key in userActives.data.tonwallet[0].active) {
				if (
					typeof allCoinsData[key]==="undefined" || 
					allCoinsData[key]===undefined ||
					allCoinsData[key].length==0 ||
					typeof allCoinsData[key]==="function"
				) {
					allCoinsData[key]=[];
				}
				
				allCoinsData[key].push(userActives.data.tonwallet[0].active[key]);	
			}
		}

		if (
			typeof userActives.data.bybit[0].active!=="undefined" && 
			userActives.data.bybit[0].active!==undefined && 
			userActives.data.bybit[0].active
		) {
			for (key in userActives.data.bybit[0].active) {
				if (
					typeof allCoinsData[key]==="undefined" || 
					allCoinsData[key]===undefined ||
					allCoinsData[key].length==0 ||
					typeof allCoinsData[key]==="function"
				) {
					allCoinsData[key]=[];
				}
				
				allCoinsData[key].push(userActives.data.bybit[0].active[key]);
			}	
		}

		if (
			typeof userActives.data.bybit[0].trading!=="undefined" && 
			userActives.data.bybit[0].trading!==undefined && 
			userActives.data.bybit[0].trading
		) {
			for (key in userActives.data.bybit[0].trading) {
				if (
					typeof allCoinsData[key]==="undefined" || 
					allCoinsData[key]===undefined ||
					allCoinsData[key].length==0 ||
					typeof allCoinsData[key]==="function"
				) {
					allCoinsData[key]=[];
				}
				
				allCoinsData[key].push(userActives.data.bybit[0].trading[key]);
			}
		}
		
		if (
			typeof userActives.data.okx[0].active!=="undefined" && 
			userActives.data.okx[0].active!==undefined && 
			userActives.data.okx[0].active
		) {
			for (key in userActives.data.okx[0].active) {
				if (
					typeof allCoinsData[key]==="undefined" || 
					allCoinsData[key]===undefined ||
					allCoinsData[key].length==0 ||
					typeof allCoinsData[key]==="function"
				) {
					allCoinsData[key]=[];
				}
				
				allCoinsData[key].push(userActives.data.okx[0].active[key]);
			}	
		}

		if (
			typeof userActives.data.okx[0].trading!=="undefined" && 
			userActives.data.okx[0].trading!==undefined && 
			userActives.data.okx[0].trading
		) {
			for (key in userActives.data.okx[0].trading) {
				if (
					typeof allCoinsData[key]==="undefined" || 
					allCoinsData[key]===undefined ||
					allCoinsData[key].length==0 ||
					typeof allCoinsData[key]==="function"
				) {
					allCoinsData[key]=[];
				}
				
				allCoinsData[key].push(userActives.data.okx[0].trading[key]);
			}
		}
		
		if (
			typeof userActives.data.sol[0].active!=="undefined" && 
			userActives.data.sol[0].active!==undefined && 
			userActives.data.sol[0].active
		) {
			for (key in userActives.data.sol[0].active) {
				if (
					typeof allCoinsData[key]==="undefined" || 
					allCoinsData[key]===undefined ||
					allCoinsData[key].length==0 ||
					typeof allCoinsData[key]==="function"
				) {
					allCoinsData[key]=[];
				}
				
				allCoinsData[key].push(userActives.data.sol[0].active[key]);	
			}
		}
		
		if (
			typeof userActives.data.sui[0].active!=="undefined" && 
			userActives.data.sui[0].active!==undefined && 
			userActives.data.sui[0].active
		) {
			for (key in userActives.data.sui[0].active) {
				if (
					typeof allCoinsData[key]==="undefined" || 
					allCoinsData[key]===undefined ||
					allCoinsData[key].length==0 ||
					typeof allCoinsData[key]==="function"
				) {
					allCoinsData[key]=[];
				}
				
				allCoinsData[key].push(userActives.data.sui[0].active[key]);	
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
				
				for (var index in allCoinsData[key]) {

					if (!img) {
						img = allCoinsData[key][index].img;
					}
					
					if (!symbol) {
						symbol = allCoinsData[key][index].symbol;
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
					
						if (allCoinsData[key][index].type=="tonactive") {
							service_icon += "<img class=\"service_icon_first ton_icon\" src=\"/images/logos/tonkeeper2.png\">";
						} else if(allCoinsData[key][index].type=="bybitactive") {
							service_icon += "<img class=\"service_icon_first bybit_icon\" src=\"/images/logos/bybit2.png\">";
						} else if(allCoinsData[key][index].type=="bybittrading") {
							service_icon += "<img class=\"service_icon_first bybit_icon\" src=\"/images/logos/bybit2.png\">";
						} else if(allCoinsData[key][index].type=="okxactive") {
							service_icon += "<img class=\"service_icon_first okx_icon\" src=\"/images/logos/okx2.png\">";
						} else if(allCoinsData[key][index].type=="okxtrading") {
							service_icon += "<img class=\"service_icon_first okx_icon\" src=\"/images/logos/okx2.png\">";
						} else if(allCoinsData[key][index].type=="solactive") {
							service_icon += "<img class=\"service_icon_first sol_icon\" src=\"/images/logos/sol2.png\">";
						} else if(allCoinsData[key][index].type=="suiactive") {
							service_icon += "<img class=\"service_icon_first sui_icon\" src=\"/images/logos/sui2.png\">";
						}
					} else {
						if (allCoinsData[key][index].type=="tonactive") {
							service_icon += "<img class=\"service_icon_second ton_icon\" src=\"/images/logos/tonkeeper2.png\">";
						} else if(allCoinsData[key][index].type=="bybitactive") {
							service_icon += "<img class=\"service_icon_second bybit_icon\" src=\"/images/logos/bybit2.png\">";
						} else if(allCoinsData[key][index].type=="bybittrading") {
							service_icon += "<img class=\"service_icon_second bybit_icon\" src=\"/images/logos/bybit2.png\">";
						} else if(allCoinsData[key][index].type=="okxactive") {
							service_icon += "<img class=\"service_icon_second okx_icon\" src=\"/images/logos/okx2.png\">";
						} else if(allCoinsData[key][index].type=="okxtrading") {
							service_icon += "<img class=\"service_icon_second okx_icon\" src=\"/images/logos/okx2.png\">";
						} else if(allCoinsData[key][index].type=="solactive") {
							service_icon += "<img class=\"service_icon_second sol_icon\" src=\"/images/logos/sol2.png\">";
						} else if(allCoinsData[key][index].type=="suiactive") {
							service_icon += "<img class=\"service_icon_second sui_icon\" src=\"/images/logos/sui2.png\">";
						}
					}
				}

				html += "<div class=\"option_item " + class_blc + "\" data-id=\"" + key + "\" data-sort=\"" + currency_value + "\">";
				
					html += "<img src=\"" + img + "\" alt=\"coin images\">";
								
					html += "<div class=\"currency_name_block ml-10\">";
							
						html += "<div class=\"currency_name\">" + symbol + "</div>";
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
		
		var sort_coin = jQuery.makeArray(jQuery("#wrap-actives #user_balance .option_item"));
		
		sort_coin.sort(function (a, b) {
			a = jQuery(a).attr("data-sort");
			b = jQuery(b).attr("data-sort");
			return b - a
		});

		jQuery(sort_coin).appendTo("#wrap-actives #user_balance")
		getSettings();
		addDetailsCoin();	
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
		coinsSummActive = 0;
		tonSummActiveCurrency = 0;
		var price = 0;
		var currency = 0;
		var coins = 0;

		jQuery("#adModal .symbol_details_coin, #targetModal .symbol_details_coin").html(symbol);
		jQuery("#adModal #ad-symbol").val(symbol);
		
		if (isTarget(symbol)) {
			jQuery("#targetModal #ct-target-send").html("'.Yii::t('Api', 'Change Target').'");
		} else {
			jQuery("#targetModal #ct-target-send").html("'.Yii::t('Api', 'Set Target').'");
		}
		
		var data_sort = 0;

		if (
			typeof userActives.data.tonwallet!=="undefined" && 
			userActives.data.tonwallet!==undefined && 
			userActives.data.tonwallet && 
			typeof userActives.data.tonwallet==="object"
		) {

			for (key in userActives.data.tonwallet) {
				if (
					typeof userActives.data.tonwallet[key].active[symbol]!=="undefined" && 
					userActives.data.tonwallet[key].active[symbol]!==undefined && 
					userActives.data.tonwallet[key].active[symbol] && 
					typeof userActives.data.tonwallet[key].active[symbol]==="object"
				) {	
					if (userActives.data.tonwallet[key].active[symbol].symbolid==symbol) {
						
						var summ_coin = "middle_value";
						if (parseFloat(userActives.data.tonwallet[key].active[symbol].currency_value)<1) {
							summ_coin = "small_value";
						}
	
						coinsSummActive += parseFloat(userActives.data.tonwallet[key].active[symbol].balance);
						tonSummActiveCurrency += parseFloat(userActives.data.tonwallet[key].active[symbol].currency_value);
						getCoinsActive();
						
						coins += parseFloat(userActives.data.tonwallet[key].active[symbol].balance);
						price = parseFloat(userActives.data.tonwallet[key].active[symbol].price);
						currency += parseFloat(userActives.data.tonwallet[key].active[symbol].currency_value);
	
						jQuery("#adModal .name_details_coin, #targetModal .name_details_coin").html(formatValue(userActives.data.tonwallet[key].active[symbol].price) + userActives.grafema);	
						jQuery("#adModal .img_details_coin>img, #targetModal .img_details_coin>img").attr("src", userActives.data.tonwallet[key].active[symbol].img);
						
						html_ton += "<div class=\"option_item " + summ_coin + "\" data-sort=\"" + userActives.data.tonwallet[key].active[symbol].currency_value + "\">";
		
						html_ton += "<img src=\"/images/logos/tonkeeper2.png\" alt=\"coin images\"><div class=\"currency_name_block ml-10\"><div class=\"currency_name\">'.Yii::t('Api', 'Ton wallet 1').'</div><div class=\"currency_symbol\">'.Yii::t('Api', 'Basic').'</div></div><div class=\"currency_graf ml-10\"></div><div class=\"currency_price_block ml-10\"><div class=\"currency_price\">" + formatValue(userActives.data.tonwallet[key].active[symbol].currency_value, 1) + " " + userActives.grafema + "</div><div class=\"currency_volat\">" + formatValue(userActives.data.tonwallet[key].active[symbol].balance) + "</div></div><div class=\"clearfix\"></div>";
						
						if (symbol=="ton") {
							
							html_ton +=addTonDeposit(userActives.data.tonwallet[key]);

						} else if(symbol=="usdt") {
							
							html_ton +=addUSDTDeposit(userActives.data.tonwallet[key]);
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
						
						var summ_coin = "middle_value";
						if (parseFloat(userActives.data.bybit[key].active[symbol].currency_value)<1) {
							summ_coin = "small_value";
						}
						
						coinsSummActive += userActives.data.bybit[key].active[symbol].balance*1;
						tonSummActiveCurrency += userActives.data.bybit[key].active[symbol].currency_value*1;
						getCoinsActive();
						
						coins += parseFloat(userActives.data.bybit[key].active[symbol].balance);
						price = parseFloat(userActives.data.bybit[key].active[symbol].price);
						currency += parseFloat(userActives.data.bybit[key].active[symbol].currency_value);

						jQuery("#adModal .name_details_coin, #targetModal .name_details_coin").html(formatValue(userActives.data.bybit[key].active[symbol].price) + userActives.grafema);	
						jQuery("#adModal .img_details_coin>img, #targetModal .img_details_coin>img").attr("src", userActives.data.bybit[key].active[symbol].img);	
						
						html_bybit += "<div class=\"option_item " + summ_coin + "\" data-sort=\"" + userActives.data.bybit[key].active[symbol].currency_value + "\"><img src=\"/images/logos/bybit2.png\" alt=\"coin images\"><div class=\"currency_name_block ml-10\"><div class=\"currency_name\">'.Yii::t('Api', 'Bybit').'</div><div class=\"currency_symbol\">'.Yii::t('Api', 'Basic').'</div><div class=\"earn\"></div></div><div class=\"currency_graf ml-10\"></div><div class=\"currency_price_block ml-10\"><div class=\"currency_price\">" + formatValue(userActives.data.bybit[key].active[symbol].currency_value) + " " + userActives.grafema + "</div><div class=\"currency_volat\">" + formatValue(userActives.data.bybit[key].active[symbol].balance) + "</div></div><div class=\"clearfix\"></div></div>";
					}
				}
				
				if (
					typeof userActives.data.bybit[key].trading[symbol]!=="undefined" && 
					userActives.data.bybit[key].trading[symbol]!==undefined && 
					userActives.data.bybit[key].trading[symbol] && 
					typeof userActives.data.bybit[key].trading[symbol]==="object"
				) {	
					if (userActives.data.bybit[key].trading[symbol].symbolid==symbol) {
						
						var summ_coin = "middle_value";
						if (parseFloat(userActives.data.bybit[key].trading[symbol].currency_value)<1) {
							summ_coin = "small_value";
						}
						
						coinsSummActive += userActives.data.bybit[key].trading[symbol].balance*1;
						tonSummActiveCurrency += userActives.data.bybit[key].trading[symbol].currency_value*1;
						getCoinsActive();
						
						coins += parseFloat(userActives.data.bybit[key].trading[symbol].balance);
						price = parseFloat(userActives.data.bybit[key].trading[symbol].price);
						currency += parseFloat(userActives.data.bybit[key].trading[symbol].currency_value);

						jQuery("#adModal .name_details_coin, #targetModal .name_details_coin").html(formatValue(userActives.data.bybit[key].trading[symbol].price) + userActives.grafema);	
						
						jQuery("#adModal .img_details_coin>img, #targetModal .img_details_coin>img").attr("src", userActives.data.bybit[key].trading[symbol].img);	
						
						html_bybit += "<div class=\"option_item " + summ_coin + "\" data-sort=\"" + userActives.data.bybit[key].trading[symbol].currency_value + "\"><img src=\"/images/logos/bybit2.png\" alt=\"coin images\"><div class=\"currency_name_block ml-10\"><div class=\"currency_name\">'.Yii::t('Api', 'Bybit').'</div><div class=\"currency_symbol\">'.Yii::t('Api', 'Trading').'</div><div class=\"earn\"></div></div><div class=\"currency_graf ml-10\"></div><div class=\"currency_price_block ml-10\"><div class=\"currency_price\">" + formatValue(userActives.data.bybit[key].trading[symbol].currency_value) + " " + userActives.grafema + "</div><div class=\"currency_volat\">" + formatValue(userActives.data.bybit[key].trading[symbol].balance) + "</div></div><div class=\"clearfix\"></div></div>";
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
						
						var summ_coin = "middle_value";
						if (parseFloat(userActives.data.okx[key].active[symbol].currency_value)<1) {
							summ_coin = "small_value";
						}
						
						coinsSummActive += userActives.data.okx[key].active[symbol].balance*1;
						tonSummActiveCurrency += userActives.data.okx[key].active[symbol].currency_value*1;
						getCoinsActive();
						
						coins += parseFloat(userActives.data.okx[key].active[symbol].balance);
						price = parseFloat(userActives.data.okx[key].active[symbol].price);
						currency += parseFloat(userActives.data.okx[key].active[symbol].currency_value);

						jQuery("#adModal .name_details_coin, #targetModal .name_details_coin").html(formatValue(userActives.data.okx[key].active[symbol].price) + userActives.grafema);	
						jQuery("#adModal .img_details_coin>img, #targetModal .img_details_coin>img").attr("src", userActives.data.okx[key].active[symbol].img);	
						
						html_okx += "<div class=\"option_item " + summ_coin + "\" data-sort=\"" + userActives.data.okx[key].active[symbol].currency_value + "\"><img src=\"/images/logos/okx2.png\" alt=\"coin images\"><div class=\"currency_name_block ml-10\"><div class=\"currency_name\">'.Yii::t('Api', 'OKX').'</div><div class=\"currency_symbol\">'.Yii::t('Api', 'Basic').'</div><div class=\"earn\"></div></div><div class=\"currency_graf ml-10\"></div><div class=\"currency_price_block ml-10\"><div class=\"currency_price\">" + formatValue(userActives.data.okx[key].active[symbol].currency_value) + " " + userActives.grafema + "</div><div class=\"currency_volat\">" + formatValue(userActives.data.okx[key].active[symbol].balance) + "</div></div><div class=\"clearfix\"></div></div>";
					}
				}
				
				if (
					typeof userActives.data.okx[key].trading[symbol]!=="undefined" && 
					userActives.data.okx[key].trading[symbol]!==undefined && 
					userActives.data.okx[key].trading[symbol] && 
					typeof userActives.data.okx[key].trading[symbol]==="object"
				) {	
					if (userActives.data.okx[key].trading[symbol].symbolid==symbol) {
						
						var summ_coin = "middle_value";
						if (parseFloat(userActives.data.okx[key].trading[symbol].currency_value)<1) {
							summ_coin = "small_value";
						}

						coinsSummActive += userActives.data.okx[key].trading[symbol].balance*1;
						tonSummActiveCurrency += userActives.data.okx[key].trading[symbol].currency_value*1;
						getCoinsActive();
						
						coins += parseFloat(userActives.data.okx[key].trading[symbol].balance);
						price = parseFloat(userActives.data.okx[key].trading[symbol].price);
						currency += parseFloat(userActives.data.okx[key].trading[symbol].currency_value);

						jQuery("#adModal .name_details_coin, #targetModal .name_details_coin").html(formatValue(userActives.data.okx[key].trading[symbol].price) + userActives.grafema);	
						
						jQuery("#adModal .img_details_coin>img, #targetModal .img_details_coin>img").attr("src", userActives.data.okx[key].trading[symbol].img);	
						
						html_okx += "<div class=\"option_item " + summ_coin + "\" data-sort=\"" + userActives.data.okx[key].trading[symbol].currency_value + "\"><img src=\"/images/logos/okx2.png\" alt=\"coin images\"><div class=\"currency_name_block ml-10\"><div class=\"currency_name\">'.Yii::t('Api', 'OKX').'</div><div class=\"currency_symbol\">'.Yii::t('Api', 'Trading').'</div><div class=\"earn\"></div></div><div class=\"currency_graf ml-10\"></div><div class=\"currency_price_block ml-10\"><div class=\"currency_price\">" + formatValue(userActives.data.okx[key].trading[symbol].currency_value) + " " + userActives.grafema + "</div><div class=\"currency_volat\">" + formatValue(userActives.data.okx[key].trading[symbol].balance) + "</div></div><div class=\"clearfix\"></div></div>";
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
						
						var summ_coin = "middle_value";
						if (parseFloat(userActives.data.sol[key].active[symbol].currency_value)<1) {
							summ_coin = "small_value";
						}

						coinsSummActive += userActives.data.sol[key].active[symbol].balance*1;
						tonSummActiveCurrency += userActives.data.sol[key].active[symbol].currency_value*1;
						getCoinsActive();
						
						coins += parseFloat(userActives.data.sol[key].active[symbol].balance);
						price = parseFloat(userActives.data.sol[key].active[symbol].price);
						currency += parseFloat(userActives.data.sol[key].active[symbol].currency_value);

						jQuery("#adModal .name_details_coin, #targetModal .name_details_coin").html(formatValue(userActives.data.sol[key].active[symbol].price) + userActives.grafema);	
						jQuery("#adModal .img_details_coin>img, #targetModal .img_details_coin>img").attr("src", userActives.data.sol[key].active[symbol].img);	
						
						html_sol += "<div class=\"option_item " + summ_coin + "\" data-sort=\"" + userActives.data.sol[key].active[symbol].currency_value + "\"><img src=\"/images/logos/sol2.png\" alt=\"coin images\"><div class=\"currency_name_block ml-10\"><div class=\"currency_name\">'.Yii::t('Api', 'SOL Wallet').' 1</div><div class=\"currency_symbol\">'.Yii::t('Api', 'Basic').'</div><div class=\"earn\"></div></div><div class=\"currency_graf ml-10\"></div><div class=\"currency_price_block ml-10\"><div class=\"currency_price\">" + formatValue(userActives.data.sol[key].active[symbol].currency_value) + " " + userActives.grafema + "</div><div class=\"currency_volat\">" + formatValue(userActives.data.sol[key].active[symbol].balance) + "</div></div><div class=\"clearfix\"></div></div>";
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

						var summ_coin = "middle_value";
						if (parseFloat(userActives.data.sui[key].active[symbol].currency_value)<1) {
							summ_coin = "small_value";
						}

						coinsSummActive += userActives.data.sui[key].active[symbol].balance*1;
						tonSummActiveCurrency += userActives.data.sui[key].active[symbol].currency_value*1;
						getCoinsActive();
						
						coins += parseFloat(userActives.data.sui[key].active[symbol].balance);
						price = parseFloat(userActives.data.sui[key].active[symbol].price);
						currency += parseFloat(userActives.data.sui[key].active[symbol].currency_value);

						jQuery("#adModal .name_details_coin, #targetModal .name_details_coin").html(formatValue(userActives.data.sui[key].active[symbol].price) + userActives.grafema);	
						jQuery("#adModal .img_details_coin>img, #targetModal .img_details_coin>img").attr("src", userActives.data.sui[key].active[symbol].img);	
						
						html_sui += "<div class=\"option_item " + summ_coin + "\" data-sort=\"" + userActives.data.sui[key].active[symbol].currency_value + "\"><img src=\"/images/logos/sui2.png\" alt=\"coin images\"><div class=\"currency_name_block ml-10\"><div class=\"currency_name\">'.Yii::t('Api', 'SUI Wallet').' 1</div><div class=\"currency_symbol\">'.Yii::t('Api', 'Basic').'</div><div class=\"earn\"></div></div><div class=\"currency_graf ml-10\"></div><div class=\"currency_price_block ml-10\"><div class=\"currency_price\">" + formatValue(userActives.data.sui[key].active[symbol].currency_value) + " " + userActives.grafema + "</div><div class=\"currency_volat\">" + formatValue(userActives.data.sui[key].active[symbol].balance) + "</div></div><div class=\"clearfix\"></div></div>";
					}
				}
			};
			
			jQuery("#adModal #wrap_actives_coin").append(html_sui);
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
	
	//addTargetPage(symbol)
	function addTargetPage(symbol) {
		
		var value = 2;
		var summ_coins = parseFloat(jQuery("#adModal #ad-coins").val());
		var price_coins = parseFloat(jQuery("#adModal #ad-price").val());

		if (isTarget(symbol)) {	
		
			var dataTarget = getTarget(symbol);
			value = parseFloat(dataTarget.multiply);
			var price = parseFloat(dataTarget.price);
			jQuery("#adModal #target-info").html("'.Yii::t('Api', 'Current Target').': " + price);

		} else {

			var price = price_coins*value;
		}	
		
		var summ_price = summ_coins*price;
		
		jQuery("#targetModal #inputPrice").val(formatValue(price));			
		jQuery("#targetModal #inputAmount").val(formatValue(summ_price));
		jQuery("#customRange1").val(value);
		jQuery("#ad-user-price").val(value);
	}
	
	//addTonDeposit(userDataWallet)
	function addTonDeposit(userDataWallet) {
		if (
			typeof userDataWallet==="undefined" || 
			userDataWallet===undefined || 
			!userDataWallet || 
			typeof userDataWallet.active==="undefined" || 
			userDataWallet.active===undefined || 
			!userDataWallet.active
		) {
			return false;
		}

		var usdt_balance = 0;
		if (
			typeof userDataWallet.active["usdt"]!== "undefined" &&
			userDataWallet.active["usdt"]!==undefined &&
			userDataWallet.active["usdt"] &&
			typeof userDataWallet.active["usdt"].balance!== "undefined" &&
			userDataWallet.active["usdt"].balance!==undefined &&
			userDataWallet.active["usdt"].balance
		) {
			usdt_balance = userDataWallet.active["usdt"].balance;
		}
		
		var ton_apr = "";
		var ton_balance = 0;
		if (
			typeof userDataWallet.active["ton"].balance!== "undefined" &&
			userDataWallet.active["ton"].balance!==undefined &&
			userDataWallet.active["ton"].balance &&
			userDataWallet.active["ton"].balance>1
		) {
			ton_balance = toFloatDecimals(userDataWallet.active["ton"].balance - 1, 2);			
		}

		if (
			typeof userDataWallet.active["ton"].apr!== "undefined" &&
			userDataWallet.active["ton"].apr!==undefined &&
			userDataWallet.active["ton"].apr
		) {
			ton_apr += "APR=" + userDataWallet.active["ton"].apr;
		}
		
		var ton_exchange_balance = 0;
		var ton_send_balance = 0;
		var usdt_send_balance = 0;
		var ton_wallet_balance = 0;
		var swap_button = "";
		var depo_button = "";
		var usdt_class = "is-valid";
		var ton_class = "is-valid";
		var usdt_price_balance = 0;
		var swapBlock = "block";
		var poolBlock = "block"
		
		if (ton_balance) {		
			usdt_price_balance = ton_balance*userDataWallet.active["ton"].price;
		}

		if (ton_balance && usdt_balance) {
			
			usdt_send_balance = ton_balance*userDataWallet.active["ton"].price;
			ton_send_balance = ton_balance;
			
			if (usdt_send_balance<usdt_balance) {

				usdt_class = "is-valid";
				swapBlock = "none";
			
			} else {
			
				usdt_class = "is-invalid";
				swapBlock = "block";
			
			}
	
		} else if(ton_balance && !usdt_balance) {
			
			var usdt_class = "is-invalid";
			
			ton_send_balance = ton_balance/2;
			ton_exchange_balance = ton_balance/2;
			swapBlock = "block";

			
		} else if(!ton_balance && usdt_balance) {	
			
			var ton_class = "is-invalid";
			var usdt_class = "is-valid";
			swapBlock = "none";
			poolBlock = "none";
			
		} else {
			
			var usdt_class = "is-invalid";
			var ton_class = "is-invalid";
			var swap_button = "disabled";
			var depo_button = "disabled";			
		}

		if (ton_exchange_balance>0) {
			ton_exchange_balance = customRound(ton_exchange_balance, 4);
		}
		
		if (ton_send_balance>0) {
			ton_send_balance = toFloatDecimals(ton_send_balance, 4);
		}	
		if (!ton_send_balance) {
			ton_send_balance = "";
		}
		
		if (usdt_send_balance>0) {
			usdt_send_balance = toFloatDecimals(usdt_send_balance, 4);
		}
		
		if (!usdt_send_balance) {
			usdt_send_balance = "";
		}
		
		ton_exchange_balance = toFloatAmont(ton_exchange_balance);
		if (!ton_exchange_balance) {
			ton_exchange_balance = "";
		}

		ton_wallet_balance = toFloatAmont(ton_balance);
		if (!ton_wallet_balance) {
			ton_wallet_balance = "";
		}
		
		if (ton_class=="is-valid" && usdt_class=="is-valid") {
			swapBlock = "none";
		} else {
			swapBlock = "block";
		}

		var html_deposit = "<div class=\"earn\">";

			html_deposit += "<div style=\"font-size:18px;margin-bottom:4px;\">'.Yii::t('Api', 'I want to Deposit').'&nbsp;<div id=\"question-addon4\" class=\"is-external-info\"></div></div>";

			html_deposit += "<div style=\"border:1px solid #fff;border-radius:8px;padding:15px;margin-bottom:8px;\">";
							
				html_deposit += "<div style=\"margin-bottom:8px\"></div>";

				html_deposit += "<div style=\"width:calc(50% - 5px);height:60px\" class=\"form-floating float-start\"><input style=\"width:100%;height:60px;font-size:20px\" type=\"text\" class=\"form-control float-start\" id=\"inputToDeposit\" placeholder=\"'.Yii::t('Api', 'TON to Deposit').'\" autocomplete=\"off\" inputmode=\"numeric\" value=\"" + toFloatDecimals(ton_wallet_balance, 4) + "\"><label for=\"inputToDeposit\">'.Yii::t('Api', 'TON to Deposit').'</label></div>";
				
				html_deposit += "<div style=\"width:10px;height:60px;padding:20px 5px 0 5px\" class=\"float-start\"></div>";
				
				html_deposit += "<div style=\"width:calc(50% - 5px);height:60px\" class=\"form-floating float-start\"><input style=\"width:100%;height:60px;font-size:20px\" type=\"text\" class=\"form-control float-start\" id=\"inputToDepositLeft\" placeholder=\"'.Yii::t('Api', 'USDT').'\" autocomplete=\"off\" inputmode=\"numeric\" value=\"" + toFloatDecimals(usdt_price_balance, 4) + "\"><label for=\"inputToDepositLeft\">'.Yii::t('Api', 'USDT').'</label></div>";
	
				html_deposit += "<div class=\"clearfix\"></div>";
				
			html_deposit += "</div>";
			
			html_deposit += "<div id=\"poolBlock\" style=\"display:" + poolBlock + "\">";

				html_deposit += "<div id=\"deposit_apr\" style=\"font-size:18px;margin-bottom:4px;position:relative;\">'.Yii::t('Api', 'Your pool with').' " + ton_apr + "&nbsp;<div id=\"question-addon5\" class=\"is-external-info\"></div></div>";
							
				html_deposit += "<div style=\"border:1px solid #fff;border-radius:8px;padding:15px;margin-bottom:8px;\"><div style=\"margin-bottom:8px\">'.Yii::t('Api', 'Build LP using').'</div>";

					html_deposit += "<div style=\"width:calc(50% - 60px);height:60px\" class=\"form-floating float-start\"><input style=\"width:100%;height:60px;font-size:20px\" type=\"text\" class=\"form-control float-start " + usdt_class + "\" id=\"inputTonDeposit\" placeholder=\"'.Yii::t('Api', 'TON').'\" autocomplete=\"off\" inputmode=\"numeric\" value=\"" + ton_send_balance + "\"><label for=\"inputTonDeposit\">'.Yii::t('Api', 'TON').'</label></div>";
									
					html_deposit += "<div style=\"width:10px;height:60px;padding:20px 5px 0 5px\" class=\"float-start\"></div>";
								
					html_deposit += "<div style=\"width:calc(50% - 60px);height:60px\" class=\"form-floating float-start\"><input style=\"width:100%;height:60px;font-size:20px\" type=\"text\" class=\"form-control float-start " + ton_class + "\" id=\"inputUSDTDeposit\" placeholder=\"'.Yii::t('Api', 'USDT').'\" autocomplete=\"off\" inputmode=\"numeric\" value=\"" + usdt_send_balance + "\"><label for=\"inputUSDTDeposit\">'.Yii::t('Api', 'USDT').'</label></div>";

					html_deposit += "<button id=\"tonusdt-add-liquidity-button\" class=\"btn btn-outline-light float-end\" style=\"width:100px;height:60px;font-size:20px\" " + depo_button + ">'.Yii::t('Api', 'Deposit').'</button>";

					html_deposit += "<div class=\"clearfix\"></div>";

				html_deposit += "</div>";
				
			html_deposit += "</div>";
			
			html_deposit += "<div id=\"swapBlock\" style=\"display:" + swapBlock + "\">";
			
				html_deposit += "<div style=\"font-size:18px;margin-bottom:4px;\">'.Yii::t('Api', 'Swap').'</div>";
							
				html_deposit += "<div style=\"border:1px solid #fff;border-radius:8px;padding:15px;margin-bottom:8px;position:relative;\">";
								
					html_deposit += "<div style=\"margin-bottom:8px\">'.Yii::t('Api', 'Exchange your TON to balance assets for this pool').'<br>'.Yii::t('Api', 'USDT Balance').': " + usdt_balance + "</div>";
								
					html_deposit += "<div style=\"width:calc(100% - 110px);height:60px\" class=\"form-floating float-start\"><input style=\"width:100%;height:60px;font-size:20px\" type=\"text\" class=\"form-control\" id=\"inputSwap\" placeholder=\"'.Yii::t('Api', 'TON to USDT').'\" autocomplete=\"off\" inputmode=\"numeric\" value=\"" + ton_exchange_balance + "\"><label for=\"inputSwap\">'.Yii::t('Api', 'TON to USDT').'</label></div>";

					html_deposit += "<div style=\"width:10px;\" class=\"float-start\"></div>";
								
					html_deposit += "<button id=\"swap-tonusdt-button\" class=\"btn btn-outline-light float-end\" style=\"width:100px;height:60px;font-size:20px\" " + swap_button + ">'.Yii::t('Api', 'Swap').'</button><div class=\"clearfix\"></div>";
								
				html_deposit += "</div>";
				
			html_deposit += "</div>";

		html_deposit += "</div>";
		
		return html_deposit;
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
					addNotify("'.Yii::t('Api', 'Successfully swap ton').'", "success");
				}
				
			} else if(type="addusdtaqua") {
				
				if (status==1) {
					jQuery("#inputSwap2").val("");
					addNotify("'.Yii::t('Api', 'Successfully swap ton').'", "success");
				}
				
				
			} else if(type="swapusdtaqua") {
				
				jQuery("#inputSwap").val("");
			}
		} 
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
	
	//displayConnectMenu(type=0, flag=0)
	function displayConnectMenu(type=0, flag=0) {
		if (typeof type==="undefined" || type===undefined || !type) {
			return false;
		} 

		var elem;
		var ident;
		
		if (type==1) {
			elem = jQuery("#ton-wallet-click-button");
			ident = "ton";
		} else if(type==2) {
			elem = jQuery("#sol-wallet-click-button");
			ident = "sol";
		} else if(type==3) {
			elem = jQuery("#bybit-exchange-click-button");
			ident = "bybit";
		} else if(type==4) {
			elem = jQuery("#okx-exchange-click-button");
			ident = "okx";
		} else if(type==5) {
			elem = jQuery("#sui-wallet-click-button");
			ident = "sui";
		} else {
			return false;
		}

		var popover = bootstrap.Popover.getInstance(elem);
		popover.dispose();

		if (flag) {
			
			var template = "<div class=\"popover connect_popover\" role=\"tooltip\"><div class=\"popover-content\"><div class=\"" + ident + "_disconnect_button\"><div class=\"mdi mdi-logout\"></div><div class=\"popover_text\">'.Yii::t('Api', 'Disconnect').'</div><div class=\"clearfix\"></div></div></div></div>";

		} else {
			
			var template = "<div class=\"popover connect_popover\" role=\"tooltip\"><div class=\"popover-content\"><div class=\"" + ident + "_connect_button\"><div class=\"mdi mdi-login\"></div><div class=\"popover_text\">'.Yii::t('Api', 'Connect').'</div><div class=\"clearfix\"></div></div></div></div>";

		}	
		
		elem.popover({
			placement: "bottom",
			content: " ",
			trigger: "click",
			template: template,
		});
	}
	
	//document ready
	jQuery(document).ready(function($) {

		//console.log(solanaWeb3);
		
		
		
		
		
		
		
		
		
		
		
		
		

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
		
		if (bybitConnectedStatus==1) {
			bybitconnect();
		}
		
		if(okxConnectedStatus==1) {
			okxconnect();
		}
		
		if(solConnectedStatus==1) {
			solconnect();
		}
		
		if(suiConnectedStatus==1) {
			suiconnect();
		}

		// Popover help
		$("#question-addon1").popover({
			placement: "left",
			content: "This is the body of Popover",
			trigger: "focus",
			template: "<div class=\"popover question_popover\" role=\"tooltip\"><div class=\"popover-arrow\" style=\"position: absolute; top: 0px; transform: translate(0px, 12px);\"></div><div class=\"popover-content\"><div class=\"question_addon_popover\"><div class=\"popover_text\">'.addslashes(Yii::t('Api', 'Question Bybit UID')).' <a href=\"https://finkeeper.gitbook.io/finkeeper/integration/exchange\" target=\"_blank\">'.Yii::t('Api', 'Detailed instructions').' <i class=\"fa fa-external-link-alt\"></i></a></div><div class=\"clearfix\"></div></div></div></div>",
		});
		
		$("#question-addon2").popover({
			placement: "left",
			content: "This is the body of Popover",
			trigger: "focus",
			template: "<div class=\"popover question_popover\" role=\"tooltip\"><div class=\"popover-arrow\" style=\"position: absolute; top: 0px; transform: translate(0px, 12px);\"></div><div class=\"popover-content\"><div class=\"question_addon_popover\"><div class=\"popover_text\">'.addslashes(Yii::t('Api', 'Question Bybit APIKey')).' <a href=\"https://finkeeper.gitbook.io/finkeeper/integration/exchange\" target=\"_blank\">'.Yii::t('Api', 'Detailed instructions').' <i class=\"fa fa-external-link-alt\"></i></a></div><div class=\"clearfix\"></div></div></div></div>",
		});
		
		$("#question-addon3").popover({
			placement: "left",
			content: "This is the body of Popover",
			trigger: "focus",
			template: "<div class=\"popover question_popover\" role=\"tooltip\"><div class=\"popover-arrow\" style=\"position: absolute; top: 0px; transform: translate(0px, 12px);\"></div><div class=\"popover-content\"><div class=\"question_addon_popover\"><div class=\"popover_text\">'.addslashes(Yii::t('Api', 'Question Bybit APISecret')).' <a href=\"https://finkeeper.gitbook.io/finkeeper/integration/exchange\" target=\"_blank\">'.Yii::t('Api', 'Detailed instructions').' <i class=\"fa fa-external-link-alt\"></i></a></div><div class=\"clearfix\"></div></div></div></div>",
		});
		
		$("#question-addon6").popover({
			placement: "left",
			content: "This is the body of Popover",
			trigger: "focus",
			template: "<div class=\"popover question_popover\" role=\"tooltip\"><div class=\"popover-arrow\" style=\"position: absolute; top: 0px; transform: translate(0px, 12px);\"></div><div class=\"popover-content\"><div class=\"question_addon_popover\"><div class=\"popover_text\">'.addslashes(Yii::t('Api', 'Question OKX UID')).' <a href=\"https://finkeeper.gitbook.io/finkeeper/integration/exchange\" target=\"_blank\">'.Yii::t('Api', 'Detailed instructions').' <i class=\"fa fa-external-link-alt\"></i></a></div><div class=\"clearfix\"></div></div></div></div>",
		});
		
		$("#question-addon7").popover({
			placement: "left",
			content: "This is the body of Popover",
			trigger: "focus",
			template: "<div class=\"popover question_popover\" role=\"tooltip\"><div class=\"popover-arrow\" style=\"position: absolute; top: 0px; transform: translate(0px, 12px);\"></div><div class=\"popover-content\"><div class=\"question_addon_popover\"><div class=\"popover_text\">'.addslashes(Yii::t('Api', 'Question OKX APIKey')).' <a href=\"https://finkeeper.gitbook.io/finkeeper/integration/exchange\" target=\"_blank\">'.Yii::t('Api', 'Detailed instructions').' <i class=\"fa fa-external-link-alt\"></i></a></div><div class=\"clearfix\"></div></div></div></div>",
		});
		
		$("#question-addon8").popover({
			placement: "left",
			content: "This is the body of Popover",
			trigger: "focus",
			template: "<div class=\"popover question_popover\" role=\"tooltip\"><div class=\"popover-arrow\" style=\"position: absolute; top: 0px; transform: translate(0px, 12px);\"></div><div class=\"popover-content\"><div class=\"question_addon_popover\"><div class=\"popover_text\">'.addslashes(Yii::t('Api', 'Question OKX APISecret')).' <a href=\"https://finkeeper.gitbook.io/finkeeper/integration/exchange\" target=\"_blank\">'.Yii::t('Api', 'Detailed instructions').' <i class=\"fa fa-external-link-alt\"></i></a></div><div class=\"clearfix\"></div></div></div></div>",
		});
		
		$("#question-addon9").popover({
			placement: "left",
			content: "This is the body of Popover",
			trigger: "focus",
			template: "<div class=\"popover question_popover\" role=\"tooltip\"><div class=\"popover-arrow\" style=\"position: absolute; top: 0px; transform: translate(0px, 12px);\"></div><div class=\"popover-content\"><div class=\"question_addon_popover\"><div class=\"popover_text\">'.addslashes(Yii::t('Api', 'Question OKX Password')).' <a href=\"https://finkeeper.gitbook.io/finkeeper/integration/exchange\" target=\"_blank\">'.Yii::t('Api', 'Detailed instructions').' <i class=\"fa fa-external-link-alt\"></i></a></div><div class=\"clearfix\"></div></div></div></div>",
		});
		
		$("#question-addon10").popover({
			placement: "left",
			content: "This is the body of Popover",
			trigger: "focus",
			template: "<div class=\"popover question_popover\" role=\"tooltip\"><div class=\"popover-arrow\" style=\"position: absolute; top: 0px; transform: translate(0px, 12px);\"></div><div class=\"popover-content\"><div class=\"question_addon_popover\"><div class=\"popover_text\">'.addslashes(Yii::t('Api', 'Question SOL Address')).' <a href=\"https://finkeeper.gitbook.io/finkeeper/integration/exchange\" target=\"_blank\">'.Yii::t('Api', 'Detailed instructions').' <i class=\"fa fa-external-link-alt\"></i></a></div><div class=\"clearfix\"></div></div></div></div>",
		});
		
		$("#question-addon11").popover({
			placement: "left",
			content: "This is the body of Popover",
			trigger: "focus",
			template: "<div class=\"popover question_popover\" role=\"tooltip\"><div class=\"popover-arrow\" style=\"position: absolute; top: 0px; transform: translate(0px, 12px);\"></div><div class=\"popover-content\"><div class=\"question_addon_popover\"><div class=\"popover_text\">'.addslashes(Yii::t('Api', 'Question SUI Address')).' <a href=\"https://finkeeper.gitbook.io/finkeeper/integration/exchange\" target=\"_blank\">'.Yii::t('Api', 'Detailed instructions').' <i class=\"fa fa-external-link-alt\"></i></a></div><div class=\"clearfix\"></div></div></div></div>",
		});
		
		$("#smart-toy, #smart-toy-active").popover({
			placement: "right",
			content: "This is the body of Popover",
			trigger: "hover",
			template: "<div class=\"popover question_popover\" role=\"tooltip\"><div class=\"popover-arrow\" style=\"position: absolute; top: 0px; transform: translate(0px, 12px);\"></div><div class=\"popover-content\"><div class=\"question_addon_popover\"><div class=\"popover_text\">'.addslashes(Yii::t('Api', 'AI assistant')).'</div><div class=\"clearfix\"></div></div></div></div>",
		});

		// Popover menu
		jQuery("#ton-wallet-click-button").popover({
			placement: "bottom",
			content: " ",
			container: "body",
			trigger: "click",
			template: "<div class=\"popover connect_popover\" role=\"tooltip\"><div class=\"popover-content\"><div class=\"ton_connect_button\"><div class=\"mdi mdi-logout\"></div><div class=\"popover_text\">'.Yii::t('Api', 'Connect').'</div><div class=\"clearfix\"></div></div></div></div>",
		});
		
		jQuery("#sol-wallet-click-button").popover({
			placement: "bottom",
			content: " ",
			container: "body",
			trigger: "click",
			template: "<div class=\"popover connect_popover\" role=\"tooltip\"><div class=\"popover-content\"><div class=\"sol_connect_button\"><div class=\"mdi mdi-logout\"></div><div class=\"popover_text\">'.Yii::t('Api', 'Connect').'</div><div class=\"clearfix\"></div></div></div></div>",
		});

		jQuery("#bybit-exchange-click-button").popover({
			placement: "bottom",
			content: " ",
			container: "body",
			trigger: "click",
			template: "<div class=\"popover connect_popover\" role=\"tooltip\"><div class=\"popover-content\"><div class=\"bybit_connect_button\"><div class=\"mdi mdi-login\"></div><div class=\"popover_text\">'.Yii::t('Api', 'Connect').'</div><div class=\"clearfix\"></div></div></div></div>",
		});
		
		jQuery("#okx-exchange-click-button").popover({
			placement: "bottom",
			content: " ",
			container: "body",
			trigger: "click",
			template: "<div class=\"popover connect_popover\" role=\"tooltip\"><div class=\"popover-content\"><div class=\"okx_connect_button\"><div class=\"mdi mdi-login\"></div><div class=\"popover_text\">'.Yii::t('Api', 'Connect').'</div><div class=\"clearfix\"></div></div></div></div>",
		});
		
		jQuery("#sui-wallet-click-button").popover({
			placement: "bottom",
			content: " ",
			container: "body",
			trigger: "click",
			template: "<div class=\"popover connect_popover\" role=\"tooltip\"><div class=\"popover-content\"><div class=\"sui_connect_button\"><div class=\"mdi mdi-logout\"></div><div class=\"popover_text\">'.Yii::t('Api', 'Connect').'</div><div class=\"clearfix\"></div></div></div></div>",
		});
	
		/*
		jQuery.ajax({
			"url": "/v2/datas/userdata",
			"type": "post",
			"dataType": "json",
			"contentType": "application/json",
			"data": JSON.stringify(Telegram.WebApp),
			"success": function(response){
				
			},
			error: function(e) {
				addNotify(e, "error");
				jQuery("#bybit-connect-button").html(text_button);
				return false;
			}
		});	
		*/
	});
', yii\web\View::POS_END);