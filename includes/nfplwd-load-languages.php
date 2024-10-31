<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

//LOAD LANGUAGES

//load languages functions
if(!function_exists('nfplwd_load_languages')){

	function nfplwd_load_languages($nfplwd_mo_file, $nfplwd_text_domain) {

		if(
		
			$nfplwd_text_domain === 'nutsforpress-login-watchdog'
			&& strpos($nfplwd_mo_file, WP_LANG_DIR.'/plugins/') !== false
			
		){
				
			$nfplwd_locale = apply_filters('plugin_locale', determine_locale(), $nfplwd_text_domain);
			$nfplwd_mo_file = NFPLWD_BASE_PATH.'languages/'.$nfplwd_text_domain.'-'.$nfplwd_locale.'.mo';
			
		}
		
		return $nfplwd_mo_file;

	}
	
} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfplwd_load_languages" already exists');
	
}