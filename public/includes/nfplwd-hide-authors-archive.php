<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

if(!function_exists('nfplwd_hide_authors_archive')){

	function nfplwd_hide_authors_archive() {

		//get options 
		global $nfproot_current_language_settings;

		//if hide usernames and hide authors archive are enabled
		if(

			!empty($nfproot_current_language_settings['nfplwd']['nfproot_hide_usernames'])
			&& $nfproot_current_language_settings['nfplwd']['nfproot_hide_usernames'] === '1'
			&& !empty($nfproot_current_language_settings['nfplwd']['nfproot_hide_authors_archive'])
			&& $nfproot_current_language_settings['nfplwd']['nfproot_hide_authors_archive'] === '1'
								
		) {
			
			if(!empty($_GET['author'])) {
				
				wp_safe_redirect(home_url());
				exit;
				
			}
			
			//if is author page, redirect to home url
			/*if(is_author()){
				
				wp_safe_redirect(home_url());
				exit;

			}*/			

		}			

	}

} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfplwd_hide_authors_archive" already exists');
	
}