<?php
	if(current_user_can('activate_plugins')){
		$settings_default = $this->core->settings->get_settings_default();
?>
<div id="sv_settings">
	<div id="sv_header">
		<div id="sv_logo"><img src="<?php echo $this->core->url; ?>lib/assets/img/logo.png" /></div>
	</div>
	<div id="sv_thankyou">
		<h2><?php _e('SV Press Permit Converter Member Groups to Roles', 'sv_ppcmgtr'); ?></h2>
		<p><?php _e('Just map Press Permit Groups to WP Roles and run the converter - members will be added to the mapped roles.', 'sv_ppcmgtr'); ?></p>
	</div>
	<h2><?php _e('Info', 'sv_ppcmgtr'); ?></h2>
	<?php
		global $wpdb;

		if($wpdb->get_var('SHOW TABLES LIKE "'.$wpdb->prefix.'pp_groups"') == $wpdb->prefix.'pp_groups'){
			$groups									= $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'pp_groups WHERE metagroup_type=""', ARRAY_A);
		}else{
			$groups									= array();
		}
		if($wpdb->get_var('SHOW TABLES LIKE "'.$wpdb->prefix.'pp_group_members"') == $wpdb->prefix.'pp_group_members'){
			$member_groups							= $wpdb->get_var('SELECT COUNT(*) FROM '.$wpdb->prefix.'pp_group_members WHERE status="active"');
		}else{
			$member_groups							= 0;
		}
		
		echo '<div>Groups: '.count($groups).'</div>';
		echo '<div>Groups assigned to Members: '.$member_groups.'</div>';
		echo '<div>Total Conversion Cycles required: '.ceil($member_groups/10).'</div>';
	?>
	<form action="#" method="post" id="sv_global_settings">
		<h2><?php _e('Map User Groups to WP Roles', 'sv_ppcmgtr'); ?></h2>
		<table width="100%">
		<?php
			echo '
			<div class="sv_setting sv_setting_'.$this->settings_default['import']['PP_GROUPS_MAPPING']['type'].'">
				<div class="sv_setting_name">'.$this->settings_default['import']['PP_GROUPS_MAPPING']['name'].'</div>
				<div class="sv_setting_desc">'.$this->settings_default['import']['PP_GROUPS_MAPPING']['desc'].'</div>
				<div class="sv_setting_value">';
				foreach($groups as $data){
					echo '<tr><td>'.$data['group_name'].': '.$data['group_description'].'</td><td><select name="import[PP_GROUPS_MAPPING][value]['.$data['ID'].']"><option value="">'.__('no role assigned', 'sv_ppcmgtr').'</option>';
					wp_dropdown_roles(isset($this->settings['import']['PP_GROUPS_MAPPING']['value'][$data['ID']]) ? $this->settings['import']['PP_GROUPS_MAPPING']['value'][$data['ID']] : '');
					echo '</select></td></tr>';
				}
			echo '</div></div>';
		?>
		</table>
		<input type="hidden" name="sv_ppcmgtr_settings" value="1" />
		<div style="clear:both;"><input type="submit" value="<?php echo _e('Save Settings', 'sv_ppcmgtr'); ?>" /></div>
	</form>
	<h2><?php _e('Start Import', 'sv_ppcmgtr'); ?></h2>
	<p><?php _e('Please do not close browser windows or tab until import has been finished.', 'sv_ppcmgtr'); ?></p>
	<div style="clear:both;">
		<input type="submit" value="<?php echo _e('Start Import', 'sv_ppcmgtr'); ?>" id="sv_ppcmgtr_start" />
		<?php if(get_transient('sv_ppcmgtr_pages_completed') > 1 && get_transient('sv_ppcmgtr_pages_completed') < $member_groups){ ?>
		<input type="submit" value="<?php echo _e('Continue Import from Cycle', 'sv_ppcmgtr'); ?> <?php echo get_transient('sv_ppcmgtr_pages_completed'); ?>" id="sv_ppcmgtr_continue" />
		<?php } ?>
	</div>
	<div id="sv_ppcmgtr_status">
		<h2>Import Status</h2>
		<div id="sv_ppcmgtr_status_progress_bar"></div>
		<div id="sv_ppcmgtr_status_progress_text"><div class="left"></div><div class="center"></div><div class="right"></div></div>
		<div id="sv_ppcmgtr_status_progress_log"></div>
	</div>
</div>
<?php
	}
?>