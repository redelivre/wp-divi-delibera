jQuery(document).ready(function() {
	var seen = false;
	jQuery('.et_pb_ajax_login_panel').each(function() { //remove duplicated panels
	    if (seen)
	        jQuery(this).remove();
	    else
	        seen = true;
	});
	//jQuery('.et_pb_ajax_login li.wp-opauth-login-strategy').addClass('et_pb_button');
	jQuery('.et_pb_ajax_login_button a, a.delibera-like-login, a.delibera-unlike-login, a.delibera-seguir-login, .pauta-content .warning.message a.button').attr("href", "javascript:ajax_login_toggle_panel();");
	jQuery('.et_pb_ajax_login_button a, a.delibera-like-login, a.delibera-unlike-login, a.delibera-seguir-login, .pauta-content .warning.message a.button').click(function(event){
		event.preventDefault();
		jQuery('.et_pb_ajax_login').find(".et_pb_ajax_login_panel").toggle();
		
	});
	jQuery('.second-register-painel .close-button').click(function(){
		jQuery('.second-register-painel').hide();
		jQuery('.second-register-overlay').hide();
	});
	
	jQuery('.et_pb_ajax_login_panel .close-button').click(function(){
		jQuery('.et_pb_ajax_login').find(".et_pb_ajax_login_panel").toggle();
	});
	
	jQuery(".second-register-painel .submit-button").click(function() {
		var container = jQuery(this).parent();
		
		var valid_required_inputs = true;
		
		for(var key in ET_Builder_Module_Ajax_Login.required_inputs)
		{
			if(ET_Builder_Module_Ajax_Login.required_inputs.key && container.find('input[name="'+key+'"]').length > 0)
			{
				if(container.find('input[name="'+key+'"]').val().length < 1)
				{
					valid_required_inputs = false;
					container.find('input[name="'+key+'"]').addClass('invalid');
				}
			}
		}
		
		if(
			( container.find('.custom-register-bairro option').length == 0 || container.find('.custom-register-bairro').val() > 0 ) &&
			isValidEmail(container.find('input[name="user-email"]').val()) &&
			container.find('input[name="phone"]').val() > 0 &&
			valid_required_inputs
		)
		{
			var data = {
					action : "second_register",
					_wpnonce : container.find('input[name="_wpnonce"]').val(),
					_wp_http_referer: container.find('input[name="_wp_http_referer"]').val(),
					email: container.find('input[name="user-email"]').val(),
					bairro: container.find('.custom-register-bairro').val(),
					telefone: container.find('input[name="phone"]').val(),
			};
			for(var key in ET_Builder_Module_Ajax_Login.required_inputs)
			{
				if(container.find('input[name="'+key+'"]').length > 0)
				{
					data[key] = container.find('input[name="'+key+'"]').val();
				}
			}
			jQuery.post(
				ET_Builder_Module_Ajax_Login.ajaxurl,
				data,
				function(response) {
					alert('Obrigado!');
					container.hide();
					jQuery('.second-register-overlay').hide();
				}
			);
		}
		else
		{
			if(container.find('.custom-register-bairro').val() < 1)
			{
				container.find('.custom-register-bairro').addClass('invalid');
			}
			else
			{
				container.find('.custom-register-bairro').removeClass('invalid');
			}
			
			if(! isValidEmail(container.find('input[name="user-email"]').val()))
			{
				container.find('input[name="user-email"]').addClass('invalid');
			}
			else
			{
				container.find('input[name="user-email"]').removeClass('invalid');
			}
			
			if(container.find('input[name="phone"]').val() < 1)
			{
				container.find('input[name="phone"]').addClass('invalid');
			}
			else
			{
				container.find('input[name="phone"]').removeClass('invalid');
			}
			alert("Favor preecher corretamente os campos, obrigado");
		}
	});
	if(jQuery('.second-register-painel').length > 0)
	{
		jQuery('.second-register-overlay').show();
	}
});

function isValidEmail(email)
{
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return regex.test(email) && (email.search(ET_Builder_Module_Ajax_Login.invalid_email) == -1);
}

function ajax_login_toggle_panel()
{
	jQuery('.et_pb_ajax_login').find(".et_pb_ajax_login_panel").toggle();
	jQuery('.et_mobile_menu').toggle();
	jQuery('.mobile_nav').toggleClass('opened');
	jQuery('.mobile_nav').toggleClass('closed');
}