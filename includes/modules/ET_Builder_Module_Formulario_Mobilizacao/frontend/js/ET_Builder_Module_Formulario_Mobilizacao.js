jQuery(document).ready(function() {
	var $et_contact_container = jQuery( '.et_pb_mobilizacao_form_container' );

	if ( $et_contact_container.length ) {
		$et_contact_container.each( function() {
			var $this_contact_container = jQuery( this ),
				$et_contact_form = $this_contact_container.find( 'form' ),
				$et_contact_submit = $this_contact_container.find( 'input.et_pb_mobilizacao_submit' ),
				$et_inputs = $et_contact_form.find( 'input[type=text],textarea,option:selected' ),
				et_email_reg = /^[\w-]+(\.[\w-]+)*@([a-z0-9-]+(\.[a-z0-9-]+)*?\.[a-z]{2,6}|(\d{1,3}\.){3}\d{1,3})(:\d{4})?$/,
				redirect_url = typeof $this_contact_container.data( 'redirect_url' ) !== 'undefined' ? $this_contact_container.data( 'redirect_url' ) : '';

			$et_inputs.live( 'focus', function() {
				if ( jQuery( this ).val() === jQuery( this ).siblings('label').text() ) {
					jQuery( this ).val('');
				}
			}).live( 'blur', function() {
				if ( '' === jQuery( this ).val() ) {
					jQuery( this ).val( jQuery( this ).siblings( 'label' ).text() );
				}
			});

			$et_contact_form.on( 'submit', function( event ) {
				var $this_contact_form = jQuery( this ),
					$this_inputs = $this_contact_form.find( 'input[type=text],textarea,option:selected' ),
					this_et_contact_error = false,
					$et_contact_message = $this_contact_form.closest( '.et_pb_mobilizacao_form_container' ).find( '.et-pb-contact-message' ),
					et_message = '',
					et_fields_message = '',
					$this_contact_container = $this_contact_form.closest( '.et_pb_mobilizacao_form_container' ),
					$captcha_field = $this_contact_form.find( '.et_pb_contact_captcha' ),
					form_unique_id = typeof $this_contact_container.data( 'form_unique_num' ) !== 'undefined' ? $this_contact_container.data( 'form_unique_num' ) : 0,
					inputs_list = [];
				et_message = '<ul>';

				$this_inputs.removeClass( 'et_contact_error' );

				$this_inputs.each( function(){
					var $this_el = jQuery( this ),
						this_val = $this_el.val(),
						this_label = $this_el.siblings( 'label' ).text(),
						field_type = typeof $this_el.data( 'field_type' ) !== 'undefined' ? $this_el.data( 'field_type' ) : 'text',
						required_mark = typeof $this_el.data( 'required_mark' ) !== 'undefined' ? $this_el.data( 'required_mark' ) : 'not_required',
						original_id = typeof $this_el.data( 'original_id' ) !== 'undefined' ? $this_el.data( 'original_id' ) : '',
						default_value;
					if('' == this_label && 'select' == field_type)
					{
						this_label = $this_el.parent().siblings( 'label' ).text();
					}

					// add current field data into array of inputs
					if('select' == field_type)
					{
						if ( typeof $this_el.parent().attr( 'id' ) !== 'undefined' ) {
							inputs_list.push( { 'field_id' : $this_el.parent().attr( 'id' ), 'original_id' : original_id, 'required_mark' : required_mark, 'field_type' : field_type, 'field_label' : this_label } );
						}
					}
					else if ( typeof $this_el.attr( 'id' ) !== 'undefined' ) {
						inputs_list.push( { 'field_id' : $this_el.attr( 'id' ), 'original_id' : original_id, 'required_mark' : required_mark, 'field_type' : field_type, 'field_label' : this_label } );
					}

					// add error message for the field if it is required and empty
					if ( 'required' === required_mark && ( '' === this_val || this_label === this_val ) ) {
						$this_el.addClass( 'et_contact_error' );
						this_et_contact_error = true;

						default_value = this_label;

						if ( '' === default_value ) {
							default_value = et_pb_custom.captcha;
						}

						et_fields_message += '<li>' + default_value + '</li>';
					}

					// add error message if email field is not empty and fails the email validation
					if ( 'email' === field_type && '' !== this_val && this_label !== this_val && ! et_email_reg.test( this_val ) ) {
						$this_el.addClass( 'et_contact_error' );
						this_et_contact_error = true;

						if ( ! et_email_reg.test( this_val ) ) {
							et_message += '<li>' + et_pb_custom.invalid + '</li>';
						}
					}
				});

				// check the captcha value if required for current form
				if ( $captcha_field.length && '' !== $captcha_field.val() ) {
					var first_digit = parseInt( $captcha_field.data( 'first_digit' ) ),
						second_digit = parseInt( $captcha_field.data( 'second_digit' ) );

					if ( parseInt( $captcha_field.val() ) !== first_digit + second_digit ) {

						et_message += '<li>' + et_pb_custom.wrong_captcha + '</li>';
						this_et_contact_error = true;

						// generate new digits for captcha
						first_digit = Math.floor( ( Math.random() * 15 ) + 1 );
						second_digit = Math.floor( ( Math.random() * 15 ) + 1 );

						// set new digits for captcha
						$captcha_field.data( 'first_digit', first_digit );
						$captcha_field.data( 'second_digit', second_digit );

						// regenerate captcha on page
						$this_contact_form.find( '.et_pb_contact_captcha_question' ).empty().append( first_digit  + ' + ' + second_digit );
					}

				}

				if ( ! this_et_contact_error ) {
					var $href = jQuery( this ).attr( 'action' ),
						form_data = jQuery( this ).serializeArray();

					form_data.push( { 'name': 'et_pb_contact_email_fields_' + form_unique_id, 'value' : JSON.stringify( inputs_list ) } );

					$this_contact_container.fadeTo( 'fast', 0.2 ).load( $href + ' #' + $this_contact_form.closest( '.et_pb_mobilizacao_form_container' ).attr( 'id' ), form_data, function( responseText ) {
						// redirect if redirect URL is not empty and no errors in contact form
						if ( '' !== redirect_url && ! jQuery( responseText ).find( '.et_pb_contact_error_text').length ) {
							window.location.href = redirect_url;
						}

						$this_contact_container.fadeTo( 'fast', 1 );
					} );
				}

				et_message += '</ul>';

				if ( '' !== et_fields_message ) {
					if ( et_message != '<ul></ul>' ) {
						et_message = '<p class="et_normal_padding">' + et_pb_custom.contact_error_message + '</p>' + et_message;
					}

					et_fields_message = '<ul>' + et_fields_message + '</ul>';

					et_fields_message = '<p>' + et_pb_custom.fill_message + '</p>' + et_fields_message;

					et_message = et_fields_message + et_message;
				}

				if ( et_message != '<ul></ul>' ) {
					$et_contact_message.html( et_message );
				}

				event.preventDefault();
			});
		});
	}
});
