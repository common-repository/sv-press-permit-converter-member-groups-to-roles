function sv_ppcmgtr(){
	var data = {
		'action': 'sv_ppcmgtr',
		'page': sv_ppcmgtr_vars.page
	};
	
	jQuery.post(ajaxurl, data, function(response){
		jQuery('#sv_ppcmgtr_status_progress_log').append(response);
		
		var percentage		= 100/sv_ppcmgtr_vars.members_total_cycles*sv_ppcmgtr_vars.page;
		
		jQuery('#sv_ppcmgtr_status_progress_bar').progressbar({
			value: percentage
		});
		
		jQuery('#sv_ppcmgtr_status_progress_text .left').html((Math.round(percentage * 100) / 100)+'%');
		jQuery('#sv_ppcmgtr_status_progress_text .center').html(sv_ppcmgtr_vars.page+'/'+sv_ppcmgtr_vars.members_total_cycles+' Cycles');
		jQuery('#sv_ppcmgtr_status_progress_text .right').html(jQuery('#sv_ppcmgtr_status_progress_log').children('div').length+'/'+sv_ppcmgtr_vars.members_total+' Members');
		
		if(parseInt(sv_ppcmgtr_vars.page) < parseInt(sv_ppcmgtr_vars.members_total_cycles)){
			sv_ppcmgtr_vars.page = parseInt(sv_ppcmgtr_vars.page)+1;
			sv_ppcmgtr();
		}
	}).fail(function() {
		sv_ppcmgtr();
	});
}

jQuery(document).ready(function(){
	// progressbar
	jQuery('#sv_ppcmgtr_status_progress_bar').progressbar({
		value: 0
	});
	
	jQuery('#sv_ppcmgtr_start').one('click', function(){
		jQuery('#sv_ppcmgtr_status_progress_log').append('<h2>Import Log</h2>');
		jQuery('#sv_ppcmgtr_start, #sv_ppcmgtr_continue').attr('disabled', 'disabled');
		
		sv_ppcmgtr_vars.page = 1;
		
		// ajax
		sv_ppcmgtr();
	});
	
	jQuery('#sv_ppcmgtr_continue').one('click', function(){
		jQuery('#sv_ppcmgtr_status_progress_log').append('<h2>Import Log</h2>');
		jQuery('#sv_ppcmgtr_start, #sv_ppcmgtr_continue').attr('disabled', 'disabled');
		
		// ajax
		sv_ppcmgtr();
	});
});