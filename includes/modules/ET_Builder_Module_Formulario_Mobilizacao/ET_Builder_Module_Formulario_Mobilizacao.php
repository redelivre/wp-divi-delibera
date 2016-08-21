<?php

class ET_Builder_Module_Formulario_Mobilizacao extends ET_Builder_Module {
	function init() {
		$this->name = esc_html__( 'Formulário Mobilização', 'et_builder' );
		$this->slug = 'et_pb_formulario_mobilizacao';
		$this->child_slug      = 'et_pb_form_field_mobilize';
		$this->child_item_text = esc_html__( 'Field', 'et_builder' );

		$this->whitelisted_fields = array(
			'captcha',
			'email',
			'title',
			'admin_label',
			'module_id',
			'module_class',
			'form_background_color',
			'input_border_radius',
			'use_redirect',
			'redirect_url',
			'success_message',
			'button_text'
		);

		$this->fields_defaults = array(
			'captcha'      => array( 'off' ),
			'use_redirect' => array( 'off' ),
			'button_text'  => array( __('Participar', 'et_builder') ),
		);

		$this->main_css_element = '%%order_class%%.et_pb_form_mobilizacao_container';
		$this->advanced_options = array(
			'fonts' => array(
				'title' => array(
					'label'    => esc_html__( 'Title', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} h1",
					),
					),
					'form_field'   => array(
						'label'    => esc_html__( 'Form Field', 'et_builder' ),
						'css'      => array(
							'main' => "{$this->main_css_element} .input",
						),
						),
						),
						'border' => array(
							'css'      => array(
								'main' => "{$this->main_css_element} .input",
							),
							'settings' => array(
								'color' => 'alpha',
							),
							),
							'button' => array(
								'button' => array(
									'label' => esc_html__( 'Button', 'et_builder' ),
								),
							),
								);
		$this->custom_css_options = array(
			'contact_title' => array(
				'label'    => esc_html__( 'Contact Title', 'et_builder' ),
				'selector' => '.et_pb_contact_main_title',
			),
			'contact_button' => array(
				'label'    => esc_html__( 'Contact Button', 'et_builder' ),
				'selector' => '.et_pb_contact_submit',
			),
			'contact_fields' => array(
				'label'    => esc_html__( 'Form Fields', 'et_builder' ),
				'selector' => '.et_pb_contact_left input',
			),
			'text_field' => array(
				'label'    => esc_html__( 'Message Field', 'et_builder' ),
				'selector' => 'textarea.et_pb_contact_message',
			),
			'captcha_field' => array(
				'label'    => esc_html__( 'Captcha Field', 'et_builder' ),
				'selector' => 'input.et_pb_contact_captcha',
			),
			'captcha_label' => array(
				'label'    => esc_html__( 'Captcha Text', 'et_builder' ),
				'selector' => '.et_pb_contact_right p',
			),
		);
		
		add_action('wp_enqueue_scripts', array($this, 'javascriptFiles'), 1001);
		add_action('wp_enqueue_scripts', array($this, 'cssFiles'), 1001);
		//add_filter('query_vars', array($this, 'query_vars')); //moved to function, no loading here 
		add_action('template_redirect', array($this, 'template_redirect'));
	}

	function get_fields() {
		$fields = array(
			'captcha' => array(
				'label'           => esc_html__( 'Display Captcha', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'description' => esc_html__( 'Turn the captcha on or off using this option.', 'et_builder' ),
			),
			'email' => array(
				'label'           => esc_html__( 'Email', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Input the email address where messages should be sent.', 'et_builder' ),
			),
			'title' => array(
				'label'           => esc_html__( 'Title', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Define a title for your form.', 'et_builder' ),
			),
			'button_text' => array(
				'label'           => esc_html__( 'Texto do Botão', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Define the text of submit button.', 'et_builder' ),
			),

			'use_redirect' => array(
				'label'           => esc_html__( 'Enable Redirect URL', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'affects' => array(
					'#et_pb_redirect_url',
				),
				'description' => esc_html__( 'Redirect users after successful form submission.', 'et_builder' ),
			),
			'redirect_url' => array(
				'label'           => esc_html__( 'Redirect URL', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'depends_show_if' => 'on',
				'description'     => esc_html__( 'Type the Redirect URL', 'et_builder' ),
			),
			'success_message' => array(
				'label'           => esc_html__( 'Success Message', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'description'     => esc_html__( 'Type the message you want to display after successful form submission. Leave blank for default', 'et_builder' ),
			),
			'form_background_color' => array(
				'label'             => esc_html__( 'Form Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'input_border_radius'   => array(
				'label'             => esc_html__( 'Input Border Radius', 'et_builder' ),
				'type'              => 'range',
				'default'           => '0',
				'range_settings'    => array(
					'min'  => '0',
					'max'  => '100',
					'step' => '1',
				),
				'option_category'   => 'layout',
				'tab_slug'          => 'advanced',
			),
			'disabled_on' => array(
				'label'           => esc_html__( 'Disable on', 'et_builder' ),
				'type'            => 'multiple_checkboxes',
				'options'         => array(
					'phone'   => esc_html__( 'Phone', 'et_builder' ),
					'tablet'  => esc_html__( 'Tablet', 'et_builder' ),
					'desktop' => esc_html__( 'Desktop', 'et_builder' ),
				),
				'additional_att'  => 'disable_on',
				'option_category' => 'configuration',
				'description'     => esc_html__( 'This will disable the module on selected devices', 'et_builder' ),
			),
			'admin_label' => array(
				'label'       => esc_html__( 'Admin Label', 'et_builder' ),
				'type'        => 'text',
				'description' => esc_html__( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
			),
			'module_id' => array(
				'label'           => esc_html__( 'CSS ID', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'tab_slug'        => 'custom_css',
				'option_class'    => 'et_pb_custom_css_regular',
			),
			'module_class' => array(
				'label'           => esc_html__( 'CSS Class', 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'configuration',
				'tab_slug'        => 'custom_css',
				'option_class'    => 'et_pb_custom_css_regular',
			),
		);
		return $fields;
	}

	function predefined_child_modules() {

		$output = sprintf(
				'[et_pb_form_field_mobilize field_title="%1$s" field_type="input" field_id="nome" required_mark="on" fullwidth_field="off" /]'.
				'[et_pb_form_field_mobilize field_title="%2$s" field_type="input" field_id="sobrenome" required_mark="on" fullwidth_field="off" /]'.
				'[et_pb_form_field_mobilize field_title="%3$s" field_type="input" field_id="email" required_mark="on" /]'.
				'[et_pb_form_field_mobilize field_title="%4$s" field_type="input" field_id="telefone" required_mark="on" /]'.
				'[et_pb_form_field_mobilize field_title="%5$s" field_type="bairro" field_id="bairro" required_mark="on" /]',
				esc_attr__( 'Nome', 'et_builder' ),
				esc_attr__( 'Sobrenome', 'et_builder' ),
				esc_attr__( 'E-Mail', 'et_builder' ),
				esc_attr__( 'Telefone', 'et_builder' ),
				esc_attr__( 'Bairro', 'et_builder' )
				);

		return $output;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$module_id             = $this->shortcode_atts['module_id'];
		$module_class          = $this->shortcode_atts['module_class'];
		$captcha               = $this->shortcode_atts['captcha'];
		$email                 = $this->shortcode_atts['email'];
		$title                 = $this->shortcode_atts['title'];
		$form_background_color = $this->shortcode_atts['form_background_color'];
		$input_border_radius   = $this->shortcode_atts['input_border_radius'];
		$button_custom         = $this->shortcode_atts['custom_button'];
		$custom_icon           = $this->shortcode_atts['button_icon'];
		$use_redirect          = $this->shortcode_atts['use_redirect'];
		$redirect_url          = $this->shortcode_atts['redirect_url'];
		$success_message       = $this->shortcode_atts['success_message'];
		$button_text           = $this->shortcode_atts['button_text'];

		global $et_pb_mobilizacao_form_num;

		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

		if ( '' !== $form_background_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .input',
				'declaration' => sprintf(
						'background-color: %1$s;',
						esc_html( $form_background_color )
						),
			) );
		}

		if ( ! in_array( $input_border_radius, array( '', '0' ) ) ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .input',
				'declaration' => sprintf(
						'-moz-border-radius: %1$s; -webkit-border-radius: %1$s; border-radius: %1$s;',
						esc_html( et_builder_process_range_value( $input_border_radius ) )
						),
			) );
		}

		$success_message = '' !== $success_message ? $success_message : esc_html__( 'Thanks for contacting us', 'et_builder' );

		$et_pb_mobilizacao_form_num = $this->shortcode_callback_num();
		
		$content = $this->shortcode_content;

		$et_error_message = '';
		$et_contact_error = false;
		$current_form_fields = isset( $_POST['et_pb_contact_email_fields_' . $et_pb_mobilizacao_form_num] ) ? $_POST['et_pb_contact_email_fields_' . $et_pb_mobilizacao_form_num] : '';
		$contact_email = '';
		$processed_fields_values = array();

		$nonce_result = isset( $_POST['_wpnonce-et-pb-mobilizacao-form-submitted'] ) && wp_verify_nonce( $_POST['_wpnonce-et-pb-mobilizacao-form-submitted'], 'et-pb-contact-form-submit' ) ? true : false;

		// check that the form was submitted and et_pb_contactform_validate field is empty to protect from spam
		if ( $nonce_result && isset( $_POST['et_pb_mobilizacaoform_submit_' . $et_pb_mobilizacao_form_num] ) && empty( $_POST['et_pb_contactform_validate_' . $et_pb_mobilizacao_form_num] ) ) {
			if ( '' !== $current_form_fields )
			{
				$fields_data_json = str_replace( '\\', '' ,  $current_form_fields );
				$fields_data_array = json_decode( $fields_data_json, true );

				// check whether captcha field is not empty
				if ( 'on' === $captcha && ( ! isset( $_POST['et_pb_contact_captcha_' . $et_pb_mobilizacao_form_num] ) || empty( $_POST['et_pb_contact_captcha_' . $et_pb_mobilizacao_form_num] ) ) ) {
					$et_error_message .= sprintf( '<p class="et_pb_contact_error_text">%1$s</p>', esc_html__( 'Make sure you entered the captcha.', 'et_builder' ) );
					$et_contact_error = true;
				}

				// check all fields on current form and generate error message if needed
				if ( ! empty( $fields_data_array ) ) {
					foreach( $fields_data_array as $index => $value ) {
						// check all the required fields, generate error message if required field is empty
						if ( 'required' === $value['required_mark'] && empty( $_POST[ $value['field_id'] ] ) ) {
							$et_error_message .= sprintf( '<p class="et_pb_contact_error_text">%1$s</p>', esc_html__( 'Make sure you fill in all required fields.', 'et_builder' ).'0' );
							$et_contact_error = true;
							continue;
						}

						// additional check for email field
						if ( 'email' === $value['field_type'] && ! empty( $_POST[ $value['field_id'] ] ) ) {
							$contact_email = sanitize_email( $_POST[ $value['field_id'] ] );
							if ( ! is_email( $contact_email ) ) {
								$et_error_message .= sprintf( '<p class="et_pb_contact_error_text">%1$s</p>', esc_html__( 'Invalid Email.', 'et_builder' ) );
								$et_contact_error = true;
							}
						}

						// prepare the array of processed field values in convenient format
						if ( false === $et_contact_error ) {
							$processed_fields_values[ $value['original_id'] ]['value'] = isset( $_POST[ $value['field_id'] ] ) ? $_POST[ $value['field_id'] ] : '';
							$processed_fields_values[ $value['original_id'] ]['label'] = $value['field_label'];
						}
					}
				}
			} 
			else
			{
				$et_error_message .= sprintf( '<p class="et_pb_contact_error_text">%1$s</p>', esc_html__( 'Make sure you fill in all required fields.', 'et_builder' ).'1' );
				$et_contact_error = true;
			}
		} else {
			if ( false === $nonce_result && isset( $_POST['et_pb_mobilizacaoform_submit_' . $et_pb_mobilizacao_form_num] ) && empty( $_POST['et_pb_contactform_validate_' . $et_pb_mobilizacao_form_num] ) ) {
				$et_error_message .= sprintf( '<p class="et_pb_contact_error_text">%1$s</p>', esc_html__( 'Please refresh the page and try again.', 'et_builder' ) );
			}
			$et_contact_error = true;
		}

		// generate digits for captcha
		$et_pb_first_digit = rand( 1, 15 );
		$et_pb_second_digit = rand( 1, 15 );

		if ( ! $et_contact_error && $nonce_result )
		{
			if('' !== $email)
			{
				$et_site_name = get_option( 'blogname' );
	
				$contact_name = isset( $processed_fields_values['name'] ) ? stripslashes( sanitize_text_field( $processed_fields_values['name']['value'] ) ) : '';
	
				if ( '' !== $custom_message ) {
					$message_pattern = $custom_message;
					// insert the data from contact form into the message pattern
					foreach ( $processed_fields_values as $key => $value ) {
						$message_pattern = str_ireplace( "%%{$key}%%", $value['value'], $message_pattern );
					}
				} else {
					// use default message pattern if custom pattern is not defined
					$message_pattern = isset( $processed_fields_values['message']['value'] ) ? $processed_fields_values['message']['value'] : '';
	
					// Add all custom fields into the message body by default
					foreach ( $processed_fields_values as $key => $value ) {
						if ( ! in_array( $key, array( 'message', 'name', 'email' ) ) ) {
							$message_pattern .= "\r\n";
							$message_pattern .= sprintf(
								'%1$s: %2$s',
								'' !== $value['label'] ? $value['label'] : $key,
								$value['value']
							);
						}
					}
				}
	
				$headers[] = "From: \"{$contact_name}\" <{$contact_email}>";
				$headers[] = "Reply-To: <{$contact_email}>";
	
				wp_mail( apply_filters( 'et_contact_page_email_to', $et_email_to ),
					et_get_safe_localization( sprintf(
						__( 'New Message From %1$s%2$s', 'et_builder' ),
						sanitize_text_field( html_entity_decode( $et_site_name ) ),
						( '' !== $title ? et_get_safe_localization( sprintf( _x( ' - %s', 'contact form title separator', 'et_builder' ), sanitize_text_field( html_entity_decode( $title ) ) ) ) : '' )
					) ),
					stripslashes( wp_strip_all_tags( $message_pattern ) ), apply_filters( 'et_contact_page_headers', $headers, $contact_name, $contact_email ) );
			}
			//$registrations = get_post_meta(get_the_ID(), '_et_pb_mobilizacao_form_registrations_'.$et_pb_mobilizacao_form_num, true);
			
			$data = array();
			$data['time'] = array( 'label' => __('Unix Time', 'et_builder'), 'value' => time());
			$data['datetime'] = array( 'label' => __('Wordpress Date', 'et_builder'), 'value' => mysql2date( get_option( 'date_format' ), date('Y-m-d H:i:s') ));
			$data['ip'] = array( 'label' => __('IP', 'et_builder'), 'value' => $_SERVER['REMOTE_ADDR']);
			$data['hostaddress'] = array( 'label' => __('Host Name', 'et_builder'), 'value' => gethostbyaddr($data['ip']['value']));
			$data['browser'] = array( 'label' => __('Browser', 'et_builder'), 'value' => $this->getBrowser());
			$data['OS'] = array( 'label' => __('OS', 'et_builder'), 'value' => $this->getOS());
			$data['geoip'] = array( 'label' => __('Geo Data', 'et_builder'), 'value' => json_decode(file_get_contents("http://ipinfo.io/{$data['ip']['value']}/json")));
			$data = array_merge($processed_fields_values, $data);
			
			add_post_meta(get_the_ID(), '_et_pb_mobilizacao_form_registrations_'.$et_pb_mobilizacao_form_num, $data, false);

			$et_error_message = sprintf( '<p>%1$s</p>', esc_html( $success_message ) );

		}

		$form = '';

		$et_pb_captcha = sprintf( '
			<div class="et_pb_contact_right">
				<p class="clearfix">
					<span class="et_pb_contact_captcha_question">%1$s</span> = <input type="text" size="2" class="input et_pb_contact_captcha" data-first_digit="%3$s" data-second_digit="%4$s" value="" name="et_pb_contact_captcha_%2$s" data-required_mark="required">
				</p>
			</div> <!-- .et_pb_contact_right -->',
				sprintf( '%1$s + %2$s', esc_html( $et_pb_first_digit ), esc_html( $et_pb_second_digit ) ),
				esc_attr( $et_pb_mobilizacao_form_num ),
				esc_attr( $et_pb_first_digit ),
				esc_attr( $et_pb_second_digit )
				);

		if ( '' === trim( $content ) ) {
			$content = do_shortcode( $this->predefined_child_modules() );
		}

		if ( $et_contact_error ) {
			$form = sprintf( '
				<div class="et_pb_contact" onclick="divi_child_teste();">
					<form class="et_pb_contact_form clearfix" method="post" action="%1$s">
						%8$s
						<input type="hidden" value="et_contact_proccess" name="et_pb_mobilizacaoform_submit_%7$s">
						<input type="text" value="" name="et_pb_contactform_validate_%7$s" class="et_pb_contactform_validate_field" />
						<div class="et_contact_bottom_container">
							%2$s
							<button type="submit" class="et_pb_mobilizacao_submit et_pb_button%6$s"%5$s>%3$s</button>
						</div>
						%4$s
					</form>
				</div> <!-- .et_pb_contact -->',
					esc_url( get_permalink( get_the_ID() ) ),
					(  'on' === $captcha ? $et_pb_captcha : '' ),
					esc_html($button_text),
					wp_nonce_field( 'et-pb-contact-form-submit', '_wpnonce-et-pb-mobilizacao-form-submitted', true, false ),
					'' !== $custom_icon && 'on' === $button_custom ? sprintf(
							' data-icon="%1$s"',
							esc_attr( et_pb_process_font_icon( $custom_icon ) )
							) : '',
					'' !== $custom_icon && 'on' === $button_custom ? ' et_pb_custom_button_icon' : '',
					esc_attr( $et_pb_mobilizacao_form_num ),
					$content
					);
		}

		$output = sprintf( '
			<div id="%4$s" class="et_pb_module et_pb_mobilizacao_form_container clearfix%5$s" data-form_unique_num="%6$s"%7$s>
				%1$s
				<div class="et-pb-contact-message">%2$s</div>
				%3$s
			</div> <!-- .et_pb_mobilizacao_form_container -->
			',
				( '' !== $title ? sprintf( '<h1 class="et_pb_contact_main_title">%1$s</h1>', esc_html( $title ) ) : '' ),
				'' !== $et_error_message ? $et_error_message : '',
				$form,
				( '' !== $module_id
						? esc_attr( $module_id )
						: esc_attr( 'et_pb_mobilizacao_form_' . $et_pb_mobilizacao_form_num )
						),
				( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
				esc_attr( $et_pb_mobilizacao_form_num ),
				'on' === $use_redirect && '' !== $redirect_url ? sprintf( ' data-redirect_url="%1$s"', esc_attr( $redirect_url ) ) : ''
				);

		return $output;
	}
	
	function javascriptFiles()
	{
		wp_enqueue_script('ET_Builder_Module_Formulario_Mobilizacao', get_stylesheet_directory_uri().'/includes/modules/ET_Builder_Module_Formulario_Mobilizacao/frontend/js/ET_Builder_Module_Formulario_Mobilizacao.js', array('jquery'));
	
		$data = array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'url' => wp_login_url( get_permalink() )
		);
		wp_localize_script('ET_Builder_Module_Formulario_Mobilizacao', 'ET_Builder_Module_Formulario_Mobilizacao', $data);
	}
	
	function cssFiles()
	{
		wp_enqueue_style('ET_Builder_Module_Formulario_Mobilizacao', get_stylesheet_directory_uri().'/includes/modules/ET_Builder_Module_Formulario_Mobilizacao/frontend/css/ET_Builder_Module_Formulario_Mobilizacao.css');
	}
	
	function getOS($user_agent = false) {
		
		if($user_agent === false)
		{
			$user_agent     =   $_SERVER['HTTP_USER_AGENT'];
		}
	
		$os_platform    =   "Unknown OS Platform";
	
		$os_array       =   array(
			'/windows nt 10/i'     =>  'Windows 10',
			'/windows nt 6.3/i'     =>  'Windows 8.1',
			'/windows nt 6.2/i'     =>  'Windows 8',
			'/windows nt 6.1/i'     =>  'Windows 7',
			'/windows nt 6.0/i'     =>  'Windows Vista',
			'/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
			'/windows nt 5.1/i'     =>  'Windows XP',
			'/windows xp/i'         =>  'Windows XP',
			'/windows nt 5.0/i'     =>  'Windows 2000',
			'/windows me/i'         =>  'Windows ME',
			'/win98/i'              =>  'Windows 98',
			'/win95/i'              =>  'Windows 95',
			'/win16/i'              =>  'Windows 3.11',
			'/macintosh|mac os x/i' =>  'Mac OS X',
			'/mac_powerpc/i'        =>  'Mac OS 9',
			'/linux/i'              =>  'Linux',
			'/ubuntu/i'             =>  'Ubuntu',
			'/iphone/i'             =>  'iPhone',
			'/ipod/i'               =>  'iPod',
			'/ipad/i'               =>  'iPad',
			'/android/i'            =>  'Android',
			'/blackberry/i'         =>  'BlackBerry',
			'/webos/i'              =>  'Mobile'
		);
	
		foreach ($os_array as $regex => $value) {
	
			if (preg_match($regex, $user_agent)) {
				$os_platform    =   $value;
			}
	
		}
	
		return $os_platform;
	
	}
	
	function getBrowser($user_agent = false) {
		
		if($user_agent === false)
		{
			$user_agent     =   $_SERVER['HTTP_USER_AGENT'];
		}
	
		$browser        =   "Unknown Browser";
	
		$browser_array  =   array(
			'/msie/i'       =>  'Internet Explorer',
			'/firefox/i'    =>  'Firefox',
			'/safari/i'     =>  'Safari',
			'/chrome/i'     =>  'Chrome',
			'/edge/i'       =>  'Edge',
			'/opera/i'      =>  'Opera',
			'/netscape/i'   =>  'Netscape',
			'/maxthon/i'    =>  'Maxthon',
			'/konqueror/i'  =>  'Konqueror',
			'/mobile/i'     =>  'Handheld Browser'
		);
	
		foreach ($browser_array as $regex => $value) {
	
			if (preg_match($regex, $user_agent)) {
				$browser    =   $value;
			}
	
		}
	
		return $browser;
	
	}
	
	/**
	 * Form Query Variables
	 */ 
	/*function query_vars($public_query_vars) {
		$public_query_vars[] = 'et_pb_formulario_mobilizacao_export';
		return $public_query_vars;
	}*/ // Moved to functions
	
	/**
	 * Export When asked
	 */
	function template_redirect()
	{
		$post_id = is_home() && get_option( 'show_on_front' ) == 'page' ? get_option( 'page_on_front' ) : get_the_ID();
		if(is_user_logged_in() && current_user_can('edit_'.get_post_type(), $post_id) && intval(get_query_var('et_pb_formulario_mobilizacao_export')) > 0 )
		{
			global $wp_query,$et_pb_mobilizacao_form_num;
			
			if(!isset($et_pb_mobilizacao_form_num)) $et_pb_mobilizacao_form_num = 0;
			
			$registrations = get_post_meta($post_id, '_et_pb_mobilizacao_form_registrations_'.$et_pb_mobilizacao_form_num);
			
			if(is_array($registrations) && count($registrations) > 0)
			{
			
				header('Pragma: public');
				header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
				header("Pragma: no-cache");
				header("Expires: 0");
				header('Content-Transfer-Encoding: none');
				header('Content-Type: application/vnd.ms-excel; charset=UTF-8'); // This should work for IE & Opera
				header("Content-type: application/x-msexcel; charset=UTF-8"); // This should work for the rest
				header('Content-Disposition: attachment; filename='.date('Ymd_His').'_'.__('registrations_report', 'et_builder').'.xls');
				
				$table_header = array();
				$row = $registrations[0];
				
				foreach ($row as $item => $values)
				{
					$table_header[$item] = $values['label'];
				}
				ob_start(); ?>
				<table>
				    <tr><?php
				    	foreach ($table_header as $key => $header)
				    	{
				    		echo "<td>$header</td>";
				    	}?>
				    </tr><?php
				    echo utf8_decode(ob_get_clean());
				    foreach ($registrations as $registration) :
					    ob_start(); ?>
					    <tr><?php
					    	foreach (array_keys($table_header) as $key)
					    	{
					    		if(array_key_exists($key, $registration))
					    		{
					    			if($key == 'bairro')
					    			{
					    				$bairro = get_term($registration[$key]['value'], 'bairro');
					    				if(is_object($bairro))
					    				{
					    					$registration[$key]['value'] = $bairro->name;
					    				}
					    			}
					    			if($key == 'geoip')
					    			{
					    				$value = $registration[$key]['value'];
					    				if(is_object($value))
					    				{
					    					$value = get_object_vars($value);
					    				}
					    				if(is_array($value))
					    				{
					    					$ret = '';
					    					foreach ($value as $key => $val)
					    					{
					    						if(strlen($ret) > 0) $ret .= ', ';
					    						$ret .= $key.'=>'.$val;
					    					}
					    					$registration[$key]['value'] = $ret;
					    				}
					    			}
					    			echo "<td>{$registration[$key]['value']}</td>";
					    		}
					    	}?>
					    </tr><?php
					    echo utf8_decode(ob_get_clean());
					endforeach; ?>
				</table><?php
				
				die();
			}
		}
	}
}
new ET_Builder_Module_Formulario_Mobilizacao();

class ET_Builder_Module_Formulario_Mobilizacao_item extends ET_Builder_Module {
	function init() {
		$this->name            = esc_html__( 'Field', 'et_builder' );
		$this->slug            = 'et_pb_form_field_mobilize';
		$this->type            = 'child';
		$this->child_title_var = 'field_id';

		$this->whitelisted_fields = array(
			'field_title',
			'field_type',
			'field_id',
			'required_mark',
			'fullwidth_field',
			'input_border_radius',
			'field_background_color',
		);

		$this->advanced_setting_title_text = esc_html__( 'New Field', 'et_builder' );
		$this->settings_text               = esc_html__( 'Field Settings', 'et_builder' );
		$this->main_css_element = '%%order_class%%.et_pb_contact_field .input';
		$this->advanced_options = array(
			'fonts' => array(
				'form_field'   => array(
					'label'    => esc_html__( 'Field', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element}",
					),
					),
					),
					'border' => array(
						'css'      => array(
							'main' => "{$this->main_css_element}",
						),
						'settings' => array(
							'color' => 'alpha',
						),
						),
						);
	}

	function get_fields() {
		$fields = array(
			'field_id' => array(
				'label'       => esc_html__( 'Field ID', 'et_builder' ),
				'type'        => 'text',
				'description' => esc_html__( 'Define the unique ID of this field. You should use only English characters without special characters and spaces.', 'et_builder' ),
			),
			'field_title' => array(
				'label'       => esc_html__( 'Title', 'et_builder' ),
				'type'        => 'text',
				'description' => esc_html__( 'Here you can define the content that will be placed within the current tab.', 'et_builder' ),
			),
			'field_type' => array(
				'label'       => esc_html__( 'Type', 'et_builder' ),
				'type'        => 'select',
				'option_category' => 'basic_option',
				'options'         => array(
					'input' => esc_html__( 'Input Field', 'et_builder' ),
					'email' => esc_html__( 'Email Field', 'et_builder' ),
					'text'  => esc_html__( 'Textarea', 'et_builder' ),
					'bairro'  => esc_html__( 'Bairro Dropdown', 'et_builder' ),
				),
				'description' => esc_html__( 'Choose the type of field', 'et_builder' ),
			),
			'required_mark' => array(
				'label'           => esc_html__( 'Required Field', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'configuration',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'description' => esc_html__( 'Define whether the field should be required or optional', 'et_builder' ),
			),
			'fullwidth_field' => array(
				'label'           => esc_html__( 'Make Fullwidth', 'et_builder' ),
				'type'            => 'yes_no_button',
				'option_category' => 'layout',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'et_builder' ),
					'off' => esc_html__( 'No', 'et_builder' ),
				),
				'description' => esc_html__( 'If enabled, the field will take 100% of the width of the content area, otherwise it will take 50%', 'et_builder' ),
			),
			'field_background_color' => array(
				'label'             => esc_html__( 'Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
			),
			'input_border_radius'   => array(
				'label'             => esc_html__( 'Border Radius', 'et_builder' ),
				'type'              => 'range',
				'default'           => '0',
				'range_settings'    => array(
					'min'  => '0',
					'max'  => '100',
					'step' => '1',
				),
				'option_category'   => 'layout',
				'tab_slug'          => 'advanced',
			),
		);
		return $fields;
	}

	function shortcode_callback( $atts, $content = null, $function_name ) {
		$field_title            = $this->shortcode_atts['field_title'];
		$field_type             = $this->shortcode_atts['field_type'];
		$field_id               = $this->shortcode_atts['field_id'];
		$required_mark          = $this->shortcode_atts['required_mark'];
		$fullwidth_field        = $this->shortcode_atts['fullwidth_field'];
		$field_background_color = $this->shortcode_atts['field_background_color'];
		$input_border_radius    = $this->shortcode_atts['input_border_radius'];

		global $et_pb_mobilizacao_form_num;

		// do not output the fields with empty ID
		if ( '' === $field_id ) {
			return;
		}

		$field_id = strtolower( $field_id );

		$current_module_num = '' === $et_pb_mobilizacao_form_num ? 0 : intval( $et_pb_mobilizacao_form_num ) + 1;

		$module_class = ET_Builder_Element::add_module_order_class( '', $function_name );

		$this->half_width_counter = ! isset( $this->half_width_counter ) ? 0 : $this->half_width_counter;

		// count fields to add the et_pb_contact_field_last properly
		if ( 'off' === $fullwidth_field ) {
			$this->half_width_counter++;
		} else {
			$this->half_width_counter = 0;
		}

		$input_field = '';

		if ( '' !== $field_background_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .input',
				'declaration' => sprintf(
						'background-color: %1$s;',
						esc_html( $field_background_color )
						),
			) );
		}

		if ( ! in_array( $input_border_radius, array( '', '0' ) ) ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .input',
				'declaration' => sprintf(
						'-moz-border-radius: %1$s; -webkit-border-radius: %1$s; border-radius: %1$s;',
						esc_html( et_builder_process_range_value( $input_border_radius ) )
						),
			) );
		}

		switch( $field_type ) {
			case 'text':
				$input_field = sprintf(
				'<textarea name="et_pb_contact_%3$s_%2$s" id="et_pb_contact_%3$s_%2$s" class="et_pb_contact_message input" data-required_mark="%5$s" data-field_type="%4$s" data-original_id="%3$s">%1$s</textarea>',
				( isset( $_POST['et_pb_contact_' . $field_id . '_' . $current_module_num] ) ? esc_html( sanitize_text_field( $_POST['et_pb_contact_' . $field_id . '_' . $current_module_num] ) ) : esc_html( $field_title ) ),
				esc_attr( $current_module_num ),
				esc_attr( $field_id ),
				esc_attr( $field_type ),
				'off' === $required_mark ? 'not_required' : 'required'
						);
				break;
			case 'input' :
			case 'email' :
				$input_field = sprintf(
				'<input type="text" id="et_pb_contact_%3$s_%2$s" class="input" value="%1$s" name="et_pb_contact_%3$s_%2$s" data-required_mark="%5$s" data-field_type="%4$s" data-original_id="%3$s">',
				( isset( $_POST['et_pb_contact_' . $field_id . '_' . $current_module_num] ) ? esc_attr( sanitize_text_field( $_POST['et_pb_contact_' . $field_id . '_' . $current_module_num] ) ) : esc_attr( $field_title ) ),
				esc_attr( $current_module_num ),
				esc_attr( $field_id ),
				esc_attr( $field_type ),
				'off' === $required_mark ? 'not_required' : 'required'
						);
				break;
			case 'bairro' :
				$tax = get_taxonomy( 'bairro' );
				if(is_user_logged_in())
				{
					$user = wp_get_current_user();
				}
				
				/* Get the terms of the 'bairro' taxonomy. */
				$terms = get_terms( 'bairro', array( 'hide_empty' => false ) );
				$selected_value = isset( $_POST['et_pb_contact_' . $field_id . '_' . $current_module_num] ) ? esc_attr( sanitize_text_field( $_POST['et_pb_contact_' . $field_id . '_' . $current_module_num] ) ) : esc_attr( $field_title ) ;
				if(! empty($terms))
				{
					$input_field = '
						<select id="et_pb_contact_%3$s_%2$s" name="et_pb_contact_%3$s_%2$s" class="select" data-required_mark="%5$s" data-field_type="select" data-original_id="%3$s" >
							<option value="-1">'.__( 'Selecione um Bairro', 'et_builder' ).'</option>
					';
							foreach($terms as $term)
							{
								$input_field .= '<option id="et_pb_contact_option_%3$s_%2$s_'.$term->term_id.'" value="'.$term->term_id.'" '.( selected( $selected_value, $term->term_id, false ) ).' data-required_mark="%5$s" data-field_type="select" data-original_id="%3$s" >'.$term->name.'</option>';
							}
					$input_field .= '</select>';
						
					$input_field = sprintf($input_field,
						( isset( $_POST['et_pb_contact_' . $field_id . '_' . $current_module_num] ) ? esc_attr( sanitize_text_field( $_POST['et_pb_contact_' . $field_id . '_' . $current_module_num] ) ) : esc_attr( $field_title ) ),
						esc_attr( $current_module_num ),
						esc_attr( $field_id ),
						esc_attr( $field_type ),
						'off' === $required_mark ? 'not_required' : 'required'
					);
				}
				/* If there are no bairro terms, display a message. */
				else
				{
					$input_field = '<div class="custom-register-bairro-empty">'. 
						__('Ainda não há bairros cadastrados').
					'</div>';
				}
				break;
		}

		$output = sprintf(
				'<p class="et_pb_contact_field%5$s%6$s%7$s">
				<label for="et_pb_contact_%3$s_%2$s" class="et_pb_contact_form_label">%1$s</label>
				%4$s
			</p>',
				esc_html( $field_title ),
				esc_attr( $current_module_num ),
				esc_attr( $field_id ),
				$input_field,
				esc_attr( $module_class ),
				'off' === $fullwidth_field ? ' et_pb_contact_field_half' : '',
				0 === $this->half_width_counter % 2 ? ' et_pb_contact_field_last' : ''
				);

		return $output;
	}
}
new ET_Builder_Module_Formulario_Mobilizacao_item;
