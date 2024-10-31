<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

if(!function_exists('nfplwd_core_difference_notification')){

	function nfplwd_core_difference_notification(){
				
		//run only if the last check was performed one day ago
		$nfplwd_one_day_ago = current_time('timestamp') - 86400;
		$nfplwd_last_file_check = get_option('_nfplwd_last_core_check');
			
		if(
		
			empty($nfplwd_last_file_check)
			|| $nfplwd_last_file_check['time'] <= $nfplwd_one_day_ago
			
		){		
		
			//check nonce (if fails, dies)
			check_ajax_referer('nfplwd-core-difference-check-nonce', 'nfplwd_core_difference_check_nonce');
		
			//get options 
			global $nfproot_current_language_settings;
				
			$nfplwd_suspect_file_notification_address = sanitize_email($nfproot_current_language_settings['nfplwd']['nfproot_notification_address']);
			
			$nfplwd_send_notification = false;
			
			//get local package
			global $wp_local_package;
			
			//get the installed version
			global $wp_version;
			
			//if local package is not set, force it to US
			if(empty($wp_local_package)){
				
				$wp_local_package = 'en_US';
				
			} 
			
			$nfplwd_last_file_check_result = __('not completed, an error occurred','nutsforpress-login-watchdog');
			
			$nfplwd_checksum_api_address = wp_http_validate_url('https://api.wordpress.org/core/checksums/1.0/?version='.$wp_version.'&locale='. $wp_local_package);
			
			if($nfplwd_checksum_api_address){
			
				$nfplwd_checksum_json = file_get_contents($nfplwd_checksum_api_address);
				
				//on some server the file_get_contents function may fall
				if(!$nfplwd_checksum_json){
				  
					if(function_exists('curl_init')){ 
					  
						$nfplwd_crul_instance = curl_init();
						curl_setopt($nfplwd_crul_instance, CURLOPT_URL, $nfplwd_checksum_api_address);
						curl_setopt($nfplwd_crul_instance, CURLOPT_RETURNTRANSFER, true);
						$nfplwd_checksum_json = curl_exec($nfplwd_crul_instance);
						curl_close($nfplwd_crul_instance);                  
					
					}

				}				
				
				$nfplwd_checksum_array = json_decode($nfplwd_checksum_json, true);

				if(json_last_error() !== JSON_ERROR_NONE){

					//send mail to inform about core file check fail
					$nfplwd_suspect_file_notification_subject = __('Error on security check','nutsforpress-login-watchdog').' '.get_bloginfo('name');
					$nfplwd_suspect_file_notification_body = '<html><body><p>'.__('The daily security check failed on','nutsforpress-login-watchdog').' '.get_bloginfo('name').' ('.get_bloginfo('url').')</p></body></html>';
					
				} else {
					
					$nfplwd_wordpress_core_files = $nfplwd_checksum_array['checksums'];
					
					if(empty($nfplwd_wordpress_core_files)){
						
						$nfplwd_suspect_file_notification_subject = __('Error on security check','nutsforpress-login-watchdog').' '.get_bloginfo('name');
						$nfplwd_suspect_file_notification_body = '<html><body><p>'.__('The daily security check failed on','nutsforpress-login-watchdog').' '.get_bloginfo('name').' ('.get_bloginfo('url').')</p></body></html>';					
						
					} else {
												
						//get the root file list, excluding subfolders
						$nfplwd_this_website_files = list_files(ABSPATH, 1);						

						//define the main WordPress directories
						$nfplwd_main_wordpress_directories = array(
						
							ABSPATH.'wp-admin/',
							ABSPATH.'wp-content/',
							ABSPATH.'wp-includes/',
						
						);
												
						//loop into the main WordPress directories
						foreach($nfplwd_main_wordpress_directories as $nfplwd_main_wordpress_directory){
							
							$nfplwd_current_wordpress_directory_files = list_files($nfplwd_main_wordpress_directory);
							
							$nfplwd_this_website_files = array_merge($nfplwd_this_website_files, $nfplwd_current_wordpress_directory_files);
							
						}
												
						//introduce variables used further on
						$nfplwd_different_from_the_core = array();
						$nfplwd_different_from_the_core_with_hidden_php = array();
						$nfplwd_not_in_the_core = array();
						$nfplwd_not_in_the_core_with_hidden_php = array();						
						
						//define the suspetc extensions
						$nfplwd_suspect_extensions = array(
						
							'php',
							'js',
						
						);						

						//these directories and their subdirectories have to be skipped, if the .js or .php files contained in it do not match the checksum
						$nfplwd_core_directories_to_skip = array(
						
							//skip these ones since the version included with the core can be different from the version installed (if plugin or theme has been updated)
							ABSPATH.'wp-content/themes/',
							ABSPATH.'wp-content/plugins/akismet/',
							ABSPATH.'wp-content/plugins/hello-dolly/',
						
						);

						$nfplwd_file_extension = false;
						
						//loop into this website files
						foreach($nfplwd_this_website_files as $nfplwd_this_website_file){
							
							$nfplwd_file_extension = pathinfo($nfplwd_this_website_file, PATHINFO_EXTENSION);
							
							//remove the ABSPATH from the current website file, to compare it with the key of the array obtained from api.wordpress.org
							$nfplwd_wordpress_core_file_key = str_replace(ABSPATH, '', $nfplwd_this_website_file);
							
							//if file is found, checksum should be compared
							if(array_key_exists($nfplwd_wordpress_core_file_key, $nfplwd_wordpress_core_files)){
								
								//get the core file checksum from the object obtained from api.wordpress.org
								$nfplwd_wordpress_core_file_checksum = $nfplwd_wordpress_core_files[$nfplwd_wordpress_core_file_key];
								
								//get this website checkusm
								$nfplwd_this_website_file_checksum = md5_file($nfplwd_this_website_file);
								
								//if the two checksums do not match
								if($nfplwd_wordpress_core_file_checksum !== $nfplwd_this_website_file_checksum){
									
									/*some default file does not match the checksum: if they are .js or .php and they do not belong to one of the directory to skip, considered them as suspect;
									otherwise consider them as suspect only if they have different extensions and if they contains the php opening tag*/

									//define not to skip the current file
									$nfplwd_this_website_file_skip = false;
									
									//loop into core directories to skip
									foreach($nfplwd_core_directories_to_skip as $nfplwd_core_directory_to_skip){
									
										//if file is into one of the directory to be skipped
										if(substr($nfplwd_this_website_file, 0, strlen($nfplwd_core_directory_to_skip)) !== $nfplwd_core_directory_to_skip){
											
											$nfplwd_this_website_file_skip = true;
										
										} 
										
									}
									
									if($nfplwd_this_website_file_skip === false){
									
										if(in_array($nfplwd_file_extension, $nfplwd_suspect_extensions)){
										
											//add to suspect file array
											$nfplwd_different_from_the_core[] = $nfplwd_this_website_file. '('.$nfplwd_this_website_file.' - '.$nfplwd_core_directory_to_skip.')';										
										
										} else {

											//get the file content
											$nfplwd_this_website_file_content = file_get_contents($nfplwd_this_website_file);
																		
											//check if contains php openin tags
											if(strpos($nfplwd_this_website_file_content, '<?php') !== false) {
												
												//add to suspect file array
												$nfplwd_different_from_the_core_with_hidden_php[] = $nfplwd_this_website_file;
											
											}	

										}											
										
									}

								} 
								
							//deal with files not included with the core
							} else {
																								
								//the content (not the subdirectories) of these directories have to be excluded from the check
								$nfplwd_directories_to_skip = array(
								
									ABSPATH.'wp-content/',
								
								);		

								//the content of all the directories wich are child of these directories have to be excluded from the check
								$nfplwd_subdirectories_to_skip = array(
								
									ABSPATH.'wp-content/',
								
								);								
								
								//these files have to be excluded from the check
								$nfplwd_files_to_skip = array(
								
									ABSPATH.'wp-content/advanced-cache.php',
								
								);			
																
								//deal only with files outside the root
								if(dirname($nfplwd_this_website_file).'/' !==  ABSPATH){
									
									//deal only with files and exclude directories
									if(!is_dir($nfplwd_this_website_file)){
										
										//define not to skip the current file
										$nfplwd_this_website_file_skip = false;

										//loop into directories to skip
										foreach($nfplwd_directories_to_skip as $nfplwd_directory_to_skip){
																	
											//if the current website file is in a directory to skip 
											if(
											
												substr($nfplwd_this_website_file, 0, strlen($nfplwd_directory_to_skip)) === $nfplwd_directory_to_skip
												&& dirname($nfplwd_this_website_file).'/' === $nfplwd_directory_to_skip
												
											){
												
												//define to skip the current file
												$nfplwd_this_website_file_skip = true;
												
											} 
											
										}	

										//loop into subdirectories to skip
										foreach($nfplwd_subdirectories_to_skip as $nfplwd_subdirectory_to_skip){
																	
											//if the current website file is in a child directory of the current subdirectory to skip 
											if(
											
												substr($nfplwd_this_website_file, 0, strlen($nfplwd_subdirectory_to_skip)) === $nfplwd_subdirectory_to_skip
												&& dirname($nfplwd_this_website_file).'/' !== $nfplwd_subdirectory_to_skip
												
											){
												
												//define to skip the current file
												$nfplwd_this_website_file_skip = true;
												
											} 
											
										}

										//if the current website file is included into the files to skip
										if(in_array($nfplwd_this_website_file, $nfplwd_files_to_skip)){

											//define to skip the current file
											$nfplwd_this_website_file_skip = true;								
											
										}
										
										//deal with files not to be skipped
										if($nfplwd_this_website_file_skip === false){							
											
											/*investigate files not to be skipped: if they are .js or .php, considered them as suspect,
											otherwise consider them as suspect only if they contains the php opening tag*/
											
											//if file has a suspect extension
											if(in_array($nfplwd_file_extension, $nfplwd_suspect_extensions)){
										
												//add to suspect file array
												$nfplwd_not_in_the_core[] = $nfplwd_this_website_file;
												
											//file is not a php or a js, check if it contains the php opening tag
											} else {
												
												//get the file content
												$nfplwd_this_website_file_content = file_get_contents($nfplwd_this_website_file);
																			
												//check if contains php openin tags
												if(strpos($nfplwd_this_website_file_content, '<?php') !== false) {
													
													//add to suspect file array
													$nfplwd_not_in_the_core_with_hidden_php[] = $nfplwd_this_website_file;
												
												}							
												
											}								

										}
										
									}

								}
				
							}
							
						}						
						
					}
					
					if(
					
						!empty($nfplwd_different_from_the_core)
						|| !empty($nfplwd_different_from_the_core_with_hidden_php)
						|| !empty($nfplwd_not_in_the_core)
						|| !empty($nfplwd_not_in_the_core_with_hidden_php)
						
					){
						
						$nfplwd_send_notification = true;
						
						$nfplwd_last_file_check_result = __('some suspect files found, please investigate','nutsforpress-login-watchdog');
												
						//introduce variables used further on 
						$nfplwd_suspect_file_list = false;
						
						if(!empty($nfplwd_different_from_the_core)){
							
							$nfplwd_suspect_file_list .= '<ul><strong>'.__('These files are different from the ones included in the original WordPress package', 'nutsforpress-login-watchdog').'</strong>:';
							$nfplwd_suspect_file_list .= '<ul>';
							
							foreach($nfplwd_different_from_the_core as $nfplwd_different_from_the_core_file){
								
								$nfplwd_suspect_file_list .= '<li>'.str_replace(ABSPATH, get_bloginfo('url').'/', $nfplwd_different_from_the_core_file).'</li>';
								
							}
							
							$nfplwd_suspect_file_list .= '</ul></ul>';
							
						}	

						if(!empty($nfplwd_different_from_the_core_with_hidden_php)){
							
							$nfplwd_suspect_file_list .= '<ul><strong>'.__('These files are different from the ones included in the original WordPress package', 'nutsforpress-login-watchdog').' '.__('and furthermore they hide some unexpected PHP code', 'nutsforpress-login-watchdog').'</strong>:';
							$nfplwd_suspect_file_list .= '<ul>';
							
							foreach($nfplwd_different_from_the_core_with_hidden_php as $nfplwd_different_from_the_core_with_hidden_php_file){
								
								$nfplwd_suspect_file_list .= '<li>'.str_replace(ABSPATH, get_bloginfo('url').'/', $nfplwd_different_from_the_core_with_hidden_php_file).'</li>';
								
							}
							
							$nfplwd_suspect_file_list .= '</ul></ul>';
							
						}		

						if(!empty($nfplwd_not_in_the_core_with_hidden_php)){
							
							$nfplwd_suspect_file_list .= '<ul><strong>'.__('These files, not a part of the original WordPress package', 'nutsforpress-login-watchdog').', '.__('hide some unexpected PHP code', 'nutsforpress-login-watchdog').'</strong>:';
							$nfplwd_suspect_file_list .= '<ul>';
							
							foreach($nfplwd_not_in_the_core_with_hidden_php as $nfplwd_not_in_the_core_with_hidden_php_file){
								
								$nfplwd_suspect_file_list .= '<li>'.str_replace(ABSPATH, get_bloginfo('url').'/', $nfplwd_not_in_the_core_with_hidden_php_file).'</li>';
								
							}
							
							$nfplwd_suspect_file_list .= '</ul></ul>';
							
						}						

						if(!empty($nfplwd_not_in_the_core)){
							
							$nfplwd_suspect_file_list .= '<ul><strong>'.__('These files are not a part of the original WordPress package', 'nutsforpress-login-watchdog').'</strong> ('.__('some of them may turn out to be false positives', 'nutsforpress-login-watchdog').'):';
							$nfplwd_suspect_file_list .= '<ul>';
							
							foreach($nfplwd_not_in_the_core as $nfplwd_not_in_the_core_file){
								
								$nfplwd_suspect_file_list .= '<li>'.str_replace(ABSPATH, get_bloginfo('url').'/', $nfplwd_not_in_the_core_file).'</li>';
								
							}
							
							$nfplwd_suspect_file_list .= '</ul></ul>';
							
						}

						//send mail to inform about core files changed 
						$nfplwd_suspect_file_notification_subject = __('Suspect files on','nutsforpress-login-watchdog').' '.get_bloginfo('name');
						$nfplwd_suspect_file_notification_body = '<html><body><p>'.__('The daily security check on','nutsforpress-login-watchdog').' '.get_bloginfo('name').' ('.get_bloginfo('url').') '.__('has find that the following files are to be considered as suspect for the following reasons','nutsforpress-login-watchdog').'.</p><p>'.__('It is very likely that this list is not complete, since some primary folders are not inspected at all (wp-content, just to name one); therefore this e-mail message is intended to be an alert for an existing infection, which should be adequately investigated with appropriate tools','nutsforpress-login-watchdog').'.</p><p>'.$nfplwd_suspect_file_list.'</p></body></html>';
						
					} else {
						
						$nfplwd_last_file_check_result = __('no suspect files found, so far so good','nutsforpress-login-watchdog');
						
						if(get_option('_nfplwd_manually_core_check') !== false){
							
							$nfplwd_send_notification = true;
							
							delete_option('_nfplwd_manually_core_check');

							//send mail to inform about no suspect files found 
							$nfplwd_suspect_file_notification_subject = __('No suspect files on','nutsforpress-login-watchdog').' '.get_bloginfo('name');
							$nfplwd_suspect_file_notification_body = '<html><body><p>'.__('The manually security check on','nutsforpress-login-watchdog').' '.get_bloginfo('name').' ('.get_bloginfo('url').') '.__('has find that no files are to be considered as suspect','nutsforpress-login-watchdog').'.</p></body></html>';
							
						}
						
					}				
					
				}	

				if(
				
					isset($nfplwd_send_notification) 
					&& $nfplwd_send_notification === true
					&& is_email($nfplwd_suspect_file_notification_address)
					
				){
					
					$nfplwd_suspect_file_notification_headers = array('Content-Type: text/html; charset=UTF-8');

					wp_mail(
					
						$nfplwd_suspect_file_notification_address, 
						$nfplwd_suspect_file_notification_subject, 
						$nfplwd_suspect_file_notification_body, 
						$nfplwd_suspect_file_notification_headers
						
					);	

				}	
				
			}
			
			//update check
			$nfplwd_last_file_check = array(
			
				'time' => current_time('timestamp'),
				'result' => $nfplwd_last_file_check_result
			
			);
			
			update_option('_nfplwd_last_core_check', $nfplwd_last_file_check);
			
			echo json_encode(true);
					
		} else { 
		
			echo json_encode(false);
			
		}
		
		wp_die();

	}

} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfplwd_core_difference_notification" already exists');
	
}