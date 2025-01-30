var tonConnectUI, userAmount, userQueryID, userTonSend, userUSDTSend, userUSDT, swapCommission = 0.1, userAmount2, userAQUASend2, userUSDTSend2, userUSDT2, swapCommission2 = 1;

document.addEventListener("DOMContentLoaded", function () {
	var lang = document.querySelector('script[data-id="bundle"][data-id]').getAttribute('data-lang');
	
	tonConnectUI = new TON_CONNECT_UI.TonConnectUI({
		manifestUrl: "https://app.finkeeper.pro/tonconnect-manifest.json",
		buttonRootId: "ton-connect",
		language: lang
	});

	const currentIsConnectedStatus = tonConnectUI.connected;
	if (!currentIsConnectedStatus) {
		tonRecalculation();
	} 

	tonConnectUI.onStatusChange((walletAndwalletInfo) => {

		if (tonConnectUI.wallet===null) {

			if (bybitConnectedStatus==false) {
				jQuery("#wrap-actives #title_balance").html("'.Yii::t('Api', 'Connect your wallet to see list of assets').'");	
			} else {
				bybitconnect();
				okxconnect();
			}

		} else {

			var address = tonConnectUI.wallet.account.address;
			if (!$('#adModal').hasClass('show')) {
				
				if (typeof tonConnectUI!=="undefined" && tonConnectUI!==undefined && tonConnectUI) {
					tonConnectUI.disconnect();
				}
			}
			sendAddress(address);
		}
	});

	$(document).delegate(".ton_connect_button", "click", function(){
		tonConnectUI.openModal();
	});

});

function formatValue(value, type=0) {
	if (isNaN(value) || typeof value==="undefined" || value===undefined || !value) {
		return 0;
	}
	
	var num = value;

	num = parseFloat(num);
	
	if (type==1) {		
		if (num>0.1) {
			return toFloatDecimals(num, 2).replace(/(0*$|\.0*$)/, '');
		}		
	}
	
	if(num>0.01) {
		return toFloatDecimals(num, 3).replace(/(0*$|\.0*$)/, '');
	} else if(num>0.001) {	
		return toFloatDecimals(num, 4).replace(/(0*$|\.0*$)/, '');
	} else if(num>0.0001) {	
		return toFloatDecimals(num, 5).replace(/(0*$|\.0*$)/, '');
	} else if(num>0.00001) {	
		return toFloatDecimals(num, 6).replace(/(0*$|\.0*$)/, '');
	} else if(num>0.000001) {	
		return toFloatDecimals(num, 7).replace(/(0*$|\.0*$)/, '');
	} else if(num>0.0000001) {	
		return toFloatDecimals(num, 8).replace(/(0*$|\.0*$)/, '');
	} else if(num>0.00000001) {	
		return toFloatDecimals(num, 9).replace(/(0*$|\.0*$)/, '');
	} else if(num>0.000000001) {	
		return toFloatDecimals(num, 10).replace(/(0*$|\.0*$)/, '');
	} else if(num>0.0000000001) {	
		return toFloatDecimals(num, 11).replace(/(0*$|\.0*$)/, '');
	} else if(num>0.00000000001) {	
		return toFloatDecimals(num, 12).replace(/(0*$|\.0*$)/, '');
	} else {
		return toFloatDecimals(num, 2).replace(/(0*$|\.0*$)/, '');
	}
}

function toFloatAmont(amount) {

	amount = parseFloat(amount);
	if (isNaN(amount) || typeof amount==="undefined" || amount===undefined || !amount) {
		amount = 0;
	}

	return amount;
}

function customRound(num, decimals) {
	
	if (typeof num==="undefined" || num===undefined || !num) {
		return 0;
	}
	
	if (typeof decimals==="undefined" || decimals===undefined || !decimals) {
		return num;
	}
	
	var decimals2 = decimals+1;	
	num = parseFloat(num).toFixed(decimals2);	
	num = num.slice(0, -decimals)
	return parseFloat(num);	
};

function toFloatDecimals(value, precision) {
    var precision = precision || 0,
        power = Math.pow(10, precision),
        absValue = Math.abs(Math.round(value * power)),
        result = (value < 0 ? '-' : '') + String(Math.floor(absValue / power));

    if (precision > 0) {
        var fraction = String(absValue % power),
            padding = new Array(Math.max(precision - fraction.length, 0) + 1).join('0');
        result += '.' + padding + fraction;
    }
	
	return result.replace(/(0*$|\.0*$)/, '');
}

function clearNotify() {
	jQuery("#conv-notify").html("").removeClass("card-error");
	jQuery("#conv-notify").html("").removeClass("card-success");
	jQuery("#conv-notify").html("").removeClass("card-warning");
}

function addNotify(message, type) {
	clearNotify();
	jQuery("#conv-notify").html(message).addClass("card-" + type).parent('.bottom-fixed').fadeOut(30000, function() {
		jQuery("#conv-notify").html("").removeClass("card-" + type).parent('.bottom-fixed').show();
	});
}

function clearActive() {
	jQuery(".currency_value input").removeClass("currency_active");
}

function sanitizeStr(str) {

	if (typeof str ==="undefined" || str===undefined || !str) {
		return '';
	}

	str = str.replaceAll(' ', '');
	str = str.replaceAll(',', '.');

	return str;
}

function isInt(n){
    return Number(n) === n && n % 1 === 0;
}

function isFloat(n){
    return Number(n) === n && n % 1 !== 0;
}

function sortingArray(array) {
		
	var new_array = [];
	var inc = 0;
	array.forEach(function(item, i, array) {
		if (typeof item!=="undefined" && item!==undefined && item) {
			new_array[inc] = item;	
			inc++;
		}			
	});

	return new_array;
}

function recentLoad() {
		
	var search_button = abcpLocalStorage(2);

	if (
		typeof search_button==="undefined" || 
		search_button===undefined || 
		!search_button || 
		search_button===null
	) {
		return false;
	}

	searchData(search_button);
}

function searchData(type_search) {

	if (type_search=="fiat") {
		
		abcpLocalStorage(1, type_search);

		jQuery(".currency_button").removeClass("currency_button_active");
		jQuery(".currency_button[data-id=" + type_search + "]").addClass("currency_button_active");		
		
		jQuery("#convModal .option_item[data-type=1]").hide();
		jQuery("#convModal .option_item[data-type=2]").show();
		
	} else if(type_search=="crypto") {
		
		abcpLocalStorage(1, type_search);

		jQuery(".currency_button").removeClass("currency_button_active");
		jQuery(".currency_button[data-id=" + type_search + "]").addClass("currency_button_active");		
		
		jQuery("#convModal .option_item[data-type=2]").hide();
		jQuery("#convModal .option_item[data-type=1]").show();
		
	} else if(type_search=="recent") {
		
		abcpLocalStorage(1, type_search);

		jQuery(".currency_button").removeClass("currency_button_active");
		jQuery(".currency_button[data-id=" + type_search + "]").addClass("currency_button_active");		
		
		var array = abcpLocalStorage(4);
		var index = 1;
		jQuery("#convModal .option_item").hide();

		try {
			let search_object = JSON.parse(exchange);
			
			if (typeof array!=="undefined" && array!==undefined && array) {
			
				for (let i = array.length - 1; i >=0; i--) {
					jQuery.each(search_object, function(key, value) {
						if (array[i]==value.id) {
							var elem = jQuery("#convModal .option_item[data-id=" + value.id + " ]");							
							elem.show();							
							elem.parent(".modal-body").append(elem);
						}
					});
				}
			}

		} catch (e) {
			addNotify(e.message, "error");										
		}		

	} else if(type_search=="all") {

		jQuery(".currency_button").removeClass("currency_button_active");
		jQuery(".currency_button[data-id=" + type_search + "]").addClass("currency_button_active");	
		
		jQuery("#convModal .option_item[data-type=2]").show();
		jQuery("#convModal .option_item[data-type=1]").show();
		
	} else {

		jQuery(".currency_button").removeClass("currency_button_active");
		
		if (typeof type_search==="undefined" || type_search===undefined || !type_search) {
			jQuery(".currency_button[data-id=all]").addClass("currency_button_active");
			jQuery("#convModal .option_item[data-type=2]").show();
			jQuery("#convModal .option_item[data-type=1]").show();
			return false;
		}

		try {
			let search_object = JSON.parse(exchange);
			jQuery.each(search_object, function(key, value) {

				var thisRegex = new RegExp("^(" + type_search + ")", "iu");

				if (thisRegex.test(value.name) || thisRegex.test(value.symbol)) {
					
					jQuery("#convModal .option_item[data-id=" + value.id + " ]").show();
					
				} else {
					
					jQuery("#convModal .option_item[data-id=" + value.id + " ]").hide();
				}
			})

		} catch (e) {
			addNotify(e.message, "error");										
		}				
	}		
}

function getFormat(str, decimal=4, type) {
		
	var tmp = 0;
	
	if (type==2) {

		tmp = str;

	} else {

		for (let i=decimal; i<=10; i++) {
			
			tmp = str;
			tmp = number_format(tmp, i, ".", " ");
			
			var thisRegex = new RegExp("^([0]{1,})\.([0]{1,})$", "i");

			if (!thisRegex.test(tmp)) {
					
				tmp = tmp.replace(/[0]{1,}$/i, "");
				tmp = tmp.replace(/[.]{1}$/i, "");

				return tmp;		
			} 
		}
	}
	
	return tmp;
}

function number_format(number, decimals, dec_point, thousands_sep) {

	var i, j, kw, kd, km;

	if( isNaN(decimals = Math.abs(decimals)) ){
		decimals = 2;
	}
	
	if( dec_point == undefined ){
		dec_point = ",";
	}
	
	if( thousands_sep == undefined ){
		thousands_sep = ".";
	}

	i = parseInt(number = (+number || 0).toFixed(decimals)) + "";

	if( (j = i.length) > 3 ){
		j = j % 3;
	} else{
		j = 0;
	}

	km = (j ? i.substr(0, j) + thousands_sep : "");
	kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
	kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");


	return km + kw + kd;
}

function getCurrencyIcon(str) {

	var symbol = "";
	
	if (typeof str==="undefined" || str===undefined || !str) {
		return symbol;
	}
	
	str = str.toLowerCase();

	var currency = {
		"aud": "$",
		"amd": "Դ",
		"cad": "$",
		"cny": "¥",
		"czk": "Kč",
		"dkk": "kr",
		"huf": "ƒ",
		"inr": "₹",
		"jpy": "¥",
		"kzt": "₸",
		"krw": "₩",
		"kgs": "KGS",
		"lvl": "LVL",
		"ltl": "LTL",
		"mdl": "MDL",
		"nok": "kr",
		"sgd": "$",
		"zar": "ZAR",
		"sek": "kr",
		"chf": "Fr",
		"gbp": "£",
		"usd": "$",
		"uzs": "Soʻm",
		"tmt": "TMT",
		"azn": "₼",
		"ron": "RON",
		"try": "₺",
		"xdr": "XDR",
		"tjs": "TJS",
		"byr": "BYN",
		"bgn": "BGN",
		"eur": "€",
		"uah": "₴",
		"pln": "zł",
		"brl": "$",
		"rub": "₽",
	};
	
	if (typeof currency[str]==="undefined" || currency[str]===undefined || !currency[str]) {
		return symbol;
	}
	
	return currency[str];
}

function closeAllModal() {
	jQuery(".modal").each(function (index, element) {
		var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById(element.id));
		modal.hide();
	});
	
	jQuery(".modal-backdrop").remove();
}

function calcform() {

	jQuery("#ct-calc-apr").val(sanitizeStr(jQuery("#ct-calc-apr").val()));
	var apr = parseInt(jQuery("#ct-calc-apr").val(), 10);

	if (typeof apr==="undefined" || apr===undefined || isNaN(apr) || !apr) {
		apr = 0;
	}
	
	jQuery("#ct-calc-deposit").val(sanitizeStr(jQuery("#ct-calc-deposit").val()));
	var deposit = parseInt(jQuery("#ct-calc-deposit").val(), 10);
	if (typeof deposit==="undefined" || deposit===undefined || isNaN(deposit) || !deposit) {
		deposit = 0;
	}
	
	jQuery("#ct-calc-days").val(sanitizeStr(jQuery("#ct-calc-days").val()));
	var days = parseInt(jQuery("#ct-calc-days").val(), 10);
	if (typeof days==="undefined" || days===undefined || isNaN(days) || !days) {
		days = 0;
	}
	
	var daily_yield = apr/365*deposit/100;
	if (typeof daily_yield==="undefined" || daily_yield===undefined || isNaN(daily_yield) || !daily_yield) {
		daily_yield = 0;
	}

	jQuery("#ct-calc-daily_yield").val(daily_yield.toFixed(2));
	
	var sum_yield = daily_yield*days;
	if (typeof sum_yield==="undefined" || sum_yield===undefined || isNaN(sum_yield) || !sum_yield) {
		sum_yield = 0;
	}

	jQuery("#ct-calc-sum_yield").val(sum_yield.toFixed(2));
}

function getAllActive() {

	tonSummActive = parseFloat(tonSummActive);

	if (
		typeof tonSummActive==="undefined" || 
		tonSummActive===undefined || 
		!tonSummActive || 
		isNaN(tonSummActive) ||
		(!isFloat(tonSummActive) && !isInt(tonSummActive))
	) {
		tonSummActive = 0;		
	}	
		
	bybitSummActive = parseFloat(bybitSummActive);	
	
	if (
		typeof bybitSummActive==="undefined" || 
		bybitSummActive===undefined || 
		!bybitSummActive || 
		isNaN(bybitSummActive) ||
		(!isFloat(bybitSummActive) && !isInt(bybitSummActive))
	) {
		bybitSummActive = 0;		
	}	
	
	okxSummActive = parseFloat(okxSummActive);	
	
	if (
		typeof okxSummActive==="undefined" || 
		okxSummActive===undefined || 
		!okxSummActive || 
		isNaN(okxSummActive) ||
		(!isFloat(okxSummActive) && !isInt(okxSummActive))
	) {
		okxSummActive = 0;		
	}	
	
	solSummActive = parseFloat(solSummActive);	
	
	if (
		typeof solSummActive==="undefined" || 
		solSummActive===undefined || 
		!solSummActive || 
		isNaN(solSummActive) ||
		(!isFloat(solSummActive) && !isInt(solSummActive))
	) {
		solSummActive = 0;		
	}	
	
	
	
		
	var allSumm = tonSummActive + bybitSummActive + okxSummActive + solSummActive;
	
	if (allSumm==0) {
		return false;
	}
	
	if (allSumm>1) {
		allSumm = Math.floor(allSumm * 100 ) / 100;
	}

	jQuery('#all-summ-active>.all-summ-price').text(formatValue(allSumm));
	jQuery('#all-summ-active').show();
}

function getCoinsActive() {
	
	tonSummActive = tonSummActive*1;
	tonSummActiveCurrency = tonSummActiveCurrency*1;

	if (
		typeof coinsSummActive==="undefined" || 
		coinsSummActive===undefined || 
		!coinsSummActive || 
		isNaN(coinsSummActive) ||
		(!isFloat(coinsSummActive) && !isInt(coinsSummActive))
	) {
		coinsSummActive = 0;		
	}	
	
	if (coinsSummActive>0) {
		
	}
	
	if (tonSummActiveCurrency>1) {
		//tonSummActiveCurrency = Math.floor(tonSummActiveCurrency * 100 ) / 100;
	}
	
	var fomatSummActiveCurrency = formatValue(tonSummActiveCurrency, 1);
	var fomatSummActive = formatValue(coinsSummActive);

	jQuery('#targetModal #inputAmount').val(fomatSummActiveCurrency);
	jQuery('#adModal .wrap_details_coin, #targetModal .wrap_details_coin').text(fomatSummActiveCurrency);
	jQuery('#adModal .currency_details_coin, #targetModal .currency_details_coin').text(fomatSummActive);
	
}

function getSettings() {

	var set1 = abcpLocalStorage(10);
	if (typeof set1!=="undefined" && set1!==undefined && set1==1) {

		jQuery('#select-hsa').prop('checked', true);
		jQuery('.small_value').hide();
	
	}
	
	return false;
}

function tonRecalculation() {
	jQuery("#all-summ-active").hide().find(".all-sum-price").html("0");
	tonSummActive = 0;
	getAllActive();
}

function bybitRecalculation() {
	jQuery("#all-summ-active").hide().find(".all-sum-price").html("0");
	bybitSummActive = 0;
	getAllActive();
}

function okxRecalculation() {
	jQuery("#all-summ-active").hide().find(".all-sum-price").html("0");
	okxSummActive = 0;
	getAllActive();
}

function solRecalculation() {
	jQuery("#all-summ-active").hide().find(".all-sum-price").html("0");
	solSummActive = 0;
	getAllActive();
}

function suiRecalculation() {
	jQuery("#all-summ-active").hide().find(".all-sum-price").html("0");
	suiSummActive = 0;
	getAllActive();
}

function getActiveButton(btn=1) {
	jQuery('.bottom-navigation-line div').removeClass('active-line').addClass('default-line');
	jQuery('.bottom-navigation__icons').find('li').removeClass('navigation_active');
	jQuery('.bottom-navigation__icons').find('.bottom_button_nav_active').hide();
	jQuery('.bottom-navigation__icons').find('.bottom_button_nav_default').show();

	if (btn==1) {
		jQuery('#close-all-modal').parent('li').addClass('navigation_active');
		jQuery('.bottom-navigation-line #home_line').removeClass('default-line').addClass('active-line');
		jQuery('#close-all-modal').find('.bottom_button_nav_active').show();
		jQuery('#close-all-modal').find('.bottom_button_nav_default').hide();
	} else if(btn==2) {
		jQuery('#as-modal').parent('li').addClass('navigation_active');
		jQuery('.bottom-navigation-line #as_line').removeClass('default-line').addClass('active-line');
		jQuery('#as-modal').find('.bottom_button_nav_active').show();
		jQuery('#as-modal').find('.bottom_button_nav_default').hide();
	} else if(btn==3) {
		jQuery('#fr-modal').parent('li').addClass('navigation_active');
		jQuery('.bottom-navigation-line #fr_line').removeClass('default-line').addClass('active-line');
		jQuery('#fr-modal').find('.bottom_button_nav_active').show();
		jQuery('#fr-modal').find('.bottom_button_nav_default').hide();
	} else {
		jQuery('#close-all-modal').parent('li').addClass('navigation_active');
		jQuery('.bottom-navigation-line #home_line').removeClass('default-line').addClass('active-line');
		jQuery('#close-all-modal').find('.bottom_button_nav_active').show();
		jQuery('#close-all-modal').find('.bottom_button_nav_default').hide();
	}
}

function getQueryID() {
	var ident = '55555';
	var date = new Date().getTime();
	return ident + date;
}

function targetConvertValue(num) {
	
	var price = jQuery('#adModal #ad-price').val();
	var coins = jQuery('#adModal #ad-coins').val();
	
	if (typeof num==="undefined" || num===undefined || isNaN(num) || !num) {
		num = 1;
	}

	var newPrice = price*num;
	var newCurrency = coins*newPrice;
	
	jQuery('#targetModal #inputPrice').val(formatValue(newPrice));
	jQuery('#targetModal #inputAmount').val(formatValue(newCurrency));
}

function getTarget(symbol) {
	if (typeof symbol==='undefined' || symbol===undefined || !symbol) {
		return false;
	}
	
	if (typeof targets==="undefined" || targets===undefined || !targets) {
		return false;
	}
	
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
					return targetsObj[key];						
				}
			}
		}

	} catch (e) {
		console.log(e.message);	
		return false;			
	}	

	return data;	
}

function setTarget(symbol, price) {
	
	if (typeof symbol==='undefined' || symbol===undefined || !symbol) {
		return false;
	}
	
	if (typeof price==='undefined' || price===undefined || !price) {
		return false;
	}
	
	if (typeof targets==="undefined" || targets===undefined || !targets) {
		return false;
	}
	
	try {
		let targetsObj = JSON.parse(targets);

		if (
			typeof targetsObj!=="undefined" && 
			targetsObj!==undefined && 
			targetsObj &&
			typeof targetsObj==="object"
		) {
			
			var is_added = 1;
			var indexObj = 0;
			for (key in targetsObj) {
				if (targetsObj[key].symbol==symbol) {
					is_added = 0;											
				}
				
				var indexObj = parseInt(key, 10);
			}

			if (is_added==1) {
				targetsObj[indexObj+1]={
					"symbol": symbol,
					"price": price,
				};
				
				targetsObj = targetsObj.filter(element => element !== null);
				targets = JSON.stringify(targetsObj);
				return true;
			}
		}

	} catch (e) {
		console.log(e.message);										
	}	

	return false;	
}

function isTarget(symbol) {
	
	if (typeof symbol==='undefined' || symbol===undefined || !symbol) {
		return false;
	}
	
	symbol = symbol.toLowerCase();
	
	if (typeof targets==="undefined" || targets===undefined || !targets) {
		return false;
	}

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
					return true;
				}
			}
		}

	} catch (e) {
		console.log(e.message);	
	}
	
	return false;	
}

function displayBackdrop(type=0, flag=0) {
	if (typeof type==="undefined" || type===undefined || !type) {
		return false;
	} 
	
	var elem;
	var send_button;
	var text_button;
	var spinner = '<i class="fas fa-asterisk fa-spin"></i>';
	
	if (type==1) {
		elem = jQuery("#asModal #ton-wallet-connect-button .backdrop-connect-button");
	} else if(type==2) {
		elem = jQuery("#asModal #sol-wallet-connect-button .backdrop-connect-button");
		send_button = jQuery("#ct-sol-api-send");
		text_button = jQuery(send_button).text();
	} else if(type==3) {
		elem = jQuery("#asModal #bybit-exchange-connect-button .backdrop-connect-button");
		send_button = jQuery("#ct-bybit-api-send");
		text_button = jQuery(send_button).text();
	} else if(type==4) {
		elem = jQuery("#asModal #okx-exchange-connect-button .backdrop-connect-button");
		send_button = jQuery("#ct-okx-api-send");
		text_button = jQuery(send_button).text();
	} else if(type==5) {
		elem = jQuery("#asModal #sui-wallet-connect-button .backdrop-connect-button");
		send_button = jQuery("#ct-sui-api-send");
		text_button = jQuery(send_button).text();
	} else if(type==6) {
		elem = jQuery("#asModal #wc-wallet-connect-button .backdrop-connect-button");
		send_button = jQuery("#ct-wc-api-send");
		text_button = jQuery(send_button).text();
	} else if(type==7) {
		//elem = jQuery("#asModal #sol-wallet-connect-button .backdrop-connect-button");
		//send_button = jQuery("#wallet-connect");
		//text_button = jQuery(send_button).text();
	} else {
		return false;
	}

	if (flag) {
		jQuery(elem).css('left', '0px');
		jQuery(elem).show();

		if (typeof send_button !=="undefined" && send_button!==undefined && send_button) {
			jQuery(send_button).html(spinner + "&nbsp;" + text_button);
		}
	} else {
		jQuery(elem).css('left', '1000px');
		jQuery(elem).hide();

		if (typeof send_button !=="undefined" && send_button!==undefined && send_button) {
			jQuery(send_button).html(text_button);
		}
	}	
}

function displayConnectIcon(type=0, flag=0, error=0) {
	if (typeof type==="undefined" || type===undefined || !type) {
		return false;
	} 
	
	var elem;
	
	if (type==1) {
		elem = jQuery("#asModal #ton-wallet-connect-button .mdi");
	} else if(type==2) {
		elem = jQuery("#asModal #sol-wallet-connect-button .mdi");
	} else if(type==3) {
		elem = jQuery("#asModal #bybit-exchange-connect-button .mdi");
	} else if(type==4) {
		elem = jQuery("#asModal #okx-exchange-connect-button .mdi");
	} else if(type==5) {
		elem = jQuery("#asModal #sui-wallet-connect-button .mdi");
	} else if(type==6) {
		elem = jQuery("#asModal #wc-wallet-connect-button .mdi");
	} else if(type==7) {
		//elem = jQuery("#asModal #sol-wallet-connect-button .mdi");
	} else {
		return false;
	}
	
	jQuery(elem).removeClass("error");
	
	if (flag) {
		if (typeof error!=="undefined" && error!==undefined && error) {
			jQuery(elem).removeClass("mdi-wifi").addClass("mdi-wifi-off error");
		} else {
			jQuery(elem).removeClass("mdi-wifi-off").addClass("mdi-wifi");
		}
	} else {
		jQuery(elem).removeClass("mdi-wifi").addClass("mdi-wifi-off");
	}	
}

function updatePopover(id='', message='', placement='bottom', trigger='click') {
	
	if (typeof id==="undefined" || id===undefined || !id) {
		return false;
	} 
	
	if (typeof message==="undefined" || message===undefined || !message) {
		return false;
	} 
	
	var elem = jQuery('#' + id);
	var popover = bootstrap.Popover.getInstance(elem);
	popover.dispose();

	var template = '<div class=\"popover question_popover\" role=\"tooltip\"><div class=\"popover-arrow\" style=\"position: absolute; top: 0px; transform: translate(0px, 12px);\"></div><div class=\"popover-content\"><div class=\"question_addon_popover\"><div class=\"popover_text\">' + message + '</div><div class=\"clearfix\"></div></div></div></div>';

	elem.popover({
		placement: placement,
		content: " ",
		trigger: trigger,
		template: template,
	});
}
function closeSearch() {
	$("#form-search-active").hide();
	$("#search-active-input").val("");
	$("#user_balance .option_item").show();
}

function findCoins(search) {
	
	search = search.toLowerCase();
	
	var result = $("#user_balance div.option_item[data-id^=\"" + search + "\"]");
	if (
		typeof result!=="undefined" && 
		result!==undefined && 
		result
	) {
		$("#user_balance .option_item").hide();
		$(result).each(function(i, element) {
			$(element).show();
		});
		
	} else {
		$("#user_balance .option_item").show();
	}
}

function selectCoin(coin) {
		
	if (
		typeof userActivesMin==="undefined" || 
		userActivesMin===undefined || 
		!userActivesMin || 
		typeof userActivesMin!=="object"
	) {
		return false;
	} 
	
	var obj = {};
	for (key in userActivesMin) {

		if (
			typeof userActivesMin[key]!=="undefined" && 
			userActivesMin[key]!==undefined && 
			userActivesMin[key] && 
			typeof userActivesMin[key]==="object"
		) {
			
			for (index in userActivesMin[key]) {
			
				if (
					typeof userActivesMin[key][index]!=="undefined" && 
					userActivesMin[key][index]!==undefined && 
					userActivesMin[key][index] && 
					typeof userActivesMin[key][index]==="object"
				) {
					
					for (type in userActivesMin[key][index]) {
				
						if (
							typeof userActivesMin[key][index][type]!=="undefined" && 
							userActivesMin[key][index][type]!==undefined && 
							userActivesMin[key][index][type] && 
							typeof userActivesMin[key][index][type]==="object"
						) {
							
							for (symbol in userActivesMin[key][index][type]) {
						
								if (coin==symbol) {
									
									obj[key] = {};
									obj[key][index] = {};
									obj[key][index][type] = {};
									obj[key][index][type][symbol] = userActivesMin[key][index][type][symbol];
								}
							}
						}
					}
				}
			}
		} 
	}
	
	return obj;		
}

jQuery(document).ready(function($) {

	$("#search-actives").on("click", function() {
		$("#form-search-active").show();
	});
	
	$("#close-search").on("click", function() {
		closeSearch();			
	});
	
	$("#search-active-input").on("input", function() {
		var str = $(this).val();
		
		if (
			typeof str!=="undefined" && 
			str!==undefined && 
			str
		) {
			
			findCoins(str);	
			
		} else {
			
			$("#user_balance .option_item").show();
		}		
	});
	
	$("#asModal").on("hide.bs.modal", function () {
		closeSearch();
	});
	
	$("#adModal").delegate("#inputSwap","input", function(){
		userAmount = jQuery("#adModal #inputSwap").val();
		userAmount = toFloatAmont(userAmount);
	});
	
	$("#adModal").delegate("#inputTonDeposit","input", function(){
		userTonSend = jQuery("#adModal #inputTonDeposit").val();
		userTonSend = toFloatAmont(userTonSend);
	});
	
	$("#adModal").delegate("#inputUSDTDeposit","input", function(){
		userUSDTSend = jQuery("#adModal #inputUSDTDeposit").val();
		userUSDTSend = toFloatAmont(userUSDTSend);
	});

	$("#adModal").delegate("#swap-tonusdt-button","click", function(){
		userAmount = jQuery("#adModal #inputSwap").val();
		userAmount = toFloatAmont(userAmount);
	});
	
	$("#adModal").delegate("#tonusdt-add-liquidity-button","click", function(){
		userTonSend = jQuery("#adModal #inputTonDeposit").val();
		userTonSend = toFloatAmont(userTonSend);
		userUSDTSend = jQuery("#adModal #inputUSDTDeposit").val();
		userUSDTSend = toFloatAmont(userUSDTSend);
	});

	$("#adModal").delegate("#inputToDeposit", "input", function(){

		if (
			typeof userActives.data.tonwallet==="undefined" || 
			userActives.data.tonwallet===undefined || 
			!userActives.data.tonwallet || 
			typeof userActives.data.tonwallet[0]==="undefined" || 
			userActives.data.tonwallet[0]===undefined || 
			!userActives.data.tonwallet[0] ||
			typeof userActives.data.tonwallet[0].active==="undefined" || 
			userActives.data.tonwallet[0].active===undefined || 
			!userActives.data.tonwallet[0].active || 
			typeof userActives.data.tonwallet[0].active['ton']==="undefined" || 
			userActives.data.tonwallet[0].active['ton']===undefined || 
			!userActives.data.tonwallet[0].active['ton']
		) {
			return false;
		}

		var input = $(this).val();
		if (typeof input==="undefined" || input===undefined || !input) {
			return false;
		}
			
		input = parseFloat(input);
		if (typeof input==="undefined" || input===undefined || isNaN(input) || !input) {
			return false;
		}

		var usdt = input*userActives.data.tonwallet[0].active['ton'].price;
		var ton = input;
		var ton_apr = "Deposit " + input + " TON=" + toFloatDecimals(input * userActives.data.tonwallet[0].active["ton"].price, 2) + " USDT with APR=" + userActives.data.tonwallet[0].active["ton"].apr;
		
		$('#inputToDepositLeft').val(toFloatDecimals(usdt, 4));

		var tonclass = 'is-valid';
		if (
			typeof userActives.data.tonwallet[0].active['ton']!=="undefined" &&
			userActives.data.tonwallet[0].active['ton']!==undefined &&
			userActives.data.tonwallet[0].active['ton'] &&
			userActives.data.tonwallet[0].active['ton'].balance<ton
		) {
			tonclass = 'is-invalid';
		}

		var usdtclass = 'is-valid';
		if (
			typeof userActives.data.tonwallet[0].active['usdt']!=="undefined" &&
			userActives.data.tonwallet[0].active['usdt']!==undefined &&
			userActives.data.tonwallet[0].active['usdt'] &&
			userActives.data.tonwallet[0].active['usdt'].balance<usdt
		) {
			if (usdt>userActives.data.tonwallet[0].active['usdt'].balance) {
				var exchange_usdt = usdt-userActives.data.tonwallet[0].active['usdt'].balance;
				var exchange_ton = exchange_usdt / userActives.data.tonwallet[0].active['ton'].price
				$('#inputSwap').val(toFloatDecimals(exchange_ton, 4));
			}

			usdtclass = 'is-invalid';
		}
		
		if (tonclass=='is-valid' && usdtclass=='is-valid') {
			jQuery("#swapBlock").hide();
		} else {
			jQuery("#swapBlock").show();
		}
		
		$('#deposit_apr').html(ton_apr);
		$('#inputTonDeposit').val(toFloatDecimals(ton, 4)).removeClass("is-invalid is-valid").addClass(tonclass);
		$('#inputUSDTDeposit').val(toFloatDecimals(usdt, 4)).removeClass("is-invalid is-valid").addClass(usdtclass);
		

	});
	
	$("#adModal").delegate("#inputToDeposit2", "input", function(){

		if (
			typeof userActives.data.tonwallet==="undefined" || 
			userActives.data.tonwallet===undefined || 
			!userActives.data.tonwallet || 
			typeof userActives.data.tonwallet[0]==="undefined" || 
			userActives.data.tonwallet[0]===undefined || 
			!userActives.data.tonwallet[0] ||
			typeof userActives.data.tonwallet[0].active==="undefined" || 
			userActives.data.tonwallet[0].active===undefined || 
			!userActives.data.tonwallet[0].active || 
			typeof userActives.data.tonwallet[0].active['usdt']==="undefined" || 
			userActives.data.tonwallet[0].active['usdt']===undefined || 
			!userActives.data.tonwallet[0].active['usdt']
		) {
			return false;
		}

		var input = $(this).val();
		if (typeof input==="undefined" || input===undefined || !input) {
			return false;
		}
			
		input = parseFloat(input);
		if (typeof input==="undefined" || input===undefined || isNaN(input) || !input) {
			return false;
		}

		var aqua = input*userActives.data.tonwallet[0].active['usdt'].price;
		var usdt = input;
		var usdt_apr = "Deposit " + input + " USDT=" + toFloatDecimals(input * userActives.data.tonwallet[0].active["usdt"].price, 2) + " AQUA with APR=" + userActives.data.tonwallet[0].active["usdt"].apr;
		
		$('#inputToDepositLeft2').val(toFloatDecimals(aqua, 4));
		
		var usdtclass = 'is-valid';
		if (
			typeof userActives.data.tonwallet[0].active['usdt']!=="undefined" &&
			userActives.data.tonwallet[0].active['usdt']!==undefined &&
			userActives.data.tonwallet[0].active['usdt'] &&
			userActives.data.tonwallet[0].active['usdt'].balance<usdt
		) {
			usdtclass = 'is-invalid';
		}

		var aquaclass = 'is-valid';
		if (
			typeof userActives.data.tonwallet[0].active['aquausd']!=="undefined" &&
			userActives.data.tonwallet[0].active['aquausd']!==undefined &&
			userActives.data.tonwallet[0].active['aquausd'] &&
			userActives.data.tonwallet[0].active['aquausd'].balance<aqua
		) {

			if (input>userActives.data.tonwallet[0].active['aquausd'].balance) {
				var exchange = input-userActives.data.tonwallet[0].active['aquausd'].balance;
				$('#inputSwap2').val(toFloatDecimals(exchange, 4));
			}

			aquaclass = 'is-invalid';
		}
		
		if (usdtclass=='is-valid' && aquaclass=='is-valid') {
			jQuery("#swapBlock2").hide();
		} else {
			jQuery("#swapBlock2").show();
		}

		$('#deposit_apr').html(usdt_apr);
		$('#inputAQUADeposit2').val(toFloatDecimals(aqua, 4)).removeClass("is-invalid is-valid").addClass(aquaclass);
		$('#inputUSDTDeposit2').val(toFloatDecimals(usdt, 4)).removeClass("is-invalid is-valid").addClass(usdtclass);
	});

	$("#adModal").delegate("#inputSwap2","input", function(){
		userAmount2 = jQuery("#adModal #inputSwap2").val();
		userAmount2 = toFloatAmont(userAmount2);
	});
	
	$("#adModal").delegate("#inputUSDTDeposit2","input", function(){
		userUSDTSend2 = jQuery("#adModal #inputUSDTDeposit2").val();
		userUSDTSend2 = toFloatAmont(userUSDTSend2);
	});
	
	$("#adModal").delegate("#inputAQUADeposit2","input", function(){
		userAQUASend2 = jQuery("#adModal #inputAQUADeposit2").val();
		userAQUASend2 = toFloatAmont(userAQUASend2);
	});

	$("#adModal").delegate("#swap-usdtaqua-button","click", function(){
		userAmount2 = jQuery("#adModal #inputSwap2").val();
		userAmount2 = toFloatAmont(userAmount2);
	});
	
	$("#adModal").delegate("#usdtaqua-add-liquidity-button","click", function(){
		userUSDTSend2 = jQuery("#adModal #inputUSDTDeposit2").val();
		userUSDTSend2 = toFloatAmont(userUSDTSend2);
		userAQUASend2 = jQuery("#adModal #inputAQUADeposit2").val();
		userAQUASend2 = toFloatAmont(userAQUASend2);
	});

	var BackButton = Telegram.WebApp.BackButton;
	BackButton.onClick(function() {
		if (!$('.modal').hasClass('show')) {
			BackButton.hide();
		} else {
			BackButton.show();
			var parent_page = $('.show').attr('data-parent');
			if (typeof parent_page==="undefined" || parent_page===undefined ||!parent_page) {
				closeAllModal();
				BackButton.hide();
			} else {
				closeAllModal();
				var modal = new bootstrap.Modal(document.getElementById(parent_page), {
					backdrop: false,
					keyboard: false			
				});
				modal.show();
			}
		}
	});
	
	$('.modal').on('shown.bs.modal', function(){		
		BackButton.show();
	});
	
	$('.modal').on('hide.bs.modal', function(){		
		BackButton.hide();
	});
	
	// Add storage data
	abcpLocalStorage(6);
	
	// ARP calc
	calcform();
	
	// Settings
	getSettings();

	// Input currency
	$("#ti-conv-1").on("input", function() {			
		var id = $('#dd_conv_1').attr("data-id");	
		convert(4, id);
		abcpLocalStorage(5);
	});
	
	$("#ti-conv-2").on("input", function() {
		var id = $('#dd_conv_2').attr("data-id");		
		convert(5, id);
		abcpLocalStorage(5);
	});
	
	$("#ti-conv-3").on("input", function() {
		var id = $('#dd_conv_3').attr("data-id");			
		convert(6, id);
		abcpLocalStorage(5);
	});
	
	// Search
	$("#search-conv").on("input", function() {
		jQuery("#search-conv").val(sanitizeStr(jQuery("#search-conv").val()));
		type_search = $("#search-conv").val();
		searchData(type_search);						
	});

	$(".currency_button").on("click", function() {
		var type_search = $(this).attr("data-id");
		searchData(type_search);
	});
	
	// Copy
	$(".copy_button").on("click", function() {
		copyValue(this);
	});
	
	// Radio language
	$(".lg-radio[name=select_lg]").on("click", function() {
		if ($(this).is(":checked")){
			var value = $(this).val();	
			$.get(page_url + '&lang=' + value, function($data) {
				location.href=page_url;
			});
		}
	});
	
	// Open / Close Modal page
	$("#lg-modal").on("click", function() {
		if (!jQuery("#lgModal").hasClass("show")) {
			closeAllModal();
			var modal = new bootstrap.Modal(document.getElementById("lgModal"), {
				backdrop: false,
				keyboard: false			
			});
			modal.show();
		}
	});
	
	$("#as-modal").on("click", function(event) {
		if (!jQuery("#asModal").hasClass("show")) {
			closeAllModal();
			getActiveButton(2);
			var modal = new bootstrap.Modal(document.getElementById("asModal"), {
				backdrop: false,
				keyboard: false			
			});
			modal.show();
		}
	});
	
	$("#fr-modal, .bot-banner").on("click", function() {
		if (!jQuery("#frModal").hasClass("show")) {
			closeAllModal();
			getActiveButton(3);
			var modal = new bootstrap.Modal(document.getElementById("frModal"), {
				backdrop: false,
				keyboard: false			
			});
			modal.show();
		}
	});
	
	$(document).delegate(".bybit_connect_button", "click", function(){
		if (bybitConnectedStatus==false) {
			if (!jQuery("#bybitModal").hasClass("show")) {
				closeAllModal();
				getActiveButton(2);
				var modal = new bootstrap.Modal(document.getElementById("bybitModal"), {
					backdrop: false,
					keyboard: false			
				});
				modal.show();
			}
		}
	});
	
	$("#ct-bybit-api-send").on("click", function() {
		bybitform();
	});	

	$(document).delegate(".okx_connect_button", "click", function(){
		if (okxConnectedStatus==false) {
			if (!jQuery("#okxModal").hasClass("show")) {
				closeAllModal();
				getActiveButton(2);
				var modal = new bootstrap.Modal(document.getElementById("okxModal"), {
					backdrop: false,
					keyboard: false			
				});
				modal.show();
			}
		}
	});
	
	$("#ct-okx-api-send").on("click", function() {
		okxform();
	});
	
	$(document).delegate(".sol_connect_button", "click", function(){
		if (solConnectedStatus==false) {
			if (!jQuery("#solModal").hasClass("show")) {
				closeAllModal();
				getActiveButton(2);
				var modal = new bootstrap.Modal(document.getElementById("solModal"), {
					backdrop: false,
					keyboard: false			
				});
				modal.show();
			}
		}
	});
	
	$("#ct-sol-api-send").on("click", function() {
		solform();
	});

	$(document).delegate(".sui_connect_button", "click", function(){
		if (suiConnectedStatus==false) {
			if (!jQuery("#suiModal").hasClass("show")) {
				closeAllModal();
				getActiveButton(2);
				var modal = new bootstrap.Modal(document.getElementById("suiModal"), {
					backdrop: false,
					keyboard: false			
				});
				modal.show();
			}
		}
	});
	
	$("#ct-sui-api-send").on("click", function() {
		suiform();
	});

	$("#target_actions-button").on("click", function() {
		if (!jQuery("#targetModal").hasClass("show")) {
			closeAllModal();
			getActiveButton(2);
			var modal = new bootstrap.Modal(document.getElementById("targetModal"), {
				backdrop: false,
				keyboard: false			
			});
			modal.show();
		}
	});
	
	$('#customRange1').on('input', function() {
		var num = formatValue(parseFloat($(this).val()), 1);
		if (typeof num==="undefined" || num===undefined || !num || isNaN(num)) {
			num = 1;
		}

		targetConvertValue(num);
		$('#ad-user-price').val(num);
	});
	
	$('#ad-user-price').on('input', function() {
		var num = $(this).val();
		num = num.replace(/[^\d\.]/g, '');
		$(this).val(num);
		num = parseFloat(num);
		num = formatValue(num,1);
		

		if (typeof num==="undefined" || num===undefined || isNaN(num)) {
			return false;
		}

		targetConvertValue(num);
		$('#customRange1').val(num);
	});
	
	$('#inputAmount, #inputPrice').on('input', function() {
		var num = $(this).val();
		num = num.replace(/[^\d\.]/g, '');
		$(this).val(num);
	});

	$("#ct-target-send").on("click", function() {
		targetform();
	});

	$("#st-modal").on("click", function() {
		if (!jQuery("#stModal").hasClass("show")) {
			closeAllModal();
			var modal = new bootstrap.Modal(document.getElementById("stModal"), {
				backdrop: false,
				keyboard: false			
			});
			modal.show();
		}
	});
	
	$("#close-all-modal").on("click", function() {			
		closeAllModal();
		getActiveButton(1);
		$("#convModal .option_item").attr("data-num", 0);
		
	});
	
	$("#convModal .option_item").on("click", function() {

		var id = $(this).attr("data-id");
		
		if (typeof globe_num!=="undefined" && globe_num!==undefined && globe_num) {
			globe_num = $("#convModal .option_item").attr("data-num");
		}
		
		convert(globe_num, id);
		
		abcpLocalStorage(3, id);
		abcpLocalStorage(5);

		var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById("convModal"));
		modal.hide();
	});	

	$(".currency_block").on("click", function() {
		if (!jQuery("#convModal").hasClass("show")) {
			closeAllModal();
			recentLoad();
			globe_num = $(this).attr("data-num");
			$("#convModal .option_item").attr("data-num", globe_num);

			var modal = new bootstrap.Modal(document.getElementById("convModal"), {
				backdrop: false,
				keyboard: false			
			});
			modal.show();
		}
	});

	$("#ct-calc-apr").on("input", function() {
		calcform();
	});

	$("#ct-calc-deposit").on("input", function() {
		calcform();
	});
	
	$("#ct-calc-days").on("input", function() {
		calcform();
	});
	
	$(document).delegate(".bybit_disconnect_button","click", function(){
		bybitdisconnect();
	});
	
	$(document).delegate(".ton_disconnect_button","click", function(){
		tondisconnect();
	});
	
	$(document).delegate(".okx_disconnect_button","click", function(){
		okxdisconnect();
	});
	
	$(document).delegate(".sol_disconnect_button","click", function(){
		soldisconnect();
	});
	
	$(document).delegate(".sui_disconnect_button","click", function(){
		suidisconnect();
	});
	
	$("#send-invite").on("click", function() {
		console.log('send invite');
	});
	
	$("#select-hsa").on("click", function() {
		if ($(this).is(":checked")){
			jQuery('.small_value').hide();
			abcpLocalStorage(9, 1);
		} else {
			jQuery('.small_value').show();
			abcpLocalStorage(9, 0);
		}
	});
	
	$('#asModal').delegate(".option_item", "click", function(){
		if (!jQuery("#adModal").hasClass("show")) {
			var symbol = $(this).attr('data-id');
			$("#adModal").attr('data-id', symbol);
			addDetailsCoin(symbol);
			closeAllModal();
			getActiveButton(2);
			var modal = new bootstrap.Modal(document.getElementById("adModal"), {
				backdrop: false,
				keyboard: false
			});
			modal.show();
		}
	});
});
