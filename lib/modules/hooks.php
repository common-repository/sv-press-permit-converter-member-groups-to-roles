<?php
	/**
	 * @author			Matthias Reuter
	 * @package			hooks
	 * @copyright		2007-2016 Matthias Reuter
	 * @link			https://straightvisions.com/
	 * @since			1.0
	 * @license			This is no free software. See license.txt or https://straightvisions.com/
	 */
	class sv_ppcmgtr_hooks extends sv_ppcmgtr{
		public $core				= NULL;
		
		/**
		 * @desc			Loads other classes of package
		 * @author			Matthias Reuter
		 * @since			1.0
		 * @ignore
		 */
		public function __construct($core){
			$this->core				= isset($core->core) ? $core->core : $core; // loads common classes
		}
		/**
		 * @desc			initialize actions and filters
		 * @return	void
		 * @author			Matthias Reuter
		 * @since			1.0
		 */
		public function init(){
			$this->actions();
			$this->filters();
		}
		/**
		 * @desc			initialize actions
		 * @return	void
		 * @author			Matthias Reuter
		 * @since			1.0
		 */
		public function actions(){
			add_action('admin_menu', array($this->core->settings, 'get_settings_menu'));
			add_action('admin_enqueue_scripts', array($this->core->settings, 'acp_style'));
			add_action('wp_ajax_sv_ppcmgtr', array($this->core->ajax, 'init'));
		}
		/**
		 * @desc			initialize filters
		 * @return	void
		 * @author			Matthias Reuter
		 * @since			1.0
		 */
		public function filters(){
			add_filter('plugin_action_links', array($this->core->settings,'plugin_action_links'), 10, 5);
		}
	}
?>