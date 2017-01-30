<?php

if (!defined('UPDRAFTPLUS_DIR')) die('No access.');

if (!class_exists('UpdraftCentral_Commands')) require_once('commands.php');
if (!class_exists('UpdraftPlus_Admin')) require_once(plugin_dir_path(__FILE__) . '..\admin.php');


class UpdraftCentral_Advanced_Commands extends UpdraftCentral_Commands {
	
	public function get_advanced_settings(){
		
		global $updraftplus_admin;
		global $updraftplus;
		
		if (!is_a($updraftplus_admin, 'UpdraftPlus_Admin')) $updraftplus_admin = new UpdraftPlus_Admin();
		if (!is_a($updraftplus, 'UpdraftPlus')) $updraftplus = new UpdraftPlus();
		
		
		$updraft_dir = $updraftplus->backups_dir_location();
		$backup_disabled = ($updraftplus->really_is_writable($updraft_dir)) ? '' : 'disabled="disabled"';
		$html = $updraftplus_admin->get_settings_expertsettings($backup_disabled);
		
		
		return $this->_response($html);
	}
	
	//DONE
	public function php_info(){
		ob_start();
		phpinfo();
		$result = ob_get_clean();
		return $this->_response($result);
	}
	
	//DONE
	public function http_get($uri){
		if (empty($uri)) {
			return $this->_generic_error_response("error", "no_uri");
		}
		
		$response = wp_remote_get($uri, array(
			'timeout' => 50,
			'httpversion' => '1.1',
		));
		if (is_wp_error($response)) {
			return $this->_generic_error_response("error", htmlspecialchars($response->get_error_message()));
		}
		
		return $this->_response(
			array(
				'status' => wp_remote_retrieve_response_code($response),
				'response' => htmlspecialchars(substr(wp_remote_retrieve_body($response), 0, 2048))
			)
		);
	}
	
	//DONE
	public function http_get_curl($uri){
		if (empty($uri)) {
			return $this->_generic_error_response("error", "no_uri");
		}
		
		if (!function_exists('curl_exec')) {
			return $this->_generic_error_response("error", "no_curl");
		}
		
		$ch = curl_init($uri);
		
		curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FAILONERROR => true,
			CURLOPT_HEADER => false,
			CURLOPT_VERBOSE => true,
			CURLOPT_STDERR => $output = fopen('php://temp', "w+")
		));
		
		$response = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$error = curl_error($ch);
		curl_close($ch);
		
		rewind($output);
		$verb = stream_get_contents($output);
		
		if($response === false){
			return $this->_generic_error_response("error", array(
				"error" => htmlspecialchars($error),
				"status" => $status,
				"log" => htmlspecialchars($verb)

			));
		}
		
		return $this->_response(array(
			"response"=> htmlspecialchars(substr($response, 0, 2048)),
			"status"=> $status,
			"log"=> htmlspecialchars($verb)
		));
	}
	
	//DONE
	public function call_wordpress_action($data){
		
		$log = "";
		
		if(empty($data["wp_action"])){
			return $this->_generic_error_response("error", "no command sent");
		}
		
		
		$wp_action = $data["wp_action"];
		//Check if action has arguments
		if(preg_match('/^([^:]+)+:(.*)$/', stripslashes($wp_action), $matches)){
			$log .= "Arguments given \n";
			
			$action = $matches[1];
			$args = json_decode($matches[2], true);
			
			if($args === null){
				return $this-> _generic_error_response("error", "arguments_not_json");
			}else{
				ob_start();
				$returned = do_action_ref_array($action, $args);
				$output = ob_get_clean();
			}
		}else{
			$log .= "No arguments given \n" . $wp_action . "\n";
			//No arguments
			ob_start();
			echo do_action($wp_action);
			$output = ob_get_clean();
		}
		
		return $this->_response(array(
			"output" => esc_html($output),
			"returned" => esc_html($returned),
			"log" => $log
		));
	}
	
	//Done
	public function reset_site_id(){
		
		global $updraftplus;
		delete_site_option('updraftplus-addons_siteid');
		return $this->_response($updraftplus->siteid());
	}
	
	//Done
	public function show_raw_backup_and_file_list(){
		
		global $updraftplus;
		
		$response = array();
		
		ob_start();
			var_dump($updraftplus->get_backup_history());
		$response["backups"] = ob_get_clean(); 
		
		
		//echo '<h3 id="ud-debuginfo-files">Files</h3><pre>';
		$updraft_dir = $updraftplus->backups_dir_location();
		$raw_output = array();
		$d = dir($updraft_dir);
		while (false !== ($entry = $d->read())) {
			$fp = $updraft_dir.'/'.$entry;
			$mtime = filemtime($fp);
			if (is_dir($fp)) {
				$size = '       d';
			} elseif (is_link($fp)) {
				$size = '       l';
			} elseif (is_file($fp)) {
				$size = sprintf("%8.1f", round(filesize($fp)/1024, 1)).' '.gmdate('r', $mtime);
			} else {
				$size = '       ?';
			}
			if (preg_match('/^log\.(.*)\.txt$/', $entry, $lmatch)) $entry = '<a target="_top" href="?action=downloadlog&page=updraftplus&updraftplus_backup_nonce='.htmlspecialchars($lmatch[1]).'">'.$entry.'</a>';
			
			
						
			array_push($raw_output, sprintf("%s %s\n", $size, $entry) );
		}
		@$d->close();
		//krsort($raw_output, SORT_NUMERIC);
		$response["files"] = $raw_output; 
		
		$opts = $updraftplus->get_settings_keys();
		asort($opts);
		$options = array();
		foreach ($opts as $opt) {	
			$options[htmlspecialchars($opt)] = htmlspecialchars(print_r(UpdraftPlus_Options::get_updraft_option($opt), true));
		}
		$response["options"] = $options;
		
		ob_start();
		do_action('updraftplus_showrawinfo');
		$response["url"] = ob_get_clean();
		
		return $this->_response($response);
	}
	
	//Done
	private function _get_opts(){
		$options = UpdraftPlus_Options::get_updraft_option('updraft_adminlocking');
		
		if (!is_array($options)) $options = array();
		if (!isset($options['password'])) $options['password'] = '';
		if (!isset($options['session_length'])) $options['session_length'] = 3600;
		if (!isset($options['support_url'])) $options['support_url'] = '';
		
		return $options;
	}
	
	//Done
	public function change_lock_settings($data){
		
		$session_length = $data["session_length"];
		$password = $data["password"];
		$old_password = $data["old_password"];
		$support_url = $data["support_url"];
		
		if(	empty($session_length) && empty($password)){
			return $this->_generic_error_response("no_password_or_session_set");
		};
		
		if(	empty($session_length)){
			return $this->_generic_error_response("no_session_set");
		};
		
		if(	empty($password)){
			//return $this->_generic_error_response("no_password_set");
			$password = '';
		};
		
		if(	empty($old_password)){
			//return $this->_generic_error_response("no_old_password");
			$old_password = '';
		};
		
		$user = wp_get_current_user();
		if(!is_a($user, 'WP_User')){
			return $this->_generic_error_response("no_user_found");
		};
		
		
		$options = $this->_get_opts();
		if($old_password == $options['password']) {
			
			$options['password'] = (string)$password;
			$options['support_url'] = (string)$support_url;
			$options['session_length'] = (int)$session_length;
			UpdraftPlus_Options::update_updraft_option('updraft_adminlocking', $options);
						
			return $this->_response("lock_changed");
		} else {
			return $this->_generic_error_response("error","wrong_old_password");
		}
	}
	
	//Done
	public function delete_key($key_id){
		
		global $updraftplus_updraftcentral_main;
		if (!is_a($updraftplus_updraftcentral_main, 'UpdraftPlus_UpdraftCentral_Main')) {
			return $this->_generic_error_response("error", 'UpdraftPlus_UpdraftCentral_Main object not found');
		}
		
		$response = $updraftplus_updraftcentral_main->delete_key($key_id);
		return $this->_response($response);
		
	}
	
	//Done
	public function create_key($data){
		
		global $updraftplus_updraftcentral_main;
		if (!is_a($updraftplus_updraftcentral_main, 'UpdraftPlus_UpdraftCentral_Main')) {
			return $this->_generic_error_response("error", 'UpdraftPlus_UpdraftCentral_Main object not found');
		}
		
		$response = call_user_func(array($updraftplus_updraftcentral_main, "create_key"), $data);
		
		return $this->_response($response);
	}
	
	//Done
	public function fetch_log($data){
		
		global $updraftplus_updraftcentral_main;
		if (!is_a($updraftplus_updraftcentral_main, 'UpdraftPlus_UpdraftCentral_Main')) {
			return $this->_generic_error_response('error', 'UpdraftPlus_UpdraftCentral_Main object not found');
		}
		
		$response = call_user_func(array($updraftplus_updraftcentral_main, "get_log"), $data);
		return $this->_response($response);
	}
	
	//
	private function _updraftplus_restore_db_pre() {
		$this->saved_site_id = $this->siteid();
	}
	
	//Done
	public function search_replace($query){
		$response = array();
		global $updraftplus_addons_migrator;
		
		if(!class_exists("UpdraftPlus_Addons_Migrator")){
			return $this->_generic_error_response("error","no_clas_found");
		}
		
		if(!is_a( $updraftplus_addons_migrator, "UpdraftPlus_Addons_Migrator")){
			return $this->_generic_error_response("error","no_object_found");
		}
		
		$_POST = $query;
		
		ob_start();
		
		do_action("updraftplus_adminaction_searchreplace");
		
		$response["log"] = ob_get_clean();
		
		return $this->_response($response);
	}
	
	//Done
	public function count($entity){
		
		global $updraftplus;
		if ($entity == 'updraft') {
			
			$response = $this->_recursive_directory_size($updraftplus->backups_dir_location());
			return $this->_response($response);
		} else {
			$backupable_entities = $updraftplus->get_backupable_file_entities(true, false);
			if ($entity == 'all') {
				$total_size = 0;
				foreach ($backupable_entities as $entity => $data) {
					# Might be an array
					$basedir = $backupable_entities[$entity];
					$dirs = apply_filters('updraftplus_dirlist_'.$entity, $basedir);
					$size = $this->_recursive_directory_size($dirs, $updraftplus->get_exclude($entity), $basedir, 'numeric');
					if (is_numeric($size) && $size>0) $total_size += $size;
				}
				
				$response = $updraftplus->convert_numeric_size_to_text($total_size);
				return $this->_response($response);
				
			} elseif (!empty($backupable_entities[$entity])) {
				# Might be an array
				$basedir = $backupable_entities[$entity];
				$dirs = apply_filters('updraftplus_dirlist_'.$entity, $basedir);
				
				$response = $this->_recursive_directory_size($dirs, $updraftplus->get_exclude($entity), $basedir);
				return $this->_response($response);
			}
		}
		return $this->_response('error');
		
	}
	
	//
	private function _recursive_directory_size($directorieses, $exclude = array(), $basedirs = '', $format='text') {
		# If $basedirs is passed as an array, then $directorieses must be too

		$size = 0;

		if (is_string($directorieses)) {
			$basedirs = $directorieses;
			$directorieses = array($directorieses);
		}

		if (is_string($basedirs)) $basedirs = array($basedirs);

		foreach ($directorieses as $ind => $directories) {
			if (!is_array($directories)) $directories=array($directories);

			$basedir = empty($basedirs[$ind]) ? $basedirs[0] : $basedirs[$ind];

			foreach ($directories as $dir) {
				if (is_file($dir)) {
					$size += @filesize($dir);
				} else {
					$suffix = ('' != $basedir) ? ((0 === strpos($dir, $basedir.'/')) ? substr($dir, 1+strlen($basedir)) : '') : '';
					$size += $this->_recursive_directory_size_raw($basedir, $exclude, $suffix);
				}
			}

		}

		if ('numeric' == $format) return $size;

		global $updraftplus;
		return $updraftplus->convert_numeric_size_to_text($size);

	}
	
	//
	private function _recursive_directory_size_raw($prefix_directory, &$exclude = array(), $suffix_directory = '') {

		$directory = $prefix_directory.('' == $suffix_directory ? '' : '/'.$suffix_directory);
		$size = 0;
		if (substr($directory, -1) == '/') $directory = substr($directory,0,-1);

		if (!file_exists($directory) || !is_dir($directory) || !is_readable($directory)) return -1;
		if (file_exists($directory.'/.donotbackup')) return 0;

		if ($handle = opendir($directory)) {
			while (($file = readdir($handle)) !== false) {
				if ($file != '.' && $file != '..') {
					$spath = ('' == $suffix_directory) ? $file : $suffix_directory.'/'.$file;
					if (false !== ($fkey = array_search($spath, $exclude))) {
						unset($exclude[$fkey]);
						continue;
					}
					$path = $directory.'/'.$file;
					if (is_file($path)) {
						$size += filesize($path);
					} elseif (is_dir($path)) {
						$handlesize = $this->_recursive_directory_size_raw($prefix_directory, $exclude, $suffix_directory.('' == $suffix_directory ? '' : '/').$file);
						if ($handlesize >= 0) { $size += $handlesize; }# else { return -1; }
					}
				}
			}
			closedir($handle);
		}
		return $size;
	}
	
	
	/* SKIP */
	/*
	public function debug_full_backup(){
		global $updraftplus;
		if (!is_a($updraftplus, 'UpdraftPlus')) $updraftplus = new UpdraftPlus();
		
		$updraftplus->boot_backup(true,true);
			
		return $this->_response("debug_full_backup_started");
	}
	
	public function debug_database_backup(){
		global $updraftplus;
		if (!is_a($updraftplus, 'UpdraftPlus')) $updraftplus = new UpdraftPlus();
		
		$updraftplus->boot_backup(false, true, false, true);
		
		return $this->_response("debug_database_backup_started");
	}
	*/
	public function wipe_settings(){
		global $updraftplus;
		if (!is_a($updraftplus, 'UpdraftPlus')) $updraftplus = new UpdraftPlus();
		
		$settings = $updraftplus->get_settings_keys();
		foreach ($settings as $s) UpdraftPlus_Options::delete_updraft_option($s);

		// These aren't in get_settings_keys() because they are always in the options table, regardless of context
		global $wpdb;
		$wpdb->query("DELETE FROM $wpdb->options WHERE ( option_name LIKE 'updraftplus_unlocked_%' OR option_name LIKE 'updraftplus_locked_%' OR option_name LIKE 'updraftplus_last_lock_time_%' OR option_name LIKE 'updraftplus_semaphore_%' OR option_name LIKE 'updraft_jobdata_%' OR option_name LIKE 'updraft_last_scheduled_%' )");

		$site_options = array('updraft_oneshotnonce');
		foreach ($site_options as $s){ 
			delete_site_option($s);
		};
		
		return $this->_response("settings_wiped");
	}

}