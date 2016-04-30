<?php

class WidgetLoginAjax extends WP_Widget
{

	public function __construct()
	{
		parent::__construct('WidgetLoginAjax',  // Base ID
				__('Ajax Login', 'et_builder'),  // Name
				array(
					'description' => __('A shadowbox Ajax Login', 'et_builder')
				)); // Args
	}

	public function widget($args, $instance)
	{
		$content = '[et_pb_ajax_login admin_label="Ajax Login" title="Entrar" current_page_redirect="on" use_background_color="on" background_color="rgba(48,23,25,0.84)" background_layout="dark" text_orientation="center" use_focus_border_color="off" header_font_size_tablet="51" header_line_height_tablet="2" body_font_size_tablet="51" body_line_height_tablet="2" use_border_color="off" border_color="#ffffff" border_style="solid" custom_button="off" button_letter_spacing="0" button_use_icon="default" button_icon_placement="right" button_on_hover="on" button_letter_spacing_hover="0" show_button="off"] [/et_pb_ajax_login]';
		echo do_shortcode($content);
	}

	public function form($instance)
	{
		 
	}

	public function update($new_instance, $old_instance)
	{
		if(!is_array($new_instance)) $new_instance = array();
		if(!is_array($old_instance)) $old_instance = array();
		
		return array_merge($old_instance, $new_instance);
	}
}

add_action( 'widgets_init', function(){
	register_widget( 'WidgetLoginAjax' );
});
