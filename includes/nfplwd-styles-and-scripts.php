<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

//STYLES AND SCRIPTS

//admin styles
if(!function_exists('nfplwd_styles_and_scripts')){
	
	function nfplwd_styles_and_scripts() {

		//script for option page		
		//wp_enqueue_script('nfplwd-option', NFPLWD_BASE_URL.'admin/js/nfplwd-option.js', array('jquery'), '', true );	
		
	}
			
} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfplwd_styles_and_scripts" already exists');
	
}