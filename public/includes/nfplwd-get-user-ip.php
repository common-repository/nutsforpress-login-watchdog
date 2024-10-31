<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

//get the IP of the user trying to authenticate
if(!function_exists('nfplwd_get_user_ip')){
	
	function nfplwd_get_user_ip() {

		if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
			
			return $_SERVER['HTTP_CLIENT_IP'];
			
		} 
		
		else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
			
		} 
			
		else if(!empty($_SERVER['REMOTE_ADDR'])) {
			
			return $_SERVER['REMOTE_ADDR'];
			
		} else {
			
			return false;
			
		}
		
	}
	
} else {
	
	error_log('function: "nfplwd_get_user_ip" already exists');
	
}