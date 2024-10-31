<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

if(!function_exists('nfplwd_hide_usernames_api')){

	function nfplwd_hide_usernames_api($nfplwd_rest_endpoints) {

		//get options 
		global $nfproot_current_language_settings;

		//if hide usernames and hide authors archive are enabled
		if(

			!empty($nfproot_current_language_settings['nfplwd']['nfproot_hide_usernames'])
			&& $nfproot_current_language_settings['nfplwd']['nfproot_hide_usernames'] === '1'
			&& !empty($nfproot_current_language_settings['nfplwd']['nfproot_hide_usernames_api'])
			&& $nfproot_current_language_settings['nfplwd']['nfproot_hide_usernames_api'] === '1'
								
		) {
			
			if(
			
				!empty($nfplwd_rest_endpoints['/wp/v2/users']) 
				&& !is_user_logged_in()
				
			) {
				
				unset($nfplwd_rest_endpoints['/wp/v2/users']);
				
			}
			
			if(
			
				!empty($nfplwd_rest_endpoints['/wp/v2/users/(?P<id>[\d]+)'])
				&& !is_user_logged_in()
				
			){
				
				unset($nfplwd_rest_endpoints['/wp/v2/users/(?P<id>[\d]+)']);
				
			}			

		} 
			
		
		return $nfplwd_rest_endpoints;	

	}

} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfplwd_hide_usernames_api" already exists');
	
}