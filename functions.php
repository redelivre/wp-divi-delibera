<?php
if(defined(WP_DEBUG) && WP_DEBUG)
{
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL & ~E_STRICT);
	
}
/**
 * Enqueues child theme stylesheet, loading first the parent theme stylesheet.
 */
function themify_custom_enqueue_child_theme_styles() {
    wp_enqueue_style( 'parent-theme-css', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'themify_custom_enqueue_child_theme_styles' );

function divi_child_clear_local_storage () {
	wp_enqueue_script( 'divi_child_clear_local_storage', get_stylesheet_directory_uri() . '/js/clear_local_storage.js' );
}
add_action( 'admin_enqueue_scripts', 'divi_child_clear_local_storage', 9999 );

if ( ! function_exists( 'et_builder_add_main_elements' ) ) :
function et_builder_add_main_elements() {
    require ET_BUILDER_DIR . 'main-structure-elements.php';
    require 'includes/builder/main-modules.php';
    do_action( 'et_builder_ready' );
}
endif;

function check_user_logged_in() {
    echo is_user_logged_in() ? '1':'0';
    die();
}


function divi_child_get_avatar_url($get_avatar){
    preg_match("/src='(.*?)'/i", $get_avatar, $matches);
    return $matches[1];
}

function divi_child_login_form()
{
	$content = '[et_pb_ajax_login admin_label="Ajax Login" title="Entrar" current_page_redirect="on" use_background_color="on" background_color="rgba(71,71,71,0.9)" background_layout="dark" text_orientation="center" use_focus_border_color="off" header_font_size_tablet="51" header_line_height_tablet="2" body_font_size_tablet="51" body_line_height_tablet="2" use_border_color="off" border_color="#ffffff" border_style="solid" custom_button="off" button_letter_spacing="0" button_use_icon="default" button_icon_placement="right" button_on_hover="on" button_letter_spacing_hover="0" show_button="off"]Você precisa se cadastrar para participar[/et_pb_ajax_login]';
	echo do_shortcode($content);
}
add_action('wp_footer', 'divi_child_login_form');

require_once get_stylesheet_directory().'/includes/widgets/WidgetLoginAjax.php';
?>