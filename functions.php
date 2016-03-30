<?php
/**
 * Enqueues child theme stylesheet, loading first the parent theme stylesheet.
 */
function themify_custom_enqueue_child_theme_styles() {
    wp_enqueue_style( 'parent-theme-css', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'themify_custom_enqueue_child_theme_styles' );

if ( ! function_exists( 'et_builder_add_main_elements' ) ) :
function et_builder_add_main_elements() {
    require ET_BUILDER_DIR . 'main-structure-elements.php';
    require 'includes/builder/main-modules.php';
    do_action( 'et_builder_ready' );
}
endif;

function disable_media_comments( $post_id ) {
    if( get_post_type( $post_id ) == 'attachment' ) {
        wp_die("Comment not allowed.");
    }
    return $open;
}
add_action( 'pre_comment_on_post', 'disable_media_comments' );

?>