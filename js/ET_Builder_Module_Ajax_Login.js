jQuery(document).ready(function() {
	var seen = false;
	jQuery('.et_pb_ajax_login_panel').each(function() { //remove duplicated panels
	    if (seen)
	        jQuery(this).remove();
	    else
	        seen = true;
	});
	//jQuery('.et_pb_ajax_login li.wp-opauth-login-strategy').addClass('et_pb_button');
	jQuery('.et_pb_ajax_login_button').click(function(event){
		jQuery('.et_pb_ajax_login').find(".et_pb_ajax_login_panel").toggle();
		event.preventDefault();
	});
});