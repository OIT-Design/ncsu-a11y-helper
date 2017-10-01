<?php 

// Add "Accessibility at NC State metabox"

function ncsu_a11y_meta_box() {

    $ncsu_a11y_options = get_option( 'ncsu_a11y', array() );

    if ( $ncsu_a11y_options['post_types'] ) {
        $checked_post_types = $ncsu_a11y_options['post_types'];
    } else {
        $checked_post_types = get_post_types( array( 'public' => true ) );
    }

    add_meta_box( 'ncsu_a11y', 'Accessibility at NC State', 'ncsu_a11y_meta_content', $checked_post_types, 'normal', 'default', null );
}
add_action( 'add_meta_boxes', 'ncsu_a11y_meta_box' );

function ncsu_a11y_meta_content() {

    $ncsu_a11y_options = get_option( 'ncsu_a11y', array() );
    $a11y_check_button = '<a class="button" href="'. get_preview_post_link( $post, array( 'ncsu_a11y' => 'true' ) ) .'" target="wp-preview-1">Run Accessibility Check<span class="screen-reader-text"> (opens in a new window)</span></a>';

    if ( $ncsu_a11y_options['meta_box_text'] ) {
        echo str_replace( '[a11y_check]', $a11y_check_button, wpautop( $ncsu_a11y_options['meta_box_text'] ) );
    } else {
        echo str_replace( '[a11y_check]', $a11y_check_button, wpautop( $default_meta_text ) );
    }

}