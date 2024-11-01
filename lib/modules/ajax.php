<?php
	/**
	 * @author			Matthias Reuter
	 * @package			hooks
	 * @copyright		2007-2016 Matthias Reuter
	 * @link			https://straightvisions.com/
	 * @since			1.0
	 * @license			This is no free software. See license.txt or https://straightvisions.com/
	 */
	class sv_ppcmgtr_ajax extends sv_ppcmgtr{
		public $core								= NULL;
		
		/**
		 * @desc			Loads other classes of package
		 * @author			Matthias Reuter
		 * @since			1.0
		 * @ignore
		 */
		public function __construct($core){
			$this->core								= isset($core->core) ? $core->core : $core; // loads common classes
		}
		/**
		 * @desc			initialize ajax
		 * @return	void
		 * @author			Matthias Reuter
		 * @since			1.0
		 */
		public function init(){
			if(current_user_can('activate_plugins')){
				global $wpdb;
				$result								= '';
				$page								= intval($_POST['page']);
				$start								= (($page-1)*10);
				$stop								= 10;
				$members							= $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'pp_group_members WHERE status="active" ORDER BY user_id ASC LIMIT '.$start.', '.$stop, ARRAY_A);
				$member_total						= $wpdb->get_var('SELECT COUNT(*) FROM '.$wpdb->prefix.'pp_group_members WHERE status="active"');
				$pages_total						= ceil($member_total/10);
				foreach($members as $member){
					if(isset($this->core->settings->settings['import']['PP_GROUPS_MAPPING']['value'][$member['group_id']])){
						$role						= $this->core->settings->settings['import']['PP_GROUPS_MAPPING']['value'][$member['group_id']];
						if(strlen($role) > 0){
							$user					= new WP_User($member['user_id']);
							$user->add_role($role);
							
							$result					.= '<div data-page="'.$page.'" data-start="'.$start.'" data-stop="'.$stop.'">'.__('Member', 'sv_ppcmgtr').' <strong>'.$member['user_id'].'</strong> '.__('converted from group', 'sv_ppcmgtr').' #'.$member['group_id'].' '.__('to role', 'sv_ppcmgtr').' '.$role.'</div>';
						}else{
							$result					.= '<div data-page="'.$page.'" data-start="'.$start.'" data-stop="'.$stop.'">'.__('Member', 'sv_ppcmgtr').' <strong>'.$member['user_id'].'</strong> '.__('NOT converted from group', 'sv_ppcmgtr').' #'.$member['group_id'].' '.__('as there was no target role set', 'sv_ppcmgtr').'</div>';
						}
					}else{
						$result					.= '<div data-page="'.$page.'" data-start="'.$start.'" data-stop="'.$stop.'">'.__('Member', 'sv_ppcmgtr').' <strong>'.$member['user_id'].'</strong> '.__('NOT converted from group', 'sv_ppcmgtr').' #'.$member['group_id'].' '.__('as there was no target role set', 'sv_ppcmgtr').'</div>';
					}
				}
				echo $result;
				
				if($pages_total > $page){
					set_transient('sv_ppcmgtr_pages_completed', $page+1);
				}else{
					set_transient('sv_ppcmgtr_pages_completed', 1);
				}
			}else{
				__('You are not allowed to do this.', 'sv_ppcmgtr');
			}
			wp_die();
		}
	}
?>