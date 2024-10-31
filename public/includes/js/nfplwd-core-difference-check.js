jQuery(document).ready(function() {
				
	jQuery.ajax({
		type: 'POST',
		dataType: 'json',
		url: nfplwd_core_difference_check_object.nfplwd_core_difference_check_url,
		data: {
			'action': 'nfplwd_core_difference_notification',
			'nfplwd_core_difference_check_nonce': nfplwd_core_difference_check_object.nfplwd_core_difference_check_nonce,
		},
		
		//deal with success
		success:function(data){			
			
			if(data === true){
			
				console.log("integrity core check completed successfully");
				
			} else {
				
				//console.log("no integrity core check performed");
				
			}
			
		},
		
		error: function(errorThrown){
			
			console.log("integrity core check failed");
		
		}
		
	});
		
});