<?php
$this->registerJs('
	function getDataTelegram() {
		
		
		var data = Telegram.WebApp;
		
		jQuery.ajax({
			"url": "/v2/datas/userdata",
			"type": "post",
			"dataType": "json",
			"contentType": "application/json",
			"data": JSON.stringify(data),
			"success": function(response){
				
			},
			error: function(e) {
				addNotify(e, "error");
				jQuery("#bybit-connect-button").html(text_button);
				return false;
			}
		});	
	}

	//document ready
	jQuery(document).ready(function($) {
		//getDataTelegram();
		
		$("#asModal #verifyYour").on("click", function() {
			
			if (!jQuery("#authModal").hasClass("show")) {
				//closeAllModal();
				var modal = new bootstrap.Modal(document.getElementById("authModal"), {
					backdrop: true,
					keyboard: false			
				});
				modal.show();
			}
			
		});
		
		
		
		$("#authModal #fk-telegram-app").on("click", function() {		
			location.href = "tg://resolve?domain=finkeeper_app_bot&startapp=auth";		
		});
	});
', yii\web\View::POS_END);