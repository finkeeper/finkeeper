function warningAlert(message) {
	$('#cf-modal-load').find('.modal-content').addClass('alert alert-warning');
	$('#cf-modal-load').find('.modal-title').html('<i class="fa fa-exclamation-triangle"></i>&nbsp;Warning');
	$('#cf-modal-load').find('.modal-body').html(message);
	$('#cf-modal-load').toggle();
}

function successAlert(message) {
	$('#cf-modal-load').find('.modal-content').addClass('alert alert-success');
	$('#cf-modal-load').find('.modal-title').html('<i class="fa fa-check-circle"></i>&nbsp;Success');
	$('#cf-modal-load').find('.modal-body').html(message);
	$('#cf-modal-load').toggle();
}

function uploadFile(file, type) {
				
	if (typeof file==="undefined" || file===undefined || !file) {
		return false;
	}	

	var formData = new FormData();		
	formData.append('file', file);
	formData.append('type', type);

	$.ajax({
		url: '/profile/upload', 
		type: 'POST',
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		xhr: function () {  
			var myXhr = $.ajaxSettings.xhr();
			if (myXhr.upload) {
				//myXhr.upload.addEventListener('progress', progressHandlingFunction, false); 	
			}
			return myXhr;
		},
		beforeSend: function () {
				
		},
		success: function (response) {

			if (response) {
				
				try {
					data = JSON.parse(response);
					if (data.error) {

						warningAlert(data.message);
						
					}
						
				} catch(e) {
						
					warningAlert(e);
				}
				
			} else {

				warningAlert('Server not responding');

			}
		},
		error: function (err) {
			warningAlert(err)
		},
	});
}