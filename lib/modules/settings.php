<?php
	/**
	 * @author			Matthias Reuter
	 * @package			settings
	 * @copyright		2007-2016 Matthias Reuter
	 * @link			https://straightvisions.com/
	 * @since			1.0
	 * @license			This is no free software. See license.txt or https://straightvisions.com/
	 */
	class sv_ppcmgtr_settings extends sv_ppcmgtr{
		public $core									= NULL;
		public $settings_default						= false;
		public $settings								= false;
		
		/**
		 * @desc			Loads other classes of package and defines available settings
		 * @author			Matthias Reuter
		 * @since			1.0
		 * @ignore
		 */
		public function __construct($core){
			$this->core									= isset($core->sv_ppcmgtr) ? $core->sv_ppcmgtr : $core; // loads common classes
			
			$this->settings_default						= array(
				'sv_ppcmgtr_settings'					=> 0,
				'import'								=> array(
					'PP_GROUPS_MAPPING'					=> array(
						'name'							=> __('Groups to Roles Mapping', 'sv_ppcmgtr'),
						'type'							=> 'select_rel',
						'placeholder'					=> '',
						'desc'							=> __('Map PP Member Groups to WP Roles for import.', 'sv_ppcmgtr'),
						'value'							=> '',
					)
				)
			);
		}
		/**
		 * @desc			initialize settings and set constants for IPBWI API
		 * @return	void
		 * @author			Matthias Reuter
		 * @since			1.0
		 */
		public function init(){
			// update settings
			$this->set_settings();
			
			// get settings
			$this->get_settings();
		}
		/**
		 * @desc			update settings
		 * @return	void
		 * @author			Matthias Reuter
		 * @since			1.0
		 */
		public function set_settings(){
			if(isset($_POST['sv_ppcmgtr_settings'])){
				if($_POST['sv_ppcmgtr_settings'] == 1){
					$options = get_option('sv_ppcmgtr');
					
					if($options && is_array($options)){
						$data						= array_replace_recursive($this->settings_default,$options,$_POST);
						$data						= $this->remove_inactive_checkbox_fields($data);
						$this->settings				= $data;
					}else{
						$data						= array_replace_recursive($this->settings_default,$_POST);
						$data						= $this->remove_inactive_checkbox_fields($data);
						$this->settings				= $data;
					}
					
					update_option('sv_ppcmgtr',$this->settings, true);
				}
			}
		}
		/**
		 * @desc			if checkbox fields are unchecked, update value to 0
		 * @param	int		$data settings data
		 * @return	array	updated settings data
		 * @author			Matthias Reuter
		 * @since			1.0
		 */
		private function remove_inactive_checkbox_fields($data){
			foreach($data as $group_name => $group){
				if(is_array($group)){
					foreach($group as $field_name => $field){
						if($field['type'] == 'checkbox'){
							$data[$group_name][$field_name]['value'] = (isset($_POST[$group_name][$field_name]['value']) ? 1 : 0);
						}
					}
				}
			}
			return $data;
		}
		/**
		 * @desc			get settings
		 * @return	array	settings array
		 * @author			Matthias Reuter
		 * @since			1.0
		 */
		public function get_settings(){
			if($this->settings){
				return $this->settings;
			}else{
				$this->settings = array_replace_recursive($this->settings_default,(array)get_option('sv_ppcmgtr'));
				return $this->settings;
			}
		}
		/**
		 * @desc			get default settings
		 * @return	array	default settings
		 * @author			Matthias Reuter
		 * @since			1.0
		 */
		public function get_settings_default(){
			return $this->settings_default;
		}
		/**
		 * @desc			define settings menu
		 * @return	void
		 * @author			Matthias Reuter
		 * @since			1.0
		 */
		public function get_settings_menu(){
			add_menu_page(
				__('PP Groups to Roles', 'sv_ppcmgtr'),				// page title
				__('PP Groups to Roles', 'sv_ppcmgtr'),				// menu title
				'activate_plugins',									// capability
				'sv_ppcmgtr',										// menu slug
				function(){ require_once($this->core->path.'lib/assets/tpl/backend.php'); },					// callable function
				$this->core->url.'lib/assets/img/logo_icon.png'			// icon url
			);
		}
		/**
		 * @desc			output the plugin action links
		 * @param	array	$actions default plugin action links
		 * @param	string	$plugin_file plugin's file name
		 * @return	array	updated plugin action links
		 * @author			Matthias Reuter
		 * @since			1.0
		 */
		public function plugin_action_links($actions, $plugin_file){
			if($this->core->basename == $plugin_file){
				$links				= array(
										'user_settings'			=> '<a href="admin.php?page=sv_ppcmgtr">'.__('PP Groups to Roles', 'sv_ppcmgtr').'</a>',
										'support'				=> '<a href="https://straightvisions.com/community/" target="_blank">'.__('Support', 'sv_ppcmgtr').'</a>',
										'documentation'			=> '<a href="https://straightvisions.com/" target="_blank">'.__('Website', 'sv_ppcmgtr').'</a>',
				);
				$actions			= array_merge($links, $actions);
			}
			return $actions;
		}
		/**
		 * @desc			ACP scripts and styles
		 * @param	string	$hook location in WP Admin
		 * @return	void	
		 * @author			Matthias Reuter
		 * @since			1.0
		 */
		public function acp_style($hook){
			if($hook == 'toplevel_page_sv_ppcmgtr'){
				wp_enqueue_style('ipbwi4wp_acp_style', $this->core->url.'lib/assets/css/backend.css');
				wp_enqueue_script('jquery-ui-core');
				wp_enqueue_script('jquery-ui-widget');
				wp_enqueue_script('jquery-ui-progressbar');
				wp_enqueue_script('sv_ppcmgtr',$this->core->url.'lib/assets/js/backend.js');
				wp_enqueue_style('sv_ppcmgtr-ui-css', '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css');
				
				global $wpdb;
				
				//$groups									= $wpdb->get_var('SELECT COUNT(*) FROM '.$wpdb->prefix.'pp_groups WHERE metagroup_type=""');
				
				if($wpdb->get_var('SHOW TABLES LIKE "'.$wpdb->prefix.'pp_group_members"') == $wpdb->prefix.'pp_group_members'){
					$member_groups							= $wpdb->get_var('SELECT COUNT(*) FROM '.$wpdb->prefix.'pp_group_members WHERE status="active"');
					
					wp_localize_script('sv_ppcmgtr', 'sv_ppcmgtr_vars', array(
						'members_total'						=> $member_groups,
						'members_total_cycles'				=> ceil($member_groups/10),
						'members_per_cycle'					=> 10,
						'page'								=> get_transient('sv_ppcmgtr_pages_completed')
					));
				}
			}
		}
	}
?>