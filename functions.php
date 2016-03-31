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

if ( ! function_exists( 'et_builder_include_categories_delibera_option' ) ) :
    function et_builder_include_categories_delibera_option( $args = array() ) {

        $defaults = apply_filters( 'et_builder_include_categories_delibera_defaults', array (
            'use_terms' => true,
            'term_name' => 'tema',
             'post_type'=>'pauta'
        ) );

        $args = wp_parse_args( $args, $defaults );

        $output = "\t" . "<% var et_pb_include_categories_temp = typeof et_pb_include_categories !== 'undefined' ? et_pb_include_categories.split( ',' ) : []; %>" . "\n";

        if ( $args['use_terms'] ) {
            $cats_array = get_terms( $args['term_name'] );
        } else {
            //$cats_array = get_categories( apply_filters( 'et_builder_get_categories_args', 'hide_empty=0' ) );
            $cats_array = get_categories();
        }


        if ( empty( $cats_array ) ) {
            $output = '<p>' . esc_html__( "You currently don't have any projects assigned to a category.", 'et_builder' ) . '</p>';
        }

        foreach ( $cats_array as $category ) {
            $contains = sprintf(
                '<%%= _.contains( et_pb_include_categories_temp, "%1$s" ) ? checked="checked" : "" %%>',
                esc_html( $category->term_id )
            );

            $output .= sprintf(
                '%4$s<label><input type="checkbox" name="et_pb_include_categories" value="%1$s"%3$s> %2$s xxx</label><br/>',
                esc_attr( $category->term_id ),
                esc_html( $category->name ),
                $contains,
                "\n\t\t\t\t\t"
            );
        }

        $output = '<div id="et_pb_include_categories">' . $output . '</div>';

        return $output;

        //return apply_filters( 'et_builder_include_categories_option_html', $output );
    }
endif;

?>