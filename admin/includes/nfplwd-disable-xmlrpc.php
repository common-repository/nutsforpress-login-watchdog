<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

if(!function_exists('nfplwd_disable_xmlrpc')){

	function nfplwd_disable_xmlrpc() {

		//get options 
		global $nfproot_current_language_settings;

		//if disable xmlrps enabled
		if(

			!empty($nfproot_current_language_settings['nfplwd']['nfproot_disable_xmlrpc'])
			&& $nfproot_current_language_settings['nfplwd']['nfproot_disable_xmlrpc'] === '1'
								
		) {
			
			if(!is_user_logged_in()) {
			
				add_filter('xmlrpc_enabled', '__return_false');
				
			}
			
		}

	}

} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfplwd_disable_xmlrpc" already exists');
	
}