jQuery(document).ready(function() {
	jQuery('.et_pb_ajax_login_button').click(function(){
		jQuery(this).parent().find(".et_pb_ajax_login_panel").toggle();
	});
});