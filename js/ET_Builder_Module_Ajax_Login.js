jQuery(document).ready(function() {
	var seen = false;
	jQuery('.et_pb_ajax_login_panel').each(function() { //remove duplicated panels
	    if (seen)
	        jQuery(this).remove();
	    else
	        seen = true;
	});
	//jQuery('.et_pb_ajax_login li.wp-opauth-login-strategy').addClass('et_pb_button');
	jQuery('.et_pb_ajax_login_button a').attr("href", "javascript:ajax_login_toggle_panel();");
	jQuery('.et_pb_ajax_login_button a').click(function(event){
		event.preventDefault();
		jQuery('.et_pb_ajax_login').find(".et_pb_ajax_login_panel").toggle();
		
	});
});

function ajax_login_toggle_panel()
{
	jQuery('.et_pb_ajax_login').find(".et_pb_ajax_login_panel").toggle();
	jQuery('.et_mobile_menu').toggle();
	jQuery('.mobile_nav').toggleClass('opened');
	jQuery('.mobile_nav').toggleClass('closed');
}