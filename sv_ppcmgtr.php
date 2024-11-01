<?php
	/*
	Plugin Name: SV Press Permit Converter Member Groups to Roles
	Plugin URI: https://straightvisions.com/
	Description: Converts Member Groups to Roles
	Version: 1.0.1
	Author: Matthias Reuter
	Author URI: https://straightvisions.com
	Text Domain: sv_ppcmgtr
	License: GPL3
	License URI: https://www.gnu.org/licenses/gpl-3.0.html
	*/

	class sv_ppcmgtr{
		public $path				= false;
		public $basename			= false;
		public $url					= false;
		public $version				= false;
		/**
		 * @desc			Load's requested libraries dynamicly
		 * @param	string	$name library-name
		 * @return			class object of the requested library
		 * @author			Matthias Reuter
		 * @since			1.0
		 * @ignore
		 */
		public function __get($name){
			if(file_exists($this->path.'lib/modules/'.$name.'.php')){
				require_once($this->path.'lib/modules/'.$name.'.php');
				$classname			= 'sv_ppcmgtr_'.$name;
				$this->$name		= new $classname($this);
				return $this->$name;
			}else{
				throw new Exception('Class '.$name.' could not be loaded (tried to load class-file '.$this->path.'lib/'.$name.'.php'.')');
			}
		}
		/**
		 * @desc			initialize plugin
		 * @author			Matthias Reuter
		 * @since			1.0
		 * @ignore
		 */
		public function __construct(){
			$this->path				= WP_PLUGIN_DIR.'/'.dirname(plugin_basename(__FILE__)).'/';
			$this->basename			= plugin_basename(__FILE__);
			$this->url				= plugins_url( '' , __FILE__ ).'/';
			$this->version			= 1000;

			// language settings
			load_textdomain('sv_ppcmgtr', WP_LANG_DIR.'/plugins/sv_ppcmgtr-'.apply_filters('plugin_locale', get_locale(), 'sv_ppcmgtr').'.mo');
			load_plugin_textdomain('sv_ppcmgtr', false, dirname(plugin_basename(__FILE__)).'/lib/assets/lang/');
			
			$this->settings->init();							// load settings
			$this->hooks->init();								// load hooks
		}
	}
	
	$GLOBALS['sv_ppcmgtr']			= new sv_ppcmgtr();
?>