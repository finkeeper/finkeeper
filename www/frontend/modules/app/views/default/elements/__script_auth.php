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

	function handleCredentialResponse(response) {
		
		const modalBody = document.querySelector("#googleModal .modal-body");
		
		modalBody.innerHTML = "<div class=\"spinner-border text-primary\" role=\"status\"><span class=\"visually-hidden\">Loading...</span></div>";

		if (typeof response==="undefined" || response===undefined || !response) {
			modalBody.innerHTML = "<div class=\"alert alert-danger\">'.Yii::t('Error', 'Missing response Google service').'</div>";
		}
		
		if (typeof response.credential==="undefined" || response===undefined || !response.credential) {
			modalBody.innerHTML = "<div class=\"alert alert-danger\">'.Yii::t('Error', 'Missing response Google service').'</div>";
		}

		fetch("/app/serviceauth", {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify({token: response.credential})
		})
		.then(response => response.json())
		.then(data => {

			if (!data.error) {
				var url = "https://finkeeper.pro/loginsecure?sc=" + data.token;
				location.href = url;
			} else {
				modalBody.innerHTML = "<div class=\"alert alert-danger\">" + data.message + "</div>";
			}
		})
		.catch(error => {
			modalBody.innerHTML = "<div class=\"alert alert-danger\">" + error.message + "</div>";
			console.error("Auth error:", error);
		});
	}

	//document ready
	jQuery(document).ready(function($) {
		$("#asModal #verifyYour").on("click", function() {
			if (!jQuery("#authModal").hasClass("show")) {
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
		
		$("#authModal #fk-google-oauth").on("click", function() {
			if (!jQuery("#googleModal").hasClass("show")) {
				closeAllModal();
				var modal = new bootstrap.Modal(document.getElementById("googleModal"), {
					backdrop: true,
					keyboard: false			
				});
				modal.show();

				
			}
		});
	});
', yii\web\View::POS_END);